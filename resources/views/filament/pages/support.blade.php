<x-filament-panels::page>
    <form>
        {{ $this->form }}
    </form>
    <div class="flex justify-end" style="margin-top: 20px">
        <x-filament::button
            wire:click="sendMessage"
            color="primary"
            size="sm"
            icon="heroicon-o-envelope"
            iconSize="sm">
            Envoyer
        </x-filament::button>
    </div>
</x-filament-panels::page>
