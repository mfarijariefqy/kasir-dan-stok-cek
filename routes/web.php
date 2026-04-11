<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\IngredientController;
use App\Http\Controllers\IngredientLogController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Redirect root to dashboard
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Auth routes
require __DIR__ . '/auth.php';

// Protected routes
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware('permission:view-dashboard')
        ->name('dashboard');

    // Profile routes
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    // Products routes
    Route::middleware('permission:manage-products')->group(function () {
        Route::resource('products', ProductController::class);
    });

    // Transactions routes
    Route::prefix('transactions')->group(function () {
        Route::get('/', [TransactionController::class, 'index'])
            ->middleware('permission:view-transactions|manage-transactions')
            ->name('transactions.index');

        Route::get('/create', [TransactionController::class, 'create'])
            ->middleware('permission:manage-transactions')
            ->name('transactions.create');

        Route::post('/', [TransactionController::class, 'store'])
            ->middleware('permission:manage-transactions')
            ->name('transactions.store');

        Route::get('/print-all', [TransactionController::class, 'printAll'])
            ->middleware('permission:view-transactions|manage-transactions')
            ->name('transactions.print-all');

        Route::get('/{transaction}', [TransactionController::class, 'show'])
            ->middleware('permission:view-transactions|manage-transactions')
            ->name('transactions.show');

        Route::get('/{transaction}/receipt', [TransactionController::class, 'receipt'])
            ->middleware('permission:view-transactions|manage-transactions')
            ->name('transactions.receipt');
    });

    // Ingredients routes
    Route::middleware('permission:manage-stock')->group(function () {
        Route::resource('ingredients', IngredientController::class);
        Route::resource('ingredient-logs', IngredientLogController::class)
            ->only(['index', 'create', 'store']);
    });

    // Reports routes
    Route::middleware('permission:view-reports')->prefix('reports')->group(function () {
        Route::get('/daily', [ReportController::class, 'daily'])->name('reports.daily');
        Route::get('/monthly', [ReportController::class, 'monthly'])->name('reports.monthly');
        Route::get('/stock', [ReportController::class, 'stock'])->name('reports.stock');
    });

    // Users & Roles routes
    Route::middleware('permission:manage-users')->group(function () {
        Route::resource('users', UserController::class);
    });
});
