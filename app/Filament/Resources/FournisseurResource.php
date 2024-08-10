<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FournisseurResource\Pages;
use App\Filament\Resources\FournisseurResource\RelationManagers;
use App\Models\Fournisseur;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Panel;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FournisseurResource extends Resource
{
    protected static ?string $model = Fournisseur::class;

    protected static bool $isScopedToTenant = true;
    protected static ?string $navigationIcon = 'heroicon-o-truck';

    protected static ?string $label = 'Fournisseur';
    protected static ?string $pluralModelLabel = 'Fournisseurs';
    protected static ?string $slug = 'fournisseurs';
    protected static ?string $navigationGroup = 'Gestion des produits';
    protected static ?int $navigationSort = 2;
    protected static ?string $recordTitleAttribute = 'name';

    public static function isTenantSubscriptionRequired(Panel $panel): bool
    {
        return true;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nom du fournisseur')
                    ->unique(ignoreRecord: true)
                    ->required()
                    ->columnSpanFull()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone')
                    ->label('Téléphone')
                    ->tel()
                    ->maxLength(255),
                Forms\Components\TextInput::make('address')
                    ->label('Adresse')
                    ->maxLength(255),
                Forms\Components\TextInput::make('city')
                    ->label('Ville')
                    ->maxLength(255),
                Forms\Components\TextInput::make('country')
                    ->label('Pays')
                    ->maxLength(255),
                Forms\Components\TextInput::make('postal_code')
                    ->label('Code postal')
                    ->numeric()
                    ->maxLength(5),
                Forms\Components\RichEditor::make('note')
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
                    ->columnSpanFull(),
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
                Tables\Columns\TextColumn::make('produits_count')
                    ->label('Nombre de produits')
                    ->counts('produits')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('phone')
                    ->label('Téléphone')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('address')
                    ->label('Adresse')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('city')
                    ->label('Ville')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('country')
                    ->label('Pays')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('postal_code')
                    ->label('Code postal')
                    ->searchable()
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
                //
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
            RelationManagers\ProduitsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFournisseurs::route('/'),
            'create' => Pages\CreateFournisseur::route('/create'),
            'edit' => Pages\EditFournisseur::route('/{record}/edit'),
        ];
    }
}
