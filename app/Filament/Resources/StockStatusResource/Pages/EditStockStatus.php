<?php

namespace App\Filament\Resources\StockStatusResource\Pages;

use App\Filament\Resources\StockStatusResource;
use App\Models\StockStatus;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditStockStatus extends EditRecord
{
    protected static string $resource = StockStatusResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->visible(function ($record) {
                    if ($record->name === StockStatus::STATUS_VENTE || $record->name === StockStatus::STATUS_LIVRAISON || $record->name === StockStatus::STATUS_PERTE)
                        return false;
                    else
                        return true;
                }),
        ];
    }

    public function beforeValidate(): void
    {
        if ($this->getRecord()->name === StockStatus::STATUS_VENTE || $this->getRecord()->name === StockStatus::STATUS_LIVRAISON || $this->getRecord()->name === StockStatus::STATUS_PERTE) {
            Notification::make('error')
                ->title('Erreur')
                ->danger()
                ->body('Vous ne pouvez pas modifier ce statut.')
                ->send();
            $this->halt();
        }
    }
}
