<?php

namespace App\Filament\Resources\StockResource\Pages;

use App\Filament\Resources\StockResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;

class EditStock extends EditRecord
{
    protected static string $resource = StockResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //            Actions\DeleteAction::make(),
        ];
    }

    public function mutateFormDataBeforeSave(array $data): array
    {
        if ($this->data['scheduled_date'] === '') {
            $this->data['scheduled_date'] = null;
        }

        return $data;
    }

    public function beforeSave()
    {
        if (Auth::user()->can('update', $this->getRecord()) && $this->getRecord()->scheduled_date !== null) {
            return true;
        }
        Notification::make()
            ->title('Accès interdit')
            ->body('Vous n\'avez pas l\'autorisation d\'effectuer cette modification.')
            ->danger()
            ->duration(10000)
            ->send();

        $this->halt();
    }
}
