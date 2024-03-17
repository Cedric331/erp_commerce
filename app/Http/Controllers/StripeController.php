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
        $payload = $request->getContent();
        $sig_header = $request->header('Stripe-Signature');

        try {
            $event = Webhook::constructEvent(
                $payload,
                $sig_header,
                env('STRIPE_WEBHOOK_SECRET')
            );
        } catch (\UnexpectedValueException $e) {
            return response()->json(['error' => 'Invalid Payload'], 400);
        } catch (SignatureVerificationException $e) {
            return response()->json(['error' => 'Invalid Signature'], 400);
        }

        $method = 'handle' . ucfirst(Str::camel(str_replace('.', '_', $event->type)));

        if (method_exists($this, $method)) {
            return $this->$method($event);
        } else {
            return response()->json(['message' => 'Unhandled event type'], 200);
        }
    }

    protected function handleCustomerSubscriptionCreated($event)
    {
        $commercant = $this->getCommercantFromEvent($event);
        if (!$commercant) return;

        $subscription = $event->data->object;

        $commercant->subscriptions()->create([
            'type' => 'default',
            'stripe_id' => $subscription->id,
            'stripe_status' => $subscription->status,
            'stripe_price' => $subscription->items->data[0]->price->id,
            'quantity' => $subscription->quantity,
            'trial_ends_at' => isset($subscription->trial_end) ? \Carbon\Carbon::createFromTimestamp($subscription->trial_end) : null,
            'ends_at' => null,
        ]);

        Log::info('Subscription created for commercant.', ['stripe_id' => $subscription->customer]);
    }

    protected function handleCustomerSubscriptionUpdated($event)
    {
        $commercant = $this->getCommercantFromEvent($event);
        if (!$commercant) return;

        $subscription = $event->data->object;

        $commercantSubscription = $commercant->subscriptions()->where('stripe_id', $subscription->id)->first();
        if ($commercantSubscription) {
            $commercantSubscription->update([
                'stripe_status' => $subscription->status,
            ]);

            // Si le statut de l'abonnement est 'incomplete', marquer pour action utilisateur
            if ($subscription->status === 'incomplete') {
                // Ici, vous pouvez déclencher une notification à l'utilisateur pour qu'il complète le paiement
                // Par exemple, enregistrer une tâche, envoyer un email, etc.
                $this->notifyUserForPaymentCompletion($commercant, $commercantSubscription);
            }
        }

        Log::info('Subscription status updated.', ['stripe_id' => $subscription->customer, 'status' => $subscription->status]);
    }

    protected function handleCustomerSubscriptionDeleted($event)
    {
        Log::info('Subscription deleted.', ['stripe_id' => $event]);
        $commercant = $this->getCommercantFromEvent($event);
        if (!$commercant) {
            Log::error('Commercant not found for Stripe ID.', ['stripe_id' => $event->data->object->customer]);
            return response()->json(['error' => 'Commercant not found'], 404);
        }

        // Récupération de l'abonnement dans votre base de données
        $subscription = $commercant->subscriptions()->where('stripe_id', $event->data->object->id)->first();

        if ($subscription) {
            // Mettez à jour l'état de l'abonnement pour refléter sa suppression
            $subscription->update([
                'stripe_status' => 'canceled',
                // Assurez-vous de mettre à jour tout autre champ pertinent, comme la date d'annulation
                'ends_at' => now(),
            ]);

            // Log pour les opérations de débogage ou informations
            Log::info('Subscription deleted for commercant.', ['stripe_id' => $event->data->object->customer]);

            // Ici, vous pouvez également ajouter une logique pour notifier l'utilisateur de la suppression de l'abonnement.
            // $this->notifyUserSubscriptionCancelled($commercant);
        } else {
            Log::error('Subscription not found.', ['stripe_id' => $event->data->object->id]);
            return response()->json(['error' => 'Subscription not found'], 404);
        }

        return response()->json(['message' => 'Subscription deletion processed'], 200);
    }


    protected function handleInvoicePaid($event)
    {
        $commercant = $this->getCommercantFromEvent($event);
        if (!$commercant) return;

        // Handle successful invoice payment
        Log::info('Invoice paid for commercant.', ['stripe_id' => $event->data->object->customer]);
    }

    protected function handleInvoicePaymentFailed($event)
    {
        $commercant = $this->getCommercantFromEvent($event);
        if (!$commercant) return;

        // Handle failed invoice payment
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

        $paymentIntent = $stripe->paymentIntents->retrieve($paymentIntentId, []);

        if ($paymentIntent && $paymentIntent->status === 'requires_action') {
            // Redirigez vers la page Stripe pour compléter le paiement
            return redirect()->to($paymentIntent->next_action->use_stripe_sdk->stripe_js);
        } else {
            // Redirigez vers une page d'erreur ou de succès selon le cas
            return redirect()->route('home')->with('error', 'Le paiement ne peut pas être complété.');
        }
    }

    protected function notifyUserForPaymentCompletion($commercant, $subscription)
    {
        // Implémentez la logique pour notifier l'utilisateur.
        // Cela pourrait être l'envoi d'un email avec un lien vers une page de paiement,
        // où vous utilisez Stripe.js pour afficher le paiement requis.
        Log::info('Notifying user to complete payment.', ['commercant_id' => $commercant->id, 'subscription_id' => $subscription->id]);
    }
}
