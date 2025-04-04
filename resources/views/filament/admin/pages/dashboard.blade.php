<x-filament-panels::page>
    <x-filament::section>
        <div class="text-center">
            <h1 class="text-2xl font-bold">Bienvenue dans le panneau d'administration</h1>
            <p class="mt-2">Gérez les utilisateurs, les commerces et les abonnements de la plateforme.</p>
        </div>
    </x-filament::section>

    @if (count($widgets))
        <x-filament-widgets::widgets
            :columns="$this->getColumns()"
            :widgets="$widgets"
        />
    @endif
</x-filament-panels::page>
