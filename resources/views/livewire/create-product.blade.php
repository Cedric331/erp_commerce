<div xmlns:x-filament-components="http://www.w3.org/1999/html">

{{--  Ajout bouton pour ajouter un produit dans la navbar --}}
{{--    @include('filament::components\button\index', [--}}
{{--        'slot' => 'Créer un produit',--}}
{{--        'color' => 'primary',--}}
{{--        'size' => 'xs',--}}
{{--        'icon' => 'heroicon-o-shopping-cart',--}}
{{--        'iconSize' => 'sm',--}}
{{--        'path' => 'products/create',--}}
{{--        'type' => 'button',--}}
{{--        'target' => 'wire:click="createProduct()"'--}}
{{--    ])--}}

    <x-filament::button
        wire:click="redirectCreateProduct"
        color="primary"
        size="xs"
        icon="heroicon-o-shopping-cart"
        iconSize="sm"
    >
        Créer un produit
    </x-filament::button>


</div>

