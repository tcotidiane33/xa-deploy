<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class LogChannel
{
    public function send($notifiable, Notification $notification)
    {
        $data = $notification->toLog($notifiable);

        Log::info('Notification: ' . get_class($notification), $data);
    }
}