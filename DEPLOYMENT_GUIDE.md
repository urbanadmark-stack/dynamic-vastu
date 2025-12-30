# Deployment Guide - Dynamic Vastu

This guide explains how to deploy your changes to the Hostinger server.

## Quick Deployment

### Option 1: Using PowerShell Script (Recommended for Windows)

1. Open PowerShell in the project directory
2. Run:
   ```powershell
   .\deploy.ps1
   ```
3. Follow the prompts

### Option 2: Using Batch Script (Windows)

1. Double-click `deploy.bat` or run it from Command Prompt
2. Follow the prompts

### Option 3: Manual SSH Deployment

1. Make sure all changes are committed and pushed to GitHub:
   ```bash
   git add -A
   git commit -m "Your commit message"
   git push origin main
   ```

2. Connect to server and pull changes:
   ```bash
   ssh -p 65002 u698056983@82.112.229.147 "cd /home/u698056983/public_html && git pull origin main"
   ```

## Server Details

- **SSH Host:** 82.112.229.147
- **SSH Port:** 65002
- **SSH User:** u698056983
- **Remote Path:** /home/u698056983/public_html
- **Repository:** https://github.com/urbanadmark-stack/dynamic-vastu.git

## Initial Server Setup (One-time)

If Git is not initialized on the server, run these commands once:

```bash
ssh -p 65002 u698056983@82.112.229.147
cd /home/u698056983/public_html
git init
git remote add origin https://github.com/urbanadmark-stack/dynamic-vastu.git
git fetch origin
git checkout -b main origin/main
```

## Troubleshooting

### SSH Connection Issues

1. **Connection Timeout:**
   - Verify the SSH port (65002) is correct
   - Check if your IP is whitelisted in Hostinger
   - Try using Hostinger's web-based SSH terminal

2. **Authentication Failed:**
   - Ensure your SSH key is added to Hostinger
   - Check SSH key permissions: `chmod 600 ~/.ssh/id_rsa`
   - Verify the key is in `~/.ssh/authorized_keys` on the server

3. **Git Not Found:**
   - Install Git on the server if not available
   - Or use Hostinger's File Manager to upload files manually

### Deployment Issues

1. **Git Pull Fails:**
   - Check if you're in the correct directory on server
   - Verify the remote repository URL is correct
   - Ensure you have write permissions in the directory

2. **Files Not Updating:**
   - Clear browser cache
   - Check file permissions on server (should be 644 for files, 755 for directories)
   - Verify the correct files are in the repository

3. **Permission Errors:**
   - Fix permissions: `chmod -R 755 /home/u698056983/public_html`
   - Ensure files are owned by correct user

## Alternative Deployment Methods

### Via Hostinger File Manager

1. Log in to Hostinger hPanel
2. Go to **File Manager**
3. Navigate to `public_html`
4. Download updated files from GitHub
5. Upload them via File Manager

### Via FTP

1. Use FTP client (FileZilla, WinSCP)
2. Connect using Hostinger FTP credentials
3. Upload changed files to `public_html`

## Best Practices

1. **Always test locally** before deploying
2. **Commit frequently** with descriptive messages
3. **Pull before pushing** to avoid conflicts
4. **Backup database** before major changes
5. **Check error logs** after deployment

## Automated Deployment (Future)

For automated deployments, consider:
- GitHub Actions with SSH deployment
- Webhook-based deployment
- CI/CD pipeline setup

## Support

If you encounter issues:
1. Check Hostinger documentation
2. Review server error logs
3. Verify Git repository status
4. Contact Hostinger support if needed

---

**Last Updated:** $(Get-Date -Format "yyyy-MM-dd")

