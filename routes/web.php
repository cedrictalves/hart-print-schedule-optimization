<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ScheduleController;
// Show the welcome page
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Show the order creation form
Route::get('/order', [OrderController::class, 'display'])->name('order.form');

// Store the order in the database
Route::post('/create-order', [OrderController::class, 'store'])->name('order.store');

// Store the order in the database
Route::patch('/orders/{order}/complete', [OrderController::class, 'markAsComplete'])->name('order.complete');

// Get products dynamically by type (AJAX)
Route::get('/product-type', [OrderController::class, 'getProductsByType'])->name('product.byType');

// Show the schedule page
Route::get('/schedule', [ScheduleController::class, 'display'])->name('schedule');
