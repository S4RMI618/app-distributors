<?php

use App\Http\Middleware\RoleMiddleware;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CustomerDetailController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    $user = Auth::user();
    return view('dashboard', compact('user'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rutas protegidas de compañías, usuarios, detalles de clientes y órdenes

    Route::resource('companies', CompanyController::class)->middleware([RoleMiddleware::class]);
    Route::resource('users', UserController::class)->middleware([RoleMiddleware::class]);
    Route::resource('customer-details', CustomerDetailController::class);
    Route::resource('orders', OrderController::class);
});

/* ------------------------------------------ Rutas consumo API --------------------------------------*/
/* Creación de productos */
Route::post('/products/store', [ProductController::class, 'store'])->name('products.store');
Route::get('/products/get', [ProductController::class, 'getProducts'])->name('products.get');

/* Rutas secundarias */
Route::get('/get-municipalities', [LocationController::class, 'getMunicipalities'])->name('get-municipalities');

Route::get('/products/search', [ProductController::class, 'searchProducts'])->name('products.search');

/* Busqueda de Customers */
Route::get('/customers/search', [CustomerDetailController::class, 'search'])->name('customers.search');

/* Busqueda de Orders */
Route::get('/orders/search/{companyId}/{status}', [OrderController::class, 'downloadOrders'])->name('orders.downloadOrders');

/* Route::prefix('AppPedidos')->group(function () {
    Route::get('products/search', [ProductController::class, 'search'])->name('products.search');
    Route::get('/customers/search', [CustomerDetailController::class, 'search'])->name('customers.search');
}); */


require __DIR__ . '/auth.php';
