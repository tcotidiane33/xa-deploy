<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RelationCreated extends Notification implements ShouldQueue
{
    use Queueable;

    protected $relation;
    protected $creator;

    public function __construct($relation, $creator)
    {
        $this->relation = $relation;
        $this->creator = $creator;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Nouvelle relation Gestionnaire-Client créée')
                    ->line('Une nouvelle relation Gestionnaire-Client a été créée.')
                    ->line('Client: ' . $this->relation->client->name)
                    ->line('Gestionnaire principal: ' . $this->relation->gestionnaire->name)
                    ->line('Créée par: ' . $this->creator->name)
                    ->action('Voir les détails', url('/admin/gestionnaire-client/' . $this->relation->id))
                    ->line('Merci d\'utiliser notre application!');
    }

    public function toArray($notifiable)
    {
        return [
            'relation_id' => $this->relation->id,
            'client_name' => $this->relation->client->name,
            'gestionnaire_name' => $this->relation->gestionnaire->name,
            'creator_name' => $this->creator->name,
            'email_sent' => true, // ou false selon l'état de l'envoi de l'e-mail

        ];
    }
}