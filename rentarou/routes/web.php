<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Customer\DashboardController;
use App\Http\Controllers\Customer\InvoiceController;

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
    
    // Categories (placeholder for now)
    Route::get('/categories', function () {
        return 'Categories management coming soon';
    })->name('categories.index');
    
    // Rentals (placeholder for now)
    Route::get('/rentals', function () {
        return 'Rentals management coming soon';
    })->name('rentals.index');
    
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
    Route::get('/dashboard', [App\Http\Controllers\Customer\DashboardController::class, 'index'])->name('dashboard');
    
    // Rentals
    Route::get('/rentals', [App\Http\Controllers\Customer\RentalController::class, 'index'])->name('rentals.index');
    Route::get('/rentals/{rental}', [App\Http\Controllers\Customer\RentalController::class, 'show'])->name('rentals.show');

    // Invoices
    Route::get('/invoices', [App\Http\Controllers\Customer\InvoiceController::class, 'index'])->name('invoices.index');
    Route::get('/invoices/{invoice}', [App\Http\Controllers\Customer\InvoiceController::class, 'show'])->name('invoices.show');
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