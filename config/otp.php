<?php

return [
    'otp' => [
        'ttl' => 120,
        'max_attempts' => 3,
    ],
    'sms' => [
        'main' => env('SMS_MAIN_URL', 'https://my-json-server.typicode.com/salawatbro/fake-api/sms-primary'),
        'secondary' => env('SMS_SECONDARY_URL', 'https://my-json-server.typicode.com/salawatbro/fake-api/sms-second'),
    ]
];
