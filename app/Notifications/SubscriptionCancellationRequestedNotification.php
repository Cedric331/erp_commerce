<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SubscriptionCancellationRequestedNotification extends Notification
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
            ->subject('Demande de résiliation enregistrée')
            ->greeting('Bonjour ' . $this->shop->name . ',')
            ->line('Nous avons bien pris en compte votre demande de résiliation.')
            ->line('Votre abonnement restera actif jusqu\'au ' . $this->subscription->ends_at->format('d/m/Y') . '.')
            ->line('Vous pouvez continuer à utiliser tous nos services jusqu\'à cette date.')
            ->action('Gérer votre abonnement', url('/app/billing'))
            ->line('Nous espérons vous revoir bientôt !');
    }
}