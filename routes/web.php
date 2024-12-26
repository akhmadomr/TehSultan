<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\CrewOutletController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\GudangDashboardController; // Add this import
use App\Http\Controllers\FinancialReportController; // Add this import
use App\Http\Middleware\CheckStockRequestRoles;
use App\Http\Controllers\StockRequestController;
use App\Http\Controllers\StockItemController;
use App\Http\Controllers\FinancialTotalController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckRole;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::get('/about', function () {
    return Inertia::render('AboutUs');
})->name('about');

Route::get('/produk', function () {
    return Inertia::render('Produk');
})->name('produk');

Route::get('/outlets', function () {
    return Inertia::render('Outlets');
})->name('outlet');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password.update');
    Route::get('/verify-update/{token}', [ProfileController::class, 'verifyUpdate'])->name('update.verify');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Manager routes
    Route::get('/manager-dashboard', function () {
        $activeUsers = \App\Models\User::where('is_active', true)->count();
        $financialSummary = \App\Models\FinancialSummary::getFinancialSummary();
        $latestReports = \App\Models\Report::latest()
            ->take(5)
            ->with(['user', 'outlet'])
            ->get();

        return Inertia::render('Dashboard/ManagerDashboard', [
            'activeUsers' => $activeUsers,
            'financialSummary' => $financialSummary,
            'latestReports' => $latestReports
        ]);
    })->middleware(CheckRole::class . ':manager')->name('manager.dashboard');
    
    Route::middleware(CheckRole::class . ':manager')->group(function () {
        // Individual routes first
        Route::get('manager/users', [UserController::class, 'index'])->name('manager.users.index');
        Route::post('manager/users', [UserController::class, 'store'])->name('manager.users.store');
        Route::get('manager/users/{user}', [UserController::class, 'show'])->name('manager.users.show');
        Route::patch('manager/users/{user}', [UserController::class, 'update'])->name('manager.users.update');
        Route::delete('manager/users/{user}', [UserController::class, 'destroy'])->name('manager.users.destroy');
        Route::patch('manager/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('manager.users.toggle-status');
        
        Route::resource('reports', ReportController::class);
        
        // Add StockItem routes
        Route::resource('stock-items', StockItemController::class)->except(['show']);
        
        // Manager Reports Routes
        Route::get('/manager/reports/stock', [ReportController::class, 'managerStockReports'])
            ->name('manager.reports.stock');
        Route::get('/manager/reports/financial', [ReportController::class, 'managerFinancialReports'])
            ->name('manager.reports.financial');
        Route::patch('/manager/reports/{report}/validate', [ReportController::class, 'validateReport'])
            ->name('manager.reports.validate');
        
        Route::patch('/manager/financial-reports/{report}/validate', [FinancialReportController::class, 'validate'])
            ->name('manager.financial-reports.validate');
    });

    // Crew Outlet routes  
    Route::get('/crew-dashboard', [CrewOutletController::class, 'dashboard'])
        ->middleware(CheckRole::class . ':crewoutlet')
        ->name('crew.dashboard');
    
    Route::middleware(CheckRole::class . ':crewoutlet')->group(function () {
        Route::resource('stock', StockController::class);
        Route::resource('sales', SaleController::class);
        Route::get('/financial-reports', [ReportController::class, 'financial'])->name('financial.reports');
        Route::get('/stock', [ReportController::class, 'stock'])->name('stock.index');
        Route::post('/stock', [ReportController::class, 'storeStock'])->name('stock.store');
        // Remove or comment out the old stock-reports routes
        // Route::get('/stock-reports'...
        // Route::post('/stock-reports'...
        Route::post('/reports', [ReportController::class, 'store'])->name('reports.store');
        Route::get('/stock/{id}', [StockController::class, 'show'])->name('stock.show');
        Route::put('/stock/{id}', [StockController::class, 'update'])->name('stock.update');
        
        // Add this inside the middleware group
        Route::get('/financial-totals/{outlet_id}', [FinancialTotalController::class, 'getLatestTotals'])
            ->name('financial.totals');
        Route::post('/reports/financial', [ReportController::class, 'storeFinancial'])
            ->name('reports.store.financial');
    });

    // Gudang routes
    Route::get('/gudang-dashboard', [GudangDashboardController::class, 'index'])
        ->middleware(CheckRole::class . ':gudang')
        ->name('gudang.dashboard');
    
    Route::middleware(CheckRole::class . ':gudang')->group(function () {
        Route::resource('inventory', StockController::class);
    });

    // Stock Request Routes - Accessible by both gudang and crewoutlet
    Route::middleware(['auth', 'verified'])->group(function () {
        Route::controller(StockRequestController::class)->group(function () {
            Route::get('/stock-requests', 'index')->name('stock-requests.index');
            Route::post('/stock-requests', 'store')->name('stock-requests.store'); // This is the important route
            Route::get('/stock-requests/create', 'create')->name('stock-requests.create');
            Route::get('/stock-requests/{stockRequest}', 'show')->name('stock-requests.show');
            Route::patch('/stock-requests/{stockRequest}', 'update')->name('stock-requests.update');
            Route::delete('/stock-requests/{stockRequest}', 'destroy')->name('stock-requests.destroy');
            
            // Gudang only route
            Route::patch('/stock-requests/{stockRequest}/validate', 'validateRequest')
                ->middleware(CheckRole::class . ':gudang')
                ->name('stock-requests.validate');
        });
    });
});

// Add this line before require __DIR__.'/auth.php'
Auth::routes(['verify' => true]);

require __DIR__.'/auth.php';
