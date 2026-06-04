# cPanel Public HTML Deployment Guide

This guide explains how to deploy the Laravel tourism starter kit to a shared cPanel hosting environment where the full application is uploaded inside `public_html`.

## What this guide is for

Use this guide when the target hosting provider requires all files to live inside `public_html` instead of allowing a separate `public/` document root.

## Local preparation

Before uploading, verify the project locally:

1. Install PHP dependencies:

```powershell
composer install
```

2. Install JavaScript dependencies:

```powershell
npm install
```

3. Build frontend assets:

```powershell
npm run build
```

4. Run tests if available:

```powershell
php artisan test
```

## Deployment files

The templates for cPanel deployment are located in `deploy/cpanel-public-html/`:

- `deploy/cpanel-public-html/index.php` — public root front controller for Laravel when uploaded into `public_html`
- `deploy/cpanel-public-html/.htaccess` — web root protection and rewrite rules
- `deploy/cpanel-public-html/.env.cpanel.example` — production `.env` example without secrets

## Upload layout

Upload the full application contents into `public_html`, then copy or rename the deployment templates into place:

- `deploy/cpanel-public-html/index.php` → `public_html/index.php`
- `deploy/cpanel-public-html/.htaccess` → `public_html/.htaccess`
- `deploy/cpanel-public-html/.env.cpanel.example` → `public_html/.env`

Ensure the following directories remain adjacent to `index.php` in the uploaded site root:

- `app`
- `bootstrap`
- `config`
- `database`
- `public` (optional for local structure, but built assets can be uploaded directly)
- `resources`
- `routes`
- `storage`
- `vendor`

## Production environment example

Copy `deploy/cpanel-public-html/.env.cpanel.example` to `.env` and update values for your host:

- `APP_URL`
- `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`
- `MAIL_HOST`, `MAIL_USERNAME`, `MAIL_PASSWORD`
- `APP_KEY` after running `php artisan key:generate`

## SSH deployment commands

If SSH access is available, run these commands from inside `public_html`:

```powershell
composer install --no-dev --optimize-autoloader
php artisan key:generate
php artisan migrate --force
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## No-SSH fallback

If terminal access is unavailable:

- Build assets locally and upload the generated `build/`, `css/`, and `js/` asset folders.
- Upload the `vendor/` directory from your local machine.
- Upload the full application files, keeping the same relative layout.
- Generate and import the database schema manually from local SQL or use phpMyAdmin.
- Create `storage/app/public` contents manually if symlinks cannot be created.

## File permissions

Ensure these directories are writable by PHP:

- `storage`
- `bootstrap/cache`

On shared hosts, set directory permissions to `755` and file permissions to `644`, then grant the web server ownership or writable group access as needed.

## Security verification

After deployment, verify that sensitive paths are blocked:

- `/.env`
- `/vendor/`
- `/storage/`
- `/app/`
- `/config/`
- `/routes/`
- `/composer.json`

These should return `403 Forbidden` or `404 Not Found` rather than exposing contents.

## Functional verification

Confirm the site behaves as expected:

- `/` redirects to the configured default locale
- `/en` renders the public homepage
- `/ar` renders with right-to-left direction if Arabic is enabled
- `/admin` redirects to the Filament admin login or the appropriate admin panel

## Troubleshooting

### Blank page or 500 error

- Verify `.env` is present and `APP_KEY` is set
- Check `storage/logs/laravel.log`
- Confirm `vendor/` exists and dependencies were installed

### Missing CSS/JS assets

- Confirm `npm run build` completed successfully
- Verify `build/`, `css/`, and `js/` assets are uploaded
- Ensure the public root `index.php` is routing requests through the rewrite rules

### Database connection error

- Verify `DB_HOST`, `DB_DATABASE`, `DB_USERNAME`, and `DB_PASSWORD`
- Confirm the MySQL database exists and the user has access

### Storage image or upload issues

- Ensure `storage/app/public` is linked or copied correctly
- Verify `storage` permissions allow web server write access
