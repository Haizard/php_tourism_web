<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class GeneralSettings extends Settings
{
    public string $siteName = 'Tourism Starter Kit';
    public string $tagline = 'A reusable Laravel tourism management starter kit.';
    public ?string $logo = null;
    public ?string $favicon = null;
    public string $contactEmail = 'contact@example.com';
    public string $contactPhone = '+1 555 0100';
    public string $address = '123 Tourism Ave, Travel City';
    public array $socialLinks = [];

    public static function group(): string
    {
        return 'general';
    }
}
