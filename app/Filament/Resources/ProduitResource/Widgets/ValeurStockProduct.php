<?php

namespace App\Filament\Resources\ProduitResource\Widgets;

use App\Models\Produit;
use Filament\Facades\Filament;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ValeurStockProduct extends BaseWidget
{
    protected function getStats(): array
    {
        $products = Produit::where('commercant_id', Filament::getTenant()->id)->get();
        $valeurStockHt = $this->getValueStock($products, 'ht');
        $valeurStockTtc = $this->getValueStock($products, 'ttc');
        return [
            Stat::make('Nombre de produit', $products->count())
                ->icon('heroicon-o-circle-stack'),
            Stat::make('Valeur stock HT', $valeurStockHt . ' €')
               ->icon('heroicon-o-currency-euro'),
            Stat::make('Valeur stock TTC', $valeurStockTtc . ' €')
               ->icon('heroicon-o-currency-euro')
        ];
    }

    protected function getValueStock($products, $type)
    {
        $value = 0;
        foreach ($products as $product) {
            if ($product->stock > 0) {
                if ($type === 'ht') {
                    $value += $product->stock * $product->prix_ht;
                } else {
                    $value += $product->stock * $product->prix_ttc;
                }
            }
        }

        return $value;
    }
}
