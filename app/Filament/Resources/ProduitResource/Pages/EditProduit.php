<?php

namespace App\Filament\Resources\ProduitResource\Pages;

use App\Filament\Resources\ProduitResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Models\Activity;

class EditProduit extends EditRecord
{
    protected static string $resource = ProduitResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    public function afterSave()
    {
        activity()
            ->causedBy(Auth::user())
            ->performedOn($this->record)
            ->event('Modification du produit')
            ->log('Le produit a été modifié avec succès.');
    }
}
