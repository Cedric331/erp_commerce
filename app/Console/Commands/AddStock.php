<?php

namespace App\Console\Commands;

use App\Models\Stock;
use App\Models\StockStatus;
use App\Models\User;
use Filament\Notifications\Notification;
use Illuminate\Console\Command;

class AddStock extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:add-stock';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $stocks = Stock::with('stockStatus')
            ->where('scheduled_date', '!=', null)
            ->where('scheduled_date', '<=', now())
            ->get();

        foreach ($stocks as $stock) {
            $type = $stock->stockStatus->type;

            if ($type === StockStatus::TYPE_ENTREE) {
                $stock->product->increment('stock', $stock->quantity);
            } elseif ($type === StockStatus::TYPE_SORTIE) {
                $stock->product->decrement('stock', $stock->quantity);
            }

            activity('Produit')
                ->event('Stock modifié')
                ->causedBy($stock->user)
                ->performedOn($stock)
                ->log('Le stock a été modifié avec succès le '.date('d/m/Y', strtotime(now())).'. Le stock du produit est maintenant de '.$stock->product->stock.'.');

            $stock->update([
                'scheduled_date' => null,
                'date_process' => now(),
            ]);

            $recipient = User::with('rolesAllTenant')->whereHas('shop', function ($query) use ($stock) {
                $query->where('shop_id', $stock->shop_id);
            })->get();
            $recipient = $recipient->filter(function ($user) {
                return $user->rolesAllTenant()->whereIn('name', ['Gérant', 'Manager'])->exists();
            });

            Notification::make()
                ->title('Stock mis à jour pour le produit '.$stock->product->name.' - '.$stock->shop->enseigne)
                ->body('Le stock du produit '.$stock->product->name.' sur le commerce '.$stock->shop->enseigne.' a été mis à jour. Il est maintenant de '.$stock->product->stock.' unités.')
                ->sendToDatabase($recipient);
        }
    }
}
