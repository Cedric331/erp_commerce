<?php

namespace App\Filament\Resources\ProduitResource\Pages;

use App\Filament\Resources\ProduitResource;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateProduit extends CreateRecord
{
    protected static string $resource = ProduitResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Produit créé')
            ->body('Le produit a été créé avec succès.');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $tenant = Filament::getTenant();

        $data['commercant_id'] = $tenant->id;

        return $data;
    }

    public function afterCreate()
    {
        activity('Produit')
            ->event('Création du produit')
            ->causedBy(Auth::user())
            ->performedOn($this->record)
            ->log('Le produit a été créé avec succès.');

    }
}
