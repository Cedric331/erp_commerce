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
        $message = (new MailMessage)
            ->subject('Résiliation de votre abonnement')
            ->greeting('Bonjour '.$this->shop->name.',')
            ->line('Votre abonnement a été résilié.');

        // Vérifier si la date de fin existe
        if (isset($this->subscription->ends_at) && $this->subscription->ends_at) {
            $message->line('Vous aurez accès à nos services jusqu\'au '.$this->subscription->ends_at->format('d/m/Y').'.');
        } else {
            $message->line('Votre accès aux services a été immédiatement interrompu.');
        }

        return $message
            ->line('Nous espérons vous revoir bientôt !')
            ->action('Accéder à l\application', url('/app'));
    }
}
