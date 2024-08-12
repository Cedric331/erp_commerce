<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ContactNotification extends Notification
{
    use Queueable;

    protected $data;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
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
            ->subject('Nouveau message de contact')
            ->greeting('Bonjour,')
            ->line('Vous avez reçu un nouveau message de contact.')
            ->line('Nom: ' . $this->data['name'])
            ->line('Email: ' . $this->data['email'])
            ->line('Téléphone: ' . $this->data['phone'])
            ->line('Message: ' . $this->data['message']);
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
            'nom' => $this->data['nom'],
            'email' => $this->data['email'],
            'telephone' => $this->data['telephone'],
            'message' => $this->data['message'],
        ];
    }
}
