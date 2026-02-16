# Deploy to cPanel (sivakamy.ch)

Follow these steps to fix the 500 error and deploy on cPanel.

## 1. Deploy the latest code

Make sure these fixes are on your server:
- **Duplicate getLastError removed** in `app/models/Model.php` (only one `getLastError()` method)
- **Production config support** in `config/config.php`

## 2. Set document root

In cPanel **Domains** → **sivakamy.ch** → **Document Root**:
- Set to `public_html/public` (the `public` folder inside your app)

## 3. Create production config

1. In cPanel File Manager, go to `config/`
2. Copy `config.production.php.example` to `config.production.php`
3. Edit `config.production.php` and fill in your **cPanel MySQL** details:
   - `DB_USER` – your MySQL username (from cPanel → MySQL Databases)
   - `DB_PASS` – your MySQL password
   - `DB_NAME` – your database name

## 4. Use production .htaccess

In the `public/` folder:
- Copy `public/.htaccess.production` to `public/.htaccess`
- Or replace `.htaccess` content with `.htaccess.production` content

## 5. Database import

1. Create the database and user in cPanel → MySQL Databases
2. Import your `sivamgnb_sn.sql` (or your database dump) via phpMyAdmin

## 6. File permissions

- `public/uploads/` – writable (755 or 775)
- `config/config.production.php` – not web-accessible (keep outside public folder; it is in `config/` which is above `public/`)

## 7. Test

Visit: https://sivakamy.ch/admin/dashboard

If still 500: check **cPanel → Errors** or `error_log` in your account for the exact error.
