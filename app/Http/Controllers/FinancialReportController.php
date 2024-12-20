<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FinancialReportController extends Controller
{
    public function validate(Request $request, Report $report)
    {
        $validated = $request->validate([
            'status' => 'required|in:validated,rejected'
        ]);

        $report->update([
            'status' => $validated['status'],
            'validated_by' => Auth::user()->id,
            'validated_at' => now()
        ]);

        return response()->json(['message' => 'Report status updated successfully']);
    }
}
