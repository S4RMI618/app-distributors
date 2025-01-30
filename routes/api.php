<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerDetailController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

/* PRODUCTS */
Route::post('/products/store', [ProductController::class, 'store']);
Route::get('/products/get', [ProductController::class, 'getProducts']);

/* CUSTOMERS */
Route::post('customer-details/create-customers', [CustomerDetailController::class, 'storeCustomers']);

/* ORDERS */
Route::get('/orders/search/{companyId}/{status}', [OrderController::class, 'downloadOrders']);
Route::get('/orders/update/{orderId}', [OrderController::class, 'updateOrderStatus']);


/* Route::get('/csrf',  function() {
    return response()->json(['token' => csrf_token()]);
}); */