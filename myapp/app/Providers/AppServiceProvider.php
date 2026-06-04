<?php

namespace App\Providers;

use App\Settings\GeneralSettings;
use App\Settings\LanguageSettings;
use App\Settings\MailSettings;
use App\Settings\SeoSettings;
use App\Settings\ThemeSettings;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $generalSettings = app(GeneralSettings::class);
        $themeSettings = app(ThemeSettings::class);
        $seoSettings = app(SeoSettings::class);
        $languageSettings = app(LanguageSettings::class);
        $mailSettings = app(MailSettings::class);

        View::share(compact(
            'generalSettings',
            'themeSettings',
            'seoSettings',
            'languageSettings',
            'mailSettings'
        ));
    }
}
