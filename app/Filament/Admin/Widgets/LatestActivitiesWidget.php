<?php

namespace App\Filament\Admin\Widgets;

use Filament\Tables;
use Filament\Tables\Columns;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Database\Eloquent\Builder;

class LatestActivitiesWidget extends BaseWidget
{
    protected static ?int $sort = 6;
    protected int|string|null $defaultTableRecordsPerPageSelectOption = 5;

    public function table(Table $table): Table
    {
        // Vérifier si la table activity_log existe
        try {
            return $table
                ->query(
                    Activity::query()
                        ->latest()
                        ->limit(5)
                )
                ->columns([
                    Tables\Columns\TextColumn::make('causer.name')
                        ->label('Utilisateur')
                        ->searchable(),
                    Tables\Columns\TextColumn::make('description')
                        ->label('Action')
                        ->searchable(),
                    Tables\Columns\TextColumn::make('subject_type')
                        ->label('Type')
                        ->searchable(),
                    Tables\Columns\TextColumn::make('created_at')
                        ->label('Date')
                        ->dateTime('d/m/Y H:i')
                        ->sortable(),
                ])
                ->heading('Dernières activités');
        } catch (\Exception $e) {
            // En cas d'erreur, retourner un tableau vide
            return $table
                ->query(Activity::query()->limit(0))
                ->columns([
                    Tables\Columns\TextColumn::make('description')
                        ->label('Action'),
                ])
                ->heading('Dernières activités');
        }
    }
}
