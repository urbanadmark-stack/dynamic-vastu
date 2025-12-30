# Git to Server Deployment Setup

## Overview
This guide will help you set up automatic deployment from GitHub to your Hostinger server.

## Option 1: Using SSH + Git Pull (Recommended if SSH is available)

### Step 1: Check if you have SSH access
1. Log into your Hostinger hPanel
2. Look for "SSH Access" or "Terminal" option
3. Enable SSH if it's available (may require activation)

### Step 2: Connect to your server via SSH
```bash
ssh username@your-domain.com
# or
ssh username@server.hostinger.com
```

### Step 3: Navigate to your website directory
```bash
cd public_html
# or
cd domains/yourdomain.com/public_html
```

### Step 4: Initialize Git (if not already done)
```bash
git init
git remote add origin https://github.com/urbanadmark-stack/dynamic-vastu.git
git branch -M main
git pull origin main
```

### Step 5: Set up automatic pull script
Create a deployment script that pulls changes:

```bash
#!/bin/bash
cd /home/username/public_html
git pull origin main
```

### Step 6: Make it executable
```bash
chmod +x deploy.sh
```

### Step 7: Create GitHub Webhook (Optional - Advanced)
If you want automatic deployment when you push:
1. Go to your GitHub repository
2. Settings → Webhooks → Add webhook
3. Payload URL: `https://yourdomain.com/deploy.php`
4. Content type: application/json

## Option 2: Using GitHub Actions with FTP (Most Common for Shared Hosting)

### Step 1: Get FTP Credentials
From Hostinger hPanel:
1. Go to Files → FTP Accounts
2. Note your FTP host, username, and password

### Step 2: Create GitHub Actions Workflow
Create `.github/workflows/deploy.yml` in your repository:

```yaml
name: Deploy to Hostinger

on:
  push:
    branches: [ main ]

jobs:
  deploy:
    runs-on: ubuntu-latest
    steps:
    - uses: actions/checkout@v2
    
    - name: Deploy via FTP
      uses: SamKirkland/FTP-Deploy-Action@4.3.0
      with:
        server: ftp.yourdomain.com
        username: ${{ secrets.FTP_USERNAME }}
        password: ${{ secrets.FTP_PASSWORD }}
        server-dir: /public_html/
        exclude: |
          **/.git*
          **/.git*/**
          **/node_modules/**
          **/.env
```

### Step 3: Add Secrets to GitHub
1. Go to your GitHub repository
2. Settings → Secrets → Actions → New repository secret
3. Add:
   - `FTP_USERNAME`: Your FTP username
   - `FTP_PASSWORD`: Your FTP password

### Step 4: Commit and Push
The workflow will automatically deploy when you push to main branch.

## Option 3: Manual Pull Script (Simple PHP Script)

Create `deploy.php` in your server root:

```php
<?php
// SECURITY: Add authentication
$deploy_key = 'your-secret-key-change-this';
if ($_GET['key'] !== $deploy_key) {
    die('Unauthorized');
}

// Only allow POST requests from GitHub
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die('Method not allowed');
}

// Change to your project directory
$project_dir = __DIR__;

// Execute git pull
$output = [];
$return_var = 0;
chdir($project_dir);
exec('git pull origin main 2>&1', $output, $return_var);

// Log the result
file_put_contents('deploy.log', date('Y-m-d H:i:s') . "\n" . implode("\n", $output) . "\n\n", FILE_APPEND);

if ($return_var === 0) {
    echo "Deployment successful!\n";
    echo implode("\n", $output);
} else {
    echo "Deployment failed!\n";
    echo implode("\n", $output);
    http_response_code(500);
}
?>
```

Then set up GitHub webhook pointing to: `https://yourdomain.com/deploy.php?key=your-secret-key`

## Option 4: Using File Manager + Manual Download (Simplest)

If Git isn't available on your server:

1. When you push to GitHub
2. Download the repository as ZIP from GitHub
3. Extract and upload via Hostinger File Manager
4. Or use FTP client to upload changed files

## Recommended Approach for Hostinger

**For most Hostinger shared hosting accounts:**

### Quick Setup (Manual Pull):
1. Enable SSH in hPanel (if available)
2. Connect via SSH
3. Navigate to public_html
4. Initialize Git and pull from GitHub
5. Manually run `git pull` when you want to deploy

### Automated Setup (GitHub Actions + FTP):
1. Use Option 2 above
2. Set up GitHub Actions workflow
3. Add FTP credentials as secrets
4. Push to GitHub → Automatic deployment

## Step-by-Step for Hostinger SSH Access

1. **Enable SSH Access:**
   - Log into hPanel
   - Go to "Advanced" → "SSH Access"
   - Enable SSH Access
   - Note your SSH credentials

2. **Connect via SSH:**
   ```bash
   ssh u123456789@server.hostinger.com -p 65002
   # Use the port and credentials from hPanel
   ```

3. **Navigate to website directory:**
   ```bash
   cd public_html
   # or check where your files are located
   ls -la
   ```

4. **Initialize Git repository:**
   ```bash
   git init
   git remote add origin https://github.com/urbanadmark-stack/dynamic-vastu.git
   git fetch origin
   git checkout -b main origin/main
   ```

5. **Pull latest changes:**
   ```bash
   git pull origin main
   ```

6. **Set permissions (if needed):**
   ```bash
   chmod -R 755 uploads/
   chmod 644 config.php
   ```

## Important Notes

⚠️ **Security Considerations:**
- Never commit sensitive files (config.php with credentials)
- Use .gitignore for uploads/ directory
- Don't expose deploy scripts publicly without authentication
- Keep SSH keys secure

⚠️ **Database Updates:**
- Git doesn't handle database changes
- You'll need to run SQL migrations manually in phpMyAdmin
- For projects table: Run `database/projects_schema.sql` or `database/schema.sql`

⚠️ **File Conflicts:**
- Be careful if you edit files directly on server
- Always pull changes before making server-side edits
- Consider using a staging environment

## Troubleshooting

**Git not found on server:**
- Some shared hosting doesn't include Git
- Use FTP deployment instead (Option 2)

**Permission denied errors:**
- Check file permissions: `chmod 755` for directories, `chmod 644` for files
- Ensure PHP can write to uploads/ directory

**Database connection errors:**
- Make sure config.php has correct server credentials
- Don't commit actual credentials to Git

**Files not updating:**
- Clear browser cache
- Check file permissions
- Verify git pull completed successfully
- Check for merge conflicts

## Quick Commands Reference

```bash
# Connect to server
ssh username@server.hostinger.com

# Navigate to site
cd public_html

# Pull latest changes
git pull origin main

# Check status
git status

# View recent commits
git log --oneline -10

# Reset if needed (CAREFUL!)
git reset --hard origin/main
```

