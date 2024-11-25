<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ClientManagerChangeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $manager;
    public $client;
    public function __construct($manager)
    {
        $this->manager = $manager;
        $this->client = $client;
    }

    public function build()
    {
        return $this->markdown('emails.client_manager_change')
            ->subject('Changement de gestionnaire | Client Manager Change Notification')
            ->with([
                'managerName' => $this->manager->name,
                'managerEmail' => $this->manager->email,
                'managerPhone' => $this->manager->phone,
                'client' => $this->client
            ]);
    }
}
