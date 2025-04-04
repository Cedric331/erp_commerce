<?php

use App\Models\Product;
use App\Models\Shop;
use App\Models\User;
use App\Notifications\AlerteStock;
use App\Notifications\ContactNotification;
use App\Notifications\ContactSupport;
use App\Notifications\PaymentSuccessNotification;
use App\Notifications\WelcomeEmail;
use Illuminate\Support\Facades\App;

if (App::environment('local') || env('DEBUG_EMAILS')) {
    Route::prefix('notifications/emails')->group(function () {
        Route::get('CONTACT_SUPPORT', function () {
            $user = User::first();

            $data = [
                'subject' => 'Test',
                'message' => 'Test',
                'user_name' => $user->name,
                'user_email' => $user->email,
                'shop_enseigne' => 'Test',
                'shop_email' => 'test@test.com',
            ];

            return (new ContactSupport($data))->toMail($user);
        });

        Route::get('CONTACT_NOTIFICATION', function () {
            $user = User::first();

            $data = [
                'subject' => 'Test',
                'message' => 'Test',
                'name' => $user->name,
                'email' => $user->email,
                'phone' => '0123456789',
            ];

            return (new ContactNotification($data))->toMail($user);
        });

        Route::get('WELCOME', function () {
            $user = User::first();

            return (new WelcomeEmail($user))->toMail($user);
        });

        Route::get('ALERT_STOCK', function () {
            $user = User::first();

            $shop = Shop::first();

            $products = Product::where('shop_id', $shop->id)->get()->toArray();

            return (new AlerteStock($products, $shop))->toMail($user);
        });
    });
}
