<?php
namespace App\Http\Controllers;
use App\Models\Report;
use Inertia\Inertia;

class ReportController extends Controller
{
    public function index()
    {
        return Inertia::render('Reports/Index', [
            'reports' => Report::all()
        ]);
    }
}