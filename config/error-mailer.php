<?php

return [
    'email' => [
        'recipient' => env('ERROR_MAILER_RECIPIENT'),
        'subject' => 'Erreur - '.env('APP_NAME'),
    ],

    'disabledOn' => [
        'local',
    ],

    'cacheCooldown' => 10, // in minutes
];
