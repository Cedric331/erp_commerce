<?php

namespace Althinect\FilamentSpatieRolesPermissions\Resources\RoleResource\Pages;

use Althinect\FilamentSpatieRolesPermissions\Resources\RoleResource;
use App\Models\Role;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditRole extends EditRecord
{
    protected static string $resource = RoleResource::class;

    public function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }

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
