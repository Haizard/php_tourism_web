# Tourism Starter Kit Foundation Design

## Purpose

Build the Phase 0 foundation for a reusable Laravel 12 tourism management starter kit. This milestone turns the current fresh Laravel skeleton into a structured base with authentication, Filament admin access, multilingual public routing, and reusable Blade/Tailwind scaffolding for later tourism modules.

## Scope

Included in this foundation slice:

- Install and configure foundation packages for Breeze, Filament v3, permissions/RBAC support, translatable content support, media handling, settings, slugs, and Alpine.js.
- Add Laravel Breeze with the Blade stack so basic authentication works before admin/content modules depend on it.
- Install Filament v3 admin at `/admin`, with the admin panel scaffolded but without domain resources yet.
- Add locale-aware public routing where `/` redirects to the default locale and public pages live under `/{locale}`.
- Add locale middleware that sets `App::setLocale()` from the route parameter and supports RTL detection for configured locales.
- Create a reusable public Blade layout with header, navbar, footer, section includes, SEO component support, and a placeholder for future AI/chatbot integration.
- Add Tailwind v4 theme tokens and glassmorphism utility classes for the public frontend.
- Create placeholder public pages, section partials, and reusable Blade components so later phases can replace placeholders with dynamic content.
- Configure queue/database foundations needed by later booking/contact notifications.

Excluded from this foundation slice:

- Tour, destination, blog, booking, contact, newsletter, gallery, FAQ, and page-builder domain models.
- Filament resources for tourism content.
- Settings pages and settings seed data beyond any package setup required for installation.
- Real tourism content, demo seeders, payment behavior, and email notification flows.

## Architecture

The app will have three foundation surfaces:

- Public frontend: localized routes render Blade pages and reusable sections through `resources/views/layouts/app.blade.php`.
- Admin frontend: Filament v3 serves an initially empty admin panel at `/admin`, ready for future resources.
- Support layer: middleware, config, auth, queue tables, package migrations, and frontend assets establish reusable infrastructure.

The public route shape will be:

- `GET /` redirects to `/{default_locale}`.
- `GET /{locale}` renders the home placeholder.
- `GET /{locale}/about`, `/tours`, `/destinations`, `/blog`, `/gallery`, `/faq`, `/contact`, `/privacy`, and `/terms` render placeholder pages.
- Future POST routes for contact, bookings, and newsletter will be added in later phases.

## Components

Foundation config and middleware:

- `config/tourism.php` defines supported locales, RTL locales, default locale behavior, and fixed home section keys.
- `app/Http/Middleware/SetLocale.php` validates and applies the locale route parameter.
- `bootstrap/app.php` registers the middleware alias.
- `routes/web.php` defines localized public routes and the root redirect.

Frontend structure:

- `resources/views/layouts/app.blade.php`
- `resources/views/partials/header.blade.php`
- `resources/views/partials/navbar.blade.php`
- `resources/views/partials/footer.blade.php`
- `resources/views/pages/*.blade.php`
- `resources/views/sections/*.blade.php`
- `resources/views/components/glass-card.blade.php`
- `resources/views/components/tour-card.blade.php`
- `resources/views/components/blog-card.blade.php`
- `resources/views/components/destination-card.blade.php`
- `resources/views/components/seo.blade.php`
- `resources/views/components/honeypot.blade.php`

Assets:

- `resources/css/app.css` defines Tailwind v4 theme tokens and reusable glassmorphism classes.
- `resources/js/app.js` initializes Alpine.js.

## Data Flow

For this slice, public requests are simple:

1. Browser requests `/` or a localized page.
2. The route redirects or enters the localized route group.
3. `SetLocale` validates the locale and applies it to Laravel.
4. The page renders through the shared layout.
5. The layout derives `lang` and `dir` from the active locale.

Admin requests flow through the Filament panel and Laravel authentication stack. Tourism data resources will be attached in later phases.

## Error Handling

Unsupported locales should fail safely rather than silently rendering the wrong language. The preferred foundation behavior is to abort with a 404 for unsupported `/{locale}` values. The root route remains safe by redirecting to the configured default locale.

Placeholder views should avoid database dependencies so the foundation can be verified before domain migrations exist.

## Testing And Verification

The foundation is complete when these checks pass:

- `composer install` or package installation completes without dependency conflicts.
- `npm install` and `npm run build` complete successfully.
- `php artisan migrate:fresh` completes successfully.
- `/` redirects to the default locale.
- `/en` renders the placeholder public layout with header and footer.
- `/ar` renders with `dir="rtl"`.
- Breeze login routes are available and functional.
- `/admin` is reachable through the Filament panel.

## Follow-Up Phases

After this foundation is approved and implemented, the next natural phase is Settings and Theming, followed by Tours/Categories/Destinations. The foundation should not include those features, but it should leave clear extension points for them.
