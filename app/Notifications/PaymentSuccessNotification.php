<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentSuccessNotification extends Notification
{
    use Queueable;

    protected $merchant;
    protected $subscription;

    /**
     * Create a new notification instance.
     */
    public function __construct($merchant, $subscription)
    {
        $this->merchant = $merchant;
        $this->subscription = $subscription;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Paiement réussi : Votre abonnement est confirmé')
            ->greeting('Bonjour ' . $this->merchant->name . ',')
            ->line('Nous vous confirmons que votre paiement pour l\'abonnement a été effectué avec succès.')
            ->line('Votre abonnement est maintenant actif.')
            ->action('Voir votre compte', url('/app'))
            ->line('Merci d\'utiliser notre application !');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'subscription_id' => $this->subscription->id,
            'stripe_status' => $this->subscription->stripe_status,
        ];
    }
}
