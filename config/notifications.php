<?php
// filepath: /c:/xampp/htdocs/xa-deploy/config/notifications.php
return [
    'default' => env('BROADCAST_DRIVER', 'log'),

    'channels' => [
        'mail' => [
            'driver' => 'mail',
        ],
        'database' => [
            'driver' => 'database',
            'table' => 'notifications',
        ],
        'broadcast' => [
            'driver' => 'broadcast',
        ],
        'nexmo' => [
            'driver' => 'nexmo',
        ],
        'slack' => [
            'driver' => 'slack',
        ],
    ],
];
