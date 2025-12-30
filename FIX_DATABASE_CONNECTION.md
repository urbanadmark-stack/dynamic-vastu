# Fix Database Connection Error on Hostinger

## The Problem
Your site shows: `Database connection failed: Access denied for user 'your_database_user'@'localhost'`

This means `config.php` on your server still has placeholder credentials.

## Solution: Update config.php on Hostinger

### Step 1: Get Your Database Credentials from Hostinger

1. Log in to **Hostinger hPanel**
2. Go to **Databases** → **MySQL Databases**
3. Find your database and note down:
   - **Database Name** (e.g., `u123456789_realtor`)
   - **Database Username** (e.g., `u123456789_dbuser`)
   - **Database Password** (click "Show" if hidden)

### Step 2: Edit config.php on Hostinger

**Option A: Using Hostinger File Manager (Easiest)**

1. In hPanel, go to **Files** → **File Manager**
2. Navigate to your website folder (usually `public_html` or a subdirectory)
3. Find and **right-click** on `config.php`
4. Select **Edit** (or **Code Editor**)
5. Update these lines with your actual credentials:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'u123456789_realtor');      // Replace with YOUR database name
define('DB_USER', 'u123456789_dbuser');       // Replace with YOUR database username
define('DB_PASS', 'your_actual_password');    // Replace with YOUR database password
```

6. Also update the site URL:

```php
define('SITE_URL', 'https://linen-frog-939435.hostingersite.com');  // Your actual domain
```

7. Click **Save**

**Option B: Edit Locally and Upload via FTP**

1. Edit `config.php` in Cursor with your Hostinger database credentials
2. Upload the file to your server via FTP/SFTP
3. **Important:** Don't commit this file to Git (it's already in `.gitignore`)

### Step 3: Verify Database Exists

Make sure you've imported the database schema:

1. In hPanel, go to **Databases** → **phpMyAdmin**
2. Select your database
3. Check if the tables exist (`properties`, `admin_users`, etc.)
4. If not, import `database/schema.sql`:
   - Click **Import** tab
   - Choose `database/schema.sql` file
   - Click **Go**

### Step 4: Refresh Your Website

After updating `config.php`, refresh your website. The database connection error should be gone!

## Security Note

✅ **Good:** Your `config.php` is excluded from Git (via `.gitignore`)
✅ This prevents accidentally committing sensitive credentials

⚠️ **Remember:** 
- Never commit `config.php` with real credentials
- Keep different `config.php` files for local development and production
- The local `config.php` can keep placeholders for development

## Need Help?

If you still see errors:
- Double-check database name, username, and password are correct
- Ensure the database user has permissions on the database
- Verify the database exists in phpMyAdmin
- Check that you've imported the schema.sql file

