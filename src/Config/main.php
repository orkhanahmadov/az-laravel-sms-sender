<?php

return [
    'provider'  => env('SMS_API_PROVIDER'),
    'user'      => env('SMS_API_USER'),
    'pwd'       => env('SMS_API_PASSWORD'),
    'from'      => env('SMS_API_SENDER_NAME'),

    'use_db'    => env('SMS_API_USE_DB'),
];
