<?php

namespace App\Filament\Resources\StockResource\Pages;

use App\Filament\Resources\StockResource;
use App\Models\Produit;
use App\Models\StockStatus;
use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateStock extends CreateRecord
{
    protected static string $resource = StockResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public function mutateFormDataBeforeCreate(array $data): array
    {
        $this->data['commercant_id'] = Filament::getTenant()->id;

        return $data;
    }

    public function afterCreate()
    {
        if (!$this->data['scheduled_date']) {
            $type = StockStatus::find($this->data['stock_status_id'])->type;
            if ($type === StockStatus::TYPE_ENTREE) {
                Produit::find($this->data['produit_id'])->update([
                    'stock' => Produit::find($this->data['produit_id'])->stock + $this->data['quantity'],
                ]);
            } else {
                Produit::find($this->data['produit_id'])->update([
                    'stock' => Produit::find($this->data['produit_id'])->stock - $this->data['quantity'],
                ]);
            }
        }
    }

    public function beforeCreate()
    {
        if (Auth::user()->can('create', $this->getRecord())) {
            return true;
        }
        Notification::make()
            ->title('Accès interdit')
            ->body('Vous n\'avez pas l\'autorisation d\'effectuer cette action.')
            ->danger()
            ->duration(10000)
            ->send();

        $this->halt();
    }
}
