<div>
    <x-filament::modal
        id="delete-commerce"
        alignment="center"
        icon="heroicon-o-exclamation-triangle"
        icon-color="danger">
        <x-slot name="trigger">
            <x-filament::button
                color="danger"
                size="sm"
                icon="heroicon-o-trash"
                class="mx-4 w-auto text-center"
                style="margin-bottom: 10px;"
                iconSize="sm">
               Supprimer le commerce
            </x-filament::button>
        </x-slot>

        <x-slot name="heading">
            Suppression du commerce
        </x-slot>

        <x-slot name="description">
            Êtes-vous sûr de vouloir supprimer ce commerce ?
            <br>
            <br>
            Attention, cette action est supprimera définitivement le commerce, toutes les données associées et votre abonnement sera résilié.
        </x-slot>

        <x-slot name="footerActions">
            <x-filament::button
                wire:click="close"
                color="info"
                size="sm"
                icon="heroicon-o-x-mark"
                class="mx-4 w-auto text-center"
                iconSize="sm">
               Annuler
            </x-filament::button>

            <x-filament::button
                wire:click="deleteCommerce"
                color="danger"
                size="sm"
                icon="heroicon-o-trash"
                class="mx-4 w-auto text-center"
                iconSize="sm">
                Supprimer le commerce
            </x-filament::button>
        </x-slot>
    </x-filament::modal>
</div>
