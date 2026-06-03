<?php

namespace App\Http\Middleware;

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
        $locale = (string) $request->route('locale');
        $supportedLocales = array_keys(config('tourism.supported_locales', []));

        abort_unless(in_array($locale, $supportedLocales, true), 404);

        App::setLocale($locale);

        $isRtl = (bool) config("tourism.supported_locales.{$locale}.rtl", false);

        View::share('currentLocale', $locale);
        View::share('currentDirection', $isRtl ? 'rtl' : 'ltr');
        View::share('supportedLocales', config('tourism.supported_locales', []));

        return $next($request);
    }
}
