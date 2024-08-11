<?php

namespace App\Filament\Resources\StockStatusResource\Pages;

use App\Filament\Resources\StockStatusResource;
use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Resources\Pages\CreateRecord;

class CreateStockStatus extends CreateRecord
{
    protected static string $resource = StockStatusResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public function mutateFormDataBeforeCreate(array $data): array
    {
        $tenant = Filament::getTenant();
        $data['merchant_id'] = $tenant->id;

        return $data;
    }
}
