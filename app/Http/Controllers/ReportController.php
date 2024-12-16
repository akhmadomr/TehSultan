<?php
namespace App\Http\Controllers;
use App\Models\Report;
use App\Models\Outlet;
use App\Models\Stock;
use App\Models\StockItem;
use App\Models\StockTransaction;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        $reports = Report::with(['user', 'outlet', 'validator'])
            ->latest()
            ->paginate(10);

        return Inertia::render('Reports/Index', [
            'reports' => $reports
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'outlet_id' => 'required|exists:outlets,id',
            'shift' => 'required|in:pagi,siang',  // Add shift validation
            'type' => 'required|in:stock,financial',
            'total_income' => 'required_if:type,financial|numeric',
            'total_expense' => 'required_if:type,financial|numeric',
            'net_income' => 'required_if:type,financial|numeric',
            'real_cash' => 'required_if:type,financial|numeric',
            'difference' => 'required_if:type,financial|numeric'
        ]);

        $report = Report::create([
            ...$validated,
            'user_id' => Auth::id(),
            'status' => 'waiting',
            'total_income' => $request->total_income ?? 0,
            'total_expense' => $request->total_expense ?? 0,
            'net_income' => $request->net_income ?? 0,
            'real_cash' => $request->real_cash ?? 0,
            'difference' => $request->difference ?? 0
        ]);

        return redirect()->back()->with('success', 'Report created successfully');
    }

    public function validate(Report $report, Request $request)
    {
        $request->validate([
            'status' => 'required|in:validated,rejected'
        ]);

        $report->update([
            'status' => $request->status,
            'validated_by' => Auth::user()->id,
            'validated_at' => now()
        ]);

        return redirect()->back()->with('success', 'Report status updated');
    }

    public function financial()
    {
        $reports = Report::with(['user', 'outlet', 'validator', 'financialItems'])
            ->where('type', 'financial')
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return Inertia::render('Reports/Financial', [
            'reports' => $reports,
            'outlets' => Outlet::all()  // Add this line to provide outlets data
        ]);
    }

    public function stock()
    {
        try {
            $outlets = Outlet::all();

            $data = [
                'reports' => Report::with(['user']) // Remove the select constraint to get all user fields
                    ->where('type', 'stock')
                    ->latest()
                    ->paginate(10),
                'defaultItems' => StockItem::all(),
                'outlets' => $outlets
            ];

            // Add debug logging
            Log::info('Reports with users:', [
                'first_report' => $data['reports']->items()[0] ?? null,
                'user_data' => collect($data['reports']->items())->first()?->user
            ]);

            return Inertia::render('Reports/Stock', $data);
        } catch (\Exception $e) {
            Log::error('Error in stock method:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function storeStock(Request $request)
    {
        $request->validate([
            'outlet_id' => 'required|exists:outlets,id',
            'shift' => 'required|in:pagi,siang',  // Add this validation
            'items' => 'required|array'
        ]);

        $report = Report::create([
            'outlet_id' => $request->outlet_id,
            'user_id' => Auth::id(),
            'type' => 'stock',
            'status' => 'waiting',
            'shift' => $request->shift  // Add this
        ]);

        // Store report items
        foreach ($request->items as $item) {
            $report->items()->create([
                'item_name' => $item['item_name'],
                'initial_stock' => $item['initial_stock'],
                'added_stock' => $item['added_stock'],
                'total_stock' => $item['total_stock'],
                'remaining_stock' => $item['remaining_stock'],
                'used_stock' => $item['used_stock'],
                'status' => $item['status']
            ]);
        }

        return redirect()->back()->with('success', 'Report created successfully');
    }

    public function storeFinancial(Request $request)
    {
        $validated = $request->validate([
            'outlet_id' => 'required|exists:outlets,id',
            'shift' => 'required|in:pagi,siang',
            'income_items' => 'required|array',
            'expense_items' => 'required|array',
            'real_cash' => 'required|numeric'
        ]);

        DB::beginTransaction();
        try {
            $report = Report::create([
                'outlet_id' => $validated['outlet_id'],
                'user_id' => Auth::id(),
                'type' => 'financial',
                'shift' => $validated['shift'],
                'status' => 'waiting'
            ]);

            // Store income items
            foreach ($request->income_items as $item) {
                if ($item['units_sold'] > 0) { // Only store items with quantity
                    $report->financialItems()->create([
                        'outlet_id' => $validated['outlet_id'],
                        'type' => 'income',
                        'item_name' => $item['name'],
                        'price' => $item['price'],
                        'quantity' => $item['units_sold'],
                        'total' => $item['total']
                    ]);
                }
            }

            // Store expense items
            foreach ($request->expense_items as $item) {
                if ($item['units_bought'] > 0) { // Only store items with quantity
                    $report->financialItems()->create([
                        'outlet_id' => $validated['outlet_id'],
                        'type' => 'expense',
                        'item_name' => $item['name'],
                        'price' => $item['price'],
                        'quantity' => $item['units_bought'],
                        'total' => $item['total']
                    ]);
                }
            }

            DB::commit();
            return redirect()->back()->with('success', 'Financial report created successfully');
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function managerStockReports()
    {
        $reports = Report::with(['user', 'outlet', 'items', 'validator'])
            ->where('type', 'stock')
            ->latest()
            ->paginate(10);

        // Add validation messages to each report
        $reports->through(function ($report) {
            $report->validation_messages = $report->getValidationMessages();
            return $report;
        });

        return Inertia::render('Reports/StockReportManager', [
            'reports' => $reports
        ]);
    }

    public function managerFinancialReports()
    {
        $reports = Report::with(['user', 'outlet', 'financialItems', 'validator'])
            ->where('type', 'financial')
            ->latest()
            ->paginate(10);

        // Add validation messages to each report
        $reports->getCollection()->transform(function ($report) {
            $report->validation_messages = $report->getValidationMessages();
            return $report;
        });

        return Inertia::render('Reports/FinancialReportManager', [
            'reports' => $reports
        ]);
    }

    public function validateReport(Report $report, Request $request)
    {
        $validated = $request->validate([
            'status' => 'required|in:validated,rejected'
        ]);

        $report->update([
            'status' => $validated['status'],
            'validated_by' => Auth::id(),
            'validated_at' => now()
        ]);

        return redirect()->back()->with('success', 'Report status updated successfully');
    }
}