<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/products', [ProductController::class, 'catalog'])
    ->name('products.catalog');

Route::get('/customers', [CustomerController::class, 'index'])
    ->name('customers.index');
