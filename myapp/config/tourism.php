<?php

return [
    'default_locale' => env('APP_LOCALE', 'en'),

    'supported_locales' => [
        'en' => ['name' => 'English', 'native' => 'English', 'rtl' => false],
        'fr' => ['name' => 'French', 'native' => 'Francais', 'rtl' => false],
        'de' => ['name' => 'German', 'native' => 'Deutsch', 'rtl' => false],
        'es' => ['name' => 'Spanish', 'native' => 'Espanol', 'rtl' => false],
        'ar' => ['name' => 'Arabic', 'native' => 'Arabic', 'rtl' => true],
        'sw' => ['name' => 'Swahili', 'native' => 'Kiswahili', 'rtl' => false],
    ],

    'home_sections' => [
        'hero',
        'featured-tours',
        'destinations',
        'about',
        'statistics',
        'testimonials',
        'gallery',
        'blogs',
        'faq',
        'newsletter',
        'contact',
    ],
];
