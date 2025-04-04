<?php

namespace App\Services;

use App\Models\NotificationTemplate;
use Illuminate\Notifications\Messages\MailMessage;

class NotificationService
{
    /**
     * Crée un message de notification à partir d'un template
     *
     * @param string $type Type de notification
     * @param array $variables Variables à remplacer
     * @return MailMessage|null
     */
    public static function createMailMessage(string $type, array $variables = []): ?MailMessage
    {
        // Ajouter les variables communes
        $variables['app_name'] = config('app.name');
        $variables['app_url'] = url('/');
        
        // Récupérer le template
        $template = NotificationTemplate::findByType($type);
        
        if (!$template) {
            // Utiliser les valeurs par défaut si aucun template n'est trouvé
            return self::getDefaultMailMessage($type, $variables);
        }
        
        // Remplacer les variables dans le sujet et le corps
        $subject = NotificationTemplate::replaceVariables($template->subject, $variables);
        $body = NotificationTemplate::replaceVariables($template->body, $variables);
        
        // Créer le message
        $message = (new MailMessage)
            ->subject($subject)
            ->view('emails.template', [
                'content' => $body,
                'greeting' => $variables['greeting'] ?? null,
                'salutation' => $variables['salutation'] ?? null,
                'additionalContent' => $variables['additional_content'] ?? null,
            ]);
        
        // Ajouter un bouton d'action si l'URL est fournie
        if (isset($variables['action_url']) && isset($variables['action_text'])) {
            $message->action($variables['action_text'], $variables['action_url']);
        }
        
        return $message;
    }
    
    /**
     * Récupère un message par défaut si aucun template n'est trouvé
     *
     * @param string $type Type de notification
     * @param array $variables Variables à remplacer
     * @return MailMessage
     */
    private static function getDefaultMailMessage(string $type, array $variables): MailMessage
    {
        $message = new MailMessage;
        
        switch ($type) {
            case NotificationTemplate::TYPE_WELCOME:
                $message->subject('Bienvenue sur ' . $variables['app_name'])
                    ->greeting('Bonjour ' . ($variables['user_name'] ?? '') . ',')
                    ->line('Nous sommes ravis de vous compter parmi nos membres.')
                    ->action('Visitez notre site', url('/'))
                    ->line('Merci de faire partie de notre communauté!');
                break;
                
            case NotificationTemplate::TYPE_SUBSCRIPTION_CREATED:
                $message->subject('Votre abonnement a été créé')
                    ->greeting('Bonjour ' . ($variables['shop_name'] ?? '') . ',')
                    ->line('Votre abonnement a été créé avec succès.')
                    ->line('Plan : ' . ($variables['plan_name'] ?? 'Standard'))
                    ->line('Période d\'essai : ' . ($variables['trial_ends_at'] ?? 'N/A'))
                    ->action('Accéder à votre compte', url('/app'))
                    ->line('Merci de votre confiance!');
                break;
                
            case NotificationTemplate::TYPE_PAYMENT_SUCCESS:
                $message->subject('Paiement réussi')
                    ->greeting('Bonjour ' . ($variables['shop_name'] ?? '') . ',')
                    ->line('Votre paiement a été traité avec succès.')
                    ->line('Montant : ' . ($variables['amount'] ?? ''))
                    ->action('Voir la facture', $variables['invoice_url'] ?? url('/app'))
                    ->line('Merci de votre confiance!');
                break;
                
            // Ajouter d'autres cas par défaut selon les besoins
                
            default:
                $message->subject('Notification de ' . $variables['app_name'])
                    ->greeting('Bonjour,')
                    ->line('Vous avez reçu une notification de ' . $variables['app_name'] . '.')
                    ->action('Accéder à votre compte', url('/app'));
                break;
        }
        
        return $message;
    }
}
