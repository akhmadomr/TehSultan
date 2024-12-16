<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\StockRequest;
use App\Models\StockItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
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
        $stockRequests = StockRequest::with(['requestedBy', 'validatedBy', 'outlet', 'stockItem'])
            ->when(Auth::user()->role === 'crewoutlet', function ($query) {
                return $query->where('requested_by', Auth::id());
            })
            ->latest()
            ->get();

        $stockItems = StockItem::all(['id', 'name']); // Keep using 'name' since that's what's in the database
        Log::info('Stock Items being sent to view:', $stockItems->toArray());

        return Inertia::render('StockRequests/Index', [
            'stockRequests' => $stockRequests,
            'outlets' => \App\Models\Outlet::all(['id', 'nama']),
            'stockItems' => $stockItems
        ]);
    }

    public function create()
    {
        return Inertia::render('StockRequests/Create');
    }

    public function store(Request $request)
    {
        try {
            Log::info('Raw request data:', $request->all());

            $validated = $request->validate([
                'outlet_id' => 'required|integer',
                'items' => 'required|array',
                'items.*.id' => 'required|integer|exists:stock_items,id',
                'items.*.amount' => 'required|integer|min:0'
            ]);

            DB::beginTransaction();
            
            try {
                foreach ($request->items as $item) {
                    if (!empty($item['amount']) && $item['amount'] > 0) {
                        StockRequest::create([
                            'outlet_id' => $request->outlet_id,
                            'stock_item_id' => $item['id'],
                            'request_amount' => $item['amount'],
                            'requested_by' => Auth::id(),
                            'status' => 'pending',
                            'notes' => $request->notes ?? null
                        ]);
                    }
                }

                DB::commit();
                return redirect()->route('stock-requests.index')
                    ->with('success', 'Stock requests created successfully');

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }

        } catch (\Exception $e) {
            Log::error('Store method error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTrace()
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
}
