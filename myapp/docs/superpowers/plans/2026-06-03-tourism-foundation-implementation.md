# Tourism Foundation Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Build the Phase 0 Laravel 12 tourism starter foundation with auth, Filament admin, localized public routes, reusable Blade scaffolding, Tailwind glassmorphism tokens, and Alpine.js.

**Architecture:** Keep custom foundation code small and explicit: `config/tourism.php` owns locale/home-section configuration, `SetLocale` owns request locale behavior, `routes/web.php` owns localized public placeholders, and Blade files own reusable public layout scaffolding. Generated package scaffolds from Breeze and Filament should remain close to their defaults so later phases can safely extend them.

**Tech Stack:** Laravel 12, PHP 8.2+, MySQL, Laravel Breeze Blade, Filament v3, spatie/laravel-permission, bezhansalleh/filament-shield, spatie/laravel-translatable, spatie/laravel-medialibrary, spatie/laravel-settings, cviebrock/eloquent-sluggable, Tailwind CSS v4, Alpine.js, PHPUnit.

---

## File Structure Map

- Create `config/tourism.php`: supported locales, RTL locales, and fixed homepage section keys.
- Create `app/Http/Middleware/SetLocale.php`: validate locale route parameter, set app locale, and share locale direction.
- Modify `bootstrap/app.php`: register `setlocale` middleware alias.
- Modify `routes/web.php`: redirect `/` to default locale and register placeholder localized pages.
- Modify `app/Models/User.php`: add `HasRoles` trait after installing `spatie/laravel-permission`.
- Modify `resources/css/app.css`: keep Tailwind v4 sources and add tourism theme tokens/glass utilities.
- Modify `resources/js/app.js`: import and start Alpine.js.
- Create `resources/views/layouts/app.blade.php`: public layout with `lang`, `dir`, header/footer, Vite assets, and SEO slot.
- Create `resources/views/partials/header.blade.php`, `navbar.blade.php`, `footer.blade.php`: reusable public chrome.
- Create `resources/views/pages/home.blade.php`, `about.blade.php`, `tours.blade.php`, `destinations.blade.php`, `blog.blade.php`, `gallery.blade.php`, `faq.blade.php`, `contact.blade.php`, `privacy.blade.php`, `terms.blade.php`: placeholder pages.
- Create `resources/views/sections/hero.blade.php`, `about.blade.php`, `featured-tours.blade.php`, `destinations.blade.php`, `testimonials.blade.php`, `statistics.blade.php`, `blogs.blade.php`, `faq.blade.php`, `gallery.blade.php`, `newsletter.blade.php`, `contact.blade.php`: reusable placeholder sections.
- Create `resources/views/components/glass-card.blade.php`, `tour-card.blade.php`, `blog-card.blade.php`, `destination-card.blade.php`, `seo.blade.php`, `honeypot.blade.php`: reusable components.
- Create `tests/Feature/LocaleRoutingTest.php`: verifies redirect, localized pages, RTL rendering, and invalid locale behavior.
- Use generated files from Breeze, Filament, Shield, Permission, Media Library, and Settings installers/publishers rather than hand-writing vendor scaffolds.

---

## Task 1: Install Foundation Dependencies

**Files:**
- Modify: `composer.json`
- Modify: `composer.lock`
- Modify: `package.json`
- Modify: `package-lock.json`
- Generated/modified by package discovery: `bootstrap/cache/packages.php`, `bootstrap/cache/services.php` if present

- [ ] **Step 1: Confirm PHP, Composer, Node, and npm are available**

Run:

```powershell
php -v
composer --version
node -v
npm -v
```

Expected: PHP reports `8.2` or newer, Composer reports a version, Node and npm report installed versions.

- [ ] **Step 2: Install Composer packages**

Run:

```powershell
composer require filament/filament:"^3.0" spatie/laravel-translatable spatie/laravel-permission spatie/laravel-medialibrary spatie/laravel-settings cviebrock/eloquent-sluggable filament/spatie-laravel-translatable-plugin bezhansalleh/filament-shield
composer require laravel/breeze --dev
```

