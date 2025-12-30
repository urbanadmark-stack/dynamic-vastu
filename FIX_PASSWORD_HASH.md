# Fix Password Hash - Can't Login Issue

## Problem

You can see the admin user in the database, but the `password` field shows plain text `admin123` instead of a password hash. The login system requires a password hash to work.

## Solution: Update Password Hash in Database

### Step 1: Generate Password Hash

**Option A: Using setup-password.php (Easiest)**

1. **Upload `setup-password.php`** to your server (if not already there)
2. **Open in browser:** `https://linen-frog-939435.hostingersite.com/setup-password.php`
3. **Enter password:** `admin123` (or your desired password)
4. **Copy the generated hash** (long string starting with `$2y$10$...`)

**Option B: Using phpMyAdmin SQL**

1. Go to phpMyAdmin
2. Select your database: `u698056983_realtor`
3. Click **SQL** tab
4. Run this query to generate hash for "admin123":

```sql
SELECT PASSWORD('admin123') AS hash;
```

**Note:** This won't work in modern MySQL. Use Option A or C instead.

**Option C: Create Temporary PHP File**

1. Create a file `generate-hash.php` on your server with this content:
```php
<?php
echo password_hash('admin123', PASSWORD_DEFAULT);
?>
```

2. Open in browser: `https://linen-frog-939435.hostingersite.com/generate-hash.php`
3. Copy the hash
4. Delete the file

### Step 2: Update Password in Database

1. **Go to phpMyAdmin**
2. **Select your database:** `u698056983_realtor`
3. **Click on `admin_users` table**
4. **Click on the row with username "admin"**
5. **Click "Edit" button**
6. **In the `password` field, paste the hash** (from Step 1)
   - It should look like: `$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi`
7. **Click "Go" to save**

### Step 3: Verify Password Hash

After updating, the password field should show a long hash string (60+ characters), NOT plain text.

**Correct format:**
```
$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi
```

**Wrong format:**
```
admin123
```

### Step 4: Test Login

1. Go to: `https://linen-frog-939435.hostingersite.com/admin/login.php`
2. Username: `admin`
3. Password: `admin123`
4. Click Login

---

## Quick Fix Using Known Hash

If you want to use the default password "admin123", you can directly update it with this known hash:

1. Go to phpMyAdmin
2. Select database: `u698056983_realtor`
3. Click on `admin_users` table
4. Click **SQL** tab
5. Run this query:

```sql
UPDATE `admin_users` 
SET `password` = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi' 
WHERE `username` = 'admin';
```

6. Click **Go**
7. This sets the password to "admin123" with the correct hash

---

## Verify After Update

1. **Check password field:**
   - Go to phpMyAdmin → `admin_users` table → Browse
   - The password field should show a long hash (60+ characters)
   - Should NOT show plain text

2. **Test login:**
   - URL: `https://linen-frog-939435.hostingersite.com/admin/login.php`
   - Username: `admin`
   - Password: `admin123`

---

## Why This Happened

The password field was set to plain text instead of a hash. The login system uses PHP's `password_verify()` function which requires a password hash created by `password_hash()`.

**Plain text passwords won't work** - they must be hashed!

---

## Set a Custom Password

If you want to set a different password:

1. Use `setup-password.php` to generate hash for your new password
2. Update the `password` field in `admin_users` table with the new hash
3. Login with your new password

---

## Security Reminder

After successfully logging in:
1. ✅ Change your password to something secure
2. ✅ Delete `setup-password.php` from server
3. ✅ Keep your admin credentials secure

