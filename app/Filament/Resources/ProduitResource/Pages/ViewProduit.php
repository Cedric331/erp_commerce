<?php

use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;

class ViewProduit extends ViewRecord
{
    protected function getHeaderActions(): array
    {
        return [
            Action::make('createVariant')
                ->label('Créer une variante')
                ->icon('heroicon-o-plus')
                ->url(fn () => route('filament.admin.resources.produits.create', [
                    'duplicate_from' => $this->record->id,
                ])),
        ];
    }
}
