<?php

namespace App\Livewire;

use Filament\Facades\Filament;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

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
            $tenant->delete();

        }

        return redirect('/app');
    }

    public function render()
    {
        return view('livewire.delete-tenant');
    }
}
