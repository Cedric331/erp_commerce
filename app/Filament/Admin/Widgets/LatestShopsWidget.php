<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Shop;
use Filament\Tables;
use Filament\Tables\Columns;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class LatestShopsWidget extends BaseWidget
{
    protected static ?int $sort = 5;
    protected int|string|null $defaultTableRecordsPerPageSelectOption = 5;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Shop::query()
                    ->with(['users', 'products'])
                    ->latest()
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('enseigne')
                    ->label('Commerce')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date d\'inscription')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                Tables\Columns\IconColumn::make('subscribed')
                    ->label('Abonné')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),

                Tables\Columns\TextColumn::make('users_count')
                    ->label('Utilisateurs')
                    ->counts('users')
                    ->sortable(),

                Tables\Columns\TextColumn::make('products_count')
                    ->label('Produits')
                    ->counts('products')
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label('Voir')
                    ->url(fn (Shop $record): string => route('filament.admin.resources.shops.edit', $record))
                    ->icon('heroicon-m-eye'),
            ])
            ->heading('Derniers commerces enregistrés');
    }
}
