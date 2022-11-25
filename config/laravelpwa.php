<?php

return [
    'name' => 'Swipez',
    'manifest' => [
        'name' => env('APP_NAME', 'My PWA App'),
        'short_name' => 'Swipez',
        'start_url' => env('APP_URL'),
        'background_color' => '#18aebf',
        'theme_color' => '#275770',
        'display' => 'standalone',
        'orientation'=> 'any',
        'status_bar'=> 'black',
        'icons' => [
            '72x72' => [
                'path' => '/static/images/icons/icon-72x72.png',
                'purpose' => 'any'
            ],
            '96x96' => [
                'path' => '/static/images/icons/icon-96x96.png',
                'purpose' => 'any'
            ],
            '128x128' => [
                'path' => '/static/images/icons/icon-128x128.png',
                'purpose' => 'any'
            ],
            '144x144' => [
                'path' => '/static/images/icons/icon-144x144.png',
                'purpose' => 'any'
            ],
            '152x152' => [
                'path' => '/static/images/icons/icon-152x152.png',
                'purpose' => 'any'
            ],
            '196x196' => [
                'path' => '/static/images/icons/icon-196x196.png',
                'purpose' => 'any maskable'
            ],
            '384x384' => [
                'path' => '/static/images/icons/icon-384x384.png',
                'purpose' => 'any'
            ],
            '512x512' => [
                'path' => '/static/images/icons/icon-512x512.png',
                'purpose' => 'any'
            ],
        ],
        'splash' => [
            '640x1136' => '/static/images/icons/splash-640x1136.png',
            '750x1334' => '/static/images/icons/splash-750x1334.png',
            '828x1792' => '/static/images/icons/splash-828x1792.png',
            '1125x2436' => '/static/images/icons/splash-1125x2436.png',
            '1242x2208' => '/static/images/icons/splash-1242x2208.png',
            '1242x2688' => '/static/images/icons/splash-1242x2688.png',
            '1536x2048' => '/static/images/icons/splash-1536x2048.png',
            '1668x2224' => '/static/images/icons/splash-1668x2224.png',
            '1668x2388' => '/static/images/icons/splash-1668x2388.png',
            '2048x2732' => '/static/images/icons/splash-2048x2732.png',
        ],
        'shortcuts' => [
            [
                'name' => 'Login',
                'description' => 'Login and view payments collected',
                'url' => '/login',
                'icons' => [
                    "src" => "/static/images/icons/icon-72x72.png",
                    "purpose" => "any"
                ]
            ],
            [
                'name' => 'Dashboard',
                'description' => 'View payments collected, invoices raised and more',
                'url' => '/merchant/dashboard'
            ]
        ],
        'custom' => []
    ]
];
