<?php
return [

    'client_id' => env('PAYPAL_CLIENT_ID'),
    'secret' => env('PAYPAL_SECRET'),
    'settings' => [
        'mode' => env('PAYPAL_MODE', 'sandbox'), // sandbox or live
        'http.ConnectionTimeOut' => 30,
        'http.Retry' => 1,
        'log.LogEnabled' => true,
        'log.FileName' => storage_path('logs/paypal.log'),
        'log.LogLevel' => 'INFO', // ERROR, INFO, or FINE
    ],

];
