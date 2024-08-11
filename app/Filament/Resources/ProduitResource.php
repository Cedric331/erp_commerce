<?php

namespace App\Filament\Resources;

use App\Filament\Exports\ProductExporter;
use App\Filament\Resources\ProduitResource\Pages;
use App\Filament\Resources\ProduitResource\Widgets\ValeurStockProduct;
use App\Models\Category;
use App\Models\Product;
use App\Models\Storage;
use Filament\Actions\CreateAction;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Actions\Exports\Models\Export;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Panel;
use Filament\Resources\Resource;
use Filament\Support\Enums\IconPosition;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
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
    protected static ?string $recordTitleAttribute = 'nom';

    public static function isTenantSubscriptionRequired(Panel $panel): bool
    {
        return true;
    }

    public static function getWidgets(): array
    {
        return [
            ValeurStockProduct::class
        ];
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informations de base')
                    ->schema([
                        Forms\Components\TextInput::make('nom')
                            ->label('Nom du produit')
                            ->unique(ignoreRecord: true)
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Select::make('storage_id')
                            ->relationship(name: 'storage', titleAttribute: 'name')
                            ->label('Zone de stockage')
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
                                Forms\Components\RichEditor::make('note')
                                    ->columnSpanFull()
                                    ->toolbarButtons([
                                        'blockquote',
                                        'bold',
                                        'bulletList',
                                        'codeBlock',
                                        'h2',
                                        'h3',
                                        'italic',
                                        'link',
                                        'orderedList',
                                        'redo',
                                        'strike',
                                        'underline',
                                        'undo',
                                    ])
                                    ->label('Note')
                            ])
                            ->createOptionAction(function (Action $action) {
                                $action->hidden(!Auth::user()->isAdministrateurOrGerant() || !Auth::user()->hasPermissionTo('Créer zone de stockage'));
                                $action->mutateFormDataUsing(function (array $data) {
                                    $data['merchant_id'] = Filament::getTenant()->id;
                                    return $data;
                                });
                            }),

                        Forms\Components\TextInput::make('type')
                            ->label('Type de produit')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('reference')
                            ->label('Référence fournisseur')
                            ->unique(ignoreRecord: true, modifyRuleUsing: function (Unique $rule) {
                                return $rule->where('merchant_id', Filament::getTenant()->id);
                            })
                            ->unique(ignoreRecord: true, modifyRuleUsing: function (Unique $rule) {
                                return $rule->where('merchant_id', Filament::getTenant()->id);
                            })
                            ->required()
                            ->maxLength(255),

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
                                $action->hidden(!Auth::user()->isAdministrateurOrGerant() || !Auth::user()->hasPermissionTo('Créer catégorie'));
                                $action->mutateFormDataUsing(function (array $data) {
                                    $data['merchant_id'] = Filament::getTenant()->id;
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
                                $action->hidden(!Auth::user()->isAdministrateurOrGerant() || !Auth::user()->hasPermissionTo('Créer Fournisseur'));
                                $action->mutateFormDataUsing(function (array $data) {
                                    $data['merchant_id'] = Filament::getTenant()->id;
                                    return $data;
                                });
                            }),

                        Forms\Components\TextInput::make('stock')
                            ->hint('Stock actuel du produit (Le stock est mis à jour automatiquement suivant les mouvements de stock)')
                            ->label('Stock du produit')
                            ->required()
                            ->disabled(function ($livewire) {
                                if ($livewire->record) {
                                    if (Auth::user()->isAdministrateurOrGerant()) {
                                        return false;
                                    }
                                    return true;
                                }
                                return false;
                            })
                            ->numeric('integer')
                            ->default(0)
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('stock_alert')
                            ->hint('Alerte de stock (Si le stock est inférieur à cette valeur, une alerte sera envoyée). Mettre à 0 pour désactiver.')
                            ->label('Alerte de stock')
                            ->required()
                            ->numeric('integer')
                            ->default(0)
                            ->columnSpanFull(),

                        Forms\Components\RichEditor::make('description')
                            ->maxLength(65535)
                            ->label('Description du produit')
                            ->toolbarButtons([
                                'bold',
                                'italic',
                                'underline',
                                'strike',
                                'link',
                                'heading',
                                'code',
                                'bulletList',
                                'numberedList',
                                'alignment',
                                'undo',
                                'redo',
                            ])
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                // Section: Détails financiers
                Forms\Components\Section::make('Détails financiers')
                    ->schema([
                        Forms\Components\TextInput::make('prix_buy')
                            ->hint('Prix d\'achat du produit chez le fournisseur')
                            ->label('Prix d\'achat')
                            ->suffixIcon('heroicon-o-currency-euro')
                            ->numeric('decimal', 2),
                        Forms\Components\TextInput::make('prix_ht')
                            ->label('Prix Vente HT')
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
                            ->label('Prix Vente TTC')
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

                        Forms\Components\Select::make('tva')
                            ->label('TVA (%)')
                            ->options([
                                '20' => '20',
                                '10' => '10',
                                '5.5' => '5.5',
                                '2.1' => '2.1',
                            ])
                            ->default('20')
                            ->required()
                            ->hint('En modifiant la TVA, le prix TTC sera recalculé automatiquement.')
                            ->live('input', debounce: 200)
                            ->selectablePlaceholder(false)
                            ->afterStateUpdated(function (callable $set, callable $get, $state) {
                                $tva = (float) $state / 100;
                                $prixHT = (float) $get('prix_ht');
                                $prixTTC = $prixHT * (1 + $tva);
                                $set('prix_ttc', number_format($prixTTC, 2, '.', ''));
                            }),
                    ]),

                // Section: Médias
//                Forms\Components\Section::make('Médias')
//                    ->schema([
//                        SpatieMediaLibraryFileUpload::make('attachments')
//                            ->label('Images du produit')
//                            ->collection('media-product')
//                            ->conversion('thumb')
//                            ->responsiveImages()
//                            ->multiple()
//                            ->reorderable(),
//                    ]),
            ]);
    }



    public static function table(Table $table): Table
    {
        return $table
            ->columns([
//                SpatieMediaLibraryImageColumn::make('attachments')
//                    ->collection('media-product')
//                    ->circular()
//                    ->stacked()
//                    ->limit(3)
//                    ->limitedRemainingText(function ($state) {
//                        return count($state) > 3;
//                    })
//                    ->conversion('thumb')
//                    ->label('Images'),
                Tables\Columns\TextColumn::make('nom')
                    ->label('Nom du produit')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('reference')
                    ->label('Référence')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('stock')
                    ->label('Stock')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('prix_ht')
                    ->label('Prix Vente HT')
                    ->icon('heroicon-o-currency-euro')
                    ->iconPosition(IconPosition::After)
                    ->iconColor('primary')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('prix_ttc')
                    ->label('Prix Vente TTC')
                    ->icon('heroicon-o-currency-euro')
                    ->iconPosition(IconPosition::After)
                    ->iconColor('primary')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tva')
                    ->label('TVA (%)')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_stock_ht')
                    ->label('Valeur Stock HT')
                    ->icon('heroicon-o-currency-euro')
                    ->iconPosition(IconPosition::After)
                    ->iconColor('primary')
                    ->default(function ($record) {
                        return number_format($record->stock * $record->prix_ht, 2, '.', '');
                    })
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('storage.name')
                    ->label('Zone de stockage')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Catégorie')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('brand.name')
                    ->label('Fournisseur')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                SelectFilter::make('category')
                    ->label('Catégorie')
                    ->relationship('category', 'name')
                    ->preload()
                    ->options(
                        fn (Builder $query) => $query->pluck('name', 'id')->all()
                    ),
                SelectFilter::make('brand')
                    ->label('Fournisseur')
                    ->preload()
                    ->relationship('brand', 'name')
                    ->options(
                        fn (Builder $query) => $query->pluck('name', 'id')->all()
                    ),
                SelectFilter::make('storage')
                    ->label('Zone de stockage')
                    ->preload()
                    ->relationship('storage', 'name')
                    ->options(
                        fn (Builder $query) => $query->pluck('name', 'id')->all()
                    ),
                SelectFilter::make('tva')
                    ->label('TVA')
                    ->options([
                        '2.1' => '2.1',
                        '5.5' => '5.5',
                        '10' => '10',
                        '20' => '20',
                    ]),
            ], layout: FiltersLayout::Modal)
            ->persistFiltersInSession()
            ->filtersFormColumns(3)
            ->filtersTriggerAction(
                fn (\Filament\Tables\Actions\Action $action) => $action
                    ->button()
                    ->label('Filtrer'),
            )
            ->actions([
                ActionGroup::make([
                    Tables\Actions\Action::make('view_activities')
                        ->label('Historique du produit')
                        ->icon('heroicon-m-bolt')
                        ->color('purple')
                        ->url(fn ($record) => ProduitResource::getUrl('activities', ['record' => $record])),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),

                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->icon('heroicon-o-plus')
                    ->label('Créer un produit'),
                ExportAction::make()
                    ->label('Exporter')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('primary')
                    ->formats([
                        ExportFormat::Xlsx,
                        ExportFormat::Csv,
                    ])
                    ->modifyQueryUsing(fn (Builder $query) => $query->where('merchant_id', Filament::getTenant()->id))
                    ->hidden( !Auth::user()->hasPermissionTo('Exporter des données') && !Auth::user()->isAdministrateurOrGerant() && !Auth::user()->isManager())
                    ->exporter(ProductExporter::class)
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
            'activities' => Pages\ViewProduitActivities::route('/produit/{record}/activities'),
        ];
    }
}
