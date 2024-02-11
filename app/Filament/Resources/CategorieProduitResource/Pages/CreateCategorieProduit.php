<?php

namespace App\Filament\Resources\CategorieProduitResource\Pages;

use App\Filament\Resources\CategorieProduitResource;
use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Resources\Pages\CreateRecord;

class CreateCategorieProduit extends CreateRecord
{
    protected static string $resource = CategorieProduitResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $tenant = Filament::getTenant();

        $data['commercant_id'] = $tenant->id;

        return $data;
    }
}
