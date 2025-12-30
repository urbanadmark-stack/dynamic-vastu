# Quick Deploy Command

## SSH Connection Details
- **IP:** 82.112.229.147
- **Port:** 65002
- **Username:** u698056983
- **Directory:** /home/u698056983/public_html

## One-Time Setup (First Time Only)

Run this command in your terminal to initialize Git on the server:

```bash
ssh -p 65002 u698056983@82.112.229.147 "cd /home/u698056983/public_html && git init && git remote add origin https://github.com/urbanadmark-stack/dynamic-vastu.git && git fetch origin && git checkout -b main origin/main"
```

## Regular Deployment (After Each Push to GitHub)

Once Git is initialized on the server, use this simple command:

```bash
ssh -p 65002 u698056983@82.112.229.147 "cd /home/u698056983/public_html && git pull origin main"
```

## Complete Deployment Steps

1. **Push to GitHub** (if not already done):
   ```bash
   git push origin main
   ```

2. **Deploy to Server**:
   ```bash
   ssh -p 65002 u698056983@82.112.229.147 "cd /home/u698056983/public_html && git pull origin main"
   ```

3. **Done!** Your live site is updated ✅

## Troubleshooting

- If you get "remote origin already exists", that's fine - Git is already initialized
- If you get authentication errors, make sure your SSH key is added to Hostinger
- If files already exist and you get merge conflicts, you may need to backup first or use `git pull origin main --force` (use with caution)

