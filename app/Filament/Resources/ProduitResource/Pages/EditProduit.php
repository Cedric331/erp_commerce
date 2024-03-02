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

    protected bool $stockUpdated = false;

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

    public function beforeSave()
    {
       if ($this->record->stock !== $this->data['stock']) {
            $this->stockUpdated = true;
       }
    }

    public function afterSave()
    {
        if ($this->stockUpdated) {
            activity()
                ->causedBy(Auth::user())
                ->performedOn($this->record)
                ->event('Modification du stock')
                ->log('Le stock a été modifié avec succès. Le stock du produit est maintenant de ' . $this->record->stock . '.');
        }

        activity()
            ->causedBy(Auth::user())
            ->performedOn($this->record)
            ->event('Modification du produit')
            ->log('Le produit a été modifié avec succès.');
    }
}
