<?php

namespace App\Providers;

use App\Models\NavItem;
use App\Models\SectionBackground;
use App\Models\Tour;
use App\Settings\GeneralSettings;
use App\Settings\LanguageSettings;
use App\Settings\MailSettings;
use App\Settings\SeoSettings;
use App\Settings\ThemeSettings;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $generalSettings = app(GeneralSettings::class);
        $themeSettings = app(ThemeSettings::class);
        $seoSettings = app(SeoSettings::class);
        $languageSettings = app(LanguageSettings::class);
        $mailSettings = app(MailSettings::class);

        try {
            $sectionBackgrounds = SectionBackground::all()->keyBy('section_key');
        } catch (\Exception $e) {
            $sectionBackgrounds = collect();
        }

        try {
            $navItems = NavItem::with(['category.tours' => fn ($q) => $q->where('is_published', true)->orderBy('title'), 'children' => fn ($q) => $q->where('is_active', true)->orderBy('sort_order')])
                ->whereNull('parent_id')
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get();
        } catch (\Exception $e) {
            $navItems = collect();
        }

        try {
            $featuredTours = Tour::with('category')
                ->where('is_published', true)
                ->inRandomOrder()
                ->take(4)
                ->get();
        } catch (\Exception $e) {
            $featuredTours = collect();
        }

        View::share(compact(
            'generalSettings',
            'themeSettings',
            'seoSettings',
            'languageSettings',
            'mailSettings',
            'sectionBackgrounds',
            'navItems',
            'featuredTours'
        ));
    }
}
