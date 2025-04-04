<div xmlns:x-filament-components="http://www.w3.org/1999/html">

    @if(Auth::user()->hasPermissionTo('Créer produit') || Auth::user()->isAdministrateurOrGerant())
        <x-filament::button
            wire:click="redirectCreateProduct"
            color="primary"
            size="sm"
            icon="heroicon-o-plus"
            iconSize="sm"
            class="mx-4 w-full text-center"
        >
            Créer un produit
        </x-filament::button>
    @endif

</div>



