<?php

namespace App\Console\Commands;

use App\Models\Produit;
use App\Models\User;
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
        $products = Produit::with('categorie')->get();

        foreach ($products as $product) {
            $threshold = $product->stock_alert && $product->stock_alert > 0 ? $product->stock_alert : $product->categorie->alert_stock;

            if ($threshold > 0 && $product->stock <= $threshold) {
                $recipient = User::with('rolesAllTenant')->whereHas('commercant', function ($query) use ($product) {
                    $query->where('commercant_id', $product->commercant_id);
                })->get();
                // On garde que les users qui ont le rôle "Gérant" et 'Manager' dans la relation rolesAllTenant
                $recipient = $recipient->filter(function ($user) {
                    return $user->rolesAllTenant()->whereIn('name', ['Gérant', 'Manager'])->exists();
                });

                Notification::make()
                    ->title('Alerte stock sur le produit ' . $product->nom . ' - ' . $product->commercant->enseigne)
                    ->body('Le stock du produit ' . $product->nom . ' sur le commerce ' . $product->commercant->enseigne . ' est en dessous du seuil d\'alerte. Il reste ' . $product->stock . ' unités.')
                    ->sendToDatabase($recipient);
                dd('Notification envoyée pour le produit ' . $product->nom);
//                \Notification::route('mail', 'email@example.com')
//                ->notify(new StockAlertNotification($product));
            }
        }

        $this->info('Vérification du stock terminée.');
    }

}
