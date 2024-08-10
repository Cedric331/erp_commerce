<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentSuccessNotification extends Notification
{
    use Queueable;

    protected $commercant;
    protected $subscription;

    /**
     * Create a new notification instance.
     */
    public function __construct($commercant, $subscription)
    {
        $this->commercant = $commercant;
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
            ->greeting('Bonjour ' . $this->commercant->name . ',')
            ->line('Nous vous confirmons que votre paiement pour l\'abonnement a été effectué avec succès.')
            ->line('Détails de l\'abonnement :')
            ->line('ID de l\'abonnement : ' . $this->subscription->id)
            ->line('Statut : ' . ucfirst($this->subscription->stripe_status))
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
