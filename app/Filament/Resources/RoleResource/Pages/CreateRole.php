<?php

namespace Althinect\FilamentSpatieRolesPermissions\Resources\RoleResource\Pages;

use Althinect\FilamentSpatieRolesPermissions\Resources\RoleResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateRole extends CreateRecord
{
    protected static string $resource = RoleResource::class;

    public function beforeValidate(array $data): array
    {
        if ($data['name'] === 'Administrateur' || $data['name'] === 'Gérant') {
            Notification::make()
                ->title('Erreur d\'autorisation')
                ->body('Vous ne pouvez pas créer un rôle avec ce nom.')
                ->danger()
                ->send();
            $this->halt();
        }

        return $data;
    }
}
