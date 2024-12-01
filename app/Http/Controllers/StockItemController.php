<?php

namespace App\Http\Controllers;

use App\Models\StockItem;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StockItemController extends Controller
{
    public function index()
    {
        $stockItems = StockItem::orderBy('name')->paginate(10);
        return Inertia::render('StockItems/Index', [
            'stockItems' => $stockItems
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:stock_items',
            'is_default' => 'boolean'
        ]);

        StockItem::create($validated);

        return redirect()->back()->with('success', 'Stock item created successfully');
    }

    public function update(Request $request, StockItem $stockItem)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:stock_items,name,' . $stockItem->id,
            'is_default' => 'boolean'
        ]);

        $stockItem->update($validated);

        return redirect()->back()->with('success', 'Stock item updated successfully');
    }

    public function destroy(StockItem $stockItem)
    {
        $stockItem->delete();
        return redirect()->back()->with('success', 'Stock item deleted successfully');
    }
}
