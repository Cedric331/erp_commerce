<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProduitResource\Pages;
use App\Models\Produit;
use Filament\Forms;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\IconPosition;
use Filament\Tables;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\BaseFilter;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ProduitResource extends Resource
{
    protected static ?string $model = Produit::class;

    protected static bool $isScopedToTenant = true;


    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    protected static ?string $label = 'Produit';
    protected static ?string $pluralModelLabel = 'Produits';
    protected static ?string $slug = 'products';
    protected static ?string $navigationGroup = 'Gestion des produits';
    protected static ?int $navigationSort = 0;

    protected static ?string $recordTitleAttribute = 'nom';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Section: Informations de base
                Forms\Components\Section::make('Informations de base')
                    ->schema([
                        Forms\Components\TextInput::make('nom')
                            ->label('Nom du produit')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('reference')
                            ->label('Référence')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('ean')
                            ->label('Code EAN')
                            ->maxLength(255),

                        Forms\Components\Textarea::make('description')
                            ->maxLength(65535)
                            ->columnSpanFull(),
                    ]),

                // Section: Détails financiers
                Forms\Components\Section::make('Détails financiers')
                    ->schema([
                        Forms\Components\TextInput::make('prix_ht')
                            ->label('Prix HT')
                            ->suffixIcon('heroicon-o-currency-euro')
                            ->required()
                            ->live('input', debounce: 200)
                            ->afterStateUpdated(function (callable $set, callable $get, $state) {
                                $prixHT = (float) $state;
                                $tva = (float) $get('tva') / 100;
                                $prixTTC = $prixHT * (1 + $tva);
                                $set('prix_ttc', number_format($prixTTC, 2, '.', ''));
                            })
                            ->numeric('decimal', 2),

                        Forms\Components\TextInput::make('prix_ttc')
                            ->label('Prix TTC')
                            ->suffixIcon('heroicon-o-currency-euro')
                            ->required()
                            ->hint('Le prix TTC est calculé automatiquement en fonction du prix HT et de la TVA.')
                            ->live('input', debounce: 200)
                            ->afterStateUpdated(function (callable $set, callable $get, $state) {
                                $prixTTC = (float) $state;
                                $tva = (float) $get('tva') / 100;
                                $prixHT = $prixTTC / (1 + $tva);
                                $set('prix_ht', number_format($prixHT, 2, '.', ''));
                            })
                            ->numeric('decimal', 2),

                        Forms\Components\TextInput::make('tva')
                            ->label('TVA (%)')
                            ->default(20.00)
                            ->required()
                            ->hint('En modifiant la TVA, le prix TTC sera recalculé automatiquement.')
                            ->live('input', debounce: 200)
                            ->afterStateUpdated(function (callable $set, callable $get, $state) {
                                $tva = (float) $state / 100;
                                $prixHT = (float) $get('prix_ht');
                                $prixTTC = $prixHT * (1 + $tva);
                                $set('prix_ttc', number_format($prixTTC, 2, '.', ''));
                            })
                            ->numeric('decimal', 2),
                    ]),

                // Section: Médias
                Forms\Components\Section::make('Médias')
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('attachments')
                            ->label('Images du produit')
                            ->collection('media-product')
                            ->conversion('thumb')
                            ->responsiveImages()
                            ->multiple()
                            ->reorderable(),
                    ]),
            ]);
    }



    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('attachments')
                    ->collection('media-product')
                    ->circular()
                    ->stacked()
                    ->limit(3)
                    ->limitedRemainingText(function ($state) {
                        return count($state) > 3;
                    })
                    ->conversion('thumb')
                    ->label('Images'),
                Tables\Columns\TextColumn::make('nom')
                    ->label('Nom du produit')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('reference')
                    ->label('Référence')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('ean')
                    ->label('Code EAN')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('prix_ht')
                    ->label('Prix HT')
                    ->icon('heroicon-o-currency-euro')
                    ->iconPosition(IconPosition::After)
                    ->iconColor('primary')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('prix_ttc')
                    ->label('Prix TTC')
                    ->icon('heroicon-o-currency-euro')
                    ->iconPosition(IconPosition::After)
                    ->iconColor('primary')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tva')
                    ->label('TVA (%)')
                    ->numeric()
                    ->sortable(),
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
                SelectFilter::make('tva')
                    ->label('TVA')
                    ->options([
                        '5.5' => '5.5',
                        '10' => '10',
                        '20' => '20',
                    ]),
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
            'index' => Pages\ListProduits::route('/'),
            'create' => Pages\CreateProduit::route('/create'),
            'edit' => Pages\EditProduit::route('/{record}/edit'),
        ];
    }
}
