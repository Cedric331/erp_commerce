<?php

namespace App\Filament\Resources\RoleResource\Pages;

use Althinect\FilamentSpatieRolesPermissions\Resources\RoleResource;
use App\Models\Role;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateRole extends CreateRecord
{
    protected static string $resource = RoleResource::class;

    public function beforeValidate(): void
    {
        if ($this->data['name'] === Role::ROLE_GERANT || $this->data['name'] === Role::ROLE_ADMIN) {
            Notification::make()
                ->title('Erreur d\'autorisation')
                ->body('Vous ne pouvez pas créer un rôle avec ce nom.')
                ->danger()
                ->send();
            $this->halt();
        }
    }
}
