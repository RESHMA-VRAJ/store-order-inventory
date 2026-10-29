<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CustomerOrderController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| These routes are loaded by the RouteServiceProvider within a group
| which is assigned the "api" middleware group.
|--------------------------------------------------------------------------
*/
// Route::name('api.')->middleware('auth:sanctum')->group(function () {
// Create an order and deduct stock
// Route::post('/customer-orders', [CustomerOrderController::class, 'store'])
//     ->name('customer-orders.store');
Route::post('/orders', [CustomerOrderController::class, 'store']);

// Fetch all products (for order form dropdown)
Route::get('/products', [ProductController::class, 'index']);

// Fetch customer by email (for order form lookup)
Route::get('/customers/lookup', [CustomerController::class, 'lookup']);

// Fetch customer order history by email
// Route::get('/customers/orders/{email}', [CustomerController::class, 'orderHistory'])
//     ->name('customers.orders');
Route::get(
    '/customers/orders',
    [CustomerController::class, 'orderHistory']
);

// Fetch products below a certain stock threshold
Route::get('/products/low-stock', [ProductController::class, 'lowStock'])
    ->name('products.lowStock');
// });
