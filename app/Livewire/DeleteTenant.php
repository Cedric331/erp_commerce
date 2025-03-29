<?php

namespace App\Livewire;

use App\Models\Product;
use App\Providers\RouteServiceProvider;
use Filament\Facades\Filament;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Spatie\Activitylog\Models\Activity;

class DeleteTenant extends Component implements HasForms
{
    use InteractsWithForms;

    /**
     * @return \Illuminate\Foundation\Application|object|\Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse|null
     */
    public function deleteCommerce(): \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse|null
    {
        $tenant = Filament::getTenant();

        if (! $tenant) {
            return redirect('/app');
        }

        try {
            DB::beginTransaction();

            // Annulation de tous les abonnements actifs
            foreach ($tenant->subscriptions as $subscription) {
                if ($subscription->active() || $subscription->onTrial()) {
                    $subscription->cancelNow();
                }
            }

            // Si l'abonnement par défaut existe, on s'assure qu'il est bien annulé
            if ($tenant->subscribed('default')) {
                $tenant->subscription('default')->cancelNow();
            }

            // Suppression des données Stripe associées
            if ($tenant->hasStripeId()) {
                $stripeCustomer = $tenant->createOrGetStripeCustomer();
                $stripeCustomer->delete();
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
                if ($user->shop->count() === 1 && $user->isNot(auth()->user())) {
                    $user->delete();
                }
            });

            $tenant->roles()->delete();

            $tenant->delete();

            DB::commit();

            $tenants = auth()->user()->shop()->get();
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

        } catch (\Exception $e) {
            DB::rollBack();

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
