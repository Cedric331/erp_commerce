<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StripeController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Cashier\Http\Controllers\PaymentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('/app');
});

Route::get('login', function () {
    return redirect('/app');
})->name('login')->middleware('guest');

Route::post('/delete-shop/{slug}', [ProfileController::class, 'deleteShop'])->name('shop.delete');

Route::post('/stripe_webhooks', [StripeController::class, 'handleWebhook']);
Route::get('/complete-payment/{paymentIntent}', [StripeController::class, 'completePayment'])->name('complete.payment');


