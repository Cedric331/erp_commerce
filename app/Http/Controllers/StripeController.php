<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Notifications\PaymentSuccessNotification;
use App\Notifications\SubscriptionCancellationRequestedNotification;
use App\Notifications\SubscriptionRenewedNotification;
use App\Notifications\SubscriptionCancelledNotification;
use Laravel\Cashier\Http\Controllers\WebhookController as CashierController;

class StripeController extends CashierController
{
    protected function handleCustomerSubscriptionCreated($payload): \Symfony\Component\HttpFoundation\Response
    {
        $response = parent::handleCustomerSubscriptionCreated($payload);

        $subscription = $payload['data']['object'];
        $customerStripeId = $subscription['customer'];
        $shop = Shop::where('stripe_id', $customerStripeId)->first();

        if (!$shop) return $response;

        $shopSubscription = $shop->subscriptions()->where('stripe_id', $subscription['id'])->first();

        if ($shopSubscription) {
            $shop->notify(new PaymentSuccessNotification($shop, $shopSubscription));
        }

        return $response;
    }

    protected function handleCustomerSubscriptionUpdated($payload): \Symfony\Component\HttpFoundation\Response
    {
        $response = parent::handleCustomerSubscriptionUpdated($payload);

        $subscription = $payload['data']['object'];
        $previousAttributes = $payload['data']['previous_attributes'] ?? [];
        $customerStripeId = $subscription['customer'];
        $shop = Shop::where('stripe_id', $customerStripeId)->first();

        if (!$shop) return $response;

        $shopSubscription = $shop->subscriptions()->where('stripe_id', $subscription['id'])->first();

        if (!$shopSubscription) return $response;

        // Cas de demande d'annulation
        if (
            isset($previousAttributes['cancel_at']) &&
            $previousAttributes['cancel_at'] === null &&
            $subscription['cancel_at'] !== null
        ) {
            // Mise à jour de la date de fin
            $shopSubscription->ends_at = now()->createFromTimestamp($subscription['cancel_at']);
            $shopSubscription->save();

            // Notification de la demande d'annulation
            $shop->notify(new SubscriptionCancellationRequestedNotification($shop, $shopSubscription));

            return $response;
        }

        // Cas d'annulation effective immédiate
        if ($subscription['status'] === 'canceled') {
            $shopSubscription->markAsCanceled();
            $shop->notify(new SubscriptionCancelledNotification($shop, $shopSubscription));
            return $response;
        }

        // Cas de renouvellement
        if (
            $subscription['status'] === 'active' &&
            isset($subscription['current_period_end']) &&
            (!isset($previousAttributes['current_period_end']) ||
                $previousAttributes['current_period_end'] !== $subscription['current_period_end'])
        ) {
            $shop->notify(new SubscriptionRenewedNotification($shop, $shopSubscription));
        }

        return $response;
    }

    protected function handleCustomerSubscriptionDeleted($payload): \Symfony\Component\HttpFoundation\Response
    {
        $response = parent::handleCustomerSubscriptionDeleted($payload);

        $subscription = $payload['data']['object'];
        $customerStripeId = $subscription['customer'];
        $shop = Shop::where('stripe_id', $customerStripeId)->first();

        if (!$shop) return $response;

        $shopSubscription = $shop->subscriptions()->where('stripe_id', $subscription['id'])->first();

        if ($shopSubscription) {
            $shopSubscription->markAsCanceled();
            $shop->notify(new SubscriptionCancelledNotification($shop, $shopSubscription));
        }

        return $response;
    }
}
