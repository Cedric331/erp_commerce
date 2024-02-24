<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StockResource\Pages;
use App\Models\Produit;
use App\Models\Stock;
use App\Models\StockStatus;
use Carbon\Carbon;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
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
                Forms\Components\Select::make('produit_id')
                    ->label('Produit')
                    ->options(
                        Produit::where('commercant_id', Filament::getTenant()->id)
                            ->get()
                            ->mapWithKeys(fn ($produit) => [$produit->id => $produit->nom])
                            ->toArray()
                    )
                    ->required(),
                Forms\Components\TextInput::make('quantity')
                    ->label('Quantité')
                    ->required()
                    ->integer(),
                Forms\Components\Select::make('stock_status_id')
                    ->label('Statut')
                    ->options(
                       StockStatus::where('commercant_id', Filament::getTenant()->id)
                            ->get()
                            ->mapWithKeys(fn ($stockStatus) => [$stockStatus->id => $stockStatus->name])
                            ->toArray()
                    )
                    ->required(),
                Forms\Components\DatePicker::make('scheduled_date')
                    ->label('Date prévue'),
                Forms\Components\Textarea::make('note')
                    ->label('Note'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('produit.nom')
                    ->searchable()
                    ->sortable()
                    ->label('Produit'),
                Tables\Columns\TextColumn::make('quantity')
                    ->searchable()
                    ->sortable()
                    ->label('Quantité'),
                Tables\Columns\TextColumn::make('note')
                    ->searchable()
                    ->label('Note'),
                Tables\Columns\TextColumn::make('stockStatus.name')
                    ->searchable()
                    ->sortable()
                    ->label('Statut'),
                Tables\Columns\TextColumn::make('formatted_scheduled_date')
                    ->searchable()
                    ->sortable()
                    ->label('Date prévue'),
            ])
            ->filters([
                SelectFilter::make('quantity')
                    ->label('Produit')
                    ->relationship('produit', 'nom')
                    ->preload()
                    ->options(
                        fn (Builder $query) => $query->pluck('nom', 'id')->all()
                    ),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(function ($record) {
                        if ($record->scheduled_date === null)
                            return false;
                        else
                        return $record->scheduled_date->isFuture();
                    }),
                Tables\Actions\DeleteAction::make()
                    ->visible(function ($record) {
                        if ($record->scheduled_date === null)
                            return false;
                        else
                            return $record->scheduled_date->isFuture();
                    }),
            ])
            ->bulkActions([
//                Tables\Actions\BulkActionGroup::make([
//                    Tables\Actions\DeleteBulkAction::make(),
//                ]),
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
