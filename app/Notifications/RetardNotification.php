<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class RetardNotification extends Notification
{
    use Queueable;

    protected $message;
    protected $livre;
    protected $dateEmprunt;

    public function __construct($message, $livre, $dateEmprunt)
    {
        $this->message = $message;
        $this->livre = $livre;
        $this->dateEmprunt = $dateEmprunt;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => $this->message,
            'livre' => $this->livre,
            'date_emprunt' => $this->dateEmprunt,
        ];
    }
}