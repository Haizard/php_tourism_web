<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class LanguageSettings extends Settings
{
    public string $defaultLocale = 'en';
    public array $enabledLocales = ['en', 'fr', 'de', 'es', 'ar', 'sw'];

    public static function group(): string
    {
        return 'language';
    }
}
