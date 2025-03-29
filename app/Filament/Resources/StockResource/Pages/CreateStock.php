<?php

namespace App\Filament\Resources\StockResource\Pages;

use App\Filament\Resources\StockResource;
use App\Models\Product;
use App\Models\StockStatus;
use Filament\Facades\Filament;
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
        $this->data['shop_id'] = Filament::getTenant()->id;
        if ($this->data['scheduled_date'] === '') {
            $this->data['scheduled_date'] = null;
        }

        $product = Product::find($this->data['product_id']);
        $this->data['prix_product_ht'] = $product->price_ht;
        $this->data['prix_product_buy'] = $product->price_buy;

        return $data;
    }

    public function afterCreate()
    {
        if (! $this->data['scheduled_date']) {
            $type = StockStatus::find($this->data['stock_status_id'])->type;
            $product = Product::find($this->data['product_id']);
            if ($type === StockStatus::TYPE_ENTREE) {
                $product->update([
                    'stock' => $product->stock + $this->data['quantity'],
                ]);
            } else {
                $product->update([
                    'stock' => $product->stock - $this->data['quantity'],
                ]);
            }
            activity('Produit')
                ->event('Stock modifié - '.StockStatus::find($this->data['stock_status_id'])->name)
                ->causedBy(Auth::user())
                ->performedOn($product)
                ->log('Le stock a été modifié avec succès. Le stock du produit est maintenant de '.$product->stock.'.');
        }
    }
}
