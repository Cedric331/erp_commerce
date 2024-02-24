<?php

namespace App\Filament\Resources\FournisseurResource\RelationManagers;

use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Enums\IconPosition;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Validation\Rule;

class ProduitsRelationManager extends RelationManager
{
    protected static string $relationship = 'produits';

    protected static ?string $title = 'Produit du fournisseur';


    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informations de base')
                    ->schema([
                        Forms\Components\TextInput::make('nom')
                            ->label('Nom du produit')
                            ->unique(ignoreRecord: true)
                            ->required()
                            ->columnSpanFull()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('type')
                            ->label('Type de produit')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('reference')
                            ->label('Référence fournisseur')
                            ->rules([
                                Rule::unique('produits', 'reference')
                                    ->where(function ($query) {
                                        return $query->where('commercant_id', Filament::getTenant()->id);
                                    }),
                            ])
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Select::make('categorie_id')
                            ->relationship(name: 'categorie', titleAttribute: 'name')
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
                                $action->mutateFormDataUsing(function (array $data) {
                                    $data['commercant_id'] = Filament::getTenant()->id;
                                    return $data;
                                });
                            }),

                        Forms\Components\Select::make('fournisseur_id')
                            ->relationship(name: 'fournisseur', titleAttribute: 'name')
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
                                $action->mutateFormDataUsing(function (array $data) {
                                    $data['commercant_id'] = Filament::getTenant()->id;
                                    return $data;
                                });
                            }),

                        Forms\Components\TextInput::make('stock')
                            ->hint('Stock actuel du produit (Le stock est mis à jour automatiquement suivant les mouvements de stock)')
                            ->label('Stock du produit')
                            ->required()
                            ->disabledOn('edit')
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

                        Forms\Components\Textarea::make('description')
                            ->maxLength(65535)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

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
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nom')
            ->columns([
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
            ])
            ->filters([
                //
            ])
            ->headerActions([
             //   Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                  Tables\Actions\EditAction::make(),
//                Tables\Actions\DeleteAction::make(),
            ]);
//            ->bulkActions([
//                Tables\Actions\BulkActionGroup::make([
//                    Tables\Actions\DeleteBulkAction::make(),
//                ]),
//            ]);
    }
}
