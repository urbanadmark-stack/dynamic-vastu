# PowerShell SSH Deployment Script for Hostinger
# This script connects to your server and pulls the latest code from GitHub

$SERVER = "u698056983@in-mum-web1672.main-hosting.eu"
$REMOTE_DIR = "/home/u698056983/public_html"

Write-Host "🚀 Starting deployment..." -ForegroundColor Cyan

# SSH command to deploy
$sshCommand = @"
cd /home/u698056983/public_html

if [ ! -d .git ]; then
    echo '📦 Initializing Git repository...'
    git init
    git remote add origin https://github.com/urbanadmark-stack/dynamic-vastu.git
    git fetch origin
    git checkout -b main origin/main
else
    echo '🔄 Pulling latest changes from GitHub...'
    git fetch origin
    git reset --hard origin/main
    git clean -fd
fi

echo '✅ Deployment complete!'
echo '📂 Current directory: \$(pwd)'
echo '📋 Latest commit: \$(git log -1 --oneline)'
"@

# Execute SSH command
ssh $SERVER $sshCommand

Write-Host ""
Write-Host "✨ Deployment finished successfully!" -ForegroundColor Green
Write-Host "🌐 Check your website to see the changes" -ForegroundColor Green

