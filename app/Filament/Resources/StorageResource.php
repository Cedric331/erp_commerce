<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StorageResource\Pages;
use App\Filament\Resources\StorageResource\RelationManagers;
use App\Models\Storage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StorageResource extends Resource
{
    protected static ?string $model = Storage::class;

    protected static bool $isScopedToTenant = true;
    protected static ?string $navigationIcon = 'heroicon-o-archive-box';

    protected static ?string $label = 'Zone de stockage';
    protected static ?string $pluralModelLabel = 'Zones de stockage';
    protected static ?string $slug = 'stockage';
    protected static ?string $navigationGroup = 'Gestion des produits';
    protected static ?int $navigationSort = 3;
    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nom de la zone de stockage')
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
                    ->label('Note'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nom de la zone de stockage')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('products_count')
                    ->label('Nombre de produits')
                    ->counts('products')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge(function ($record) {
                        return match ($record->status) {
                            Storage::STATUS_ACTIVE => 'success',
                            Storage::STATUS_INACTIVE => 'danger',
                        };
                    })
                    ->searchable()
                    ->sortable(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStorages::route('/'),
            'create' => Pages\CreateStorage::route('/create'),
            'edit' => Pages\EditStorage::route('/{record}/edit'),
        ];
    }
}
