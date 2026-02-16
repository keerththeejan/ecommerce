# Deploy to cPanel (sivakamy.ch)

Follow these steps to fix the 500 error and deploy on cPanel.

## 1. Deploy the latest code

Make sure these fixes are on your server:
- **Duplicate getLastError removed** in `app/models/Model.php` (only one `getLastError()` method)
- **Production config support** in `config/config.php`

## 2. Set document root (choose one)

**Option A (recommended):** Document root = `public_html/public`
- In cPanel **Domains** → sivakamy.ch → **Document Root**: set to `public_html/public`
- Then use `public/.htaccess.production` (copy to `public/.htaccess`)

**Option B:** Document root = `public_html` (project root)
- Use root `.htaccess.production` (copy to `.htaccess` at project root) – so URLs work at domain root
- Use `public/.htaccess.production` in the public folder

## 3. Create production config

1. In cPanel File Manager, go to `config/`
2. Copy `config.production.php.example` to `config.production.php`
3. Edit `config.production.php` and fill in your **cPanel MySQL** details:
   - `DB_USER` – your MySQL username (from cPanel → MySQL Databases)
   - `DB_PASS` – your MySQL password
   - `DB_NAME` – your database name
   - In `config.production.php`, set `DISPLAY_ERRORS` to `true` temporarily to see PHP errors on screen if needed

## 4. Use production .htaccess files

- **Root:** Copy `.htaccess.production` to `.htaccess` (if document root is project root)
- **Public folder:** Copy `public/.htaccess.production` to `public/.htaccess`

## 5. Database import

1. Create the database and user in cPanel → MySQL Databases
2. Import your `sivamgnb_sn.sql` (or your database dump) via phpMyAdmin

## 6. File permissions

- `public/uploads/` – writable (755 or 775)
- `config/config.production.php` – not web-accessible (keep outside public folder; it is in `config/` which is above `public/`)

## 7. Debug 500 error

Visit **https://sivakamy.ch/check-setup.php** – this script shows config, database, and AdminController status. **Delete it after fixing.**

## 8. Test

Visit: https://sivakamy.ch/admin/dashboard

If still 500: check **cPanel → Metrics → Errors** or the `error_log` file for the exact PHP error.
