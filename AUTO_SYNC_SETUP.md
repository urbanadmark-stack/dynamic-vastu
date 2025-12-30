# Auto Sync Setup Guide

This guide explains how to keep your frontend and backend always in sync.

## 🚀 Quick Start

### Option 1: Manual Sync Script (Recommended)

**Windows (PowerShell):**
```powershell
.\sync.ps1 "Your commit message"
```

**Mac/Linux:**
```bash
chmod +x sync.sh
./sync.sh "Your commit message"
```

This script will:
1. Stage all changes
2. Commit with your message (or auto-generated timestamp)
3. Push to GitHub
4. Deploy to your server via SSH

### Option 2: GitHub Actions (Automatic)

The repository includes a GitHub Actions workflow (`.github/workflows/auto-deploy.yml`) that automatically deploys when you push to the `main` branch.

**Setup Steps:**

1. **Add GitHub Secrets:**
   - Go to your GitHub repository
   - Navigate to **Settings** → **Secrets and variables** → **Actions**
   - Add the following secrets:
     - `SSH_HOST`: `82.112.229.147`
     - `SSH_USER`: `u698056983`
     - `SSH_PORT`: `65002`
     - `SSH_PRIVATE_KEY`: Your SSH private key (see below)

2. **Generate SSH Key (if needed):**
   ```bash
   ssh-keygen -t rsa -b 4096 -C "your_email@example.com"
   ```
   
3. **Add Public Key to Server:**
   ```bash
   ssh-copy-id -p 65002 u698056983@82.112.229.147
   ```
   
   Or manually add your public key to `~/.ssh/authorized_keys` on the server.

4. **Add Private Key to GitHub Secrets:**
   - Copy the contents of your private key (`~/.ssh/id_rsa`)
   - Paste it into the `SSH_PRIVATE_KEY` secret in GitHub

**Once configured, every push to `main` will automatically deploy!**

### Option 3: Git Post-Commit Hook (Auto Push)

The repository includes a post-commit hook that automatically pushes after each commit.

**To enable:**
```bash
chmod +x .git/hooks/post-commit
```

**Note:** This only pushes to GitHub. For auto-deployment, use GitHub Actions or the sync script.

## 📋 Available Methods

### Method 1: Sync Script (Best for Manual Control)

**Pros:**
- Full control over when to sync
- Works immediately without setup
- Can see deployment output in real-time

**Cons:**
- Requires manual execution
- Needs SSH access configured

**Usage:**
```powershell
# Windows
.\sync.ps1 "Fixed image upload bug"

# Mac/Linux
./sync.sh "Fixed image upload bug"
```

### Method 2: GitHub Actions (Best for Automatic Deployment)

**Pros:**
- Fully automatic
- No local setup required
- Works from any device
- Deployment history in GitHub

**Cons:**
- Requires GitHub Secrets setup
- Slight delay (1-2 minutes)

**Setup:** Follow the steps above in "Option 2: GitHub Actions"

### Method 3: Post-Commit Hook (Best for Auto Push)

**Pros:**
- Automatically pushes after commit
- No manual steps needed

**Cons:**
- Only pushes to GitHub (doesn't deploy)
- Requires hook to be enabled

## 🔧 Troubleshooting

### Sync Script Fails

**Issue:** SSH connection fails
```bash
# Test SSH connection manually
ssh -p 65002 u698056983@82.112.229.147 "echo 'Connection successful'"
```

**Issue:** Git not initialized on server
```bash
# SSH into server and initialize Git
ssh -p 65002 u698056983@82.112.229.147
cd /home/u698056983/public_html
git init
git remote add origin https://github.com/urbanadmark-stack/dynamic-vastu.git
git fetch origin
git checkout -b main origin/main
```

### GitHub Actions Fails

**Issue:** SSH key authentication fails
- Verify SSH key is added to server's `~/.ssh/authorized_keys`
- Check that private key in GitHub Secrets matches your public key
- Ensure key has no passphrase (or use ssh-agent)

**Issue:** Permission denied
- Check file permissions on server: `chmod 755 /home/u698056983/public_html`
- Verify Git is installed on server: `git --version`

## 🎯 Recommended Workflow

1. **Make changes locally**
2. **Test changes**
3. **Run sync script:**
   ```powershell
   .\sync.ps1 "Description of changes"
   ```
4. **Verify deployment** on your live site

Or simply push to GitHub if GitHub Actions is configured - it will auto-deploy!

## 📝 Notes

- The sync script preserves your local changes
- GitHub Actions runs in a clean environment
- Always test changes before deploying
- Keep your SSH keys secure
- Consider using environment-specific branches (dev, staging, main)

## 🔐 Security

- Never commit SSH keys or passwords
- Use GitHub Secrets for sensitive data
- Keep your server's SSH access secure
- Regularly rotate SSH keys

---

**Need help?** Check the deployment guides in the repository or review the GitHub Actions logs.

