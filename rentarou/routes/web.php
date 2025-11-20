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
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    
    // Items - Full CRUD
    Route::resource('items', App\Http\Controllers\Admin\ItemController::class);
    
    // Categories - Full CRUD
    Route::resource('categories', App\Http\Controllers\Admin\CategoryController::class)->except(['show']);
    
    // Rentals
    Route::resource('rentals', App\Http\Controllers\Admin\RentalController::class);

    
    Route::get('/rentals/create', function () {
        return 'Create rental coming soon';
    })->name('rentals.create');
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
// Public item routes
Route::get('/items/{id}', function($id) {
    return 'Item details page - will create next';
})->name('items.show');

// Invoice routes
Route::middleware('auth')->get('/invoices', function() {
    return 'Invoices page';
})->name('invoices.index');

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