<?php

namespace App\Http\Controllers;

use App\Models\StockRequest;
use App\Models\StockItem;
use Illuminate\Http\Request;
use Inertia\Inertia;

class GudangDashboardController extends Controller
{
    public function index()
    {
        $pendingRequests = StockRequest::where('status', 'pending')->count();
        $totalInventoryItems = StockItem::count();
        
        $stockRequests = StockRequest::with(['outlet', 'stockItem', 'user'])
            ->latest()
            ->take(5)  // Add this line to limit to 5 items
            ->get()
            ->map(function ($request) {
                return [
                    'id' => $request->id,
                    'created_at' => $request->created_at,
                    'outlet' => [
                        'id' => $request->outlet->id,
                        'nama' => $request->outlet->nama,
                    ],
                    'items' => [[
                        'id' => $request->stockItem->id,
                        'name' => $request->stockItem->name,
                        'amount' => $request->request_amount,
                    ]],
                    'status' => $request->status,
                    'user' => [
                        'id' => $request->user->id,
                        'name' => $request->user->nama,
                    ],
                ];
            });

        return Inertia::render('Dashboard/GudangDashboard', [
            'pendingRequests' => $pendingRequests,
            'totalInventoryItems' => $totalInventoryItems,
            'stockRequests' => $stockRequests
        ]);
    }
}
