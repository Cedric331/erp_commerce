<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\SubscriptionResource\Pages;
use App\Models\Shop;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Laravel\Cashier\Subscription;

class SubscriptionResource extends Resource
{
    protected static ?string $model = Subscription::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    protected static ?string $navigationGroup = 'Abonnements';

    protected static ?int $navigationSort = 1;

    public static function getNavigationLabel(): string
    {
        return 'Abonnements';
    }

    public static function getPluralLabel(): string
    {
        return 'Abonnements';
    }

    public static function getLabel(): string
    {
        return 'Abonnement';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informations de l\'abonnement')
                    ->schema([
                        Forms\Components\Select::make('shop_id')
                            ->label('Commerce')
                            ->options(Shop::all()->pluck('enseigne', 'id'))
                            ->searchable()
                            ->required(),
                        Forms\Components\TextInput::make('name')
                            ->label('Nom')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('stripe_id')
                            ->label('ID Stripe')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('stripe_status')
                            ->label('Statut Stripe')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\DateTimePicker::make('trial_ends_at')
                            ->label('Fin de la période d\'essai'),
                        Forms\Components\DateTimePicker::make('ends_at')
                            ->label('Date de fin'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('owner.enseigne')
                    ->label('Commerce')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('stripe_status')
                    ->label('Statut')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'trialing' => 'info',
                        'past_due' => 'warning',
                        'canceled' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('trial_ends_at')
                    ->label('Fin de la période d\'essai')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('ends_at')
                    ->label('Date de fin')
                    ->dateTime('d/m/Y H:i')
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
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //
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
            'index' => Pages\ListSubscriptions::route('/'),
            'view' => Pages\ViewSubscription::route('/{record}'),
        ];
    }
}
