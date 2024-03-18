<div>
    @if(!$this->subscribed)
        <div class="text-white p-4 rounded-lg shadow-md flex items-center justify-between" style="background-color: #ff7979;margin-top: 10px">
            <div class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p>Vous ne disposez pas d'abonnement actif pour ce commerce.</p>
            </div>
            @if(Auth::user()->isAdministrateurOrGerant())
                    <x-filament::button
                        wire:click="redirectToCheckout"
                        size="sm"
                        class="mx-4 w-auto text-center text-danger-300"
                        style="background-color: #ffffff; color: #ff7979; border-color: #ff7979;"
                        iconSize="sm">
                        S'abonner
                    </x-filament::button>
            @endif
        </div>
    @endif
</div>
