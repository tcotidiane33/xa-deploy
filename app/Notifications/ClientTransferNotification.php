<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ClientTransferNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        $clientsList = implode(', ', $this->data['clients']);
        
        return (new MailMessage)
            ->subject('Transfert de clients')
            ->greeting('Bonjour ' . $notifiable->name)
            ->line('Un transfert de clients a été effectué par ' . $this->data['transferred_by'])
            ->line('Clients concernés : ' . $clientsList)
            ->line('Date du transfert : ' . $this->data['transferred_at']->format('d/m/Y H:i'))
            ->action('Voir les détails', route('admin.client_user.index'))
            ->line('Merci de prendre en compte ces changements.');
    }

    public function toArray($notifiable)
    {
        return [
            'clients' => $this->data['clients'],
            'action' => $this->data['action'],
            'transferred_by' => $this->data['transferred_by'],
            'transferred_at' => $this->data['transferred_at']
        ];
    }
} 