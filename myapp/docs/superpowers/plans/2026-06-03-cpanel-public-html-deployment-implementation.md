# cPanel Public HTML Deployment Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Add cPanel shared-hosting deployment readiness for the case where the whole Laravel app is uploaded directly into `public_html`.

**Architecture:** Keep local Laravel development unchanged. Add deploy-only templates under `deploy/cpanel-public-html/`, a safe production env example, and documentation that explains how to copy root front-controller/protection files into `public_html` during upload.

**Tech Stack:** Laravel 12, Apache `.htaccess`, cPanel shared hosting, PHP 8.2+, MySQL, Vite build assets.

---

## File Structure Map

- Create `deploy/cpanel-public-html/index.php`: root front controller for Laravel when project root equals `public_html`.
- Create `deploy/cpanel-public-html/.htaccess`: Apache protection/rewrite template for full-app-in-webroot hosting.
- Create `deploy/cpanel-public-html/.env.cpanel.example`: production cPanel environment template without secrets.
- Create `docs/deployment/cpanel-public-html.md`: step-by-step deployment guide and verification checklist.

---

## Task 1: Add cPanel Root Templates

**Files:**
- Create: `deploy/cpanel-public-html/index.php`
- Create: `deploy/cpanel-public-html/.htaccess`

- [ ] **Step 1: Create root `index.php` template**

Create `deploy/cpanel-public-html/index.php`:

```php
<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

if (file_exists($maintenance = __DIR__.'/storage/framework/maintenance.php')) {
    require $maintenance;
}

require __DIR__.'/vendor/autoload.php';

/** @var Application $app */
$app = require_once __DIR__.'/bootstrap/app.php';

$app->handleRequest(Request::capture());
```

- [ ] **Step 2: Create hardened root `.htaccess` template**

Create `deploy/cpanel-public-html/.htaccess`:

```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    RewriteRule (^|/)\.(?!well-known(?:/|$)) - [F,L]

    RewriteRule ^(app|bootstrap|config|database|node_modules|resources|routes|storage|tests|vendor)(/|$) - [F,L,NC]
    RewriteRule ^(.verdent|docs)(/|$) - [F,L,NC]

    RewriteRule ^(artisan|composer\.(json|lock)|package(-lock)?\.json|phpunit\.xml|vite\.config\.js|tailwind\.config\.js|\.pint\.json|README\.md)$ - [F,L,NC]

    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    RewriteCond %{HTTP:x-xsrf-token} .
    RewriteRule .* - [E=HTTP_X_XSRF_TOKEN:%{HTTP:X-XSRF-Token}]

    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^ index.php [L]
</IfModule>

<FilesMatch "^(\.env.*|auth\.json)$">
    Require all denied
</FilesMatch>
```

- [ ] **Step 3: Commit root templates**

Run:

```powershell
git add deploy/cpanel-public-html/index.php deploy/cpanel-public-html/.htaccess
git commit -m "feat: add cpanel public html root templates"
```

Expected: Commit contains only the two deployment templates.

---

## Task 2: Add Production Environment Example

**Files:**
- Create: `deploy/cpanel-public-html/.env.cpanel.example`

- [ ] **Step 1: Create cPanel env example**

Create `deploy/cpanel-public-html/.env.cpanel.example`:

```dotenv
APP_NAME="Tourism Starter Kit"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://your-domain.example

APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US

LOG_CHANNEL=stack
LOG_STACK=single
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=cpaneluser_database
DB_USERNAME=cpaneluser_dbuser
DB_PASSWORD="replace-with-cpanel-db-password"

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=public
QUEUE_CONNECTION=database
CACHE_STORE=database

MAIL_MAILER=smtp
MAIL_SCHEME=null
MAIL_HOST=mail.your-domain.example
MAIL_PORT=465
MAIL_USERNAME=no-reply@your-domain.example
MAIL_PASSWORD="replace-with-mailbox-password"
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS=no-reply@your-domain.example
MAIL_FROM_NAME="${APP_NAME}"

VITE_APP_NAME="${APP_NAME}"
```

- [ ] **Step 2: Commit env example**

Run:

```powershell
git add deploy/cpanel-public-html/.env.cpanel.example
git commit -m "docs: add cpanel production env example"
```

Expected: Commit contains the safe env template and no real credentials.

---

## Task 3: Add Deployment Guide

**Files:**
- Create: `docs/deployment/cpanel-public-html.md`

- [ ] **Step 1: Create deployment guide**

Create `docs/deployment/cpanel-public-html.md` with sections for:

- What this guide is for.
- Local preparation commands: `composer install`, `npm install`, `npm run build`, `php artisan test`.
- Upload layout: upload project contents into `public_html`, copy `deploy/cpanel-public-html/index.php` to `public_html/index.php`, copy `deploy/cpanel-public-html/.htaccess` to `public_html/.htaccess`, copy `.env.cpanel.example` to `.env` and edit credentials.
- SSH path commands: `composer install --no-dev --optimize-autoloader`, `php artisan key:generate`, `php artisan migrate --force`, `php artisan storage:link`, `php artisan config:cache`, `php artisan route:cache`, `php artisan view:cache`.
- No SSH fallback: upload local `vendor/`, upload built assets, import database manually after generating SQL locally, and create/copy public storage assets manually if symlink is unavailable.
- Permissions: directories `storage` and `bootstrap/cache` writable by PHP.
- Security verification: `/.env`, `/vendor/`, `/storage/`, `/app/`, `/config/`, `/routes/`, and `/composer.json` must return 403 or 404.
- Functional verification: `/`, `/en`, `/ar`, `/admin`.
- Troubleshooting: blank page, 500, missing CSS/JS, database connection error, and storage images.

- [ ] **Step 2: Commit deployment guide**

Run:

```powershell
git add docs/deployment/cpanel-public-html.md
git commit -m "docs: add cpanel public html deployment guide"
```

Expected: Commit contains the deployment guide.

---

## Task 4: Verify Deployment Readiness

**Files:**
- Review: `deploy/cpanel-public-html/index.php`
- Review: `deploy/cpanel-public-html/.htaccess`
- Review: `deploy/cpanel-public-html/.env.cpanel.example`
- Review: `docs/deployment/cpanel-public-html.md`

- [ ] **Step 1: Run automated checks**

Run:

```powershell
php artisan test
npm run build
```

Expected: PHPUnit passes and Vite builds successfully.

- [ ] **Step 2: Check templates for secrets**

Run:

```powershell
Select-String -Path 'deploy/cpanel-public-html/.env.cpanel.example','docs/deployment/cpanel-public-html.md' -Pattern 'haitham|password123|myapp_db|root@|gmail.com'
```

Expected: No matches.

- [ ] **Step 3: Commit verification cleanup if needed**

Run:

```powershell
git status --short
git add deploy/cpanel-public-html docs/deployment
git commit -m "chore: verify cpanel deployment readiness"
```

Expected: Commit only if verification caused documentation/template fixes; otherwise Git reports nothing to commit.

---

## Self-Review Notes

- Spec coverage: root front controller, root protection rules, env example, SSH/no-SSH instructions, asset/storage notes, production checklist, and verification steps are covered.
- Scope control: no real credentials, no FTP automation, no product features, and no assumptions that SSH is always available.
- Type consistency: deployment templates live under `deploy/cpanel-public-html/`; documentation tells the user to copy them into the real cPanel `public_html` root.
