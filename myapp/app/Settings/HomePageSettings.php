<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class HomePageSettings extends Settings
{
    public array $sections = [
        ['key' => 'hero',          'label' => 'Hero Banner',       'is_active' => true],
        ['key' => 'featured_tours','label' => 'Featured Tours',    'is_active' => true],
        ['key' => 'destinations',  'label' => 'Destinations',      'is_active' => true],
        ['key' => 'about',         'label' => 'About / Features',  'is_active' => true],
        ['key' => 'statistics',    'label' => 'Statistics',        'is_active' => true],
        ['key' => 'testimonials',  'label' => 'Testimonials',      'is_active' => true],
        ['key' => 'gallery',       'label' => 'Gallery',           'is_active' => true],
        ['key' => 'blogs',         'label' => 'Blog / Journal',    'is_active' => true],
        ['key' => 'faq',           'label' => 'FAQ',               'is_active' => true],
        ['key' => 'newsletter',    'label' => 'Newsletter',        'is_active' => true],
        ['key' => 'contact',       'label' => 'Contact',           'is_active' => true],
    ];

    public static function group(): string
    {
        return 'homepage';
    }
}