Expected: Composer completes without dependency conflicts and updates `composer.json` and `composer.lock`.

- [ ] **Step 3: Install Alpine.js**

Run:

```powershell
npm install alpinejs
```

Expected: npm updates `package.json` and `package-lock.json`; `alpinejs` appears under dependencies or devDependencies.

- [ ] **Step 4: Commit dependency changes**

Run:

```powershell
git add composer.json composer.lock package.json package-lock.json
git commit -m "chore: install tourism foundation dependencies"
```

Expected: Git creates a commit containing only dependency manifest/lockfile changes.

---

## Task 2: Install Breeze, Filament, And Package Migrations

**Files:**
- Modify/create: Breeze-generated auth controllers, views, routes, requests, and tests
- Create: `app/Providers/Filament/AdminPanelProvider.php`
- Modify: `app/Models/User.php`
- Create: package migration files under `database/migrations/`
- Create: config files published by packages under `config/`

- [ ] **Step 1: Install Breeze Blade scaffolding**

Run:

```powershell
php artisan breeze:install blade
npm install
```

Expected: Breeze adds auth routes/views/controllers and npm completes.

- [ ] **Step 2: Install Filament admin panel**

Run:

```powershell
php artisan filament:install --panels
```

When prompted for panel ID, enter:

```text
admin
```

Expected: `app/Providers/Filament/AdminPanelProvider.php` exists and `/admin` is registered.

- [ ] **Step 3: Publish support package migrations/config**

Run:

```powershell
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan vendor:publish --provider="Spatie\MediaLibrary\MediaLibraryServiceProvider" --tag="medialibrary-migrations"
php artisan vendor:publish --provider="Spatie\LaravelSettings\LaravelSettingsServiceProvider" --tag="settings-migrations"
php artisan vendor:publish --provider="Spatie\LaravelSettings\LaravelSettingsServiceProvider" --tag="settings-config"
```

Expected: migration/config files for permission, medialibrary, and settings are present.

- [ ] **Step 4: Install Filament Shield**

Run:

```powershell
php artisan shield:install admin
```

Expected: Shield publishes its config/migrations and connects to the `admin` Filament panel.

- [ ] **Step 5: Add roles support to the User model**

Edit `app/Models/User.php` so the imports and trait list include `HasRoles`:

```php
<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasRoles, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
```

- [ ] **Step 6: Run migrations**

Run:

```powershell
php artisan migrate:fresh
```

Expected: Migrations complete successfully against `myapp_db`.

- [ ] **Step 7: Commit package scaffolding**

Run:

```powershell
git add app bootstrap config database resources routes tests package.json package-lock.json composer.json composer.lock
git commit -m "chore: scaffold auth admin and package support"
```

Expected: Git creates a commit for generated Breeze/Filament/package files.

---

## Task 3: Add Locale Configuration And Middleware Tests

**Files:**
- Create: `tests/Feature/LocaleRoutingTest.php`

- [ ] **Step 1: Write failing locale routing tests**

Create `tests/Feature/LocaleRoutingTest.php`:

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;

class LocaleRoutingTest extends TestCase
{
    public function test_root_redirects_to_default_locale(): void
    {
        $this->get('/')
            ->assertRedirect('/en');
    }

    public function test_english_home_page_renders_with_ltr_direction(): void
    {
        $this->get('/en')
            ->assertOk()
            ->assertSee('Tourism Starter Kit')
            ->assertSee('lang="en"', false)
            ->assertSee('dir="ltr"', false);
    }

    public function test_arabic_home_page_renders_with_rtl_direction(): void
    {
        $this->get('/ar')
            ->assertOk()
            ->assertSee('lang="ar"', false)
            ->assertSee('dir="rtl"', false);
    }

