<?php

namespace App\Providers;

use App\Models\NavbarItem;
use App\Models\Tour;
use App\Settings\GeneralSettings;
use App\Settings\LanguageSettings;
use App\Settings\MailSettings;
use App\Settings\SectionSettings;
use App\Settings\SeoSettings;
use App\Settings\ThemeSettings;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Models\DetailTemplate;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        $this->shareSettings();
    }

    private function shareSettings(): void
    {
        $generalSettings  = $this->tryLoad(GeneralSettings::class);
        $themeSettings    = $this->tryLoad(ThemeSettings::class);
        $seoSettings      = $this->tryLoad(SeoSettings::class);
        $languageSettings = $this->tryLoad(LanguageSettings::class);
        $mailSettings     = $this->tryLoad(MailSettings::class);
        $sectionSettings  = $this->tryLoad(SectionSettings::class);

        $navbarItems = collect();
        try {
            $navbarItems = NavbarItem::where('is_active', true)
                ->orderBy('sort_order')
                ->get()
                ->map(function ($item) {
                    $dropdownItems = collect();
                    if ($item->type === 'category' && $item->reference_id) {
                        $dropdownItems = Tour::where('category_id', $item->reference_id)
                            ->where('is_published', true)
                            ->orderBy('title')
                            ->get(['id', 'title', 'slug']);
                    } elseif ($item->type === 'destination' && $item->reference_id) {
                        $dropdownItems = Tour::where('destination_id', $item->reference_id)
                            ->where('is_published', true)
                            ->orderBy('title')
                            ->get(['id', 'title', 'slug']);
                    }
                    $item->dropdownTours = $dropdownItems;
                    return $item;
                });
        } catch (\Exception $e) {
        }

        $tourTemplate = null;
        $blogTemplate = null;
        try {
            $tourTemplate = DetailTemplate::where('page_type', 'tour_detail')->first();
            $blogTemplate = DetailTemplate::where('page_type', 'blog_detail')->first();
        } catch (\Exception $e) {
        }

        View::share(compact(
            'generalSettings',
            'themeSettings',
            'seoSettings',
            'languageSettings',
            'mailSettings',
            'sectionSettings',
            'navbarItems',
            'tourTemplate',
            'blogTemplate'
        ));
    }

    private function tryLoad(string $class): ?object
    {
        try {
            return app($class);
        } catch (\Exception $e) {
            return null;
        }
    }
}
