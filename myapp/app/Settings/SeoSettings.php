<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class SeoSettings extends Settings
{
    public string $defaultMetaTitle = 'Tourism Starter Kit';
    public string $defaultMetaDescription = 'A reusable Laravel tourism management starter kit for travel agencies and tourism websites.';
    public ?string $ogImage = null;
    public ?string $twitterHandle = null;

    public static function group(): string
    {
        return 'seo';
    }
}
