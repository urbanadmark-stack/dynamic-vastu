# Quick Deployment Steps - Hostinger Server

## 🚀 Method 1: GitHub Actions + FTP (Recommended - Automatic)

This will automatically deploy to your server whenever you push to GitHub.

### Step 1: Get Your FTP Credentials from Hostinger

1. Log into Hostinger hPanel
2. Go to **Files** → **FTP Accounts**
3. Note down:
   - **FTP Server/Host**: (e.g., `ftp.yourdomain.com` or `files.000webhost.com`)
   - **FTP Username**: (usually starts with `u` followed by numbers)
   - **FTP Password**: (your FTP password)

### Step 2: Add Secrets to GitHub

1. Go to your GitHub repository: https://github.com/urbanadmark-stack/dynamic-vastu
2. Click **Settings** → **Secrets and variables** → **Actions**
3. Click **New repository secret** and add these three secrets:

   **Secret 1:**
   - Name: `FTP_SERVER`
   - Value: Your FTP server host (e.g., `ftp.yourdomain.com`)

   **Secret 2:**
   - Name: `FTP_USERNAME`
   - Value: Your FTP username

   **Secret 3:**
   - Name: `FTP_PASSWORD`
   - Value: Your FTP password

### Step 3: Push the Workflow File

The workflow file (`.github/workflows/deploy.yml`) is already created. Just push it:

```bash
git add .github/workflows/deploy.yml
git commit -m "Add GitHub Actions deployment workflow"
git push origin main
```

### Step 4: Test Deployment

1. Make a small change (like adding a comment to a file)
2. Commit and push to GitHub
3. Go to your repository on GitHub
4. Click the **Actions** tab
5. You should see a workflow running
6. Once complete, check your website - changes should be live!

---

## 🔧 Method 2: SSH + Git Pull (If SSH is Available)

### Step 1: Enable SSH Access

1. Log into Hostinger hPanel
2. Go to **Advanced** → **SSH Access**
3. Enable SSH Access if not already enabled
4. Note your SSH credentials:
   - SSH Host
   - SSH Port (usually 65002)
   - SSH Username
   - SSH Password (or use SSH key)

### Step 2: Connect to Your Server

Using a terminal (Windows PowerShell, Git Bash, or Mac Terminal):

```bash
ssh u123456789@server.hostinger.com -p 65002
# Replace with your actual SSH credentials from hPanel
```

### Step 3: Navigate to Your Website Directory

```bash
cd public_html
# or
cd domains/yourdomain.com/public_html
# Check which directory contains your website files
pwd
ls -la
```

### Step 4: Initialize Git and Link to GitHub

```bash
# Initialize Git (if not already done)
git init

# Add GitHub as remote
git remote add origin https://github.com/urbanadmark-stack/dynamic-vastu.git

# Fetch and pull latest code
git fetch origin
git checkout -b main origin/main
git pull origin main
```

### Step 5: Create a Simple Pull Script (Optional)

Create a file called `pull.sh`:

```bash
cd /home/u123456789/public_html
git pull origin main
```

Make it executable:

```bash
chmod +x pull.sh
```

Then whenever you want to deploy, just run:
```bash
./pull.sh
```

---

## 📋 Method 3: Manual FTP Upload (Simplest but Manual)

1. When you push to GitHub
2. Download your repository as ZIP from GitHub
3. Extract the files
4. Upload via Hostinger File Manager or FTP client (FileZilla)
5. Replace files on server

⚠️ **Note**: This method doesn't handle database changes - you'll need to run SQL migrations separately in phpMyAdmin.

---

## ⚠️ Important Notes

### Database Updates Required

After deploying, you MUST update your database:

1. Go to phpMyAdmin in Hostinger hPanel
2. Select your database
3. Go to **Import** tab
4. If projects table doesn't exist, import `database/projects_schema.sql`
5. Or if updating existing database, run the projects table creation SQL from `database/schema.sql`

### File Permissions

After deployment, ensure these permissions:

```bash
# Via SSH (if available):
chmod -R 755 uploads/
chmod 644 config.php
```

Or via File Manager:
- uploads/ folder: 755
- config.php: 644

### Configuration File

**IMPORTANT**: Don't commit your actual `config.php` with live credentials to GitHub!

1. Keep a `config.example.php` in Git
2. Manually update `config.php` on the server with your actual database credentials
3. Add `config.php` to `.gitignore` (if not already)

---

## 🔍 Verify Deployment

After deployment:

1. Visit your website
2. Check if new features appear (Projects section in navigation)
3. Try accessing `/projects.php`
4. Check admin panel for "Projects" menu
5. Verify database connection works

---

## 🆘 Troubleshooting

### GitHub Actions deployment fails:
- Double-check FTP credentials in Secrets
- Verify FTP server address is correct
- Check if FTP port is 21 (standard)
- Ensure server-dir path is correct (`/public_html/`)

### SSH connection fails:
- Verify SSH is enabled in hPanel
- Check SSH port number
- Confirm username and password
- Some hosts require whitelisting your IP

### Files not updating:
- Clear browser cache (Ctrl+F5)
- Check file permissions
- Verify deployment actually completed
- Check for error messages in GitHub Actions logs

### Database errors:
- Run the projects table SQL migration
- Verify config.php has correct credentials
- Check database user permissions

---

## 📞 Need Help?

Refer to the detailed guide: `GIT_DEPLOYMENT_SETUP.md`

