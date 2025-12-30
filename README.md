# Dynamic Real Estate Listing Website

A modern, responsive real estate listing website built with PHP and MySQL, perfect for shared hosting environments like Hostinger.

## Features

- 🏠 **Property Listings**: Display properties with images, details, and filters
- 🔍 **Search & Filter**: Search by location, type, price, bedrooms, and more
- 📱 **Responsive Design**: Works perfectly on desktop, tablet, and mobile devices
- 🖼️ **Image Gallery**: Multiple image support with thumbnail gallery
- 👤 **Admin Panel**: Easy-to-use admin interface for managing properties
- 🔐 **Secure Login**: Password-protected admin area
- ⚡ **Fast & Lightweight**: Optimized for performance on shared hosting

## Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher / MariaDB 10.2 or higher
- Apache web server with mod_rewrite enabled
- GD Library (for image processing)

## Installation

### Step 1: Upload Files

Upload all files to your Hostinger hosting account via FTP or File Manager to your domain's public_html directory (or subdirectory).

### Step 2: Create Database

1. Log in to your Hostinger control panel (hPanel)
2. Go to **Databases** → **MySQL Databases**
3. Create a new database (e.g., `yourname_realtor`)
4. Create a new database user and assign it to the database
5. Note down the database name, username, and password

### Step 3: Import Database Schema

1. Go to **phpMyAdmin** in your Hostinger control panel
2. Select your database
3. Click on the **Import** tab
4. Choose the `database/schema.sql` file
5. Click **Go** to import

### Step 4: Configure Database Connection

1. Open `config.php` file
2. Update the following with your database details:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'your_database_name');      // Your database name
define('DB_USER', 'your_database_user');      // Your database username
define('DB_PASS', 'your_database_password');  // Your database password
```

3. Update your site URL:

```php
define('SITE_URL', 'https://yourdomain.com');  // Your domain name
```

4. Adjust timezone if needed:

```php
date_default_timezone_set('America/New_York');  // Change to your timezone
```

### Step 5: Set Permissions

Make sure the `uploads/` directory is writable:

1. Create the `uploads` directory if it doesn't exist
2. Set permissions to 755 (or 777 if 755 doesn't work)

You can do this via File Manager in Hostinger:
- Right-click on the `uploads` folder
- Select "Change Permissions"
- Set to 755

### Step 6: Create Placeholder Image (Optional)

If you want a placeholder image for properties without images:
1. Create a placeholder image (e.g., 800x600px)
2. Save it as `assets/images/placeholder.jpg`

## Default Admin Credentials

⚠️ **IMPORTANT**: The default admin user is created, but you need to set a password!

### Setting Up Admin Password

**Option 1: Using the Password Generator (Recommended)**

1. Upload all files to your server
2. Open `setup-password.php` in your browser: `yoursite.com/setup-password.php`
3. Enter your desired password
4. Copy the generated hash
5. Go to phpMyAdmin → your database → `admin_users` table
6. Edit the admin user and paste the hash into the password field
7. **Delete setup-password.php** for security

**Option 2: Manual Method**

1. Create a temporary PHP file with this code:
```php
<?php
echo password_hash('your_new_password', PASSWORD_DEFAULT);
?>
```
2. Replace `your_new_password` with your desired password
3. Run the file in your browser to get the hash
4. Update the password field in the `admin_users` table with the hash
5. Delete the temporary file

After setting your password:
- **Username**: `admin`
- **Password**: (the password you just set)

## Directory Structure

```
/
├── admin/                 # Admin panel files
│   ├── includes/         # Admin header
│   ├── index.php         # Properties list
│   ├── login.php         # Admin login
│   ├── add-property.php  # Add new property
│   ├── edit-property.php # Edit property
│   └── delete-property.php
├── assets/
│   ├── css/
│   │   ├── style.css     # Main styles
│   │   └── admin.css     # Admin styles
│   ├── js/
│   │   └── main.js       # JavaScript
│   └── images/           # Images folder
├── database/
│   └── schema.sql        # Database schema
├── includes/
│   ├── functions.php     # PHP functions
│   ├── header.php        # Site header
│   └── footer.php        # Site footer
├── uploads/              # Property images (create this folder)
├── config.php            # Configuration file
├── index.php             # Homepage
├── listings.php          # All properties page
├── property.php          # Property detail page
├── .htaccess             # Apache configuration
└── README.md             # This file
```

## Usage

### Adding Properties

1. Log in to the admin panel at `yoursite.com/admin/`
2. Click "Add New Property"
3. Fill in the property details
4. Upload images (multiple images supported)
5. Click "Add Property"

### Editing Properties

1. Go to the admin dashboard
2. Click "Edit" on the property you want to modify
3. Make your changes
4. Click "Update Property"

### Deleting Properties

1. Go to the admin dashboard
2. Click "Delete" on the property you want to remove
3. Confirm the deletion

### Public Site

- **Homepage**: `yoursite.com/`
- **All Listings**: `yoursite.com/listings.php`
- **Property Detail**: `yoursite.com/property.php?id=X`

## Customization

### Changing Colors

Edit `assets/css/style.css` and modify the CSS variables at the top:

```css
:root {
    --primary-color: #2563eb;    /* Main brand color */
    --primary-dark: #1e40af;     /* Darker shade */
    --secondary-color: #10b981;  /* Accent color */
}
```

### Changing Site Name

Edit `config.php`:

```php
define('SITE_NAME', 'Your Site Name');
```

### Modifying Property Types

Edit the database schema in `database/schema.sql` or use phpMyAdmin to modify the `property_type` enum in the `properties` table.

## Security Tips

1. ✅ Change the default admin password immediately
2. ✅ Keep PHP and MySQL updated
3. ✅ Regularly backup your database
4. ✅ Use strong passwords for database credentials
5. ✅ Don't share your admin credentials
6. ✅ Keep the `config.php` file secure (it's already protected via .htaccess)

## Troubleshooting

### Images not uploading

- Check that `uploads/` directory exists and is writable (755 or 777 permissions)
- Verify PHP upload_max_filesize and post_max_size in php.ini
- Check file size limits (default is 5MB per image)

### Database connection error

- Verify database credentials in `config.php`
- Ensure database user has proper permissions
- Check that database exists

### Admin login not working

- Verify admin credentials in database
- Check that sessions are working (check PHP session settings)
- Clear browser cookies and try again

### Page not found (404 errors)

- Ensure mod_rewrite is enabled on your Apache server
- Check that `.htaccess` file is uploaded correctly
- Verify file paths are correct

## Support

For issues specific to Hostinger hosting:
- Check Hostinger documentation
- Contact Hostinger support

## License

This project is open source and available for personal and commercial use.

## Credits

Built with modern web technologies:
- PHP 7.4+
- MySQL/MariaDB
- Vanilla JavaScript
- CSS3 (No frameworks required!)

---

**Enjoy your new real estate website!** 🏡

