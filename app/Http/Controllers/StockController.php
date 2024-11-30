<?php
namespace App\Http\Controllers;
use App\Models\Stock;
use Inertia\Inertia;

class StockController extends Controller
{
    public function index()
    {
        return Inertia::render('Stock/Index', [
            'stocks' => Stock::all()
        ]);
    }
}