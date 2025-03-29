<?php

namespace App\Filament\Resources\ProduitResource\Pages;

use App\Filament\Resources\ProduitResource;
use App\Models\Product;
use App\Models\ProductPriceHistory;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateProduit extends CreateRecord
{
    protected static string $resource = ProduitResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Produit créé')
            ->body('Le produit a été créé avec succès.');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $tenant = Filament::getTenant();

        $data['shop_id'] = $tenant->id;

        return $data;
    }

    public function afterCreate()
    {
        ProductPriceHistory::create([
            'product_id' => $this->record->id,
            'old_price_excl_tax' => 0,
            'new_price_excl_tax' => $this->record->price_ht,
            'old_price_incl_tax' => 0,
            'new_price_incl_tax' => $this->record->price_ttc,
            'old_tax_rate' => 0,
            'new_tax_rate' => $this->record->tva,
            'user_id' => Auth::id(),
        ]);

        activity('Produit')
            ->event('Création du produit')
            ->causedBy(Auth::user())
            ->performedOn($this->record)
            ->log('Le produit a été créé avec succès.');
    }

    public function mount(): void
    {
        parent::mount();

        if ($duplicateFromId = request()->get('duplicate_from')) {
            $parentProduct = Product::find($duplicateFromId);

            if ($parentProduct) {
                $this->form->fill([
                    'name' => $parentProduct->name,
                    'type' => $parentProduct->type,
                    'description' => $parentProduct->description,
                    'price_buy' => $parentProduct->price_buy,
                    'price_ht' => $parentProduct->price_ht,
                    'price_ttc' => $parentProduct->price_ttc,
                    'tva' => $parentProduct->tva,
                    'category_id' => $parentProduct->category_id,
                    'brand_id' => $parentProduct->brand_id,
                    'storage_id' => $parentProduct->storage_id,
                    'unit' => $parentProduct->unit,
                ]);
            }
        }
    }
}
