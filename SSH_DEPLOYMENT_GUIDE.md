# SSH Deployment Guide - Hostinger

## Your Server Details

- **Server**: `in-mum-web1672.main-hosting.eu`
- **Username**: `u698056983`
- **SSH Key**: Already configured ✅

## Quick Deployment Methods

### Method 1: Direct SSH Command (Simplest)

Open your terminal (PowerShell, Git Bash, or Terminal) and run:

```bash
ssh u698056983@in-mum-web1672.main-hosting.eu "cd /home/u698056983/public_html && git pull origin main"
```

Or if Git isn't set up yet on the server:

```bash
ssh u698056983@in-mum-web1672.main-hosting.eu << 'ENDSSH'
cd /home/u698056983/public_html
git init
git remote add origin https://github.com/urbanadmark-stack/dynamic-vastu.git
git fetch origin
git checkout -b main origin/main
ENDSSH
```

### Method 2: Using the Deployment Script

#### On Windows (PowerShell):

```powershell
.\deploy-ssh.ps1
```

#### On Mac/Linux (Bash):

```bash
chmod +x deploy-ssh.sh
./deploy-ssh.sh
```

### Method 3: Manual SSH Connection

1. Connect to your server:
   ```bash
   ssh u698056983@in-mum-web1672.main-hosting.eu
   ```

2. Navigate to your website directory:
   ```bash
   cd /home/u698056983/public_html
   ```

3. Initialize Git (first time only):
   ```bash
   git init
   git remote add origin https://github.com/urbanadmark-stack/dynamic-vastu.git
   git fetch origin
   git checkout -b main origin/main
   ```

4. For future deployments, just pull:
   ```bash
   git pull origin main
   ```

5. Exit SSH:
   ```bash
   exit
   ```

## Initial Setup (One-Time)

### Step 1: First-Time Git Setup on Server

Connect to your server and set up Git:

```bash
ssh u698056983@in-mum-web1672.main-hosting.eu
cd /home/u698056983/public_html

# Initialize Git repository
git init

# Add GitHub as remote
git remote add origin https://github.com/urbanadmark-stack/dynamic-vastu.git

# Fetch and checkout code
git fetch origin
git checkout -b main origin/main
```

### Step 2: Set Up Database

1. Go to phpMyAdmin in Hostinger hPanel
2. Select your database
3. Import `database/schema.sql` or run the projects table creation SQL

### Step 3: Configure Files

Make sure `config.php` on the server has your actual database credentials (it's gitignored, so you need to update it manually on the server).

### Step 4: Set Permissions

```bash
ssh u698056983@in-mum-web1672.main-hosting.eu
cd /home/u698056983/public_html
chmod -R 755 uploads/
chmod 644 config.php
```

## Regular Deployment Workflow

### Option A: Push from Local, Pull on Server

1. **On your local machine** (Windows):
   ```bash
   git add .
   git commit -m "Your changes"
   git push origin main
   ```

2. **On the server** (via SSH):
   ```bash
   ssh u698056983@in-mum-web1672.main-hosting.eu
   cd /home/u698056983/public_html
   git pull origin main
   exit
   ```

Or in one command:
```bash
ssh u698056983@in-mum-web1672.main-hosting.eu "cd /home/u698056983/public_html && git pull origin main"
```

### Option B: Create a Simple Deploy Script

Create `deploy.bat` on Windows:

```batch
@echo off
echo Pushing to GitHub...
git push origin main

echo.
echo Deploying to server...
ssh u698056983@in-mum-web1672.main-hosting.eu "cd /home/u698056983/public_html && git pull origin main"

echo.
echo Deployment complete!
pause
```

Then just double-click `deploy.bat` whenever you want to deploy!

## Automated Deployment with Git Hooks

### Set Up Post-Receive Hook on Server

1. Connect to server:
   ```bash
   ssh u698056983@in-mum-web1672.main-hosting.eu
   ```

2. Create a bare repository (optional, for advanced setup):
   ```bash
   mkdir ~/repo.git
   cd ~/repo.git
   git init --bare
   ```

3. Set up hook:
   ```bash
   cd ~/repo.git/hooks
   nano post-receive
   ```

4. Add this content:
   ```bash
   #!/bin/bash
   cd /home/u698056983/public_html
   git --git-dir=/home/u698056983/repo.git --work-tree=/home/u698056983/public_html checkout -f
   ```

5. Make executable:
   ```bash
   chmod +x post-receive
   ```

6. Add as remote on local machine:
   ```bash
   git remote add live u698056983@in-mum-web1672.main-hosting.eu:~/repo.git
   ```

7. Deploy by pushing:
   ```bash
   git push live main
   ```

## Troubleshooting

### SSH Connection Issues

If you get permission denied:
```bash
# Check if SSH key is added correctly
ssh-add -l

# Test connection
ssh -v u698056983@in-mum-web1672.main-hosting.eu
```

### Git Not Found on Server

If Git isn't installed:
```bash
# Contact Hostinger support to install Git
# Or use FTP deployment instead (GitHub Actions method)
```

### Permission Errors

```bash
# Fix file permissions
ssh u698056983@in-mum-web1672.main-hosting.eu
cd /home/u698056983/public_html
chmod -R 755 uploads/
chmod 644 *.php
```

### Merge Conflicts

If you get merge conflicts:
```bash
ssh u698056983@in-mum-web1672.main-hosting.eu
cd /home/u698056983/public_html
git reset --hard origin/main
git clean -fd
git pull origin main
```

## Quick Reference Commands

```bash
# Connect to server
ssh u698056983@in-mum-web1672.main-hosting.eu

# Navigate to site
cd /home/u698056983/public_html

# Pull latest code
git pull origin main

# Check status
git status

# View logs
git log --oneline -10

# Reset to match GitHub (careful!)
git reset --hard origin/main
```

## Security Notes

✅ Your SSH key is already configured on the server  
✅ Use HTTPS for GitHub remote (or set up SSH key for GitHub too)  
✅ Never commit sensitive files (config.php with credentials)  
✅ Keep your private SSH key secure (never share it)

## Next Steps

1. ✅ SSH key is already set up
2. ⏭️ Connect to server and initialize Git (one-time setup)
3. ⏭️ Test deployment with `git pull`
4. ⏭️ Set up automated deployment (optional)

---

**Need help?** Run the deployment script or use the direct SSH commands above!

