# SSH Connection Details for Deployment

## Getting Your SSH Connection Details

To deploy via SSH, we need the correct connection details from Hostinger:

1. **Log into Hostinger hPanel**
2. Go to **Advanced** → **SSH Access**
3. Look for these details:
   - **SSH Host** (might be different from the domain)
   - **SSH Port** (often 65002, not 22)
   - **SSH Username** (you have: u698056983)

## Common Hostinger SSH Formats

The SSH connection might be:
- `ssh -p 65002 u698056983@in-mum-web1672.main-hosting.eu`
- Or: `ssh -p 65002 u698056983@ssh.in-mum-web1672.main-hosting.eu`
- Or: A different hostname shown in hPanel

## Quick Test

Try these commands one by one to see which works:

**Option 1 (Port 65002):**
```bash
ssh -p 65002 u698056983@in-mum-web1672.main-hosting.eu
```

**Option 2 (Different hostname):**
```bash
ssh -p 65002 u698056983@ssh.in-mum-web1672.main-hosting.eu
```

**Option 3 (Check hPanel for exact hostname):**
```bash
ssh -p [PORT_FROM_HPANEL] [USERNAME]@[HOSTNAME_FROM_HPANEL]
```

## Once Connected

After you successfully connect, run this to deploy:

```bash
cd /home/u698056983/public_html

# If Git is not initialized:
git init
git remote add origin https://github.com/urbanadmark-stack/dynamic-vastu.git
git fetch origin
git checkout -b main origin/main

# If Git already exists:
git pull origin main
```

## Alternative: Get SSH Details from hPanel

Please check your Hostinger hPanel → Advanced → SSH Access and share:
1. The exact SSH hostname shown
2. The SSH port number
3. Confirmation that SSH Access is enabled

Then I can provide the exact deployment command!

