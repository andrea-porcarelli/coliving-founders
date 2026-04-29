# Deploy — colivingfounders.com (shared hosting)

This guide assumes:
- Shared hosting with **PHP 8.2+**, **MySQL/MariaDB**, **SSH** access, and **cron jobs**.
- The dev environment runs locally via Docker (PHP 8.3 in container).
- The hosting cPanel allows pointing the domain to a sub-folder (`/public`).

If your hosting can NOT change the document root, see "Workaround: docroot fixed to /public_html" at the end.

---

## 1. Pre-deploy checklist (local)

Run from project root (host machine):

```bash
# 1. Lock the production-ready commit
git add -A && git commit -m "production build"

# 2. Install Composer deps for production (no dev tools, optimized autoload)
docker compose exec cf_panel composer install --no-dev --optimize-autoloader

# 3. Build front-end assets for production
docker compose exec cf_panel npm run build

# 4. Cache config + routes + views (saves ~30ms per request in production)
docker compose exec cf_panel php artisan config:cache
docker compose exec cf_panel php artisan route:cache
docker compose exec cf_panel php artisan view:cache
docker compose exec cf_panel php artisan event:cache
```

> **Important**: re-run `composer install --no-dev` **before** caching. The dev-only providers (Pail, Pint, Sail) would otherwise be referenced in the cached config and fail in production where they are missing.

---

## 2. Files to upload via FTP / rsync

Upload **the entire project directory** to your hosting account, *except*:

```
.env                  ← create on server with production values (see step 3)
storage/dbdata/       ← Docker MySQL data, never upload
node_modules/         ← not needed on server, only build output is
.git/                 ← optional, skip to save bandwidth
docker-compose.yaml   ← dev only
Dockerfile            ← dev only
.lighthouseci/        ← CI artifacts, not needed
```

A safe rsync command from local:

```bash
rsync -avz --delete \
  --exclude='.env' \
  --exclude='storage/dbdata/' \
  --exclude='node_modules/' \
  --exclude='.git/' \
  --exclude='docker-compose.yaml' \
  --exclude='Dockerfile' \
  --exclude='.lighthouseci/' \
  --exclude='/tmp/' \
  ./ user@your-host:/path/to/colivingfounders.com/
```

**Required folders that must exist and be writable** by the web server user (usually www-data or your shell user):
- `storage/`
- `storage/framework/cache/`
- `storage/framework/sessions/`
- `storage/framework/views/`
- `storage/logs/`
- `storage/app/public/`
- `bootstrap/cache/`

```bash
ssh user@host
cd /path/to/colivingfounders.com
chmod -R ug+rwx storage bootstrap/cache
```

---

## 3. `.env` on the server

Copy `.env.production.example` to `.env` and fill the values. Critical fields:

```
APP_NAME="Coliving Founders"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://colivingfounders.com
APP_KEY=                 # generated next step

DB_CONNECTION=mysql
DB_HOST=localhost        # or hosting-provided
DB_PORT=3306
DB_DATABASE=cofo_prod    # cPanel-created DB
DB_USERNAME=cofo_user
DB_PASSWORD=...

MAIL_MAILER=smtp
MAIL_HOST=smtp.your-host.com
MAIL_PORT=587
MAIL_USERNAME=info@colivingfounders.com
MAIL_PASSWORD=...
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=info@colivingfounders.com
MAIL_FROM_NAME="Coliving Founders"
MAIL_NOTIFICATIONS_TO=info@colivingfounders.com

CACHE_STORE=file         # avoid Redis on shared hosting
SESSION_DRIVER=file
QUEUE_CONNECTION=sync    # synchronous; no worker required
```

Then on the server:

```bash
php artisan key:generate --force
php artisan storage:link
php artisan migrate --force
php artisan db:seed --class=ContentSeeder --force   # only on first deploy, populates pages from brief
```

Optional: change the admin password after first login (the seeder uses `changeme-now`):

```bash
php artisan tinker --execute="App\Models\User::first()->update(['password' => 'YOUR-STRONG-PASSWORD']);"
```

---

## 4. Document root → `/public`

In cPanel, set the **Domain document root** to the project's `public/` directory.

