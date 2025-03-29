<?php

namespace App\Filament\Resources\ProduitResource\Pages;

use App\Filament\Resources\ProduitResource;
use App\Models\ProductPriceHistory;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;

class EditProduit extends EditRecord
{
    protected static string $resource = ProduitResource::class;

    protected bool $stockUpdated = false;

    protected bool $priceUpdated = false;

    protected array $oldPrices = [];

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('Détail du produit')
                ->url(fn () => ViewProduitActivities::getUrl(['record' => $this->record]))
                ->icon('heroicon-o-information-circle'),
            Action::make('Créer une variante')
                ->url(fn () => CreateProduit::getUrl(['duplicate_from' => $this->record->id]))
                ->icon('heroicon-o-plus'),
            Actions\DeleteAction::make(),
        ];
    }

    public function beforeSave()
    {
        // Vérification du stock
        if ($this->record->stock !== $this->data['stock']) {
            $this->stockUpdated = true;
        }

        // Vérification des prix
        if ($this->record->price_ht !== $this->data['price_ht'] ||
            $this->record->price_ttc !== $this->data['price_ttc'] ||
            $this->record->tva !== $this->data['tva']) {

            $this->priceUpdated = true;
            $this->oldPrices = [
                'old_price_excl_tax' => $this->record->price_ht,
                'old_price_incl_tax' => $this->record->price_ttc,
                'old_tax_rate' => $this->record->tva,
            ];
        }
    }

    public function afterSave()
    {
        // Enregistrement de l'historique des prix
        if ($this->priceUpdated) {
            ProductPriceHistory::create([
                'product_id' => $this->record->id,
                'old_price_excl_tax' => $this->oldPrices['old_price_excl_tax'],
                'new_price_excl_tax' => $this->record->price_ht,
                'old_price_incl_tax' => $this->oldPrices['old_price_incl_tax'],
                'new_price_incl_tax' => $this->record->price_ttc,
                'old_tax_rate' => $this->oldPrices['old_tax_rate'],
                'new_tax_rate' => $this->record->tva,
                'user_id' => Auth::id(),
            ]);

            activity('Produit')
                ->event('Modification des prix')
                ->causedBy(Auth::user())
                ->performedOn($this->record)
                ->log('Les prix du produit ont été modifiés.');
        }

        // Log de modification du stock
        if ($this->stockUpdated) {
            activity('Produit')
                ->causedBy(Auth::user())
                ->performedOn($this->record)
                ->event('Modification du stock')
                ->log('Le stock a été modifié avec succès. Le stock du produit est maintenant de '.$this->record->stock.'.');
        }

        // Log général de modification
        activity('Produit')
            ->causedBy(Auth::user())
            ->performedOn($this->record)
            ->event('Modification du produit')
            ->log('Le produit a été modifié avec succès.');
    }
}