    public function test_unsupported_locale_returns_not_found(): void
    {
        $this->get('/zz')
            ->assertNotFound();
    }

    public function test_placeholder_pages_render_under_supported_locale(): void
    {
        foreach (['about', 'tours', 'destinations', 'blog', 'gallery', 'faq', 'contact', 'privacy', 'terms'] as $page) {
            $this->get("/en/{$page}")
                ->assertOk()
                ->assertSee('Tourism Starter Kit');
        }
    }
}
```

- [ ] **Step 2: Run tests to verify they fail**

Run:

```powershell
php artisan test tests/Feature/LocaleRoutingTest.php
```

Expected: Tests fail because `config/tourism.php`, `SetLocale`, localized routes, and placeholder views do not exist yet.

- [ ] **Step 3: Commit the failing test**

Run:

```powershell
git add tests/Feature/LocaleRoutingTest.php
git commit -m "test: define locale foundation behavior"
```

Expected: Git creates a commit containing the failing tests.

---

## Task 4: Implement Locale Config, Middleware, And Routes

**Files:**
- Create: `config/tourism.php`
- Create: `app/Http/Middleware/SetLocale.php`
- Modify: `bootstrap/app.php`
- Modify: `routes/web.php`

- [ ] **Step 1: Create tourism config**

Create `config/tourism.php`:

```php
<?php

return [
    'default_locale' => env('APP_LOCALE', 'en'),

    'supported_locales' => [
        'en' => ['name' => 'English', 'native' => 'English', 'rtl' => false],
        'fr' => ['name' => 'French', 'native' => 'Français', 'rtl' => false],
        'de' => ['name' => 'German', 'native' => 'Deutsch', 'rtl' => false],
        'es' => ['name' => 'Spanish', 'native' => 'Español', 'rtl' => false],
        'ar' => ['name' => 'Arabic', 'native' => 'العربية', 'rtl' => true],
        'sw' => ['name' => 'Swahili', 'native' => 'Kiswahili', 'rtl' => false],
    ],

    'home_sections' => [
        'hero',
        'featured-tours',
        'destinations',
        'about',
        'statistics',
        'testimonials',
        'gallery',
        'blogs',
        'faq',
        'newsletter',
        'contact',
    ],
];
```

- [ ] **Step 2: Create SetLocale middleware**

Create `app/Http/Middleware/SetLocale.php`:

```php
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
```

- [ ] **Step 3: Register middleware alias**

Replace `bootstrap/app.php` with:

```php
<?php

