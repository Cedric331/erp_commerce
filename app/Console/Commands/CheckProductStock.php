<?php

namespace App\Console\Commands;

use App\Models\Merchant;
use App\Models\Product;
use App\Models\User;
use App\Notifications\AlerteStock;
use Filament\Notifications\Notification;
use Illuminate\Console\Command;

class CheckProductStock extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:product-stock';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Vérifie le stock des produits';


    /**
     * Execute the console command.
     */
    public function handle()
    {
        $merchants = Merchant::whereHas('products', function ($query) {
            $query->where('stock', '>', 0)
                    ->with('category');
        })->get();

        foreach ($merchants as $merchant) {
            $data = [];
            $recipient = User::with('rolesAllTenant')->whereHas('merchant', function ($query) use ($merchant) {
                $query->where('merchant_id', $merchant->id);
            })->get();
            $recipient = $recipient->filter(function ($user) {
                return $user->rolesAllTenant()->whereIn('name', ['Gérant', 'Manager'])->exists();
            });

            foreach ($merchant->products as $product) {
                $threshold = $product->stock_alert && $product->stock_alert > 0 || !$product->category ? $product->stock_alert : $product->categorie->alert_stock;

                if ($threshold > 0 && $product->stock <= $threshold) {
                    Notification::make()
                        ->title('Alerte stock sur le produit ' . $product->nom . ' - ' . $product->merchant->enseigne)
                        ->body('Le stock du produit ' . $product->nom . ' sur le commerce ' . $product->merchant->enseigne . ' est en dessous du seuil d\'alerte. Il reste ' . $product->stock . ' unités.')
                        ->sendToDatabase($recipient);
                    $data[] = [
                        'product' => $product->nom,
                        'stock' => $product->stock,
                        'seuil_alerte' => $threshold,
                    ];

                    activity('Produit')
                        ->event('Alerte stock')
                        ->causedBy($merchant->user)
                        ->performedOn($product)
                        ->log('Le stock du produit ' . $product->nom . ' est en dessous du seuil d\'alerte. Il reste ' . $product->stock . ' unités.');
                }
            }
            if (count($data) > 0) {
                foreach ($recipient as $user) {
                    $user->notify(new AlerteStock($data, $merchant));
                }
            }
        }


        $this->info('Vérification du stock terminée.');
    }

}
