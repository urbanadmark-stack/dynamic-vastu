#!/bin/bash
# Auto Sync Script - Syncs local changes to GitHub and Server
# Usage: ./sync.sh [commit message]

COMMIT_MESSAGE="${1:-Auto sync: $(date '+%Y-%m-%d %H:%M:%S')}"

echo "=== Auto Sync Script ==="
echo ""

# Check if there are changes to commit
if [ -z "$(git status --porcelain)" ]; then
    echo "No changes to commit."
    echo ""
else
    echo "Staging changes..."
    git add .
    
    echo "Committing changes..."
    git commit -m "$COMMIT_MESSAGE"
    
    echo "Pushing to GitHub..."
    git push origin main
    
    if [ $? -eq 0 ]; then
        echo "✓ Successfully pushed to GitHub!"
    else
        echo "✗ Failed to push to GitHub"
        exit 1
    fi
    echo ""
fi

# Deploy to server
echo "Deploying to server..."
SSH_HOST="82.112.229.147"
SSH_PORT="65002"
SSH_USER="u698056983"

DEPLOY_COMMAND="cd /home/$SSH_USER/public_html && git fetch origin && git reset --hard origin/main && git pull origin main"

echo "Connecting to server..."
ssh -p $SSH_PORT ${SSH_USER}@${SSH_HOST} "$DEPLOY_COMMAND"

if [ $? -eq 0 ]; then
    echo ""
    echo "✓ Successfully synced to server!"
    echo "Frontend and backend are now in sync."
else
    echo ""
    echo "✗ Deployment failed. Please check SSH connection."
    echo "You may need to run this manually:"
    echo "ssh -p $SSH_PORT ${SSH_USER}@${SSH_HOST} \"$DEPLOY_COMMAND\""
    exit 1
fi

echo ""
echo "=== Sync Complete ==="

