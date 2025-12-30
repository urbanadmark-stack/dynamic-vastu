#!/bin/bash
# SSH Deployment Script for Hostinger
# This script connects to your server and pulls the latest code from GitHub

# Server details
SERVER="u698056983@in-mum-web1672.main-hosting.eu"
REMOTE_DIR="/home/u698056983/public_html"

echo "🚀 Starting deployment..."

# Connect to server and pull latest code
ssh $SERVER << 'ENDSSH'
cd /home/u698056983/public_html

# Check if git is initialized
if [ ! -d .git ]; then
    echo "📦 Initializing Git repository..."
    git init
    git remote add origin https://github.com/urbanadmark-stack/dynamic-vastu.git
    git fetch origin
    git checkout -b main origin/main
else
    echo "🔄 Pulling latest changes from GitHub..."
    git fetch origin
    git reset --hard origin/main
    git clean -fd
fi

echo "✅ Deployment complete!"
echo "📂 Current directory: $(pwd)"
echo "📋 Latest commit: $(git log -1 --oneline)"
ENDSSH

echo ""
echo "✨ Deployment finished successfully!"
echo "🌐 Check your website to see the changes"

