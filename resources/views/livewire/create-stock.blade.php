<div>

    <x-filament::modal icon="heroicon-o-clipboard-document-list" icon-color="primary" slide-over width="4xl">
        <x-slot name="trigger">
            <x-filament::button
                color="primary"
                size="sm"
                icon="heroicon-o-clipboard-document-list"
                iconSize="sm">
                Gestion du stock
            </x-filament::button>
        </x-slot>

        <x-slot name="heading">
            Création ligne de stock
        </x-slot>

        <x-slot name="description">
            Veuillez remplir les champs ci-dessous pour créer une ligne de stock.
        </x-slot>

        <form>
            {{ $this->form }}
        </form>

        <x-slot name="footerActions">
            <x-filament::button
                wire:click="create"
                color="primary"
                size="sm"
                icon="heroicon-o-plus-circle"
                iconSize="sm">
                Créer la ligne de stock
            </x-filament::button>
        </x-slot>

    </x-filament::modal>

</div>