<?php

namespace App\Livewire;

use App\Filament\Resources\ProduitResource;
use App\Filament\Resources\StockStatusResource;
use App\Models\Produit;
use App\Models\Stock;
use App\Models\StockStatus;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Filament\Notifications\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CreateStock extends Component implements HasForms
{
    use InteractsWithForms;

    protected static ?string $model = Stock::class;

    public ?array $data = [];

    public bool $showForm = true;

    public function mount(): void
    {
        if (StockStatus::where('commercant_id', Filament::getTenant()->id)->count() > 0 && Produit::where('commercant_id', Filament::getTenant()->id)->count() > 0) {
            $this->form->fill();
        } else {
            $this->showForm = false;
        }
    }

    public function create(): void
    {
        $this->validate([
            'data.produit_id' => 'required',
            'data.quantity' => 'required|numeric',
            'data.stock_status_id' => 'required',
            'data.scheduled_date' => 'nullable|date',
            'data.note' => 'nullable|string',
        ]);

        $this->data['commercant_id'] = Filament::getTenant()->id;

        if ($this->data['scheduled_date'] === "") {
            $this->data['scheduled_date'] = null;
        }

        Stock::create($this->data);

        if (!$this->data['scheduled_date']) {
            $type = StockStatus::find($this->data['stock_status_id'])->type;

            $produit = Produit::find($this->data['produit_id']);
            if ($type === StockStatus::TYPE_ENTREE) {
                $produit->update([
                    'stock' => $produit->stock + $this->data['quantity'],
                ]);
            } else {
                $produit->update([
                    'stock' => $produit->stock - $this->data['quantity'],
                ]);
            }
            activity('Produit')
                ->event('Stock modifié - ' . StockStatus::find($this->data['stock_status_id'])->name)
                ->causedBy(Auth::user())
                ->performedOn($produit)
                ->log('Le stock a été modifié avec succès. Le stock du produit est maintenant de ' . $produit->stock . '.');
        }

        Notification::make()
            ->title('Ligne de stock créé avec succès')
            ->success()
            ->send();
    }

    public function form(Form $form): Form
    {
        if (!$this->showForm) {
            $this->showFormNotShown();
        }
        return $form
            ->schema([
                Select::make('produit_id')
                    ->label('Sélectionner un produit')
                    ->options(Produit::where('commercant_id', Filament::getTenant()->id)->pluck('nom', 'id'))
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
                    ->options(StockStatus::where('commercant_id', Filament::getTenant()->id)->pluck('name', 'id'))
                    ->default(
                        StockStatus::where([
                            ['commercant_id', Filament::getTenant()->id],
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
                      if (!$status) {
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
                        return Produit::where('commercant_id', Filament::getTenant()->id)->count() > 0 && Auth::user()->can('create', Produit::class);
                    })
                    ->url(ProduitResource::getUrl('create')),
                Action::make('create_statut')
                    ->button()
                    ->label('Créer un statut')
                    ->outlined()
                    ->hidden(function () {
                        return StockStatus::where('commercant_id', Filament::getTenant()->id)->count() > 0 && Auth::user()->can('create', StockStatus::class);
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
