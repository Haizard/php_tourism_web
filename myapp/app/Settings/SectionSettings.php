<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class SectionSettings extends Settings
{
    public string $hero_bg_type = 'none';
    public string $hero_bg_color = '#0f172a';
    public string $hero_bg_image = '';

    public string $featured_tours_bg_type = 'color';
    public string $featured_tours_bg_color = '#f8fafc';
    public string $featured_tours_bg_image = '';

    public string $destinations_bg_type = 'color';
    public string $destinations_bg_color = '#ffffff';
    public string $destinations_bg_image = '';

    public string $about_bg_type = 'color';
    public string $about_bg_color = '#f1f5f9';
    public string $about_bg_image = '';

    public string $statistics_bg_type = 'color';
    public string $statistics_bg_color = '#ffffff';
    public string $statistics_bg_image = '';

    public string $testimonials_bg_type = 'color';
    public string $testimonials_bg_color = '#f8fafc';
    public string $testimonials_bg_image = '';

    public string $gallery_bg_type = 'color';
    public string $gallery_bg_color = '#ffffff';
    public string $gallery_bg_image = '';

    public string $blogs_bg_type = 'color';
    public string $blogs_bg_color = '#f1f5f9';
    public string $blogs_bg_image = '';

    public string $faq_bg_type = 'color';
    public string $faq_bg_color = '#ffffff';
    public string $faq_bg_image = '';

    public string $newsletter_bg_type = 'color';
    public string $newsletter_bg_color = '#f8fafc';
    public string $newsletter_bg_image = '';

    public string $contact_bg_type = 'color';
    public string $contact_bg_color = '#ffffff';
    public string $contact_bg_image = '';

    public static function group(): string
    {
        return 'sections';
    }
}
