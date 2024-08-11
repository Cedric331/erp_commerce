<?php

namespace App\Http\Controllers;

use App\Models\Merchant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Cashier\Http\Controllers\WebhookController as CashierController;
use Stripe\Exception\SignatureVerificationException;
use Stripe\StripeClient;
use Stripe\Webhook;

class StripeController extends CashierController
{
    protected function handleCustomerSubscriptionCreated($payload)
    {
        $response = parent::handleCustomerSubscriptionCreated($payload);

        $subscription = $payload['data']['object'];

        $customerStripeId = $payload['data']['object']['customer'];

        $merchant = Merchant::where('stripe_id', $customerStripeId)->first();

        if (!$merchant) return;

        $merchantSubscription = $merchant->subscriptions()->where('stripe_id', $subscription['id'])->first();

        if ($merchantSubscription) {
            $merchant->notify(new \App\Notifications\PaymentSuccessNotification($merchant, $merchantSubscription));
        }
        return $response;
    }
}
