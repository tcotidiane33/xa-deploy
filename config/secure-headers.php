<?php
return [
    'csp' => [
        // Autoriser les scripts provenant de votre propre domaine et de Google reCAPTCHA
        'script-src' => [
            'self' => true,
            'https://www.google.com/recaptcha/',
            'https://www.gstatic.com/recaptcha/',
        ],
        // Autoriser les cadres provenant de votre propre domaine et de Google reCAPTCHA
        'frame-src' => [
            'self' => true,
            'https://www.google.com/recaptcha/',
        ],
        // Autoriser les images provenant de votre propre domaine et les images encodées en base64
        'img-src' => [
            'self' => true,
            'data' => true,
        ],
    ],
    // Autres configurations de sécurité...
    'x-content-type-options' => 'nosniff',
    'x-frame-options' => 'SAMEORIGIN',
    'x-xss-protection' => '1; mode=block',
    'referrer-policy' => 'no-referrer-when-downgrade',
    'permissions-policy' => [
        'geolocation' => 'none',
        'microphone' => 'none',
        'camera' => 'none',
    ],
];