# Quick SSH Deployment - Your Server

## 🚀 One-Time Setup

Run this once to set up Git on your server:

```bash
ssh u698056983@in-mum-web1672.main-hosting.eu "cd /home/u698056983/public_html && git init && git remote add origin https://github.com/urbanadmark-stack/dynamic-vastu.git && git fetch origin && git checkout -b main origin/main"
```

## 📤 Deploy After Each Push

After you push to GitHub, deploy to server:

**Windows PowerShell:**
```powershell
ssh u698056983@in-mum-web1672.main-hosting.eu "cd /home/u698056983/public_html && git pull origin main"
```

**Or use the script:**
```powershell
.\deploy-ssh.ps1
```

**Git Bash / Mac / Linux:**
```bash
ssh u698056983@in-mum-web1672.main-hosting.eu "cd /home/u698056983/public_html && git pull origin main"
```

Or:
```bash
./deploy-ssh.sh
```

## 🎯 Complete Workflow

1. **Make changes locally**
2. **Commit and push to GitHub:**
   ```bash
   git add .
   git commit -m "Your changes"
   git push origin main
   ```
3. **Deploy to server:**
   ```bash
   ssh u698056983@in-mum-web1672.main-hosting.eu "cd /home/u698056983/public_html && git pull origin main"
   ```
4. **Done!** ✅ Your site is updated

---

**Server**: `u698056983@in-mum-web1672.main-hosting.eu`  
**Directory**: `/home/u698056983/public_html`

