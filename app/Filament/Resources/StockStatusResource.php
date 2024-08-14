<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StockStatusResource\Pages;
use App\Models\StockStatus;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Panel;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class StockStatusResource extends Resource
{
    protected static ?string $model = StockStatus::class;
    protected static bool $isScopedToTenant = true;
    protected static ?string $navigationIcon = 'heroicon-o-cog';
    protected static ?string $label = 'Statut des stocks';
    protected static ?string $pluralModelLabel = 'Statut des stocks';
    protected static ?string $slug = 'stock-status';
    protected static ?string $navigationGroup = 'Gestion des stocks';
    protected static ?int $navigationSort = 2;

    public static function isTenantSubscriptionRequired(Panel $panel): bool
    {
        return true;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nom du statut')
                    ->unique(ignoreRecord: true)
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('type')
                    ->required()
                    ->options([
                        'entrée' => 'Entrée',
                        'sortie' => 'Sortie',
                    ])
                    ->label('Type de mouvement'),
                Forms\Components\ColorPicker::make('color')
                    ->required()
                    ->hint('Format hexadécimal')
                    ->label('Couleur'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nom du statut')
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->label('Type de mouvement')
                    ->searchable(),
                Tables\Columns\ColorColumn::make('color')
                    ->label('Couleur')
                    ->searchable(),
                Tables\Columns\TextColumn::make('stocks_count')
                    ->label('Nombre de fois utilisé')
                    ->default(0)
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Créé le')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Modifié le')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(function ($record) {
                        if ($record->name === StockStatus::STATUS_VENTE || $record->name === StockStatus::STATUS_LIVRAISON || $record->name === StockStatus::STATUS_PERTE)
                            return false;
                        else
                            return true;
                    }),
                Tables\Actions\DeleteAction::make()
                    ->visible(function ($record) {
                        if ($record->name === StockStatus::STATUS_VENTE || $record->name === StockStatus::STATUS_LIVRAISON || $record->name === StockStatus::STATUS_PERTE || $record->stocks_count > 0)
                            return false;
                        else
                            return true;
                    })
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
            'index' => Pages\ListStockStatuses::route('/'),
            'create' => Pages\CreateStockStatus::route('/create'),
            'edit' => Pages\EditStockStatus::route('/{record}/edit'),
        ];
    }
}
