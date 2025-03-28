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
        return (new MailMessage)
            ->success()
            ->subject('Renouvellement de votre abonnement')
            ->greeting('Bonjour ' . $this->shop->name . ',')
            ->line('Votre abonnement a été renouvelé avec succès.')
            ->line('Le prochain renouvellement aura lieu le ' . $this->subscription->ends_at->format('d/m/Y') . '.')
            ->action('Voir les détails', url('/app/billing'))
            ->line('Merci de votre confiance !');
    }
}