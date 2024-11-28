<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;

class FicheClientActionNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $ficheClient;
    protected $action;
    protected $details;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($ficheClient, $action, $details)
    {
        $this->ficheClient = $ficheClient;
        $this->action = $action;
        $this->details = $details;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database', 'broadcast'];
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
                    ->line('Une action a été menée sur une fiche client.')
                    ->line('Action : ' . $this->action)
                    ->line('Détails : ' . $this->details)
                    ->action('Voir la fiche client', url('/fiches-clients/' . $this->ficheClient->id))
                    ->line('Merci d\'utiliser notre application!');
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
            'fiche_client_id' => $this->ficheClient->id,
            'action' => $this->action,
            'details' => $this->details,
        ];
    }

    /**
     * Get the broadcastable representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return BroadcastMessage
     */
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'fiche_client_id' => $this->ficheClient->id,
            'action' => $this->action,
            'details' => $this->details,
        ]);
    }
}
