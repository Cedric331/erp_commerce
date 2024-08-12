<?php

namespace App\Filament\Resources;

use App\Filament\Exports\StockExporter;
use App\Filament\Resources\StockResource\Pages;
use App\Models\Product;
use App\Models\Stock;
use App\Models\StockStatus;
use Carbon\Carbon;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Panel;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class StockResource extends Resource
{
    protected static ?string $model = Stock::class;
    protected static bool $isScopedToTenant = true;
    protected static ?string $navigationIcon = 'heroicon-o-archive-box-arrow-down';

    protected static ?string $label = 'Historique du stock';
    protected static ?string $pluralModelLabel = 'Historique des stocks';
    protected static ?string $slug = 'historique-stocks';
    protected static ?string $navigationGroup = 'Gestion des stocks';
    protected static ?int $navigationSort = 1;

    public static function isTenantSubscriptionRequired(Panel $panel): bool
    {
        return true;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('product_id')
                    ->label('Produit')
                    ->options(
                        Product::where('shop_id', Filament::getTenant()->id)
                            ->get()
                            ->mapWithKeys(fn ($product) => [$product->id => $product->nom])
                            ->toArray()
                    )
                    ->required(),
                Forms\Components\TextInput::make('quantity')
                    ->label('Quantité')
                    ->required()
                    ->integer(),
                Forms\Components\Select::make('stock_status_id')
                    ->label('Statut')
                    ->options(
                       StockStatus::where('shop_id', Filament::getTenant()->id)
                            ->get()
                            ->mapWithKeys(fn ($stockStatus) => [$stockStatus->id => $stockStatus->name])
                            ->toArray()
                    )
                    ->required(),
                Forms\Components\DatePicker::make('scheduled_date')
                    ->label('Date prévue'),
                Forms\Components\Textarea::make('note')
                    ->label('Note'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('date_action')
                    ->label('Traité le')
                    ->searchable()
                    ->getStateUsing(function ($record) {
                        if ($record->date_process) {
                            return $record->date_process->format('d/m/Y');
                        }

                        if (!empty($record->scheduled_date)) {
                            $date = Carbon::parse($record->scheduled_date);
                            if ($date->isFuture()) {
                                return 'En attente';
                            } else {
                                return $date->format('d/m/Y');
                            }
                        } else {
                            return $record->created_at->format('d/m/Y');
                        }
                    }),
                Tables\Columns\TextColumn::make('product.nom')
                    ->searchable()
                    ->sortable()
                    ->label('Produit'),
                Tables\Columns\TextColumn::make('quantity')
                    ->searchable()
                    ->sortable()
                    ->label('Quantité'),
                Tables\Columns\TextColumn::make('note')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable()
                    ->label('Note'),
                Tables\Columns\TextColumn::make('stockStatus.name')
                    ->searchable()
                    ->sortable()
                    ->label('Statut'),
                Tables\Columns\TextColumn::make('formatted_scheduled_date')
                    ->searchable()
                    ->sortable()
                    ->label('Date prévue'),
            ])
            ->filters([
                SelectFilter::make('quantity')
                    ->label('Produit')
                    ->relationship('product', 'nom')
                    ->preload()
                    ->options(
                        fn (Builder $query) => $query->pluck('nom', 'id')->all()
                    ),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(function ($record) {
                        if ($record->scheduled_date === null)
                            return false;
                        else
                        return $record->scheduled_date->isFuture();
                    }),
                Tables\Actions\DeleteAction::make()
                    ->visible(function ($record) {
                        if ($record->scheduled_date === null)
                            return false;
                        else
                            return $record->scheduled_date->isFuture();
                    }),
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
                    ->modifyQueryUsing(fn (Builder $query) => $query->where('shop_id', Filament::getTenant()->id))
                    ->hidden( !Auth::user()->hasPermissionTo('Exporter des données') && !Auth::user()->isAdministrateurOrGerant())
                    ->exporter(StockExporter::class)
            ])
            ->modifyQueryUsing(fn (Builder $query) => $query->orderBy('created_at', 'desc'))
            ->bulkActions([
    //                Tables\Actions\BulkActionGroup::make([
    //                    Tables\Actions\DeleteBulkAction::make(),
    //                ]),
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
            'index' => Pages\ListStocks::route('/'),
            'create' => Pages\CreateStock::route('/create'),
            'edit' => Pages\EditStock::route('/{record}/edit'),
        ];
    }
}
