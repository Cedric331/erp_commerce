<?php

namespace App\Livewire;

use App\Filament\Resources\ProduitResource;
use App\Filament\Resources\StockStatusResource;
use App\Models\Product;
use App\Models\Stock;
use App\Models\StockStatus;
use Carbon\Carbon;
use Filament\Facades\Filament;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Panel;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CreateStock extends Component implements HasForms
{
    use InteractsWithForms;

    protected static ?string $model = Stock::class;

    public ?array $data = [];

    public bool $showForm = true;

    public static function isTenantSubscriptionRequired(Panel $panel): bool
    {
        return true;
    }

    public function mount(): void
    {
        if (StockStatus::where('shop_id', Filament::getTenant()->id)->count() > 0 && Product::where('shop_id', Filament::getTenant()->id)->count() > 0) {
            $this->form->fill();
        } else {
            $this->showForm = false;
        }
    }

    public function create(): void
    {
        $this->validate([
            'data.product_id' => 'required',
            'data.quantity' => 'required|numeric',
            'data.stock_status_id' => 'required',
            'data.scheduled_date' => 'nullable|date',
            'data.note' => 'nullable|string',
        ]);

        $this->data['shop_id'] = Filament::getTenant()->id;

        if ($this->data['scheduled_date'] === '') {
            $this->data['scheduled_date'] = null;
        }

        $product = Product::find($this->data['product_id']);
        $this->data['price_ht'] = $product->price_ht;
        $this->data['price_ttc'] = $product->price_ttc;
        $this->data['price_buy'] = $product->price_buy;

        $stock = Stock::create($this->data);

        if (! $this->data['scheduled_date']) {
            $type = StockStatus::find($this->data['stock_status_id'])->type;

            if ($type === StockStatus::TYPE_ENTREE) {
                $product->update([
                    'stock' => $product->stock + $this->data['quantity'],
                ]);
            } else {
                $product->update([
                    'stock' => $product->stock - $this->data['quantity'],
                ]);
            }
            $stock->update([
                'date_process' => now(),
            ]);

            activity('Produit')
                ->event('Stock modifié - '.StockStatus::find($this->data['stock_status_id'])->name)
                ->causedBy(Auth::user())
                ->performedOn($product)
                ->log('Le stock a été modifié avec succès. Le stock du produit est maintenant de '.$product->stock.'.');
        }

        Notification::make()
            ->title('Ligne de stock créé avec succès')
            ->success()
            ->send();

        $this->showForm = false;
    }

    public function form(Form $form): Form
    {
        if (! $this->showForm) {
            $this->showFormNotShown();
        }

        return $form
            ->schema([
                Select::make('product_id')
                    ->label('Sélectionner un produit')
                    ->options(Product::where('shop_id', Filament::getTenant()->id)->pluck('name', 'id'))
                    ->searchable()
                    ->required()
                    ->optionsLimit(5)
                    ->searchDebounce(200)
                    ->loadingMessage('Recherche des produits...'),
                TextInput::make('quantity')
                    ->label('Quantité')
                    ->type('number')
                    ->numeric()
                    ->required()
                    ->step(0.01),
                Select::make('stock_status_id')
                    ->label('Sélectionner un statut')
                    ->options(StockStatus::where('shop_id', Filament::getTenant()->id)->pluck('name', 'id'))
                    ->default(
                        StockStatus::where([
                            ['shop_id', Filament::getTenant()->id],
                            ['name', 'Vente'],
                        ])->first()?->id
                    )
                    ->searchable()
                    ->required()
                    ->columnSpanFull()
                    ->optionsLimit(5)
                    ->live(true)
                    ->hint(function (Get $get) {
                        $status = StockStatus::find($get('stock_status_id'));
                        if (! $status) {
                            return 'Sélectionnez un statut pour obtenir des informations.';
                        }
                        if ($status->type === 'entrée') {
                            return 'Le stock sera augmenté de la quantité indiquée.';
                        } else {
                            return 'Le stock sera diminué de la quantité indiquée.';
                        }
                    })
                    ->searchDebounce(200)
                    ->loadingMessage('Recherche des statuts...'),
                DatePicker::make('scheduled_date')
                    ->label('Date programmée')
                    ->hint('Si renseignée, cela sera traité automatiquement par le système à la date indiquée. Sinon, le stock sera traité immédiatement.')
                    ->format('d-m-y')
                    ->columnSpan(2)
                    ->minDate(Carbon::now()->format('Y-m-d')),
                Textarea::make('note')
                    ->label('Note')
                    ->columnSpanFull(),
            ])
            ->columns(2)
            ->statePath('data');
    }

    public function showFormNotShown()
    {
        Notification::make()
            ->title('Aucun produit ou statut de stock n\'est configuré')
            ->body('Veuillez créer un produit et configurer au moins un statut de stock.')
            ->warning()
            ->color('warning')
            ->duration(15000)
            ->actions([
                Action::make('create_product')
                    ->button()
                    ->label('Créer un produit')
                    ->outlined()
                    ->hidden(function () {
                        return Product::where('shop_id', Filament::getTenant()->id)->count() > 0 && Auth::user()->can('create', Product::class);
                    })
                    ->url(ProduitResource::getUrl('create')),
                Action::make('create_statut')
                    ->button()
                    ->label('Créer un statut')
                    ->outlined()
                    ->hidden(function () {
                        return StockStatus::where('shop_id', Filament::getTenant()->id)->count() > 0 && Auth::user()->can('create', StockStatus::class);
                    })
                    ->url(StockStatusResource::getUrl('create')),
            ])
            ->send();
    }

    public function render()
    {
        return view('livewire.create-stock');
    }
}
