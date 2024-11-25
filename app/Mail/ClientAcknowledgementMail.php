<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ClientAcknowledgementMail extends Mailable
{
    use Queueable, SerializesModels;

    public $manager;
    public $client;

    public function __construct($manager, $client)
    {
        $this->manager = $manager;
        $this->client = $client;
    }

    public function build()
    {
        return $this->markdown('emails.client_acknowledgement')
            ->subject('Accusé de réception de prise en charge')
            ->with([
                'managerName' => $this->manager->name,
                'managerEmail' => $this->manager->email,
                'clientName' => $this->client->name,
            ]);
    }
}