<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TourController;
use App\Settings\LanguageSettings;
use Illuminate\Support\Facades\Route;

$languageSettings = app(LanguageSettings::class);
$supportedLocales = array_keys(config('tourism.supported_locales', []));
$enabledLocales = array_values(array_filter(
    $languageSettings->enabledLocales ?: $supportedLocales,
    fn ($locale) => in_array($locale, $supportedLocales, true)
));

if (empty($enabledLocales)) {
    $enabledLocales = $supportedLocales;
}

$defaultLocale = in_array($languageSettings->defaultLocale, $enabledLocales, true)
    ? $languageSettings->defaultLocale
    : ($enabledLocales[0] ?? 'en');

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
        Route::view('/destinations', 'pages.destinations')->name('destinations.index');
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
