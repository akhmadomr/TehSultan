<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\Report;
use App\Models\StockTransaction;
use App\Models\StockItem;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StockController extends Controller
{
    public function index()
    {
        $reports = Report::where('type', 'stock')
            ->with('stockTransactions')
            ->latest()
            ->paginate(10);
            
        $defaultItems = StockItem::where('is_default', true)->get();

        return Inertia::render('Reports/Stock', [
            'reports' => $reports,
            'defaultItems' => $defaultItems
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.item_name' => 'required|string',
            'items.*.initial_stock' => 'required|numeric',
            'items.*.added_stock' => 'required|numeric',
            'items.*.total_stock' => 'required|numeric',
            'items.*.remaining_stock' => 'required|numeric',
            'items.*.used_stock' => 'required|numeric',
            'items.*.status' => 'required|in:completed,pending,cancelled',
        ]);

        // Create report with waiting status
        $report = Report::create([
            'type' => 'stock',
            'date' => now(),
            'status' => Report::STATUS_WAITING,
            'user_id' => \Illuminate\Support\Facades\Auth::user()->id,
        ]);

        // Create stock transactions for each item
        foreach ($request->items as $item) {
            StockTransaction::create([
                'report_id' => $report->id,
                'product_id' => StockItem::where('name', $item['item_name'])->first()->id,
                'stock_awal' => $item['initial_stock'],
                'stock_tambah' => $item['added_stock'],
                'stock_total' => $item['total_stock'],
                'stock_sisa' => $item['remaining_stock'],
                'stock_terpakai' => $item['used_stock'],
                'status' => $item['status']
            ]);
        }

        return redirect()->back()->with('success', 'Stock report submitted for validation');
    }
}