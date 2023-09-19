<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StripeWebhookController;
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
Route::get('/', [IndexController::class, 'index'])->name('home');
Route::post('/stripe/webhook', [StripeWebhookController::class, 'handleWebhook'])->name('stripe.webhook');

Auth::routes();

Route::middleware(['auth', 'role:' . Roles::B2B_CUSTOMER . '|' . Roles::B2C_CUSTOMER])->group(function () {
    Route::get('products', [ProductController::class, 'index']);
    Route::get('products/{product}', [ProductController::class, 'show'])->name("products.show");
    Route::post('purchase', [ProductController::class, 'purchase'])->name("purchase.create");
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/cancel-purchase', [DashboardController::class, 'cancelPurchase'])->name('cancel-purchase');
});
