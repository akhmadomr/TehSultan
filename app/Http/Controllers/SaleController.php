<?php
namespace App\Http\Controllers;
use App\Models\Sale;
use Inertia\Inertia;

class SaleController extends Controller
{
    public function index()
    {
        return Inertia::render('Sales/Index', [
            'sales' => Sale::all()
        ]);
    }
}