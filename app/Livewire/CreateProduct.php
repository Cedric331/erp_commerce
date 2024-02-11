<?php

namespace App\Livewire;

use App\Filament\Resources\ProduitResource;
use Filament\Facades\Filament;
use Filament\Forms\Form;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CreateProduct extends Component
{

    public $form;

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
