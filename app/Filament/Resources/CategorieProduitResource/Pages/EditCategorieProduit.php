<?php

namespace App\Filament\Resources\CategorieProduitResource\Pages;

use App\Filament\Resources\CategorieProduitResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCategorieProduit extends EditRecord
{
    protected static string $resource = CategorieProduitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
