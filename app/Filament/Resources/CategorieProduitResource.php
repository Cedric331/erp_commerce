<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategorieProduitResource\Pages;
use App\Filament\Resources\CategorieProduitResource\RelationManagers;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Panel;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CategorieProduitResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static bool $isScopedToTenant = true;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?string $label = 'Catégorie';
    protected static ?string $pluralModelLabel = 'Catégories';
    protected static ?string $slug = 'catégorie';
    protected static ?string $navigationGroup = 'Gestion des produits';
    protected static ?int $navigationSort = 1;
    protected static ?string $recordTitleAttribute = 'name';

    public static function isTenantSubscriptionRequired(Panel $panel): bool
    {
        return true;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nom de la catégorie')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                Forms\Components\TextInput::make('alert_stock')
                    ->label('Alerte de stock')
                    ->hint('Le stock à partir duquel une alerte sera envoyée pour les produits de cette catégorie. Si valeur à 0, il ne sera pas pris en compte.')
                    ->required()
                    ->integer(),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nom de la catégorie')
                    ->searchable(),
                Tables\Columns\TextColumn::make('alert_stock')
                    ->label('Alerte de stock')
                    ->searchable(),
                Tables\Columns\TextColumn::make('products_count')
                    ->label('Nombre de produits dans cette catégorie')
                    ->counts('products')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            RelationManagers\ProduitsRelationManager::class,
        ];
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCategorieProduits::route('/'),
            'create' => Pages\CreateCategorieProduit::route('/create'),
            'edit' => Pages\EditCategorieProduit::route('/{record}/edit'),
        ];
    }
}
