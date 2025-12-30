# Quick SSH Deployment - Your Server

**SSH Details:**
- IP: `82.112.229.147`
- Port: `65002`
- Username: `u698056983`

## 🚀 One-Time Setup

Run this once to set up Git on your server:

```bash
ssh -p 65002 u698056983@82.112.229.147 "cd /home/u698056983/public_html && git init && git remote add origin https://github.com/urbanadmark-stack/dynamic-vastu.git && git fetch origin && git checkout -b main origin/main"
```

## 📤 Deploy After Each Push

After you push to GitHub, deploy to server:

**Windows PowerShell:**
```powershell
ssh -p 65002 u698056983@82.112.229.147 "cd /home/u698056983/public_html && git pull origin main"
```

**Or use the script:**
```powershell
.\deploy-ssh.ps1
```

**Git Bash / Mac / Linux:**
```bash
ssh -p 65002 u698056983@82.112.229.147 "cd /home/u698056983/public_html && git pull origin main"
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
   ssh -p 65002 u698056983@82.112.229.147 "cd /home/u698056983/public_html && git pull origin main"
   ```
4. **Done!** ✅ Your site is updated

---

**SSH Connection**: `ssh -p 65002 u698056983@82.112.229.147`  
**Directory**: `/home/u698056983/public_html`

