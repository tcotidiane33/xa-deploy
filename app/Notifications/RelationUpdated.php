<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class RelationUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    private $action;
    public $details;
    private $detailsMessage;

    public function __construct($action, $detailsMessage, $details)
    {
        $this->action = $action;
        $this->details = $details;
        $this->detailsMessage = $detailsMessage;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Mise à jour de la relation Gestionnaire-Client')
            ->line("Une action de {$this->action} a été effectuée.")
            ->line($this->detailsMessage)
            ->line('Détails : ')
            ->line(json_encode($this->details))
            ->line('Merci de prendre note de ce changement.');
    }

    public function toArray($notifiable)
    {
        return [
            'action' => $this->action,
            'details' => $this->details,
            'detailsMessage' => $this->detailsMessage,
            'creator_name' => $this->details['creator_name'],
            'email_sent' => true, // ou false selon l'état de l'envoi de l'e-mail
        ];
    }
}