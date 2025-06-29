<?php

return [
    
    'paths' => ['api/*', 'sanctum/csrf-cookie'],
    'allowed_methods' => ['*'], // Izinkan semua metode (GET, POST, PUT, DELETE, dll.)
    'allowed_origins' => ['http://localhost:3000', 'http://127.0.0.1:3000'], // Ganti dengan URL frontend React Anda
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => true, 

];