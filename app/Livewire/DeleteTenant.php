<?php

namespace App\Livewire;

use App\Models\Product;
use App\Providers\RouteServiceProvider;
use Filament\Facades\Filament;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Laravel\Cashier\Exceptions\IncompletePayment;
use Laravel\Cashier\Subscription;
use Livewire\Component;
use Spatie\Activitylog\Models\Activity;
use Stripe\Exception\ApiErrorException;

class DeleteTenant extends Component implements HasForms
{
    use InteractsWithForms;

    /**
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse|null
     */
    public function deleteCommerce(): \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse|null
    {
        $tenant = Filament::getTenant();

        if (! $tenant) {
            return redirect('/app');
        }

        // Marquer ce commerce comme étant en cours de suppression manuelle
        // pour éviter les notifications en double depuis les webhooks Stripe
        cache()->put('deleting_shop_' . $tenant->id, true, now()->addMinutes(5));

        try {
            DB::beginTransaction();

            // Collecte des abonnements pour envoyer une seule notification à la fin
            $cancelledSubscriptions = [];

            // Annulation de tous les abonnements actifs
            foreach ($tenant->subscriptions as $subscription) {
                try {
                    $subscription->cancelNow();
                    $cancelledSubscriptions[] = $subscription;
                } catch (ApiErrorException $e) {
                    // Si l'erreur est "No such subscription", on marque simplement l'abonnement comme annulé dans la base de données
                    if (strpos($e->getMessage(), 'No such subscription') !== false) {
                        Log::warning("Souscription Stripe introuvable, marquage comme annulée localement : {$subscription->stripe_id}");
                        $subscription->markAsCanceled();
                        $cancelledSubscriptions[] = $subscription;
                    } else {
                        // Pour les autres erreurs Stripe, on les remonte
                        throw $e;
                    }
                }
            }

            // Si l'abonnement par défaut existe, on s'assure qu'il est bien annulé
            if ($tenant->subscribed('default')) {
                $defaultSubscription = $tenant->subscription('default');
                try {
                    // Envoyer une notification avant d'annuler la souscription par défaut
                    $defaultSubscription->cancelNow();
                } catch (ApiErrorException $e) {
                    // Si l'erreur est "No such subscription", on marque simplement l'abonnement comme annulé dans la base de données
                    if (strpos($e->getMessage(), 'No such subscription') !== false) {
                        Log::warning("Souscription par défaut Stripe introuvable, marquage comme annulée localement : {$defaultSubscription->stripe_id}");
                        $defaultSubscription->markAsCanceled();
                    } else {
                        // Pour les autres erreurs Stripe, on les remonte
                        throw $e;
                    }
                }
            }

            // Suppression des données Stripe associées
            if ($tenant->hasStripeId()) {
                try {
                    $stripeCustomer = $tenant->createOrGetStripeCustomer();
                    $stripeCustomer->delete();
                } catch (ApiErrorException $e) {
                    // Si le client Stripe n'existe pas, on ignore l'erreur
                    Log::warning("Client Stripe introuvable lors de la suppression : {$tenant->stripe_id}");
                }

                // Dans tous les cas, on nettoie les données Stripe locales
                $tenant->stripe_id = null;
                $tenant->pm_type = null;
                $tenant->pm_last_four = null;
                $tenant->save();
            }

            $productIds = $tenant->products->pluck('id');
            Activity::whereIn('subject_id', $productIds)
                ->where('subject_type', Product::class)
                ->delete();
            $tenant->products()->delete();

            $tenant->stocks()->delete();
            $tenant->categories()->delete();
            $tenant->stockStatuses()->delete();
            $tenant->storages()->delete();
            $tenant->brands()->delete();

            $tenant->users->each(function ($user) {
                if ($user->shops->count() === 1 && $user->isNot(auth()->user())) {
                    $user->delete();
                }
            });

            $tenant->roles()->delete();

            // Envoyer une seule notification pour la suppression du commerce
            try {
                Log::info("Envoi d'une notification de suppression pour le commerce : {$tenant->enseigne}");
                // Utiliser la première souscription disponible pour la notification
                $subscription = $tenant->subscriptions->first();
                if ($subscription) {
                    $tenant->notify(new \App\Notifications\SubscriptionCancelledNotification($tenant, $subscription));
                    Log::info("Notification de suppression envoyée avec succès pour le commerce : {$tenant->enseigne}");
                }
            } catch (\Exception $e) {
                Log::error("Erreur lors de l'envoi de la notification de suppression du commerce : " . $e->getMessage(), [
                    'tenant_id' => $tenant->id,
                    'exception' => $e
                ]);
            }

            $tenant->delete();

            DB::commit();

            $tenants = auth()->user()->shops()->get();

            Notification::make()
                ->title('Commerce supprimé')
                ->body('Le commerce a été supprimé avec succès.')
                ->success()
                ->duration(10000)
                ->send();

            $tenantFirst = null;
            if ($tenants->isNotEmpty()) {
                $tenantFirst = $tenants->firstWhere('id', '!=', $tenant->id);
            }

            if ($tenantFirst) {
                return $this->redirect('/app/shop/'.$tenantFirst->slug);
            }

            return $this->redirect(RouteServiceProvider::HOME);

        } catch (ApiErrorException $e) {
            DB::rollBack();

            // Supprimer le marqueur de suppression manuelle en cas d'erreur
            cache()->forget('deleting_shop_' . $tenant->id);

            // Gestion spécifique des erreurs Stripe
            $errorMessage = $e->getMessage();
            $shouldRetry = false;

            // Si l'erreur concerne une souscription inexistante
            if (strpos($errorMessage, 'No such subscription') !== false) {
                // Extraire l'ID de la souscription de l'erreur
                preg_match("/No such subscription: '([^']+)'/", $errorMessage, $matches);
                $subscriptionId = $matches[1] ?? null;

                if ($subscriptionId) {
                    Log::warning("Tentative de suppression d'une souscription Stripe inexistante : {$subscriptionId}");

                    // Supprimer la souscription de la base de données locale
                    $localSubscription = Subscription::where('stripe_id', $subscriptionId)->first();
                    if ($localSubscription) {
                        $localSubscription->markAsCanceled();
                        Log::info("Souscription locale marquée comme annulée : {$subscriptionId}");
                        $shouldRetry = true;
                    }
                }
            }

            if ($shouldRetry) {
                // Réessayer la suppression du commerce
                Notification::make()
                    ->title('Information')
                    ->body('Correction d\'une incohérence de souscription. Veuillez réessayer de supprimer le commerce.')
                    ->info()
                    ->duration(10000)
                    ->send();
            } else {
                Notification::make()
                    ->title('Erreur')
                    ->body('Une erreur est survenue lors de la suppression du commerce : '.$e->getMessage())
                    ->danger()
                    ->duration(10000)
                    ->send();

                report($e);
            }

            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollBack();

            // Supprimer le marqueur de suppression manuelle en cas d'erreur
            cache()->forget('deleting_shop_' . $tenant->id);

            Notification::make()
                ->title('Erreur')
                ->body('Une erreur est survenue lors de la suppression du commerce : '.$e->getMessage())
                ->danger()
                ->duration(10000)
                ->send();

            report($e);

            return redirect()->back();
        }
    }

    public function close(): void
    {
        $this->dispatch('close-modal', id: 'delete-commerce');
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
    {
        return view('livewire.delete-tenant');
    }
}
