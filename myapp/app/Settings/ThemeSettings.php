<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class ThemeSettings extends Settings
{
    public string $primaryColor = '#0ea5e9';
    public string $accentColor = '#f97316';
    public string $backgroundColor = '#eef9fb';
    public string $fontFamily = 'Figtree, ui-sans-serif, system-ui, sans-serif, Apple Color Emoji, Segoe UI Emoji, Segoe UI Symbol, Noto Color Emoji';
    public float  $heroOverlayOpacity = 0.45;

    public string $customCss = '';
    public string $homeCss = '';
    public string $toursCss = '';
    public string $tourDetailCss = '';
    public string $blogCss = '';
    public string $contactCss = '';
    public string $customPagesCss = '';

    public static function group(): string
    {
        return 'theme';
    }
}
