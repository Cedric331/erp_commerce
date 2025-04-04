<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

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
        $url = URL::temporarySignedRoute(
            'filament.app.auth.password-reset.reset',
            now()->addMinutes(60),
            ['token' => $this->token, 'email' => $notifiable->email]
        );

        return (new MailMessage)
            ->subject('Création de votre compte')
            ->greeting('Bonjour,')
            ->line('Un compte a été créé pour vous avec l\'adresse email : '.$notifiable->email.'.')
            ->line('Afin de pouvoir vous connecter, veuillez cliquer sur le lien ci-dessous pour suivre la procédure de récupération du mot de passe.')
            ->action('Récupérer mon mot de passe', $url)
            ->line('Merci de votre confiance !')
            ->salutation('L\'équipe '.config('app.name'));
    }
}
