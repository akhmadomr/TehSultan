<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\Report;
use App\Models\StockTransaction;
use App\Models\StockItem;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

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

    public function show($id)
    {
        $report = Report::with(['items'])->findOrFail($id);
        return response()->json($report);
    }

    public function store(Request $request)
    {
        $request->validate([
            'outlet_id' => 'required|exists:outlets,id',
            'shift' => 'required|in:pagi,siang',
            'items' => 'required|array',
            'items.*.item_name' => 'required|string',
            'items.*.initial_stock' => 'required|numeric',
            'items.*.added_stock' => 'required|numeric',
            'items.*.total_stock' => 'required|numeric',
            'items.*.remaining_stock' => 'required|numeric',
            'items.*.used_stock' => 'required|numeric',
            'items.*.status' => 'required|in:completed,pending,cancelled',
        ]);

        // Start transaction
        DB::beginTransaction();
        try {
            // Create report
            $report = Report::create([
                'outlet_id' => $request->outlet_id,
                'user_id' => \Illuminate\Support\Facades\Auth::user()->id,
                'type' => 'stock',
                'status' => 'waiting',
                'shift' => $request->shift
            ]);

            // Create stock entries
            foreach ($request->items as $item) {
                Stock::create([
                    'outlet_id' => $request->outlet_id,
                    'report_id' => $report->id,
                    'item_name' => $item['item_name'],
                    'initial_stock' => $item['initial_stock'],
                    'added_stock' => $item['added_stock'],
                    'total_stock' => $item['total_stock'],
                    'remaining_stock' => $item['remaining_stock'],
                    'used_stock' => $item['used_stock'],
                    'status' => $item['status']
                ]);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Stock report created successfully');
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'outlet_id' => 'required|exists:outlets,id',
            'shift' => 'required|in:pagi,siang',
            'items' => 'required|array'
        ]);

        DB::beginTransaction();
        try {
            $report = Report::findOrFail($id);
            
            // Update report
            $report->update([
                'outlet_id' => $request->outlet_id,
                'shift' => $request->shift
            ]);

            // Delete old stock entries
            Stock::where('report_id', $report->id)->delete();

            // Create new stock entries
            foreach ($request->items as $item) {
                Stock::create([
                    'outlet_id' => $request->outlet_id,
                    'report_id' => $report->id,
                    'item_name' => $item['item_name'],
                    'initial_stock' => $item['initial_stock'],
                    'added_stock' => $item['added_stock'],
                    'total_stock' => $item['total_stock'],
                    'remaining_stock' => $item['remaining_stock'],
                    'used_stock' => $item['used_stock'],
                    'status' => $item['status']
                ]);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Stock report updated successfully');
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}