<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SubscriptionCancelledNotification extends Notification
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
            ->subject('Résiliation de votre abonnement')
            ->greeting('Bonjour ' . $this->shop->name . ',')
            ->line('Votre abonnement a été résilié.')
            ->line('Vous aurez accès à nos services jusqu\'au ' . $this->subscription->ends_at->format('d/m/Y') . '.')
            ->line('Nous espérons vous revoir bientôt !')
            ->action('Réactiver votre abonnement', url('/app/billing'));
    }
}