<?php

namespace App\Filament\Resources\ProduitResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class VariantsRelationManager extends RelationManager
{
    protected static string $relationship = 'variants';

    protected static ?string $title = 'Variantes';

    protected static ?string $modelLabel = 'variante';

    protected static ?string $pluralModelLabel = 'variantes';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informations de base')
                    ->schema([
                        Grid::make(2)->schema([
                            Forms\Components\TextInput::make('name')
                                ->label('Nom')
                                ->required()
                                ->maxLength(255)
                                ->default(fn ($livewire) => $livewire->ownerRecord->name),

                            Forms\Components\TextInput::make('sku')
                                ->label('Référence')
                                ->required()
                                ->unique(ignoreRecord: true)
                                ->maxLength(255)
                                ->default(fn ($livewire) => $livewire->ownerRecord->reference.'-VAR'),

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

                            Forms\Components\TextInput::make('color')
                                ->label('Couleur')
                                ->maxLength(255),

                            Forms\Components\TextInput::make('weight')
                                ->label('Poids')
                                ->maxLength(255),

                            Forms\Components\TextInput::make('barcode')
                                ->label('Code-barres')
                                ->unique(ignoreRecord: true)
                                ->maxLength(255),
                        ]),
                    ]),

                Section::make('Prix et stock')
                    ->schema([
                        Grid::make(2)->schema([
                            Forms\Components\TextInput::make('price_ht')
                                ->label('Prix HT')
                                ->numeric()
                                ->prefix('€')
                                ->required()
                                ->default(fn ($livewire) => $livewire->ownerRecord->price_ht)
                                ->live(onBlur: true)
                                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                    if ($state && $get('tva')) {
                                        $set('price_ttc', round($state * (1 + $get('tva') / 100), 2));
                                    }
                                }),

                            Forms\Components\TextInput::make('price_ttc')
                                ->label('Prix TTC')
                                ->numeric()
                                ->prefix('€')
                                ->required()
                                ->default(fn ($livewire) => $livewire->ownerRecord->price_ttc)
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
                                ->default(fn ($livewire) => $livewire->ownerRecord->tva ?? '20')
                                ->required()
                                ->live()
                                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                    if ($get('price_ht')) {
                                        $set('price_ttc', round($get('price_ht') * (1 + $state / 100), 2));
                                    }
                                }),

                            Forms\Components\TextInput::make('stock')
                                ->label('Stock')
                                ->numeric()
                                ->default(0)
                                ->required(),
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

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nom')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('sku')
                    ->label('Référence')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('size')
                    ->label('Taille')
                    ->sortable(),

                Tables\Columns\TextColumn::make('color')
                    ->label('Couleur')
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

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Statut')
                    ->colors([
                        'success' => 'active',
                        'danger' => 'inactive',
                    ]),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('size')
                    ->label('Taille')
                    ->options([
                        'XS' => 'XS',
                        'S' => 'S',
                        'M' => 'M',
                        'L' => 'L',
                        'XL' => 'XL',
                        'XXL' => 'XXL',
                    ]),
                Tables\Filters\SelectFilter::make('status')
                    ->label('Statut')
                    ->options([
                        'active' => 'Actif',
                        'inactive' => 'Inactif',
                    ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->mutateFormDataUsing(function (array $data) {
                        // Générer un nom complet pour la variante si nécessaire
                        if ($data['size'] || $data['color']) {
                            $attributes = [];
                            if ($data['size']) {
                                $attributes[] = $data['size'];
                            }
                            if ($data['color']) {
                                $attributes[] = $data['color'];
                            }
                            $data['name'] = $data['name'].' - '.implode(' - ', $attributes);
                        }

                        return $data;
                    }),
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

    protected function afterSave(): void
    {
        $variant = $this->record;

        if ($variant->wasChanged(['price_ht', 'price_ttc', 'tva'])) {
            \App\Models\ProductPriceHistory::create([
                'product_id' => $variant->product_id,
                'variant_id' => $variant->id,
                'old_price_excl_tax' => $variant->getOriginal('price_ht') ?? 0,
                'new_price_excl_tax' => $variant->price_ht,
                'old_price_incl_tax' => $variant->getOriginal('price_ttc') ?? 0,
                'new_price_incl_tax' => $variant->price_ttc,
                'old_tax_rate' => $variant->getOriginal('tva') ?? 0,
                'new_tax_rate' => $variant->tva,
                'user_id' => Auth::id(),
            ]);
        }
    }
}