use App\Http\Middleware\SetLocale;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'setlocale' => SetLocale::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
```

- [ ] **Step 4: Replace public routes**

Replace `routes/web.php` with:

```php
<?php

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
```

- [ ] **Step 5: Run tests to confirm view failures remain**

Run:

```powershell
php artisan test tests/Feature/LocaleRoutingTest.php
```

Expected: Locale/routing logic is closer, but tests still fail because Blade views have not been created.

- [ ] **Step 6: Commit locale foundation code**

Run:

```powershell
git add config/tourism.php app/Http/Middleware/SetLocale.php bootstrap/app.php routes/web.php
git commit -m "feat: add localized public routing foundation"
```

Expected: Git creates a commit for locale config, middleware, and routes.

---

## Task 5: Add Public Layout, Partials, Components, Pages, And Sections

**Files:**
- Create: `resources/views/layouts/app.blade.php`
- Create: `resources/views/partials/header.blade.php`
- Create: `resources/views/partials/navbar.blade.php`
- Create: `resources/views/partials/footer.blade.php`
- Create: `resources/views/components/glass-card.blade.php`
- Create: `resources/views/components/tour-card.blade.php`
- Create: `resources/views/components/blog-card.blade.php`
- Create: `resources/views/components/destination-card.blade.php`
- Create: `resources/views/components/seo.blade.php`
- Create: `resources/views/components/honeypot.blade.php`
- Create: `resources/views/pages/*.blade.php`
- Create: `resources/views/sections/*.blade.php`

- [ ] **Step 1: Create layout**

Create `resources/views/layouts/app.blade.php`:

```blade
@props([
    'title' => 'Tourism Starter Kit',
    'description' => 'A reusable Laravel tourism management starter kit.',
])

<!DOCTYPE html>
<html lang="{{ $currentLocale ?? app()->getLocale() }}" dir="{{ $currentDirection ?? 'ltr' }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <x-seo :title="$title" :description="$description" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-[var(--color-bg)] text-slate-950 antialiased">
        <div class="gradient-bg min-h-screen">
            @include('partials.header')

            <main>
                {{ $slot }}
            </main>

            @include('partials.footer')
        </div>

        {{-- Future AI chatbot integration point. --}}
    </body>
</html>
```

- [ ] **Step 2: Create partials**

Create `resources/views/partials/header.blade.php`:

```blade
<header class="sticky top-0 z-40 border-b border-white/20 bg-white/50 backdrop-blur-xl">
    @include('partials.navbar')
</header>
```

Create `resources/views/partials/navbar.blade.php`:

```blade
@php
    $locale = $currentLocale ?? app()->getLocale();
    $navItems = [
        ['label' => 'Home', 'url' => url("/{$locale}")],
        ['label' => 'Tours', 'url' => url("/{$locale}/tours")],
        ['label' => 'Destinations', 'url' => url("/{$locale}/destinations")],
        ['label' => 'Blog', 'url' => url("/{$locale}/blog")],
        ['label' => 'Contact', 'url' => url("/{$locale}/contact")],
    ];
@endphp

<nav class="mx-auto flex max-w-7xl items-center justify-between px-6 py-4 lg:px-8">
    <a href="{{ url("/{$locale}") }}" class="text-lg font-black tracking-tight text-slate-950">
        Tourism Starter Kit
    </a>

    <div class="hidden items-center gap-6 md:flex">
        @foreach ($navItems as $item)
            <a href="{{ $item['url'] }}" class="text-sm font-semibold text-slate-700 transition hover:text-[var(--color-primary)]">
                {{ $item['label'] }}
            </a>
        @endforeach
    </div>

    <a href="{{ url("/{$locale}/contact") }}" class="rounded-full bg-[var(--color-primary)] px-5 py-2 text-sm font-bold text-white shadow-lg shadow-cyan-900/10 transition hover:-translate-y-0.5">
        Plan a Trip
    </a>
</nav>
```

Create `resources/views/partials/footer.blade.php`:

```blade
@php($locale = $currentLocale ?? app()->getLocale())

<footer class="border-t border-white/20 bg-slate-950 text-white">
    <div class="mx-auto grid max-w-7xl gap-8 px-6 py-12 md:grid-cols-3 lg:px-8">
        <div>
            <p class="text-lg font-black">Tourism Starter Kit</p>
            <p class="mt-3 max-w-sm text-sm leading-6 text-slate-300">
                A Laravel foundation for multilingual tourism websites, booking flows, and content-managed travel experiences.
            </p>
        </div>

        <div>
            <p class="font-bold">Explore</p>
            <div class="mt-3 grid gap-2 text-sm text-slate-300">
                <a href="{{ url("/{$locale}/tours") }}">Tours</a>
                <a href="{{ url("/{$locale}/destinations") }}">Destinations</a>
                <a href="{{ url("/{$locale}/gallery") }}">Gallery</a>
            </div>
        </div>

        <div>
            <p class="font-bold">Starter Status</p>
            <p class="mt-3 text-sm leading-6 text-slate-300">
                Foundation installed. Tourism modules will be added in future phases.
            </p>
        </div>
    </div>
</footer>
```

- [ ] **Step 3: Create shared components**

Create `resources/views/components/glass-card.blade.php`:

```blade
@props(['as' => 'div'])

<{{ $as }} {{ $attributes->merge(['class' => 'glass-card rounded-3xl p-6']) }}>
    {{ $slot }}
</{{ $as }}>
```

Create `resources/views/components/tour-card.blade.php`:

```blade
<x-glass-card>
    <p class="text-xs font-bold uppercase tracking-[0.3em] text-[var(--color-accent)]">Tour placeholder</p>
    <h3 class="mt-3 text-2xl font-black text-slate-950">Signature Safari Escape</h3>
    <p class="mt-3 text-sm leading-6 text-slate-600">
        Dynamic tour cards will connect here once the tours module is implemented.
    </p>
</x-glass-card>
```

Create `resources/views/components/blog-card.blade.php`:

```blade
<x-glass-card>
    <p class="text-xs font-bold uppercase tracking-[0.3em] text-[var(--color-accent)]">Blog placeholder</p>
    <h3 class="mt-3 text-2xl font-black text-slate-950">Travel Story Preview</h3>
    <p class="mt-3 text-sm leading-6 text-slate-600">
        Blog content will be managed from Filament in a later phase.
    </p>
</x-glass-card>
```

Create `resources/views/components/destination-card.blade.php`:

```blade
<x-glass-card>
    <p class="text-xs font-bold uppercase tracking-[0.3em] text-[var(--color-accent)]">Destination placeholder</p>
    <h3 class="mt-3 text-2xl font-black text-slate-950">Coastal Hideaway</h3>
    <p class="mt-3 text-sm leading-6 text-slate-600">
        Destination records and galleries will render here after the content phase.
    </p>
</x-glass-card>
```

Create `resources/views/components/seo.blade.php`:

```blade
@props([
    'title' => 'Tourism Starter Kit',
    'description' => 'A reusable Laravel tourism management starter kit.',
])

<title>{{ $title }}</title>
<meta name="description" content="{{ $description }}">
<meta property="og:title" content="{{ $title }}">
<meta property="og:description" content="{{ $description }}">
<meta property="og:type" content="website">
```

Create `resources/views/components/honeypot.blade.php`:

```blade
<div class="hidden" aria-hidden="true">
    <label for="website">Website</label>
    <input id="website" type="text" name="website" tabindex="-1" autocomplete="off">
</div>
```

- [ ] **Step 4: Create section partials**

Create these files with the exact matching section title and body below.

`resources/views/sections/hero.blade.php`:

```blade
<section class="px-6 py-20 lg:px-8">
    <div class="mx-auto max-w-7xl">
        <x-glass-card class="overflow-hidden p-8 md:p-14">
            <p class="text-sm font-bold uppercase tracking-[0.4em] text-[var(--color-accent)]">Laravel 12 + Filament</p>
            <h1 class="mt-6 max-w-4xl text-5xl font-black tracking-tight text-slate-950 md:text-7xl">
                Tourism Starter Kit
            </h1>
            <p class="mt-6 max-w-2xl text-lg leading-8 text-slate-700">
                A multilingual, admin-ready foundation for travel agencies, tour operators, and destination brands.
            </p>
        </x-glass-card>
    </div>
</section>
```

`resources/views/sections/about.blade.php`:

```blade
<section class="px-6 py-12 lg:px-8">
    <div class="mx-auto max-w-7xl">
        <x-glass-card>
            <h2 class="text-3xl font-black text-slate-950">About Section Placeholder</h2>
            <p class="mt-4 text-slate-600">Future client story, mission, and trust-building content will live here.</p>
        </x-glass-card>
    </div>
</section>
```

`resources/views/sections/featured-tours.blade.php`:

```blade
<section class="px-6 py-12 lg:px-8">
    <div class="mx-auto grid max-w-7xl gap-6 md:grid-cols-3">
        <x-tour-card />
        <x-tour-card />
        <x-tour-card />
    </div>
</section>
```

`resources/views/sections/destinations.blade.php`:

```blade
<section class="px-6 py-12 lg:px-8">
    <div class="mx-auto grid max-w-7xl gap-6 md:grid-cols-3">
        <x-destination-card />
        <x-destination-card />
        <x-destination-card />
    </div>
</section>
```

`resources/views/sections/testimonials.blade.php`:

```blade
<section class="px-6 py-12 lg:px-8">
    <div class="mx-auto max-w-7xl">
        <x-glass-card>
            <h2 class="text-3xl font-black text-slate-950">Testimonials Placeholder</h2>
            <p class="mt-4 text-slate-600">Client reviews and ratings will appear here in a later phase.</p>
        </x-glass-card>
    </div>
</section>
```

`resources/views/sections/statistics.blade.php`:

```blade
<section class="px-6 py-12 lg:px-8">
    <div class="mx-auto grid max-w-7xl gap-6 md:grid-cols-3">
        <x-glass-card><p class="text-4xl font-black">6</p><p class="mt-2 text-slate-600">Supported locales</p></x-glass-card>
        <x-glass-card><p class="text-4xl font-black">Phase 0</p><p class="mt-2 text-slate-600">Foundation milestone</p></x-glass-card>
        <x-glass-card><p class="text-4xl font-black">RTL</p><p class="mt-2 text-slate-600">Arabic-ready layout</p></x-glass-card>
    </div>
</section>
```

`resources/views/sections/blogs.blade.php`:

```blade
<section class="px-6 py-12 lg:px-8">
    <div class="mx-auto grid max-w-7xl gap-6 md:grid-cols-3">
        <x-blog-card />
        <x-blog-card />
        <x-blog-card />
    </div>
</section>
```

`resources/views/sections/faq.blade.php`:

```blade
<section class="px-6 py-12 lg:px-8">
    <div class="mx-auto max-w-7xl">
        <x-glass-card>
            <h2 class="text-3xl font-black text-slate-950">FAQ Placeholder</h2>
            <p class="mt-4 text-slate-600">Frequently asked questions will become editable content later.</p>
        </x-glass-card>
    </div>
</section>
```

`resources/views/sections/gallery.blade.php`:

```blade
<section class="px-6 py-12 lg:px-8">
    <div class="mx-auto max-w-7xl">
        <x-glass-card>
            <h2 class="text-3xl font-black text-slate-950">Gallery Placeholder</h2>
            <p class="mt-4 text-slate-600">Media library powered galleries will connect here in a later phase.</p>
        </x-glass-card>
    </div>
</section>
```

`resources/views/sections/newsletter.blade.php`:

```blade
<section class="px-6 py-12 lg:px-8">
    <div class="mx-auto max-w-7xl">
        <x-glass-card>
            <h2 class="text-3xl font-black text-slate-950">Newsletter Placeholder</h2>
            <p class="mt-4 text-slate-600">Subscription handling will be added after the foundation is stable.</p>
        </x-glass-card>
    </div>
</section>
```

`resources/views/sections/contact.blade.php`:

```blade
<section class="px-6 py-12 lg:px-8">
    <div class="mx-auto max-w-7xl">
        <x-glass-card>
            <h2 class="text-3xl font-black text-slate-950">Contact Placeholder</h2>
            <p class="mt-4 text-slate-600">Contact forms, honeypot validation, and notifications come in a later phase.</p>
        </x-glass-card>
    </div>
</section>
```

- [ ] **Step 5: Create page views**

Create `resources/views/pages/home.blade.php`:

```blade
<x-layouts.app title="Tourism Starter Kit">
    @include('sections.hero')
    @include('sections.featured-tours')
    @include('sections.destinations')
    @include('sections.about')
    @include('sections.statistics')
    @include('sections.testimonials')
    @include('sections.gallery')
    @include('sections.blogs')
    @include('sections.faq')
    @include('sections.newsletter')
    @include('sections.contact')
</x-layouts.app>
```

Create each listed file with this page-specific title:

- `resources/views/pages/about.blade.php`: `About Us`
- `resources/views/pages/tours.blade.php`: `Tour Packages`
- `resources/views/pages/destinations.blade.php`: `Destinations`
- `resources/views/pages/blog.blade.php`: `Blog`
- `resources/views/pages/gallery.blade.php`: `Gallery`
- `resources/views/pages/faq.blade.php`: `FAQ`
- `resources/views/pages/contact.blade.php`: `Contact`
- `resources/views/pages/privacy.blade.php`: `Privacy Policy`
- `resources/views/pages/terms.blade.php`: `Terms and Conditions`

Use this template for each file, replacing `PAGE_TITLE` with the matching title:

```blade
<x-layouts.app title="PAGE_TITLE | Tourism Starter Kit">
    <section class="px-6 py-20 lg:px-8">
        <div class="mx-auto max-w-7xl">
            <x-glass-card>
                <p class="text-sm font-bold uppercase tracking-[0.3em] text-[var(--color-accent)]">Foundation page</p>
                <h1 class="mt-4 text-5xl font-black tracking-tight text-slate-950">PAGE_TITLE</h1>
                <p class="mt-4 max-w-2xl text-slate-600">
                    This placeholder page confirms the localized routing and layout foundation. Dynamic content will be connected in a later phase.
                </p>
            </x-glass-card>
        </div>
    </section>
</x-layouts.app>
```

- [ ] **Step 6: Run locale tests**

Run:

```powershell
php artisan test tests/Feature/LocaleRoutingTest.php
```

Expected: All tests in `LocaleRoutingTest` pass.

- [ ] **Step 7: Commit view foundation**

Run:

```powershell
git add resources/views tests/Feature/LocaleRoutingTest.php
git commit -m "feat: add reusable public view foundation"
```

Expected: Git creates a commit for layouts, partials, components, sections, pages, and passing locale tests.

---

## Task 6: Add Tailwind Theme Tokens And Alpine Boot

**Files:**
- Modify: `resources/css/app.css`
- Modify: `resources/js/app.js`

- [ ] **Step 1: Replace app CSS**

Replace `resources/css/app.css` with:

```css
@import 'tailwindcss';

@source '../../vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php';
@source '../../storage/framework/views/*.php';
@source '../**/*.blade.php';
@source '../**/*.js';

