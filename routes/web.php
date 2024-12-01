<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\StockRequestController;
use App\Http\Controllers\StockItemController;
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

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::middleware(['auth', 'verified'])->group(function () {
    // Manager routes
    Route::get('/manager-dashboard', function () {
        return Inertia::render('Dashboard/ManagerDashboard');
    })->middleware(CheckRole::class . ':manager')->name('manager.dashboard');
    
    Route::middleware(CheckRole::class . ':manager')->group(function () {
        Route::resource('manager/users', UserController::class)->names([
            'index' => 'manager.users.index',
            'create' => 'manager.users.create',
            'store' => 'manager.users.store',
            'show' => 'manager.users.show',
            'edit' => 'manager.users.edit',
            'update' => 'manager.users.update',
            'destroy' => 'manager.users.destroy',
        ]);
        Route::patch('manager/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('manager.users.toggle-status');
        Route::resource('reports', ReportController::class);
        
        // Add StockItem routes
        Route::resource('stock-items', StockItemController::class)->except(['show']);
    });

    // Crew Outlet routes  
    Route::get('/crew-dashboard', function () {
        return Inertia::render('Dashboard/CrewOutletDashboard');
    })->middleware(CheckRole::class . ':crewoutlet')->name('crew.dashboard');
    
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
    });

    // Gudang routes
    Route::get('/gudang-dashboard', function () {
        return Inertia::render('Dashboard/GudangDashboard');
    })->middleware(CheckRole::class . ':gudang')->name('gudang.dashboard');
    
    Route::middleware(CheckRole::class . ':gudang')->group(function () {
        Route::resource('inventory', StockController::class);
        Route::resource('stock-requests', StockRequestController::class);
    });
});

require __DIR__.'/auth.php';
