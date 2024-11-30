<?php

namespace App\Http\Controllers;

use App\Models\StockRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StockRequestController extends Controller
{
    public function index()
    {
        return Inertia::render('StockRequest/Index', [
            'stockRequests' => StockRequest::all()
        ]);
    }

    public function create()
    {
        return Inertia::render('StockRequest/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'outlet_id' => 'required|exists:outlets,id',
            'product_id' => 'required|exists:products,id',
            'request_amount' => 'required|integer',
        ]);

        StockRequest::create($request->all());

        return redirect()->route('stock-requests.index');
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
            'product_id' => 'required|exists:products,id',
            'request_amount' => 'required|integer',
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