If the cPanel UI labels it "Document Root" while editing the addon/sub domain, change it to:
```
/home/USER/colivingfounders.com/public
```

This ensures `https://colivingfounders.com/` serves `public/index.php` (the Laravel front controller). All other folders stay outside the public reach.

---

## 5. Cron job

In cPanel → Cron Jobs, add:

```
* * * * * cd /home/USER/colivingfounders.com && php artisan schedule:run >> /dev/null 2>&1
```

This runs Laravel's scheduler every minute. Currently used for nothing automatic, but reserves a hook for future tasks (sitemap rebuild, log rotation, cleanup, etc.).

---

## 6. PHP extensions on the host (verify)

The hosting's PHP must have these extensions enabled. In cPanel → "Select PHP Version" → Extensions:

- bcmath
- ctype
- curl
- dom
- fileinfo
- gd  (required by Spatie MediaLibrary for image conversions)
- intl
- mbstring
- mysqli + pdo_mysql
- openssl
- xml
- zip

If `gd` is unavailable, image uploads still work but conversions (`thumb`, `card`, `lg`, `md`) will fall back to the original — which is fine but heavier on bandwidth.

---

## 7. Post-deploy verification

```bash
# From your local machine, verify the site is up:
curl -I https://colivingfounders.com/ | head -3
curl -I https://colivingfounders.com/sitemap.xml | head -3
curl -I https://colivingfounders.com/robots.txt | head -3
curl -I https://colivingfounders.com/llms.txt | head -3
```

External validation:
- Google Rich Results Test → https://search.google.com/test/rich-results — check `/partners/pomar` for `LodgingBusiness` schema
- Schema.org Validator → https://validator.schema.org
- PageSpeed Insights → https://pagespeed.web.dev
- Open Graph Debugger → https://developers.facebook.com/tools/debug

Then submit `https://colivingfounders.com/sitemap.xml` to:
- Google Search Console → https://search.google.com/search-console
- Bing Webmaster Tools → https://www.bing.com/webmasters

---

## 8. Future deploys (zero-downtime updates)

```bash
# Local
git pull (or pull from CI)
docker compose exec cf_panel composer install --no-dev --optimize-autoloader
docker compose exec cf_panel npm run build

# Sync changed files (excluding .env, storage/dbdata, etc. as before)
rsync -avz --delete --exclude='.env' --exclude='storage/dbdata/' ... ./ user@host:/path/

# Server
ssh user@host
cd /path/to/colivingfounders.com
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Optional: put the site in maintenance mode during long migrations:
```bash
php artisan down --secret="my-bypass-token"
# ... do upgrades ...
php artisan up
```

While down, you can still access the site via `https://colivingfounders.com/my-bypass-token` to test before bringing it back up.

---

## Workaround: docroot fixed to `/public_html`

If your hosting forces the domain to point to `/public_html` and you cannot change it:

1. Place all Laravel files in a sibling folder, e.g. `/home/USER/colivingfounders/`.
2. Move the contents of `colivingfounders/public/*` into `/public_html/`.
3. Edit `public_html/index.php` and change the two require paths:
   ```php
   require __DIR__.'/../colivingfounders/vendor/autoload.php';
   $app = require_once __DIR__.'/../colivingfounders/bootstrap/app.php';
   ```
4. In `colivingfounders/.env` set `APP_URL=https://colivingfounders.com`.

This is the standard "shared hosting Laravel" pattern. Works on every cPanel host.

---

## Troubleshooting

- **500 error on first hit**: check `storage/logs/laravel.log`. Most often: `storage/` not writable, or missing `APP_KEY`.
- **Mixed content (http inside https)**: ensure `APP_URL=https://...` and `config:cache` was re-run after editing `.env`.
- **Forms post but no email**: SMTP credentials wrong in `.env`. Test with `php artisan tinker` and `Mail::raw('test', fn($m) => $m->to('your@email')->subject('test'));`.
- **Editor saves but section doesn't reorder**: check that `storage/framework/cache/` is writable and `CACHE_STORE=file` in `.env`.
- **MediaLibrary uploads fail**: missing `gd` PHP extension, or `storage/app/public/` not writable, or missing symlink (`php artisan storage:link`).
