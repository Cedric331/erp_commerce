<?php

namespace App\Livewire;

use App\Models\Produit;
use App\Models\Stock;
use App\Models\StockStatus;
use Carbon\Carbon;
use Carbon\CarbonInterface;
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
use Livewire\Component;

class CreateStock extends Component implements HasForms
{
    use InteractsWithForms;

    public $openModal = false;
    protected static ?string $model = Stock::class;
    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
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

        Stock::create($this->data);

        Notification::make()
            ->title('Ligne de stock créé avec succès')
            ->success()
            ->send();
    }

    public function form(Form $form): Form
    {
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
                    ->default(StockStatus::where([
                        ['commercant_id', Filament::getTenant()->id],
                        ['name', 'Vente'],
                    ])->first()->id)
                    ->searchable()
                    ->required()
                    ->columnSpanFull()
                    ->optionsLimit(5)
                    ->live(true)
                    ->hint(function (Get $get) {
                      $status = StockStatus::find($get('stock_status_id'));
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

    public function render()
    {
        return view('livewire.create-stock');
    }
}
