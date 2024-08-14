<?php

namespace App\Livewire;

use App\Models\Product;
use App\Providers\RouteServiceProvider;
use Filament\Facades\Filament;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Livewire\Component;
use Spatie\Activitylog\Models\Activity;

class DeleteTenant extends Component implements HasForms
{
    use InteractsWithForms;

    public function deleteCommerce()
    {
        $tenant = Filament::getTenant();

        if (!$tenant) {
            return redirect('/app');
        }

        if ($tenant->subscribed('default')) {
            $tenant->subscription('default')->cancelNow();
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
        $tenant->brand()->delete();

        $tenant->users->each(function ($user) {
            if ($user->shops->count() === 1 && $user->isNot(auth()->user())) {
                $user->delete();
            }
        });

        $tenant->roles()->delete();

        $tenant->delete();

        $tenantFirst = auth()->user()->shops->first();
        Notification::make()
            ->title('Commerce supprimé')
            ->body('Le commerce a été supprimé avec succès.')
            ->success()
            ->duration(10000)
            ->send();

        if ($tenantFirst) {
            $this->redirect('/app/shop/' . $tenantFirst->slug);
        } else {
            $this->redirect(RouteServiceProvider::CREATED_APP);
        }
    }


    public function close()
    {
        $this->dispatch('close-modal', id: 'delete-commerce');
    }

    public function render()
    {
        return view('livewire.delete-tenant');
    }
}
