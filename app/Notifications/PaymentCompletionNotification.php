<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentCompletionNotification extends Notification
{
    use Queueable;

    protected $commercant;
    protected $subscription;
    protected $paymentLink;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($commercant, $subscription, $paymentLink)
    {
        $this->commercant = $commercant;
        $this->subscription = $subscription;
        $this->paymentLink = $paymentLink;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Action nécessaire : Compléter le paiement de votre abonnement')
            ->greeting('Bonjour ' . $this->commercant->name . ',')
            ->line('Nous avons remarqué que votre récent paiement pour l\'abonnement doit être complété.')
            ->action('Compléter le paiement', $this->paymentLink)
            ->line('Si vous avez des questions, n\'hésitez pas à contacter notre équipe de support.')
            ->line('Merci pour votre confiance !');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'commercant_id' => $this->commercant->id,
            'subscription_id' => $this->subscription->id,
        ];
    }
}
