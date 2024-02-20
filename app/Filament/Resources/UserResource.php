<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\Role;
use App\Models\User;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static bool $isScopedToTenant = true;
    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $label = 'Utilisateur';
    protected static ?string $pluralModelLabel = 'Utilisateurs';

    protected static ?string $slug = 'users';
    protected static ?string $navigationGroup = 'Gestion des utilisateurs';
    protected static ?int $navigationSort = 9;
    protected static ?string $recordTitleAttribute = 'name';

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        if (!Filament::auth()->user()->isAdministrateur()) {
            $query->whereHas('roles', function ($query) {
                $query->where('name', '!=', ['Administrateur', 'Gérant']);
            });
        }

        return $query;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nom')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->unique(ignoreRecord: true)
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('commercant')
                    ->label('Commerce autorisé')
                    ->relationship(name: 'commercant', titleAttribute: 'enseigne')
                    ->columnSpanFull()
                    ->hidden(fn () => !Auth::user()->isAdministrateurOrGerant())
                    ->multiple()
                    ->preload()
                    ->searchable(),
                Forms\Components\Select::make('roles')
                    ->label('Rôles')
                    ->relationship(name: 'roles', titleAttribute: 'name', modifyQueryUsing: function ($query) {
                        if (Auth::user()->isAdministrateur()) {
                            $query->where('roles.commercant_id', '=', Filament::getTenant()->id)
                                ->orWhere('roles.commercant_id', '=', null);
                        } else if (Auth::user()->isGerant()) {
                            $query->where('roles.commercant_id', '=', Filament::getTenant()->id)
                                ->orWhere('roles.name', '=', 'Gérant');
                        } else {
                            $query->where('roles.commercant_id', '=', Filament::getTenant()->id);
                        }

                    })
                    ->saveRelationshipsUsing(function (Model $record, $state) {
                        $record->roles()->syncWithPivotValues($state, [config('permission.column_names.team_foreign_key') => getPermissionsTeamId()]);
                    })
                    ->columnSpanFull()
                    ->preload()
                    ->searchable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nom')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('commercant.enseigne')
                    ->badge()
                    ->hidden(fn () => !Auth::user()->isAdministrateurOrGerant())
                    ->label('Commerce autorisé')
                    ->limit(50)
                    ->searchable(),
                Tables\Columns\TextColumn::make('roles.name')
                    ->badge()
                    ->label('Rôles')
                    ->searchable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('commercant')
                    ->label('Commerce autorisé')
                    ->relationship('commercant', 'enseigne')
                    ->searchable()
                    ->hidden(fn () => !Auth::user()->isAdministrateurOrGerant())
                    ->preload()
                    ->multiple(),
                Tables\Filters\SelectFilter::make('roles')
                    ->label('Rôles')
                    ->relationship('roles', 'name')
                    ->searchable()
                    ->preload()
                    ->multiple(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
