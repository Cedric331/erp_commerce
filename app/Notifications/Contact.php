<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class Contact extends Notification
{
    use Queueable;

    public array $data;
    public object $commercant;
    public object $user;


    /**
     * Create a new notification instance.
     */
    public function __construct($data, $commercant, $user)
    {
        $this->data = $data;
        $this->commercant = $commercant;
        $this->user = $user;
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
            ->line('Vous avez reçu une demande de contact de la part de ' . $this->commercant->enseigne . '.')
            ->line('Sujet :' . $this->data['subject'])
            ->line('Message :' . $this->data['message'])
            ->line('Informations du commercant : ')
            ->line('Enseigne : ' . $this->commercant->enseigne)
            ->line('Informations de contact : ')
            ->line('Nom : ' . $this->user->name)
            ->line('Email : ' . $this->user->email)
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
