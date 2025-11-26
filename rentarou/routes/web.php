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

     Route::post('/rentals/quick-register', [App\Http\Controllers\Admin\RentalController::class, 'quickRegister'])->name('rentals.quickRegister');
     
     // Invoice via rentals (deprecated - redirect to invoices portal)
     Route::get('/rentals/{rental}/invoice', function (App\Models\Rental $rental) {
         return redirect()->route('invoices.download', $rental->invoice_id ?? 0);
     })->name('admin.rentals.invoice');

     // Cancel rental
     Route::patch('/rentals/{rental}/cancel', [App\Http\Controllers\Admin\RentalController::class, 'cancel'])
         ->name('rentals.cancel');

     // Mark rental as returned
     Route::patch('/rentals/{rental}/return', [App\Http\Controllers\Admin\RentalController::class, 'markReturned'])
         ->name('rentals.return');

});

/*
|--------------------------------------------------------------------------
| Customer Routes (Protected)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'customer'])->prefix('customer')->name('customer.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [CustomerDashboardController::class, 'index'])->name('dashboard');
    
    // Rentals
    Route::get('/rentals', [App\Http\Controllers\Customer\RentalController::class, 'index'])->name('rentals.index');
    Route::get('/rentals/{rental}', [App\Http\Controllers\Customer\RentalController::class, 'show'])->name('rentals.show');
    
    // Invoice download (deprecated - redirect to invoices portal)
    Route::get('/rentals/{rental}/download-invoice', function (App\Models\Rental $rental) {
        // Authorization check
        if ($rental->user_id !== auth()->id()) {
            abort(403);
        }
        return redirect()->route('invoices.download', $rental->invoice_id ?? 0);
    })->name('rentals.download-invoice');

    Route::post('/rentals', [App\Http\Controllers\Customer\RentalController::class, 'store'])
    ->name('rentals.store');

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

// Public Item Routes (Anyone can view)
Route::get('/items', [App\Http\Controllers\ItemController::class, 'index'])->name('items.index');
Route::get('/items/{id}', [App\Http\Controllers\ItemController::class, 'show'])->name('items.show');


/*
|--------------------------------------------------------------------------
| Invoice Routes (Authenticated users only)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->prefix('invoices')->name('invoices.')->group(function () {
    Route::get('/', [App\Http\Controllers\InvoiceController::class, 'index'])->name('index');
    Route::get('/{invoice}', [App\Http\Controllers\InvoiceController::class, 'show'])->name('show');
    Route::get('/{invoice}/download', [App\Http\Controllers\InvoiceController::class, 'download'])->name('download');
    Route::post('/{invoice}/mark-paid', [App\Http\Controllers\InvoiceController::class, 'markPaid'])->middleware('admin')->name('mark-paid');
});