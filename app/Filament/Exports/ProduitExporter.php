<?php

namespace App\Filament\Exports;

use App\Models\Produit;
use App\Models\User;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Filament\Facades\Filament;

function getValeurStock(): float
{
    $produits = Produit::where('commercant_id', Filament::getTenant()->id)->get();
    $value = 0;
    foreach ($produits as $produit) {
        $value += $produit->stock * $produit->prix_ht;
    }

    return $value;
}

class ProduitExporter extends Exporter
{
    protected static ?string $model = Produit::class;

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

            ExportColumn::make('nom')
                ->label('Nom du produit'),
            ExportColumn::make('reference')
                ->label('Référence'),
            ExportColumn::make('description')
                ->label('Description'),
            ExportColumn::make('stock')
                ->label('Stock Actuel'),
            ExportColumn::make('stock_alert')
                ->label('Stock Alerte'),
            ExportColumn::make('stock_total_ht')
                ->label('Valeur Stock HT Produit')
                ->state(function (Produit $record): float {
                    return $record->stock * $record->prix_ht;
                }),
            ExportColumn::make('prix_ht')
                ->label('Prix HT'),
            ExportColumn::make('prix_ttc')
                ->label('Prix TTC'),
            ExportColumn::make('tva')
                ->label('TVA'),
            ExportColumn::make('fournisseur.name')
                ->label('Fournisseur'),
            ExportColumn::make('categorie.name')
                ->label('Categorie'),
            ExportColumn::make('commercant.enseigne')
                ->label('Commerce'),
            ExportColumn::make('created_at')
                ->enabledByDefault(false)
                ->label('Date de création'),
            ExportColumn::make('updated_at')
                ->enabledByDefault(false)
                ->label('Date de modification'),
            ExportColumn::make('produit_total_ht')
                ->label('Valeur Total HT')
                ->state($value),

        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Les produits sont exporter ' . number_format($export->successful_rows) . ' ' . str('ligne')->plural($export->successful_rows) . ' exporté.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' échec de l\'import.';
        }

        return $body;
    }
}
