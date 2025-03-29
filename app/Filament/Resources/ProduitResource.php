<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProduitResource\Pages;
use App\Filament\Resources\ProduitResource\RelationManagers;
use App\Filament\Resources\ProduitResource\Widgets\ValeurStockProduct;
use App\Models\Product;
use App\Models\Storage;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Panel;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Unique;

class ProduitResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static bool $isScopedToTenant = true;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    protected static ?string $label = 'un produit';

    protected static ?string $pluralModelLabel = 'Produits';

    protected static ?string $slug = 'products';

    protected static ?string $navigationGroup = 'Gestion des produits';

    protected static ?int $navigationSort = 3;

    protected static ?string $recordTitleAttribute = 'name';

    public static function isTenantSubscriptionRequired(Panel $panel): bool
    {
        return true;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informations de base')
                    ->schema([
                        Grid::make(2)->schema([
                            Forms\Components\TextInput::make('name')
                                ->label('Nom du produit')
                                ->unique(ignoreRecord: true)
                                ->required()
                                ->maxLength(255),

                            Forms\Components\TextInput::make('type')
                                ->label('Type de produit')
                                ->maxLength(255),

                            Forms\Components\TextInput::make('reference')
                                ->label('Référence du produit')
                                ->unique(ignoreRecord: true, modifyRuleUsing: function (Unique $rule) {
                                    return $rule->where('shop_id', Filament::getTenant()->id);
                                })
                                ->required()
                                ->maxLength(255),

                            Forms\Components\TextInput::make('barcode')
                                ->label('Code-barres')
                                ->unique(ignoreRecord: true)
                                ->maxLength(255),
                        ]),

                        Forms\Components\RichEditor::make('description')
                            ->label('Description')
                            ->columnSpanFull(),
                    ]),

                Section::make('Caractéristiques')
                    ->schema([
                        Grid::make(2)->schema([
                            Forms\Components\Select::make('size')
                                ->label('Taille')
                                ->options([
                                    'XS' => 'XS',
                                    'S' => 'S',
                                    'M' => 'M',
                                    'L' => 'L',
                                    'XL' => 'XL',
                                    'XXL' => 'XXL',
                                ])
                                ->searchable(),

                            Forms\Components\Select::make('unit')
                                ->label('Unité')
                                ->options([
                                    'unité' => 'Unité',
                                    'kg' => 'Kilogramme',
                                    'g' => 'Gramme',
                                    'm' => 'Mètre',
                                    'cm' => 'Centimètre',
                                    'm²' => 'Mètre carré',
                                    'l' => 'Litre',
                                    'ml' => 'Millilitre',
                                    'carton' => 'Carton',
                                    'lot' => 'Lot',
                                    'palette' => 'Palette',
                                    'paire' => 'Paire',
                                ])
                                ->default('unité')
                                ->required()
                                ->searchable(),

                            Forms\Components\TextInput::make('color')
                                ->label('Couleur')
                                ->maxLength(255),

                            Forms\Components\TextInput::make('weight')
                                ->label('Poids')
                                ->maxLength(255),

                        ]),
                    ]),

                Section::make('Prix et taxes')
                    ->schema([
                        Grid::make(2)->schema(components: [
                            Forms\Components\TextInput::make('price_buy')
                                ->label('Prix d\'achat HT')
                                ->numeric()
                                ->suffix('€'),

                            Forms\Components\TextInput::make('price_ht')
                                ->label('Prix de vente HT')
                                ->required()
                                ->numeric()
                                ->suffix('€')
                                ->live(onBlur: true)
                                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                    if ($state && $get('tva')) {
                                        $set('price_ttc', round($state * (1 + $get('tva') / 100), 2));
                                    }
                                }),

                            Forms\Components\TextInput::make('price_ttc')
                                ->label('Prix de vente TTC')
                                ->required()
                                ->numeric()
                                ->suffix('€')
                                ->live(onBlur: true)
                                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                    if ($state && $get('tva')) {
                                        $set('price_ht', round($state / (1 + $get('tva') / 100), 2));
                                    }
                                }),

                            Forms\Components\Select::make('tva')
                                ->label('TVA')
                                ->options([
                                    '20.00' => '20%',
                                    '10.00' => '10%',
                                    '5.50' => '5.5%',
                                    '2.10' => '2.1%',
                                ])
                                ->suffix('%')
                                ->default(20.00)
                                ->required()
                                ->live()
                                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                    $state = (float) $state; // Assurons-nous que c'est bien un float
                                    if ($get('price_ht')) {
                                        $set('price_ttc', round($get('price_ht') * (1 + $state / 100), 2));
                                    }
                                }),
                        ]),
                    ]),

                Section::make('Stock')
                    ->schema([
                        Grid::make(2)->schema([
                            Forms\Components\TextInput::make('stock')
                                ->label('Stock actuel')
                                ->numeric()
                                ->default(0)
                                ->required(),

                            Forms\Components\TextInput::make('stock_alert')
                                ->label('Seuil d\'alerte')
                                ->numeric()
                                ->default(0)
                                ->required(),
                        ]),
                    ]),

                Section::make('Relations')
                    ->schema([
                        Grid::make(2)->schema([
                            Forms\Components\Select::make('category_id')
                                ->relationship(name: 'category', titleAttribute: 'name')
                                ->label('Catégorie')
                                ->searchable()
                                ->optionsLimit(10)
                                ->searchDebounce(200)
                                ->loadingMessage('Recherche des catégories...')
                                ->preload()
                                ->createOptionForm([
                                    Forms\Components\TextInput::make('name')
                                        ->label('Nom de la catégorie')
                                        ->maxLength(255)
                                        ->required(),
                                    Forms\Components\TextInput::make('alert_stock')
                                        ->label('Alerte de stock')
                                        ->hint('Le stock à partir duquel une alerte sera envoyée pour les produits de cette catégorie. Si valeur à 0, il ne sera pas pris en compte.')
                                        ->required()
                                        ->integer(),

                                ])
                                ->createOptionAction(function (Action $action) {
                                    $action->hidden(! Auth::user()->isAdministrateurOrGerant() && ! Auth::user()->hasPermissionTo('Créer catégorie'));
                                    $action->mutateFormDataUsing(function (array $data) {
                                        $data['shop_id'] = Filament::getTenant()->id;

                                        return $data;
                                    });
                                }),

                            Forms\Components\Select::make('brand_id')
                                ->relationship(name: 'brand', titleAttribute: 'name')
                                ->label('Fournisseur')
                                ->searchable()
                                ->optionsLimit(10)
                                ->searchDebounce(200)
                                ->preload()
                                ->loadingMessage('Recherche des fournisseurs...')
                                ->createOptionForm([
                                    Forms\Components\TextInput::make('name')
                                        ->label('Nom du fournisseur')
                                        ->maxLength(255)
                                        ->required(),
                                ])
                                ->createOptionAction(function (Action $action) {
                                    $action->hidden(! Auth::user()->isAdministrateurOrGerant() && ! Auth::user()->hasPermissionTo('Créer Fournisseur'));
                                    $action->mutateFormDataUsing(function (array $data) {
                                        $data['shop_id'] = Filament::getTenant()->id;

                                        return $data;
                                    });
                                }),

                            Forms\Components\Select::make('storage_id')
                                ->relationship(name: 'storage', titleAttribute: 'name')
                                ->label('Emplacement')
                                ->searchable()
                                ->optionsLimit(10)
                                ->searchDebounce(200)
                                ->preload()
                                ->loadingMessage('Recherche des zone de stockage...')
                                ->createOptionForm([
                                    Forms\Components\Section::make([
                                        Forms\Components\TextInput::make('name')
                                            ->label('Nom de la zone de stockage')
                                            ->maxLength(255)
                                            ->columns(1)
                                            ->required(),
                                        Forms\Components\ToggleButtons::make('status')
                                            ->label('Status')
                                            ->colors([
                                                Storage::STATUS_ACTIVE => 'primary',
                                                Storage::STATUS_INACTIVE => 'danger',
                                            ])
                                            ->inline()
                                            ->default(Storage::STATUS_ACTIVE)
                                            ->options([
                                                Storage::STATUS_ACTIVE => 'Active',
                                                Storage::STATUS_INACTIVE => 'Inactive',
                                            ])
                                            ->required(),
                                    ])
                                        ->columns(2),
                                ])
                                ->createOptionAction(function (Action $action) {
                                    $action->hidden(! Auth::user()->isAdministrateurOrGerant() && ! Auth::user()->hasPermissionTo('Créer zone de stockage'));
                                    $action->mutateFormDataUsing(function (array $data) {
                                        $data['shop_id'] = Filament::getTenant()->id;

                                        return $data;
                                    });
                                }),
                        ]),
                    ]),
                Section::make('Statut')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->label('Statut')
                            ->options([
                                'active' => 'Actif',
                                'inactive' => 'Inactif',
                            ])
                            ->default('active')
                            ->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nom')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('reference')
                    ->label('Référence')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('stock')
                    ->label('Stock')
                    ->sortable(),

                Tables\Columns\TextColumn::make('price_ht')
                    ->label('Prix HT')
                    ->money('EUR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('price_ttc')
                    ->label('Prix TTC')
                    ->money('EUR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('Catégorie')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('brand.name')
                    ->label('Fournisseur')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                // Ajoutez vos filtres ici
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
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
            RelationManagers\PriceHistoryRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProduits::route('/'),
            'create' => Pages\CreateProduit::route('/create'),
            'edit' => Pages\EditProduit::route('/{record}/edit'),
            'activities' => Pages\ViewProduitActivities::route('/{record}/activities'),
        ];
    }

    public static function getWidgets(): array
    {
        return [
            ValeurStockProduct::class,
        ];
    }
}
