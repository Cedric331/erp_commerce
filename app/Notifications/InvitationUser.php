<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InvitationUser extends Notification
{
    use Queueable;

    public $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $url = url('/app/password-reset/request');

        return (new MailMessage)
            ->subject('Création de votre compte - Fuxia Stock')
            ->line('Un compte a été créé pour vous avec l\'adresse email : '.$notifiable->email.'.')
            ->line('Afin de pouvoir vous connecter, veuillez cliquer sur le lien ci-dessous pour suivre la procédure de récupératio du mot de pase.')
            ->action('Récupérer mon mot de passe', $url)
            ->line('Merci');
    }

}
