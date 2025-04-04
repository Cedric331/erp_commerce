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
        $message = (new MailMessage)
            ->subject('Demande de résiliation enregistrée')
            ->greeting('Bonjour '.$this->shop->name.',')
            ->line('Nous avons bien pris en compte votre demande de résiliation.');

        // Vérifier si la date de fin existe
        if (isset($this->subscription->ends_at) && $this->subscription->ends_at) {
            $message->line('Votre abonnement restera actif jusqu\'au '.$this->subscription->ends_at->format('d/m/Y').'.');
            $message->line('Vous pouvez continuer à utiliser tous nos services jusqu\'à cette date.');
        } else {
            $message->line('Votre abonnement sera résilié à la fin de la période de facturation en cours.');
        }

        return $message
            ->action('Gérer votre abonnement', url('/app/billing'))
            ->line('Nous espérons vous revoir bientôt !');
    }
}
