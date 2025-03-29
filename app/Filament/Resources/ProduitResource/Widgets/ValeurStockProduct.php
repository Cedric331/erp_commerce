<?php

namespace App\Filament\Resources\ProduitResource\Widgets;

use App\Models\Product;
use Filament\Facades\Filament;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ValeurStockProduct extends BaseWidget
{
    protected function getStats(): array
    {
        $products = Product::where('shop_id', Filament::getTenant()->id)->get();
        $valeurStockHtBrut = $this->getValueStock($products, 'ht');
        $valeurStockTtcBrut = $this->getValueStock($products, 'ttc');

        $valeurStockHt = number_format($valeurStockHtBrut, 2, '.', '');
        $valeurStockTtc = number_format($valeurStockTtcBrut, 2, '.', '');

        return [
            Stat::make('Nombre de produit', $products->count())
                ->icon('heroicon-o-circle-stack'),
            Stat::make('Valeur stock HT', $valeurStockHt.' €')
                ->icon('heroicon-o-currency-euro'),
            Stat::make('Valeur stock TTC', $valeurStockTtc.' €')
                ->icon('heroicon-o-currency-euro'),
        ];
    }

    protected function getValueStock($products, $type)
    {
        $value = 0;
        foreach ($products as $product) {
            if ($product->stock > 0) {
                if ($type === 'ht') {
                    $value += $product->stock * $product->price_ht;
                } else {
                    $value += $product->stock * $product->price_ttc;
                }
            }
        }

        return $value;
    }
}
