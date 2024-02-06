<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategorieProduitResource\Pages;
use App\Filament\Resources\CategorieProduitResource\RelationManagers;
use App\Models\CategorieProduit;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CategorieProduitResource extends Resource
{
    protected static ?string $model = CategorieProduit::class;

    protected static bool $isScopedToTenant = true;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?string $label = 'Catégorie';
    protected static ?string $pluralModelLabel = 'Catégories';
    protected static ?string $slug = 'catégorie';
    protected static ?string $navigationGroup = 'Gestion des produits';
    protected static ?int $navigationSort = 1;
    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nom de la catégorie')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nom de la catégorie')
                    ->searchable(),
                Tables\Columns\TextColumn::make('produits_count')
                    ->label('Nombre de produits dans cette catégorie')
                    ->counts('produits')
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
            //
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
