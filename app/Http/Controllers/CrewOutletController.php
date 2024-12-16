<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Report;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CrewOutletController extends Controller
{
    public function dashboard()
    {
        try {
            $userId = Auth::id();
            $user = Auth::user();

            // Get stock reports count
            $stockReports = Report::where('type', 'stock')
                ->where('user_id', $userId)
                ->count();

            // Get financial reports count
            $financialReports = Report::where('type', 'financial')
                ->where('user_id', $userId)
                ->count();

            // Get latest 5 reports with related data
            $reports = Report::with(['user', 'outlet'])
                ->where('user_id', $userId)
                ->latest()
                ->take(5)
                ->get();

            Log::info('Dashboard Data Loaded:', [
                'user_id' => $userId,
                'stock_count' => $stockReports,
                'financial_count' => $financialReports,
                'all_reports' => $reports->count()
            ]);

            return Inertia::render('Dashboard/CrewOutletDashboard', [
                'stockReports' => $stockReports,
                'financialReports' => $financialReports,
                'allReports' => [
                    'data' => $reports
                ],
                'user' => $user
            ]);
        } catch (\Exception $e) {
            Log::error('Dashboard Error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return Inertia::render('Dashboard/CrewOutletDashboard', [
                'stockReports' => 0,
                'financialReports' => 0,
                'allReports' => [
                    'data' => [],
                    'links' => [],
                    'meta' => ['total' => 0]
                ]
            ]);
        }
    }
}
