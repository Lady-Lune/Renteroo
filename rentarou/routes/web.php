<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Customer\DashboardController as CustomerDashboardController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

/*
|--------------------------------------------------------------------------
| Authentication Routes (Laravel UI)
|--------------------------------------------------------------------------
*/

Auth::routes();

/*
|--------------------------------------------------------------------------
| Redirect after login based on role
|--------------------------------------------------------------------------
*/

Route::get('/home', function () {
    if (!auth()->check()) {
        return redirect()->route('login');
    }
    
    if (auth()->user()->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }
    
    return redirect()->route('customer.dashboard');
})->middleware('auth')->name('home');

/*
|--------------------------------------------------------------------------
| Admin Routes (Protected)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // Items (Placeholder routes - will create controllers later)
    Route::get('/items', function () {
        return '<h1>Admin Items Page</h1><p>This will be the items management page.</p>';
    })->name('items.index');
    
    Route::get('/items/create', function () {
        return '<h1>Create Item Page</h1><p>Form to create new item will be here.</p>';
    })->name('items.create');
    
    // Rentals (Placeholder)
    Route::get('/rentals', function () {
        return '<h1>Admin Rentals Page</h1><p>Manage all rentals here.</p>';
    })->name('rentals.index');
    
    Route::get('/rentals/create', function () {
        return '<h1>Create Rental Page</h1><p>Form to create new rental.</p>';
    })->name('rentals.create');
    
    Route::get('/rentals/{id}', function ($id) {
        return '<h1>Rental Details #' . $id . '</h1>';
    })->name('rentals.show');
    
    // Categories (Placeholder)
    Route::get('/categories', function () {
        return '<h1>Categories Page</h1><p>Manage categories here.</p>';
    })->name('categories.index');
});

/*
|--------------------------------------------------------------------------
| Customer Routes (Protected)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'customer'])->prefix('customer')->name('customer.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [CustomerDashboardController::class, 'index'])->name('dashboard');
    
    // Rentals (Placeholder)
    Route::get('/rentals', function () {
        return '<h1>My Rentals Page</h1><p>Your rental history.</p>';
    })->name('rentals.index');
    
    Route::get('/rentals/{id}', function ($id) {
        return '<h1>Rental Details #' . $id . '</h1>';
    })->name('rentals.show');
});

/*
|--------------------------------------------------------------------------
| Public Item Routes (Anyone can view)
|--------------------------------------------------------------------------
*/

Route::get('/items', function () {
    return '<h1>Public Items Catalog</h1><p>Browse available items.</p>';
})->name('items.index');

Route::get('/items/{id}', function ($id) {
    return '<h1>Item Details #' . $id . '</h1>';
})->name('items.show');

/*
|--------------------------------------------------------------------------
| Invoice Routes (Authenticated users only)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->prefix('invoices')->name('invoices.')->group(function () {
    Route::get('/', function () {
        return '<h1>My Invoices</h1><p>Your invoice history.</p>';
    })->name('index');
    
    Route::get('/{id}', function ($id) {
        return '<h1>Invoice #' . $id . '</h1>';
    })->name('show');
});