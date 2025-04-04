<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StripeController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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
    return Inertia::render('Welcome', [
        'isAuth' => \Illuminate\Support\Facades\Auth::check(),
    ]);
})->name('app');

Route::get('/cgv', function () {
    return Inertia::render('Cgv', [
        'isAuth' => \Illuminate\Support\Facades\Auth::check(),
    ]);
})->name('cgv');

Route::get('/mentions-legales', function () {
    return Inertia::render('LegalInformation', [
        'isAuth' => \Illuminate\Support\Facades\Auth::check(),
    ]);
})->name('legal.information');

Route::get('login', function () {
    return redirect('/app');
})->name('login')->middleware('guest');

Route::post('/delete-shop/{slug}', [ProfileController::class, 'deleteTenant'])->name('shop.delete');

Route::post('/contact', [ContactController::class, 'sendMessage'])->name('contact');

Route::post('/stripe/webhook', [StripeController::class, 'handleWebhook']);
Route::get('/complete-payment/{paymentIntent}', [StripeController::class, 'completePayment'])->name('complete.payment');


require __DIR__ . '/notification.php';
