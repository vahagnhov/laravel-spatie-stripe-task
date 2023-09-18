<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\HomeController;
use App\Constants\Roles;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::post('/cancel-purchase', [HomeController::class, 'cancelPurchase'])->name('cancel-purchase');

Route::middleware("auth")->group(function () {
    Route::get('products', [ProductController::class, 'index'])
        ->middleware('role:' . Roles::B2B_CUSTOMER . '|' . Roles::B2C_CUSTOMER);
    Route::get('products/{product}', [ProductController::class, 'show'])->name("products.show")
        ->middleware('role:' . Roles::B2B_CUSTOMER . '|' . Roles::B2C_CUSTOMER);
    Route::post('purchase', [ProductController::class, 'purchase'])->name("purchase.create")
        ->middleware('role:' . Roles::B2B_CUSTOMER . '|' . Roles::B2C_CUSTOMER);
});
