<?php

namespace App\Filament\Exports;

use App\Models\Product;
use App\Models\User;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Filament\Facades\Filament;
use Filament\Panel;

function getValeurStock(): float
{
    $products = Product::where('shop_id', Filament::getTenant()->id)->get();
    $value = 0;
    foreach ($products as $product) {
        $value += $product->stock * $product->price_ht;
    }

    return $value;
}

class ProductExporter extends Exporter
{
    protected static ?string $model = Product::class;

    public static function isTenantSubscriptionRequired(Panel $panel): bool
    {
        return true;
    }

    public function getFileName(Export $export): string
    {
        return "produits-{$export->getKey()}";
    }

    public function getLabel(): string
    {
        return 'Produits';
    }

    public static function getColumns(): array
    {
        $value = getValeurStock();

        return [

            ExportColumn::make('name')
                ->label('Nom du produit'),
            ExportColumn::make('reference')
                ->label('Référence fournisseur'),
            ExportColumn::make('barcode')
                ->label('Code-barres'),
            ExportColumn::make('type')
                ->label('Type de produit'),
            ExportColumn::make('storage.name')
                ->label('Zone de stockage'),
            ExportColumn::make('description')
                ->label('Description'),
            ExportColumn::make('stock')
                ->label('Stock Actuel'),
            ExportColumn::make('stock_alert')
                ->label('Stock Alerte'),
            ExportColumn::make('stock_total_ht')
                ->label('Valeur Stock HT Produit')
                ->state(function (Product $record): float {
                    return $record->stock * $record->price_ht;
                }),
            ExportColumn::make('stock_total_ttc')
                ->label('Valeur Stock TTC Produit')
                ->state(function (Product $record): float {
                    return $record->stock * $record->price_ttc;
                }),
            ExportColumn::make('weight')
                ->label('Poids'),
            ExportColumn::make('color')
                ->label('Couleur'),
            ExportColumn::make('size')
                ->label('Taille'),
            ExportColumn::make('unit')
                ->label('Unité'),
            ExportColumn::make('attributes')
                ->label('Attributs'),
            ExportColumn::make('price_buy')
                ->label('Prix d\'achat HT'),
            ExportColumn::make('price_ht')
                ->label('Prix HT'),
            ExportColumn::make('price_ttc')
                ->label('Prix TTC'),
            ExportColumn::make('tva')
                ->label('TVA'),
            ExportColumn::make('brand.name')
                ->label('Fournisseur'),
            ExportColumn::make('category.name')
                ->label('Categorie'),
            ExportColumn::make('shop.enseigne')
                ->label('Commerce'),
            ExportColumn::make('created_by')
                ->label('Créé par')
                ->state(function (Product $record): string {
                    return User::find($record->created_by)?->name ?? '';
                }),
            ExportColumn::make('updated_by')
                ->label('Modifié par')
                ->state(function (Product $record): string {
                    return User::find($record->updated_by)?->name ?? '';
                }),
            ExportColumn::make('created_at')
                ->enabledByDefault(false)
                ->label('Date de création'),
            ExportColumn::make('updated_at')
                ->enabledByDefault(false)
                ->label('Date de modification'),
            ExportColumn::make('product_total_ht')
                ->label('Valeur Total HT')
                ->state($value),

        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Les produits sont exportés. '.number_format($export->successful_rows).' '.str('ligne')->plural($export->successful_rows).' exportées.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' '.number_format($failedRowsCount).' '.str('row')->plural($failedRowsCount).' en échec.';
        }

        return $body;
    }
}
