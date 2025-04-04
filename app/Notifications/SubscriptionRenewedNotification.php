<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SubscriptionRenewedNotification extends Notification
{
    use Queueable;

    protected $shop;

    protected $subscription;

    public function __construct($shop, $subscription)
    {
        $this->shop = $shop;
        $this->subscription = $subscription;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $message = (new MailMessage)
            ->success()
            ->subject('Renouvellement de votre abonnement')
            ->greeting('Bonjour '.$this->shop->name.',')
            ->line('Votre abonnement a été renouvelé avec succès.');

        // Vérifier si la date de fin existe
        if (isset($this->subscription->ends_at) && $this->subscription->ends_at) {
            $message->line('Le prochain renouvellement aura lieu le '.$this->subscription->ends_at->format('d/m/Y').'.');
        } elseif (isset($this->subscription->current_period_end) && $this->subscription->current_period_end) {
            $message->line('Le prochain renouvellement aura lieu le '.date('d/m/Y', $this->subscription->current_period_end).'.');
        }

        return $message
            ->action('Voir les détails', url('/app/billing'))
            ->line('Merci de votre confiance !');
    }
}
