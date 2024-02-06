<?php

namespace App\Filament\Resources\CategorieProduitResource\Pages;

use App\Filament\Resources\CategorieProduitResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCategorieProduits extends ListRecords
{
    protected static string $resource = CategorieProduitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
