# How to Reset Admin Password

## Step-by-Step Password Reset Guide

### Method 1: Using setup-password.php (Easiest - Recommended)

#### Step 1: Upload setup-password.php (if not already on server)

1. Make sure `setup-password.php` file is in your website root folder on Hostinger
2. It should be at: `public_html/setup-password.php`

#### Step 2: Open Password Generator

1. Go to: `https://linen-frog-939435.hostingersite.com/setup-password.php`
2. You'll see a form to enter your new password

#### Step 3: Enter Your New Password

1. Enter your desired new password in the form
   - Example: `MySecurePassword123!@#`
   - Make it strong (12+ characters, mix of letters, numbers, symbols)
2. Click the button to generate hash
3. **Copy the generated hash** (long string starting with `$2y$10$...`)

#### Step 4: Update Password in Database

1. **Go to Hostinger hPanel**
2. **Go to Databases → phpMyAdmin**
3. **Select your database:** `u698056983_realtor`
4. **Click on `admin_users` table**
5. **Click "Browse" tab** to see existing users
6. **Click "Edit"** on the row with username "admin"
7. **In the `password` field:**
   - Delete the old hash
   - Paste the new hash you copied from setup-password.php
8. **Click "Go"** to save

#### Step 5: Test Login

1. Go to: `https://linen-frog-939435.hostingersite.com/admin/login.php`
2. Username: `admin`
3. Password: (the new password you entered in setup-password.php)
4. Click Login

#### Step 6: Delete setup-password.php (Important!)

**For security, delete this file after use:**

1. In Hostinger File Manager, navigate to your website root
2. Find `setup-password.php`
3. Right-click → **Delete**
4. Confirm deletion

---

### Method 2: Direct SQL Update (Advanced)

If you know the password hash, you can update directly:

1. **Go to phpMyAdmin**
2. **Select database:** `u698056983_realtor`
3. **Click "SQL" tab**
4. **Run this query** (replace `NEW_PASSWORD_HASH` with your hash):

```sql
UPDATE `admin_users` 
SET `password` = 'NEW_PASSWORD_HASH' 
WHERE `username` = 'admin';
```

5. **Click "Go"**

**Note:** You need to generate the hash first (use Method 1 Step 3, or create a temporary PHP file).

---

### Method 3: Using Temporary PHP File

If setup-password.php doesn't work:

1. **Create a file** `generate-password.php` on your server with this content:

```php
<?php
$password = 'YourNewPassword123!'; // Change this to your desired password
echo password_hash($password, PASSWORD_DEFAULT);
?>
```

2. **Upload to your server** (in website root folder)
3. **Open in browser:** `https://linen-frog-939435.hostingersite.com/generate-password.php`
4. **Copy the generated hash**
5. **Update password in database** (follow Method 1 Step 4)
6. **Delete the temporary file** after use

---

## Complete Example Walkthrough

Let's say you want to change password to: `MyNewSecurePass123!@#`

### Step 1: Generate Hash
1. Go to: `https://linen-frog-939435.hostingersite.com/setup-password.php`
2. Enter: `MyNewSecurePass123!@#`
3. Click generate
4. Copy hash: `$2y$10$abcd1234efgh5678ijkl9012mnop3456qrst7890uvwx...`

### Step 2: Update Database
1. phpMyAdmin → Database `u698056983_realtor` → `admin_users` table
2. Edit admin user row
3. Paste hash in password field
4. Click Go

### Step 3: Login
1. Go to admin login page
2. Username: `admin`
3. Password: `MyNewSecurePass123!@#`
4. Login successfully!

### Step 4: Clean Up
1. Delete `setup-password.php` from server

---

## Password Requirements & Recommendations

### ✅ Good Password Examples:
- `MySecure123!@#Pass`
- `RealEstate2024$Admin`
- `DynamicVastu#2024`
- At least 12 characters
- Mix of uppercase, lowercase, numbers, symbols

### ❌ Bad Password Examples:
- `admin123` (too common)
- `password` (too weak)
- `123456` (too simple)
- Short passwords (less than 8 characters)

---

## Troubleshooting

### Problem: setup-password.php not accessible
**Solution:**
- Check file exists on server
- Verify file permissions (should be 644)
- Try accessing direct URL

### Problem: Hash not working after update
**Solution:**
- Make sure you copied the ENTIRE hash (60+ characters)
- Verify no extra spaces before/after hash
- Check password field in database shows the hash (not plain text)
- Generate new hash and try again

### Problem: Can't login after password change
**Solution:**
- Double-check you're using the NEW password (not old one)
- Verify hash was saved correctly in database
- Check for typos when pasting hash
- Try generating new hash and updating again

### Problem: Forgot the new password
**Solution:**
- Use setup-password.php to generate hash for your NEW password
- Update it in database
- Login with your new password
- If you really forgot, you'll need to reset it again

---

## Security Checklist

After resetting password:

- [ ] New password is strong (12+ characters, mixed case, numbers, symbols)
- [ ] Password hash updated in database
- [ ] Can login with new password
- [ ] `setup-password.php` deleted from server
- [ ] Old password is not used anywhere
- [ ] New password is stored securely (password manager)

---

## Quick Reference

**Reset Password:**
1. `setup-password.php` → Enter new password → Copy hash
2. phpMyAdmin → `admin_users` → Edit → Paste hash → Save
3. Login with new password
4. Delete `setup-password.php`

**Current Default Password:**
- Username: `admin`
- Password: `admin123` (change this!)

**Login URL:**
- `https://linen-frog-939435.hostingersite.com/admin/login.php`

