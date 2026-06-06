<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\TourController;
use App\Settings\LanguageSettings;
use Illuminate\Support\Facades\Route;

$supportedLocales = array_keys(config('tourism.supported_locales', []));

try {
    $languageSettings = app(LanguageSettings::class);
    $enabledLocales = array_values(array_filter(
        $languageSettings->enabledLocales ?: $supportedLocales,
        fn ($locale) => in_array($locale, $supportedLocales, true)
    ));
    $defaultLocale = in_array($languageSettings->defaultLocale, $enabledLocales, true)
        ? $languageSettings->defaultLocale
        : ($enabledLocales[0] ?? 'en');
} catch (\Throwable $e) {
    $enabledLocales = $supportedLocales;
    $defaultLocale = $enabledLocales[0] ?? 'en';
}

if (empty($enabledLocales)) {
    $enabledLocales = $supportedLocales;
}

Route::get('/', function () use ($defaultLocale) {
    return redirect('/'.$defaultLocale);
});

Route::prefix('{locale}')
    ->whereIn('locale', $enabledLocales)
    ->middleware('setlocale')
    ->group(function (): void {
        Route::view('/', 'pages.home')->name('home');
        Route::view('/about', 'pages.about')->name('about');
        Route::view('/tours', 'pages.tours')->name('tours.index');
        Route::get('/tours/{slug}', [TourController::class, 'show'])->name('tours.show');
        Route::post('/tours/{tour}/book', [BookingController::class, 'store'])->where('tour', '\d+')->name('booking.store');
        Route::post('/tours/{tour}/review', [ReviewController::class, 'store'])->where('tour', '\d+')->name('review.store');
        Route::view('/destinations', 'pages.destinations')->name('destinations.index');
        Route::get('/destinations/{slug}', [\App\Http\Controllers\DestinationController::class, 'show'])->name('destinations.show');
        Route::view('/blog', 'pages.blog')->name('blog.index');
        Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');
        Route::view('/gallery', 'pages.gallery')->name('gallery');
        Route::view('/faq', 'pages.faq')->name('faq');
        Route::view('/contact', 'pages.contact')->name('contact');
        Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
        Route::view('/privacy', 'pages.privacy')->name('privacy');
        Route::view('/terms', 'pages.terms')->name('terms');
    });

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
