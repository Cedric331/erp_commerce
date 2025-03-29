<?php

namespace App\Filament\Resources\ProduitResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Panel;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class PriceHistoryRelationManager extends RelationManager
{
    protected static string $relationship = 'priceHistory';

    protected static ?string $title = 'Historique des prix';

    protected static ?string $modelLabel = 'historique de prix';

    protected static ?string $pluralModelLabel = 'historiques de prix';

    public static function isTenantSubscriptionRequired(Panel $panel): bool
    {
        return true;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('created_at')
                    ->label('Date')
                    ->timezone('Europe/Paris')
                    ->columnSpanFull()
                    ->required(),

                Forms\Components\TextInput::make('old_tax_rate')
                    ->label('Ancienne TVA')
                    ->numeric()
                    ->required(),

                Forms\Components\TextInput::make('new_tax_rate')
                    ->label('Nouvelle TVA')
                    ->numeric()
                    ->required(),

                Forms\Components\TextInput::make('old_price_excl_tax')
                    ->label('Ancien prix HT')
                    ->numeric()
                    ->prefix('€')
                    ->required(),

                Forms\Components\TextInput::make('new_price_excl_tax')
                    ->label('Nouveau prix HT')
                    ->numeric()
                    ->prefix('€')
                    ->required(),

                Forms\Components\TextInput::make('old_price_incl_tax')
                    ->label('Ancien prix TTC')
                    ->numeric()
                    ->prefix('€')
                    ->required(),

                Forms\Components\TextInput::make('new_price_incl_tax')
                    ->label('Nouveau prix TTC')
                    ->numeric()
                    ->prefix('€')
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime('d/m/Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('new_tax_rate')
                    ->label('Nouvelle TVA')
                    ->suffix('%')
                    ->sortable(),

                Tables\Columns\TextColumn::make('new_price_excl_tax')
                    ->label('Nouveau prix HT')
                    ->money('EUR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('new_price_incl_tax')
                    ->label('Nouveau prix TTC')
                    ->money('EUR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Modifié par')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                //
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make(), // Probablement à retirer car l'historique devrait être automatique
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
