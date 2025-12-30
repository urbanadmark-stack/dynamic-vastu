# Quick Installation Guide

## Step-by-Step Installation for Hostinger

### 1. Upload Files
Upload all files to your domain's `public_html` folder (or subdirectory) via FTP or File Manager.

### 2. Create Database
1. Login to Hostinger hPanel
2. Go to **Databases** → **MySQL Databases**
3. Click **Create Database**
4. Note: Database name, username, and password
5. Assign the user to the database

### 3. Import Database
1. Go to **phpMyAdmin**
2. Select your database
3. Click **Import** tab
4. Choose `database/schema.sql`
5. Click **Go**

### 4. Configure
Edit `config.php` and update:
- `DB_NAME` - Your database name
- `DB_USER` - Your database username  
- `DB_PASS` - Your database password
- `SITE_URL` - Your domain (e.g., https://yoursite.com)

### 5. Set Permissions
In File Manager, set `uploads/` folder permissions to **755**

### 6. Set Admin Password
1. Open: `yoursite.com/setup-password.php`
2. Enter your password
3. Copy the hash
4. Go to phpMyAdmin → `admin_users` table
5. Edit admin user → paste hash in password field
6. **DELETE setup-password.php**

### 7. Login
Go to `yoursite.com/admin/` and login with:
- Username: `admin`
- Password: (the one you set)

## Done! 🎉

You can now:
- Add properties via Admin Panel
- View listings on your website
- Customize the design in CSS files

