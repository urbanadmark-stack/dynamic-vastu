# Deployment Connection Issues

## SSH Connection Timeout

If you're getting connection timeout errors, here are solutions:

## Option 1: Enable SSH in Hostinger

1. Log into Hostinger hPanel
2. Go to **Advanced** → **SSH Access**
3. Enable SSH Access if not already enabled
4. Note the correct SSH port (usually 65002 for Hostinger)
5. Check if your IP needs to be whitelisted

## Option 2: Use FTP Deployment (GitHub Actions)

Since SSH might have connection issues, use the GitHub Actions FTP deployment instead:

1. Go to GitHub: https://github.com/urbanadmark-stack/dynamic-vastu
2. Go to **Settings** → **Secrets and variables** → **Actions**
3. Add these secrets:
   - `FTP_SERVER`: Your FTP server (from Hostinger FTP Accounts)
   - `FTP_USERNAME`: Your FTP username
   - `FTP_PASSWORD`: Your FTP password
4. Push to GitHub - it will auto-deploy via FTP

## Option 3: Manual FTP Upload

1. Download repository ZIP from GitHub
2. Extract files
3. Upload via Hostinger File Manager or FTP client
4. Replace files on server

## Option 4: Try Different SSH Port

Hostinger often uses port 65002:

```bash
ssh -p 65002 u698056983@in-mum-web1672.main-hosting.eu
```

## Option 5: Check SSH Access Status

1. Log into hPanel
2. Go to **Advanced** → **SSH Access**
3. Check if SSH is enabled
4. Verify your SSH key is added
5. Check if your IP is whitelisted (if required)

## Recommended: Use GitHub Actions FTP Deployment

This is the most reliable method for Hostinger shared hosting:

1. Get FTP credentials from Hostinger hPanel → Files → FTP Accounts
2. Add them as GitHub Secrets (see Option 2 above)
3. Push to GitHub → Automatic deployment!

The workflow file (`.github/workflows/deploy.yml`) is already set up and ready to use.

