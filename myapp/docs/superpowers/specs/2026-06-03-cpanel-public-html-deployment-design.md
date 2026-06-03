# cPanel Public HTML Deployment Design

## Purpose

Prepare the Laravel tourism starter kit for shared cPanel hosting where the entire project must live inside `public_html`. This deployment slice focuses on safe upload structure, web-root protection, production configuration guidance, and verification steps.

## Hosting Constraint

The hosting account requires all project files to be placed inside `public_html`. This is not Laravel's preferred deployment shape because Laravel normally exposes only the `public/` directory. Since the full app will be inside the web-accessible folder, this slice must add defensive rules that block direct access to framework internals and sensitive files.

## Scope

Included in this deployment-readiness slice:

- Add a cPanel/shared-hosting deployment guide with exact upload, configuration, permissions, build, cache, and verification steps.
- Add a production environment example for cPanel without real credentials.
- Add a hardened root `.htaccess` template for `public_html` deployments that blocks direct access to sensitive Laravel files and directories.
- Add a cPanel root front-controller template that can be used when `index.php` must live directly in `public_html`.
- Document how to handle assets built by Vite and Filament assets.
- Document how to handle storage links on hosts with and without terminal access.
- Provide verification checks for public pages, admin login redirect, and blocked sensitive paths.

Excluded from this slice:

- Real cPanel credentials, database names, database passwords, SMTP credentials, or domain-specific values.
- Automated FTP/cPanel upload scripts.
- Payment, booking, content, or admin feature work.
- Changes that assume SSH/terminal access is definitely available.

## Architecture

The production upload will place the Laravel project in `public_html`. The web server should route normal requests through a root front controller while blocking direct access to sensitive internals.

The intended deployed shape is:

- `public_html/index.php`: front controller adapted from Laravel's `public/index.php`.
- `public_html/.htaccess`: root protection and rewrite rules.
- `public_html/build/`: Vite build assets copied from `public/build`.
- `public_html/js/filament` and `public_html/css/filament`: Filament published assets copied from `public/`.
- `public_html/app`, `bootstrap`, `config`, `database`, `resources`, `routes`, `storage`, `tests`, `vendor`: present but blocked from direct HTTP access.

This repo should keep Laravel's normal local development layout intact. Deployment templates and docs should explain what to copy or rename on cPanel rather than breaking local development.

## Security Rules

The root `.htaccess` template must block browser access to:

- `.env` and all dotfiles except `.well-known`.
- `app/`, `bootstrap/`, `config/`, `database/`, `resources/`, `routes/`, `storage/`, `tests/`, and `vendor/`.
- Composer/npm/package files such as `composer.json`, `composer.lock`, `package.json`, `package-lock.json`, `vite.config.js`, `phpunit.xml`, and `.pint.json`.
- Markdown planning/internal docs where practical.

The rules should still allow normal Laravel requests to route through `index.php` and should allow public assets such as `build/`, `css/`, `js/`, images, fonts, and favicon files.

## Configuration Guidance

The deployment guide will instruct the user to set production `.env` values:

- `APP_ENV=production`
- `APP_DEBUG=false`
- `APP_URL=https://your-domain.example`
- `DB_CONNECTION=mysql`
- cPanel MySQL database, username, and password
- `SESSION_DRIVER=database`
- `CACHE_STORE=database`
- `QUEUE_CONNECTION=database`
- mail settings from the hosting provider

It will include commands for hosts with terminal access:

- `composer install --no-dev --optimize-autoloader`
- `php artisan key:generate`
- `php artisan migrate --force`
- `php artisan storage:link`
- `php artisan config:cache`
- `php artisan route:cache`
- `php artisan view:cache`

It will also include fallback notes for hosts without terminal access, including building assets locally, uploading `vendor/`, importing SQL manually if needed, and creating `storage/app/public` access manually when symlinks are unavailable.

## Verification

The deployment is considered ready when these local/template checks pass:

- The Laravel local development layout still works.
- `php artisan test` passes.
- `npm run build` passes.
- The deployment guide gives clear steps for both SSH and no-SSH cPanel hosting.
- The `.htaccess` template blocks sensitive paths by design.

On the live cPanel host, verification should include:

- `/` redirects to `/en`.
- `/en` renders the public starter page.
- `/ar` renders with RTL direction.
- `/admin` redirects to `/admin/login`.
- `/.env`, `/vendor/`, `/storage/`, `/app/`, `/config/`, `/routes/`, and `/composer.json` return forbidden or not found responses.

## Follow-Up

After cPanel deployment readiness is implemented, the next product feature should be Phase 1 Settings and Theming or Phase 2 Tours/Destinations, depending on whether deployment is tested successfully first.
