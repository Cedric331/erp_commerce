<?php

namespace App\Filament\Exports;

use App\Models\Stock;
use Carbon\Carbon;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Filament\Panel;

class StockExporter extends Exporter
{
    protected static ?string $model = Stock::class;

    public static function isTenantSubscriptionRequired(Panel $panel): bool
    {
        return true;
    }

    public function getFileName(Export $export): string
    {
        return "stock-{$export->getKey()}";
    }

    public function getLabel(): string
    {
        return 'Historique des stocks';
    }

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('scheduled_date')
                ->state(function (Stock $record) {
                    if (! empty($recordscheduled_date)) {
                        $date = Carbon::parse($record->scheduled_date);

                        return $date->format('d/m/Y');
                    } else {
                        return $record->created_at->format('d/m/Y');
                    }
                })
                ->label('Date de traitement'),
            ExportColumn::make('produit.name')->label('Nom du produit'),
            ExportColumn::make('stockStatus.name')->label('Statut du stock'),
            ExportColumn::make('stockStatus.type')->label('Type du stock'),
            ExportColumn::make('quantity')->label('Quantité'),
            ExportColumn::make('note')->label('Note'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'L\'historique de stock : '.number_format($export->successful_rows).' '.str('ligne')->plural($export->successful_rows).' exporté.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' '.number_format($failedRowsCount).' '.str('row')->plural($failedRowsCount).' échec de l\'import.';
        }

        return $body;
    }
}
