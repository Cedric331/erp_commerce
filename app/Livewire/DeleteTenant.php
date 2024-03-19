<?php

namespace App\Livewire;

use App\Models\Produit;
use Filament\Facades\Filament;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Livewire\Component;
use Spatie\Activitylog\Models\Activity;

class DeleteTenant extends Component implements HasForms
{
    use InteractsWithForms;

    public function deleteCommerce()
    {
        $tenant = Filament::getTenant();

        if ($tenant) {
            if ($tenant->subscribed('default')) {
                $tenant->subscription('default')->cancelNow();
            }
            $products = $tenant->produits;
            foreach ($products as $product) {
                Activity::where('subject_id', $product->id)
                    ->with('causer')
                    ->where('subject_type', Produit::class)
                    ->orderBy('created_at', 'desc')
                    ->delete();
            }

            $users = $tenant->users;
            foreach ($users as $user) {
                if ($user->commercant->count() === 1) {
                    if ($user !== auth()->user()) {
                        $user->delete();
                    }
                }
            }
            $tenant->roles()->delete();
            $tenant->permissions()->delete();
            $tenant->delete();
        }

        return redirect('/app');
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
