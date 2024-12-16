<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\StockRequest;
use App\Models\StockItem;
use App\Models\Outlet;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class StockRequestController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!in_array(Auth::user()->role, ['gudang', 'crewoutlet'])) {
                return redirect()->route('dashboard');
            }
            return $next($request);
        });
    }

    public function index()
    {
        try {
            $user = Auth::user();
            Log::info('Current user:', ['user' => (array)$user]);

            $stockRequests = StockRequest::with([
                'user' => function($query) {
                    $query->select('id', 'nama', 'username');
                },
                'validator' => function($query) {
                    $query->select('id', 'nama', 'username');
                },
                'outlet',
                'stockItem'
            ])
            ->latest()
            ->get()
            ->map(function ($request) {
                // Debug logging
                Log::info('Processing request:', [
                    'id' => $request->id,
                    'requested_by' => $request->requested_by,
                    'user_data' => $request->user ? $request->user->toArray() : null
                ]);

                // Make sure we have user data before accessing it
                $userData = null;
                if ($request->user) {
                    $userData = [
                        'id' => $request->user->id,
                        'name' => $request->user->nama ?: $request->user->username
                    ];
                }

                return [
                    'id' => $request->id,
                    'created_at' => $request->created_at,
                    'outlet' => $request->outlet ? [
                        'id' => $request->outlet->id,
                        'nama' => $request->outlet->nama,
                    ] : null,
                    'user' => $userData,
                    'stock_item' => $request->stockItem ? [
                        'id' => $request->stockItem->id,
                        'name' => $request->stockItem->name,
                    ] : null,
                    'request_amount' => $request->request_amount,
                    'status' => $request->status,
                    'validator' => $request->validator ? [
                        'id' => $request->validator->id,
                        'name' => $request->validator->nama ?: $request->validator->username
                    ] : null
                ];
            });

            // Debug full data structure
            Log::info('Full stock requests data:', $stockRequests->toArray());

            return Inertia::render('StockRequests/Index', [
                'stockRequests' => $stockRequests,
                'stockItems' => StockItem::all(['id', 'name']),
                'outlets' => Outlet::all(['id', 'nama'])
            ]);

        } catch (\Exception $e) {
            Log::error('Error in index method:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function create()
    {
        return Inertia::render('StockRequests/Create');
    }

    public function store(Request $request)
    {
        try {
            Log::info('Raw request data:', $request->all());
            Log::info('Current user:', ['user' => Auth::user() ? (array)Auth::user() : null]);

            $validated = $request->validate([
                'outlet_id' => 'required|integer',
                'items' => 'required|array',
                'items.*.id' => 'required|integer|exists:stock_items,id',
                'items.*.amount' => 'required|integer|min:0'
            ]);

            DB::beginTransaction();
            
            try {
                $userId = Auth::id();
                $createdRequests = [];

                foreach ($request->items as $item) {
                    if (!empty($item['amount']) && $item['amount'] > 0) {
                        $stockRequest = StockRequest::create([
                            'outlet_id' => $request->outlet_id,
                            'stock_item_id' => $item['id'],
                            'request_amount' => $item['amount'],
                            'requested_by' => $userId,
                            'status' => StockRequest::STATUS_PENDING, // Use constant
                            'notes' => $request->notes ?? null
                        ]);

                        $createdRequests[] = $stockRequest->load('user', 'stockItem', 'outlet')->toArray();
                    }
                }

                DB::commit();
                
                Log::info('Created stock requests:', ['requests' => $createdRequests]);
                
                return redirect()->route('stock-requests.index')
                    ->with('success', 'Stock requests created successfully');

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }

        } catch (\Exception $e) {
            Log::error('Store method error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function show(StockRequest $stockRequest)
    {
        return Inertia::render('StockRequest/Show', [
            'stockRequest' => $stockRequest
        ]);
    }

    public function edit(StockRequest $stockRequest)
    {
        return Inertia::render('StockRequest/Edit', [
            'stockRequest' => $stockRequest
        ]);
    }

    public function update(Request $request, StockRequest $stockRequest)
    {
        $request->validate([
            'outlet_id' => 'required|exists:outlets,id',
            'stock_item_id' => 'required|exists:stock_items,id',
            'request_amount' => 'required|integer',
            'shift' => 'required|in:pagi,siang',
            'date' => 'required|date',
            'status' => 'required|in:pending,approved,rejected',
        ]);

        $stockRequest->update($request->all());

        return redirect()->route('stock-requests.index');
    }

    public function destroy(StockRequest $stockRequest)
    {
        $stockRequest->delete();

        return redirect()->route('stock-requests.index');
    }

    public function validateRequest(Request $request, StockRequest $stockRequest)
    {
        $validated = $request->validate([
            'notes' => 'nullable|string|max:255',
            'status' => 'required|in:pending,approved,rejected'
        ]);

        $stockRequest->update([
            'status' => $validated['status'],
            'validated_by' => Auth::id(),
            'validated_at' => now(),
            'notes' => $validated['notes'] ?? null
        ]);

        $statusMessage = match($validated['status']) {
            'approved' => 'approved',
            'rejected' => 'rejected',
            default => 'updated'
        };

        return redirect()->back()->with('success', "Stock request {$statusMessage} successfully");
    }
}
