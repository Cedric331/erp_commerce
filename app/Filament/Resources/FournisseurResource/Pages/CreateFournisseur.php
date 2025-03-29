<?php

namespace App\Filament\Resources\FournisseurResource\Pages;

use App\Filament\Resources\FournisseurResource;
use Filament\Facades\Filament;
use Filament\Resources\Pages\CreateRecord;

class CreateFournisseur extends CreateRecord
{
    protected static string $resource = FournisseurResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $tenant = Filament::getTenant();

        $data['shop_id'] = $tenant->id;

        return $data;
    }
}
