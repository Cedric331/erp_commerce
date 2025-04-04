<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentSuccessNotification extends Notification
{
    use Queueable;

    protected $shop;

    protected $subscription;

    /**
     * Create a new notification instance.
     */
    public function __construct($shop, $subscription)
    {
        $this->shop = $shop;
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
        $message = (new MailMessage)
            ->subject('Paiement réussi : Votre abonnement est confirmé')
            ->greeting('Bonjour '.$this->shop->name.',')
            ->line('Nous vous confirmons que votre paiement pour l\'abonnement a été effectué avec succès.');

        // Vérifier si abonnement avec période d'essai
        if ($this->subscription->onTrial()) {
            $message->line('Période d\'essai jusqu\'au '.$this->subscription->trial_ends_at->format('d/m/Y'));
        }

        // Vérifier si la date de fin existe
        if (isset($this->subscription->ends_at) && $this->subscription->ends_at) {
            $message->line('Prochain renouvellement : '.$this->subscription->ends_at->format('d/m/Y'));
        } elseif (isset($this->subscription->current_period_end) && $this->subscription->current_period_end) {
            $message->line('Prochain renouvellement : '.date('d/m/Y', $this->subscription->current_period_end));
        }

        return $message
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