@theme {
    --font-sans: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji',
        'Segoe UI Symbol', 'Noto Color Emoji';
    --color-primary: #0891b2;
    --color-accent: #f97316;
    --color-bg: #eef9fb;
}

@layer components {
    .gradient-bg {
        background:
            radial-gradient(circle at top left, rgb(14 165 233 / 0.22), transparent 34rem),
            radial-gradient(circle at 85% 15%, rgb(249 115 22 / 0.18), transparent 28rem),
            linear-gradient(135deg, #eef9fb 0%, #f8fafc 52%, #e0f2fe 100%);
    }

    .glass-card {
        border: 1px solid rgb(255 255 255 / 0.45);
        background: linear-gradient(135deg, rgb(255 255 255 / 0.78), rgb(255 255 255 / 0.42));
        box-shadow: 0 24px 80px rgb(15 23 42 / 0.12);
        backdrop-filter: blur(24px);
    }

    .glass-nav {
        border: 1px solid rgb(255 255 255 / 0.35);
        background: rgb(255 255 255 / 0.56);
        backdrop-filter: blur(20px);
    }
}
```

- [ ] **Step 2: Start Alpine in app JS**

Replace `resources/js/app.js` with:

```js
import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();
```

- [ ] **Step 3: Build frontend assets**

Run:

```powershell
npm run build
```

Expected: Vite builds successfully and produces assets under `public/build`.

- [ ] **Step 4: Run locale tests**

Run:

```powershell
php artisan test tests/Feature/LocaleRoutingTest.php
```

Expected: All locale tests pass.

- [ ] **Step 5: Commit frontend asset foundation**

Run:

```powershell
git add resources/css/app.css resources/js/app.js public/build package.json package-lock.json
git commit -m "feat: add glassmorphism theme and alpine boot"
```

Expected: Git creates a commit for CSS/JS changes and build artifacts if this project tracks Vite output.

---

## Task 7: Add Foundation Smoke Tests For Auth And Admin

**Files:**
- Create: `tests/Feature/FoundationSmokeTest.php`

- [ ] **Step 1: Write smoke tests**

Create `tests/Feature/FoundationSmokeTest.php`:

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;

class FoundationSmokeTest extends TestCase
{
    public function test_login_screen_is_available(): void
    {
        $this->get('/login')
            ->assertOk()
            ->assertSee('Email', false);
    }

    public function test_admin_panel_entry_requires_authentication(): void
    {
        $this->get('/admin')
            ->assertRedirect();
    }
}
```

- [ ] **Step 2: Run smoke tests**

Run:

```powershell
php artisan test tests/Feature/FoundationSmokeTest.php
```

Expected: Tests pass. `/login` is available from Breeze, and `/admin` redirects unauthenticated users.

- [ ] **Step 3: Run full test suite**

Run:

```powershell
php artisan test
```

Expected: All tests pass.

- [ ] **Step 4: Commit smoke tests**

Run:

```powershell
git add tests/Feature/FoundationSmokeTest.php
git commit -m "test: add foundation smoke coverage"
```

Expected: Git creates a commit for auth/admin smoke tests.

---

## Task 8: Final Verification And Cleanup

**Files:**
- Review: all changed files
- Optional create/modify: `.pint.json`

- [ ] **Step 1: Add Laravel Pint config**

Create `.pint.json`:

```json
{
    "preset": "laravel"
}
```

- [ ] **Step 2: Run formatter**

Run:

```powershell
vendor\bin\pint
```

Expected: Pint finishes and reports either no changes or formatted PHP files.

- [ ] **Step 3: Run database reset verification**

Run:

```powershell
php artisan migrate:fresh
```

Expected: All migrations run cleanly against `myapp_db`.

- [ ] **Step 4: Run full automated verification**

Run:

```powershell
php artisan test
npm run build
```

Expected: PHPUnit passes and Vite builds successfully.

- [ ] **Step 5: Manually verify key URLs**

If using Laravel's dev server, run:

```powershell
php artisan serve --host=127.0.0.1 --port=8000
```

Verify in a browser or with HTTP requests:

```powershell
curl.exe -I http://127.0.0.1:8000/
curl.exe http://127.0.0.1:8000/en
curl.exe http://127.0.0.1:8000/ar
curl.exe -I http://127.0.0.1:8000/admin
```

Expected: `/` redirects to `/en`, `/en` contains `Tourism Starter Kit`, `/ar` contains `dir="rtl"`, and `/admin` responds with a redirect to login for unauthenticated users.

- [ ] **Step 6: Review git status**

Run:

```powershell
git status --short
```

Expected: Only intentional files are modified or untracked. Do not stage unrelated files such as `../dashboard/`.

- [ ] **Step 7: Commit final cleanup**

Run:

```powershell
git add .pint.json
git add app bootstrap config database resources routes tests composer.json composer.lock package.json package-lock.json
git commit -m "chore: verify tourism foundation"
```

Expected: Git creates a final cleanup/verification commit, or reports nothing to commit if all prior tasks already committed every change.

---

## Self-Review Notes

- Spec coverage: dependency packages, Breeze auth, Filament admin, locale routing, middleware, RTL behavior, reusable Blade layout, sections/components, Tailwind tokens, Alpine boot, package migrations, and verification commands are covered.
- Scope control: tours, bookings, blog, settings pages, real content, email notifications, payments, and domain Filament resources are intentionally excluded.
- Type consistency: locale values are strings, `supported_locales` is a keyed config array, `SetLocale` shares `$currentLocale`, `$currentDirection`, and `$supportedLocales` with views, and tests assert those exact rendered attributes.
