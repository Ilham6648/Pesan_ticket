<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\OrderController;

Route::get('/', function () {
    return view('welcome');
});

// Route untuk customer (bisa diakses tanpa login)
Route::get('/customer', [OrderController::class, 'index'])->name('customer.index');
Route::post('/customer/checkout', [OrderController::class, 'store'])->name('customer.checkout');

// Route Dashboard Admin
Route::get('/dashboard', [OrderController::class, 'dashboard'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // CRUD Event
    Route::resource('events', EventController::class);

    // Update status pesanan di dashboard
    Route::patch('/admin/orders/{id}/status', [OrderController::class, 'updateDashboard'])->name('admin.orders.updateStatus');
});

require __DIR__.'/auth.php';