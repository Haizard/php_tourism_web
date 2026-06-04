<?php

namespace App\Http\Middleware;

use App\Settings\LanguageSettings;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $languageSettings = app(LanguageSettings::class);
        $enabledLocales = $languageSettings->enabledLocales ?: array_keys(config('tourism.supported_locales', []));
        $locale = (string) $request->route('locale');

        abort_unless(in_array($locale, $enabledLocales, true), 404);

        App::setLocale($locale);

        $locales = $this->resolveSupportedLocales($enabledLocales);
        $isRtl = (bool) ($locales[$locale]['rtl'] ?? false);

        View::share('currentLocale', $locale);
        View::share('currentDirection', $isRtl ? 'rtl' : 'ltr');
        View::share('supportedLocales', $locales);

        return $next($request);
    }

    protected function resolveSupportedLocales(array $enabledLocales): array
    {
        $allLocales = config('tourism.supported_locales', []);

        return collect($enabledLocales)
            ->filter(fn ($locale) => isset($allLocales[$locale]))
            ->mapWithKeys(fn ($locale) => [$locale => $allLocales[$locale]])
            ->toArray();
    }
}
