<?php

namespace App\Livewire;

use Filament\Facades\Filament;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Dashboard;
use Illuminate\Config\Repository;
use Laravel\Cashier\SubscriptionBuilder;
use Livewire\Component;

class BannerSubscrib extends Component
{
    public $tenant;

    public $subscribed = false;


    public function mount()
    {
        $this->tenant = Filament::getTenant();
        $this->hasSubscribed();
    }

    public function hasSubscribed()
    {
        $this->subscribed = $this->tenant->subscribed('default');
    }

    public function redirectToCheckout()
    {
        $plan = 'default';
        $priceId = config("cashier.plans.$plan.price_id");
        $trialDays = config("cashier.plans.$plan.trial_days", false);
        $collectTaxIds = config("cashier.plans.$plan.collect_tax_ids", false);

        return $this->tenant->newSubscription($plan, $priceId)
            ->allowPromotionCodes()
            ->when($trialDays, static fn (SubscriptionBuilder $subscription) => $subscription->trialDays($trialDays))
            ->when($collectTaxIds, static fn (SubscriptionBuilder $subscription) => $subscription->collectTaxIds())
            ->checkout([
                'success_url' => Dashboard::getUrl(),
                'cancel_url' => Dashboard::getUrl(),
            ])
            ->redirect();
    }

    public function render()
    {
        return view('livewire.banner-subscrib', [
            'subscribed' => $this->subscribed,
        ]);
    }
}
