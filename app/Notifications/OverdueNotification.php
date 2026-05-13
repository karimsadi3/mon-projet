<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OverdueNotification extends Notification
{
    use Queueable;

    protected $emprunt;

    public function __construct($emprunt)
    {
        $this->emprunt = $emprunt;
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'message' => 'Vous avez un retard pour le livre : ' . $this->emprunt->livre->titre,
            'livre' => $this->emprunt->livre->titre,
            'date_retour_prevue' => $this->emprunt->date_retour_prevue,
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Retard de retour de livre')
            ->line('Vous avez un retard pour le livre : ' . $this->emprunt->livre->titre)
            ->line('Date de retour prévue : ' . $this->emprunt->date_retour_prevue)
            ->line('Merci de retourner le livre rapidement.');
    }
}