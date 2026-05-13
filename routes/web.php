<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FishCategoryController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\PasswordResetController;

// Authentication Routes
Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Customer registration and signup
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// Email Verification Routes
Route::get('/email/verify/{token}', [EmailVerificationController::class, 'verify'])->name('email.verify');
Route::post('/email/resend', [EmailVerificationController::class, 'resend'])->name('email.resend');

// Password Reset Routes
Route::get('/password/reset', [PasswordResetController::class, 'showRequestForm'])->name('password.request');
Route::post('/password/email', [PasswordResetController::class, 'sendResetLink'])->name('password.email');
Route::get('/password/reset/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
Route::post('/password/reset', [PasswordResetController::class, 'reset'])->name('password.update');

// First-time Password Change Route
Route::get('/password/change-first/{email}', [AuthController::class, 'showFirstPasswordChangeForm'])->name('password.change.first');
Route::post('/password/change-first', [AuthController::class, 'handleFirstPasswordChange'])->name('password.change.first.handle');

// Registration Link Sharing Route
Route::get('/register-link/{token}', [AuthController::class, 'showRegistrationFromLink'])->name('register.link');

// Dashboard Routes
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard');

// Customer Catalog Route
Route::get('/catalog', [FishCategoryController::class, 'browse'])->middleware('auth')->name('catalog.index');

// User Management Routes (Admin only)
Route::middleware(['auth', 'role:admin'])->prefix('users')->name('users.')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('index');
    Route::get('/create', [UserController::class, 'create'])->name('create');
    Route::post('/', [UserController::class, 'store'])->name('store');
    Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
    Route::put('/{user}', [UserController::class, 'update'])->name('update');
    Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
});

// Profile Routes (All authenticated users)
Route::middleware(['auth'])->prefix('profile')->name('profile.')->group(function () {
    Route::get('/', [UserController::class, 'profile'])->name('index');
    Route::put('/', [UserController::class, 'updateProfile'])->name('update');
    Route::put('/password', [UserController::class, 'updatePassword'])->name('password.update');
});

// Settings Routes (Admin only)
Route::middleware(['auth', 'role:admin'])->prefix('settings')->name('settings.')->group(function () {
    Route::get('/', [SettingsController::class, 'index'])->name('index');
    Route::put('/', [SettingsController::class, 'update'])->name('update');
});

// Fish Category Routes (Admin only)
Route::middleware(['auth', 'role:admin'])->prefix('fish-categories')->name('fish-categories.')->group(function () {
    Route::get('/', [FishCategoryController::class, 'index'])->name('index');
    Route::get('/create', [FishCategoryController::class, 'create'])->name('create');
    Route::post('/', [FishCategoryController::class, 'store'])->name('store');
    Route::get('/{fishCategory}/edit', [FishCategoryController::class, 'edit'])->name('edit');
    Route::put('/{fishCategory}', [FishCategoryController::class, 'update'])->name('update');
    Route::delete('/{fishCategory}', [FishCategoryController::class, 'destroy'])->name('destroy');
});

// Stock Routes
Route::middleware(['auth'])->prefix('stocks')->name('stocks.')->group(function () {
    Route::get('/', [StockController::class, 'index'])->name('index');
    Route::get('/create', [StockController::class, 'create'])->name('create');
    Route::post('/', [StockController::class, 'store'])->name('store');
    Route::get('/{stock}', [StockController::class, 'show'])->name('show');
    Route::get('/{stock}/edit', [StockController::class, 'edit'])->name('edit');
    Route::put('/{stock}', [StockController::class, 'update'])->name('update');
    Route::post('/{stock}/approve', [StockController::class, 'approve'])->name('approve');
    Route::post('/{stock}/reject', [StockController::class, 'reject'])->name('reject');
    Route::delete('/{stock}', [StockController::class, 'destroy'])->name('destroy');
});

// Sale Routes
Route::middleware(['auth'])->prefix('sales')->name('sales.')->group(function () {
    Route::get('/', [SaleController::class, 'index'])->name('index');
    Route::get('/create', [SaleController::class, 'create'])->name('create');
    Route::post('/', [SaleController::class, 'store'])->name('store');
    Route::get('/{sale}', [SaleController::class, 'show'])->name('show');
    Route::get('/report', [SaleController::class, 'report'])->name('report');
    Route::delete('/{sale}', [SaleController::class, 'destroy'])->name('destroy');
});

// Test route for debugging
Route::get('/test-purchase', function() {
    return 'Test route is working';
});

// Direct test route
Route::get('/purchase-create-test', function() {
    return view('purchases.create');
});

// Alternative purchase create route
Route::middleware(['auth'])->get('/purchase/create', [PurchaseController::class, 'create'])->name('purchase.create.alt');

// Purchase Routes
Route::middleware(['auth'])->prefix('purchases')->name('purchases.')->group(function () {
    Route::get('/', [PurchaseController::class, 'index'])->name('index');
    Route::get('/{purchase}', [PurchaseController::class, 'show'])->name('show');
    Route::get('/report', [PurchaseController::class, 'report'])->name('report');
});

// Purchase Management Routes (Admin only)
Route::middleware(['auth', 'role:admin'])->get('/purchases/create', [PurchaseController::class, 'create'])->name('purchases.create');
Route::middleware(['auth', 'role:admin'])->post('/purchases', [PurchaseController::class, 'store'])->name('purchases.store');
Route::middleware(['auth', 'role:admin'])->get('/purchases/{purchase}/edit', [PurchaseController::class, 'edit'])->name('purchases.edit');
Route::middleware(['auth', 'role:admin'])->put('/purchases/{purchase}', [PurchaseController::class, 'update'])->name('purchases.update');
Route::middleware(['auth', 'role:admin'])->delete('/purchases/{purchase}', [PurchaseController::class, 'destroy'])->name('purchases.destroy');

// Report Routes
Route::middleware(['auth'])->prefix('reports')->name('reports.')->group(function () {
    Route::get('/', [ReportController::class, 'index'])->name('index');
    Route::get('/all-activities', [ReportController::class, 'generateAllActivities'])->name('all_activities');
    Route::get('/stock', [ReportController::class, 'generateStockReport'])->name('stock');
    Route::get('/sales', [ReportController::class, 'generateSalesReport'])->name('sales');
    Route::get('/purchases', [ReportController::class, 'generatePurchaseReport'])->name('purchases');
});
