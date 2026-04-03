<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    */

    // Apply CORS to these paths
    'paths' => ['api/*', 'oauth/token'],

    // Allow all HTTP methods
    'allowed_methods' => ['*'],

    // Allow requests from your frontend (Vite dev server + Docker container)
    'allowed_origins' => [
        'http://localhost:5173',      // Vite running on host machine
        'http://ticket-frontend:5173', // Frontend container inside Docker network
        'http://localhost:8080',      // Backend Nginx port (optional if testing via Nginx)
    ],

    'allowed_origins_patterns' => [],

    // Allow all headers
    'allowed_headers' => ['*'],

    // Headers exposed to frontend
    'exposed_headers' => [],

    // How long the results of a preflight request can be cached
    'max_age' => 0,

    // If your frontend needs to send cookies/auth headers, set true
    'supports_credentials' => true,
];
