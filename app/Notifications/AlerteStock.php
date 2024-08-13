<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AlerteStock extends Notification
{
    use Queueable;

    public array $data;
    public object $shop;

    /**
     * Create a new notification instance.
     */
    public function __construct($data, $shop)
    {
        $this->data = $data;
        $this->shop = $shop;
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
                    ->subject('Alerte Stock - Quantisys Stock')
                    ->line('Voici la liste des produits dont le stock est en dessous du seuil d\'alerte sur votre commerce ' . $this->shop->enseigne . '.')
                    ->line('Merci de prendre les mesures nécessaires pour réapprovisionner votre stock.')
                    ->line('Liste des produits :')
                    ->line(implode("\n", array_map(function ($item) {
                        return $item['product'] . ' - Stock actuel : ' . $item['stock'] . ' unités';
                    }, $this->data)))
                    ->action('Accéder au site', url('/'))
                    ->line('Merci de votre confiance.');
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
