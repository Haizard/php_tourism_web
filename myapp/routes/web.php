<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/'.config('tourism.default_locale', 'en'));
});

Route::prefix('{locale}')
    ->whereIn('locale', array_keys(config('tourism.supported_locales', [])))
    ->middleware('setlocale')
    ->group(function (): void {
        Route::view('/', 'pages.home')->name('home');
        Route::view('/about', 'pages.about')->name('about');
        Route::view('/tours', 'pages.tours')->name('tours.index');
        Route::view('/destinations', 'pages.destinations')->name('destinations.index');
        Route::view('/blog', 'pages.blog')->name('blog.index');
        Route::view('/gallery', 'pages.gallery')->name('gallery');
        Route::view('/faq', 'pages.faq')->name('faq');
        Route::view('/contact', 'pages.contact')->name('contact');
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
