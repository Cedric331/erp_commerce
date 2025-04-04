<?php

namespace App\Console\Commands;

use App\Models\Shop;
use Illuminate\Console\Command;
use Laravel\Cashier\Subscription;
use Symfony\Component\Console\Command\Command as CommandAlias;

class UpdateShopSubscriptionStatus extends Command
{
    protected $signature = 'shops:update-subscription-status';
    protected $description = 'Update the subscribed column in shops table based on active subscriptions';

    public function handle()
    {
        $this->info('Updating shop subscription status...');

        // Réinitialiser tous les statuts d'abonnement
        Shop::query()->update(['subscribed' => false]);
        $this->info('Reset all subscription statuses to false');

        // Récupérer tous les abonnements actifs
        $activeSubscriptions = Subscription::where('stripe_status', 'active')
            ->whereNull('ends_at')
            ->get();

        $this->info("Found {$activeSubscriptions->count()} active subscriptions");

        // Mettre à jour le statut d'abonnement pour chaque boutique avec un abonnement actif
        foreach ($activeSubscriptions as $subscription) {
            $shopId = $subscription->shop_id;

            if ($shopId) {
                Shop::where('id', $shopId)->update(['subscribed' => true]);
                $this->info("Updated shop ID {$shopId} to subscribed=true");
            }
        }

        // Récupérer également les abonnements en période d'essai
        $trialSubscriptions = Subscription::where(function ($query) {
            $query->where('stripe_status', 'trialing')
                ->orWhereNotNull('trial_ends_at');
        })
            ->where(function ($query) {
                $query->whereNull('trial_ends_at')
                    ->orWhere('trial_ends_at', '>', now());
            })
            ->get();

        $this->info("Found {$trialSubscriptions->count()} trial subscriptions");

        // Détails des abonnements en essai pour débogage
        foreach ($trialSubscriptions as $subscription) {
            $this->info("Trial subscription details: ID={$subscription->id}, Status={$subscription->stripe_status}, TrialEndsAt=" . ($subscription->trial_ends_at ? $subscription->trial_ends_at->format('Y-m-d H:i:s') : 'null'));
        }

        // Mettre à jour le statut d'abonnement pour chaque boutique avec un abonnement en essai
        foreach ($trialSubscriptions as $subscription) {
            $shopId = $subscription->shop_id;

            if ($shopId) {
                Shop::where('id', $shopId)->update(['subscribed' => true]);
                $this->info("Updated shop ID {$shopId} to subscribed=true (trial)");
            }
        }

        $this->info('Shop subscription status update completed');

        return CommandAlias::SUCCESS;
    }
}
