<?php

namespace App\Filament\Resources\StockResource\Pages;

use App\Filament\Resources\StockResource;
use App\Models\Produit;
use App\Models\StockStatus;
use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateStock extends CreateRecord
{
    protected static string $resource = StockResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public function mutateFormDataBeforeCreate(array $data): array
    {
        $this->data['commercant_id'] = Filament::getTenant()->id;
        if ($this->data['scheduled_date'] === "") {
            $this->data['scheduled_date'] = null;
        }

        return $data;
    }

    public function afterCreate()
    {
        if (!$this->data['scheduled_date']) {
            $type = StockStatus::find($this->data['stock_status_id'])->type;
            $produit = Produit::find($this->data['produit_id']);
            if ($type === StockStatus::TYPE_ENTREE) {
                $produit->update([
                    'stock' => $produit->stock + $this->data['quantity'],
                ]);
            } else {
                $produit->update([
                    'stock' => $produit->stock - $this->data['quantity'],
                ]);
            }
            activity('Produit')
                ->event('Stock modifié - ' . StockStatus::find($this->data['stock_status_id'])->name)
                ->causedBy(Auth::user())
                ->performedOn($produit)
                ->log('Le stock a été modifié avec succès. Le stock du produit est maintenant de ' . $produit->stock . '.');
        }
    }
}
