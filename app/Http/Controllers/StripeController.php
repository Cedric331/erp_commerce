<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Notifications\PaymentSuccessNotification;
use App\Notifications\SubscriptionCancellationRequestedNotification;
use App\Notifications\SubscriptionCancelledNotification;
use App\Notifications\SubscriptionRenewedNotification;
use Illuminate\Support\Facades\Log;
use Laravel\Cashier\Http\Controllers\WebhookController as CashierController;

class StripeController extends CashierController
{
    protected function handleCustomerSubscriptionCreated($payload): \Symfony\Component\HttpFoundation\Response
    {
        $response = parent::handleCustomerSubscriptionCreated($payload);

        $subscription = $payload['data']['object'];
        $customerStripeId = $subscription['customer'];
        $shop = Shop::where('stripe_id', $customerStripeId)->first();

        if (! $shop) {
            return $response;
        }

        $shopSubscription = $shop->subscriptions()->where('stripe_id', $subscription['id'])->first();

        if ($shopSubscription) {
            try {
                $shop->notify(new PaymentSuccessNotification($shop, $shopSubscription));
            } catch (\Exception $e) {
                Log::error('Erreur lors de l\'envoi de la notification de paiement réussi : ' . $e->getMessage(), [
                    'subscription_id' => $shopSubscription->id,
                    'stripe_id' => $subscription['id'],
                    'exception' => $e
                ]);
            }
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

        if (! $shop) {
            return $response;
        }

        $shopSubscription = $shop->subscriptions()->where('stripe_id', $subscription['id'])->first();

        if (! $shopSubscription) {
            return $response;
        }

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
            try {
                $shop->notify(new SubscriptionCancellationRequestedNotification($shop, $shopSubscription));
            } catch (\Exception $e) {
                Log::error('Erreur lors de l\'envoi de la notification de demande d\'annulation : ' . $e->getMessage(), [
                    'subscription_id' => $shopSubscription->id,
                    'stripe_id' => $subscription['id'],
                    'exception' => $e
                ]);
            }

            return $response;
        }

        // Cas d'annulation effective immédiate
        if ($subscription['status'] === 'canceled') {
            $shopSubscription->markAsCanceled();
            try {
                $shop->notify(new SubscriptionCancelledNotification($shop, $shopSubscription));
            } catch (\Exception $e) {
                Log::error('Erreur lors de l\'envoi de la notification d\'annulation : ' . $e->getMessage(), [
                    'subscription_id' => $shopSubscription->id,
                    'stripe_id' => $subscription['id'],
                    'exception' => $e
                ]);
            }

            return $response;
        }

        // Cas de renouvellement
        if (
            $subscription['status'] === 'active' &&
            isset($subscription['current_period_end']) &&
            (! isset($previousAttributes['current_period_end']) ||
                $previousAttributes['current_period_end'] !== $subscription['current_period_end'])
        ) {
            try {
                $shop->notify(new SubscriptionRenewedNotification($shop, $shopSubscription));
            } catch (\Exception $e) {
                Log::error('Erreur lors de l\'envoi de la notification de renouvellement : ' . $e->getMessage(), [
                    'subscription_id' => $shopSubscription->id,
                    'stripe_id' => $subscription['id'],
                    'exception' => $e
                ]);
            }
        }

        return $response;
    }

    protected function handleCustomerSubscriptionDeleted($payload): \Symfony\Component\HttpFoundation\Response
    {
        $response = parent::handleCustomerSubscriptionDeleted($payload);

        $subscription = $payload['data']['object'];
        $customerStripeId = $subscription['customer'];
        $shop = Shop::where('stripe_id', $customerStripeId)->first();

        if (! $shop) {
            return $response;
        }

        $shopSubscription = $shop->subscriptions()->where('stripe_id', $subscription['id'])->first();

        if ($shopSubscription) {
            $shopSubscription->markAsCanceled();

                try {
                    Log::info("Envoi de notification depuis webhook pour la souscription : {$subscription['id']}");
                    $shop->notify(new SubscriptionCancelledNotification($shop, $shopSubscription));
                } catch (\Exception $e) {
                    Log::error('Erreur lors de l\'envoi de la notification de suppression : ' . $e->getMessage(), [
                        'subscription_id' => $shopSubscription->id,
                        'stripe_id' => $subscription['id'],
                        'exception' => $e
                    ]);
                }
        }

        return $response;
    }
}
