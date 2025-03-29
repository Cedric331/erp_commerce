<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ContactSupport extends Notification
{
    use Queueable;

    public array $data;

    /**
     * Create a new notification instance.
     */
    public function __construct($data)
    {
        $this->data = $data;
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

    public function routeNotificationForMail(Notification $notification): array|string
    {
        return env('ERROR_MAILER_RECIPIENT');
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Demande de contact')
            ->line('Vous avez reçu une demande de contact.')
            ->line('Sujet :'.$this->data['subject'])
            ->line('Message :'.$this->data['message'])
            ->line('Informations du contact : ')
            ->line('Informations de contact : ')
            ->line('Nom : '.$this->data['user_name'])
            ->line('Email : '.$this->data['user_email'])
            ->line('Enseigne : '.$this->data['shop_enseigne'])
            ->line('Email du Magasin: '.$this->data['shop_email'])
            ->action('Accéder au site', url('/'))
            ->line('Merci.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
