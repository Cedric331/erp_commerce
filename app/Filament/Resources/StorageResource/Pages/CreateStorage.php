<?php

namespace App\Filament\Resources\StorageResource\Pages;

use App\Filament\Resources\StorageResource;
use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateStorage extends CreateRecord
{
    protected static string $resource = StorageResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Zone de stockage créée')
            ->body('La zone de stockage a été créée avec succès.');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $tenant = Filament::getTenant();

        $data['shop_id'] = $tenant->id;

        return $data;
    }
}
