<?php

namespace App\Http\Controllers;

use App\Models\Commercant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Cashier\Http\Controllers\WebhookController as CashierController;
use Stripe\Exception\SignatureVerificationException;
use Stripe\StripeClient;
use Stripe\Webhook;

class StripeController extends CashierController
{
    public function handleWebhook(Request $request)
    {
        Log::info('Received webhook', ['payload' => $request->getContent()]);

        $payload = $request->getContent();
        $sig_header = $request->header('Stripe-Signature');

        try {
            $event = Webhook::constructEvent(
                $payload,
                $sig_header,
                env('STRIPE_WEBHOOK_SECRET')
            );
        } catch (\UnexpectedValueException $e) {
            Log::error('Invalid payload', ['exception' => $e->getMessage()]);
            return response()->json(['error' => 'Invalid Payload'], 400);
        } catch (SignatureVerificationException $e) {
            Log::error('Invalid signature', ['exception' => $e->getMessage()]);
            return response()->json(['error' => 'Invalid Signature'], 400);
        }

        $method = 'handle' . ucfirst(Str::camel(str_replace('.', '_', $event->type)));

        if (method_exists($this, $method)) {
            return $this->$method($event);
        } else {
            Log::warning('Unhandled event type', ['event' => $event->type]);
            return response()->json(['message' => 'Unhandled event type'], 200);
        }
    }

    protected function handleCustomerSubscriptionCreated($payload)
    {
        $subscription = $payload->data->object;
        if (!$subscription || !isset($subscription->id)) {
            Log::error('Invalid subscription data', ['payload' => $payload]);
            return response()->json(['error' => 'Invalid subscription data'], 400);
        }

        $commercant = $this->getCommercantFromEvent($payload);

        if (!$commercant) return;

        $commercant->subscriptions()->create([
            'type' => 'default',
            'stripe_id' => $subscription->id,
            'stripe_status' => $subscription->status,
            'stripe_price' => $subscription->items->data[0]->price->id,
            'quantity' => $subscription->quantity,
            'trial_ends_at' => isset($subscription->trial_end) ? \Carbon\Carbon::createFromTimestamp($subscription->trial_end) : null,
            'ends_at' => null,
        ]);

        $commercantSubscription = $commercant->subscriptions()->where('stripe_id', $subscription->id)->first();
        if ($commercantSubscription) {
            $commercant->notify(new \App\Notifications\PaymentSuccessNotification($commercant, $commercantSubscription));
        }
    }

    protected function handleCustomerSubscriptionUpdated($payload)
    {
        $subscription = $payload->data->object;
        if (!$subscription || !isset($subscription->id)) {
            Log::error('Invalid subscription data', ['payload' => $payload]);
            return response()->json(['error' => 'Invalid subscription data'], 400);
        }

        $commercant = $this->getCommercantFromEvent($payload);
        if (!$commercant) return;

        $commercantSubscription = $commercant->subscriptions()->where('stripe_id', $subscription->id)->first();

        if ($commercantSubscription) {
            $commercantSubscription->update([
                'stripe_status' => $subscription->status,
            ]);

            if ($subscription->status === 'incomplete') {
                $this->notifyUserForPaymentCompletion($commercant, $commercantSubscription);
            }
        }

        Log::info('Subscription status updated.', ['stripe_id' => $subscription->customer, 'status' => $subscription->status]);
    }

    protected function handleCustomerSubscriptionDeleted($payload)
    {
        $commercant = $this->getCommercantFromEvent($payload);
        if (!$commercant) {
            Log::error('Commercant not found for Stripe ID.', ['stripe_id' => $payload->data->object->customer]);
            return response()->json(['error' => 'Commercant not found'], 404);
        }

        $subscription = $commercant->subscriptions()->where('stripe_id', $payload->data->object->id)->first();

        if ($subscription) {
            $subscription->update([
                'stripe_status' => 'canceled',
                'ends_at' => now(),
            ]);

            Log::info('Subscription deleted for commercant.', ['stripe_id' => $payload->data->object->customer]);

            // Vous pouvez également ajouter une logique pour notifier l'utilisateur de la suppression de l'abonnement.
            // $this->notifyUserSubscriptionCancelled($commercant);
        } else {
            Log::error('Subscription not found.', ['stripe_id' => $payload->data->object->id]);
            return response()->json(['error' => 'Subscription not found'], 404);
        }

        return response()->json(['message' => 'Subscription deletion processed'], 200);
    }

    protected function handleInvoicePaid($event)
    {
        $commercant = $this->getCommercantFromEvent($event);
        if (!$commercant) return;

        Log::info('Invoice paid for commercant.', ['stripe_id' => $event->data->object->customer]);
    }

    protected function handleInvoicePaymentFailed($event)
    {
        $commercant = $this->getCommercantFromEvent($event);
        if (!$commercant) return;

        Log::info('Invoice payment failed for commercant.', ['stripe_id' => $event->data->object->customer]);
    }

    private function getCommercantFromEvent($event)
    {
        $commercant = Commercant::where('stripe_id', $event->data->object->customer)->first();

        if (!$commercant) {
            Log::error('Commercant not found for Stripe ID.', ['stripe_id' => $event->data->object->customer]);
            return null;
        }

        return $commercant;
    }

    public function completePayment($paymentIntentId)
    {
        $stripe = new StripeClient(env('STRIPE_SECRET'));

        try {
            $paymentIntent = $stripe->paymentIntents->retrieve($paymentIntentId, []);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve PaymentIntent', ['paymentIntentId' => $paymentIntentId, 'error' => $e->getMessage()]);
            return redirect()->route('home')->with('error', 'Erreur lors de la récupération du paiement.');
        }

        if ($paymentIntent && $paymentIntent->status === 'requires_action') {
            return redirect()->to($paymentIntent->next_action->use_stripe_sdk->stripe_js);
        } else {
            return redirect()->route('home')->with('error', 'Le paiement ne peut pas être complété.');
        }
    }

    protected function notifyUserForPaymentCompletion($commercant, $subscription)
    {
        // Vérification que l'utilisateur a bien une adresse email
        if (!$commercant->email) {
            Log::error('No email found for commercant.', ['commercant_id' => $commercant->id]);
            return;
        }

        // Créer le lien de paiement
        $paymentLink = route('complete.payment', ['paymentIntent' => $subscription->stripe_id]);

        // Envoi de la notification
        try {
            $commercant->notify(new \App\Notifications\PaymentCompletionNotification($commercant, $subscription, $paymentLink));
            Log::info('Payment completion notification sent.', ['commercant_id' => $commercant->id, 'subscription_id' => $subscription->id]);
        } catch (\Exception $e) {
            Log::error('Failed to send payment completion notification.', [
                'commercant_id' => $commercant->id,
                'subscription_id' => $subscription->id,
                'error' => $e->getMessage()
            ]);
        }
    }


}
