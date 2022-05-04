<?php

return [
    'ADMOB' => [
        'APP_ID'   => '',
        'BANNER_ID' => '',
        'INTERSTITIAL_ID' => '',
    ],
/*
    'PAYTM' => [
        'MERCHANT_ID' => '',
        'SECRET_KEY' => '',
        'CHANNEL_ID' => '',
        'WEBSITE' => '',
        'INDUSTRY_TYPE_ID' => '',
        'CALLBACK_URL' => '',
    ],
*/
    'COLOR' => [
        'PRIMARY_COLOR' => '',
        'SECONDARY_COLOR' => '',
    ],

    'CURRENCY' => [
        'COUNTRY_ID' => '',
        'POSITION' => ''
    ],

    'ONESIGNAL' => [
        'API_KEY' => env('ONESIGNAL_API_KEY'),
        'REST_API_KEY' => env('ONESIGNAL_REST_API_KEY'),
    ],

    'DISTANCE' => [
        'TYPE' => '',
        'RADIOUS' => '',
    ],
]
?>