<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StockResource\Pages;
use App\Models\Stock;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StockResource extends Resource
{
    protected static ?string $model = Stock::class;
    protected static bool $isScopedToTenant = true;
    protected static ?string $navigationIcon = 'heroicon-o-archive-box-arrow-down';

    protected static ?string $label = 'Historique du stock';
    protected static ?string $pluralModelLabel = 'Historique des stocks';
    protected static ?string $slug = 'historique-stocks';
    protected static ?string $navigationGroup = 'Gestion des stocks';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('produit.nom')->label('Produit'),
                Tables\Columns\TextColumn::make('quantity')->label('Quantité'),
                Tables\Columns\TextColumn::make('stockStatus.name')->label('Statut'),
                Tables\Columns\TextColumn::make('scheduled_date')->label('Date prévue'),
                Tables\Columns\TextColumn::make('note')->label('Note'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->before(
                        fn ($record) => date('Y-m-d') > $record->scheduled_date
                            ? Tables\Actions\EditAction::make()
                            : null
                    ),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStocks::route('/'),
            'create' => Pages\CreateStock::route('/create'),
            'edit' => Pages\EditStock::route('/{record}/edit'),
        ];
    }
}
