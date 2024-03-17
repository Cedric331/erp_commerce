<?php

namespace App\Livewire;

use Filament\Facades\Filament;
use Filament\Panel;
use Livewire\Component;

class CreateProduct extends Component
{

    public static function isTenantSubscriptionRequired(Panel $panel): bool
    {
        return true;
    }

    public function redirectCreateProduct()
    {
       return  redirect()->route('filament.app.resources.products.create', [
           'tenant' => Filament::getTenant()->slug,
       ]);
    }

    public function render()
    {
        return view('livewire.create-product');
    }
}
