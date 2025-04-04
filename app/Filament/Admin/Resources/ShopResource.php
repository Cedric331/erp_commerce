<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ShopResource\Pages;
use App\Models\Shop;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ShopResource extends Resource
{
    protected static ?string $model = Shop::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';

    protected static ?string $navigationGroup = 'Gestion des commerces';

    protected static ?int $navigationSort = 1;

    public static function getNavigationLabel(): string
    {
        return 'Commerces';
    }

    public static function getPluralLabel(): string
    {
        return 'Commerces';
    }

    public static function getLabel(): string
    {
        return 'Commerce';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informations du commerce')
                    ->schema([
                        Forms\Components\TextInput::make('enseigne')
                            ->label('Enseigne')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        Forms\Components\TextInput::make('adresse')
                            ->label('Adresse')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('code_postal')
                            ->label('Code postal')
                            ->required()
                            ->maxLength(10),
                        Forms\Components\TextInput::make('ville')
                            ->label('Ville')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('telephone')
                            ->label('Téléphone')
                            ->tel()
                            ->maxLength(20),
                    ])->columns(2),
                
                Forms\Components\Section::make('Utilisateurs')
                    ->schema([
                        Forms\Components\Select::make('users')
                            ->label('Utilisateurs')
                            ->relationship('users', 'name')
                            ->multiple()
                            ->preload()
                            ->searchable(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('enseigne')
                    ->label('Enseigne')
                    ->searchable(),
                Tables\Columns\TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable(),
                Tables\Columns\TextColumn::make('ville')
                    ->label('Ville')
                    ->searchable(),
                Tables\Columns\TextColumn::make('users_count')
                    ->label('Nombre d\'utilisateurs')
                    ->counts('users')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Créé le')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListShops::route('/'),
            'create' => Pages\CreateShop::route('/create'),
            'edit' => Pages\EditShop::route('/{record}/edit'),
            'view' => Pages\ViewShop::route('/{record}'),
        ];
    }
}
