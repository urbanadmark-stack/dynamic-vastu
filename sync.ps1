# Auto Sync Script - Syncs local changes to GitHub and Server
# Usage: .\sync.ps1 [commit message]

param(
    [string]$CommitMessage = "Auto sync: $(Get-Date -Format 'yyyy-MM-dd HH:mm:ss')"
)

Write-Host "=== Auto Sync Script ===" -ForegroundColor Cyan
Write-Host ""

# Check if there are changes to commit
$status = git status --porcelain
if ([string]::IsNullOrWhiteSpace($status)) {
    Write-Host "No changes to commit." -ForegroundColor Yellow
    Write-Host ""
} else {
    Write-Host "Staging changes..." -ForegroundColor Green
    git add .
    
    Write-Host "Committing changes..." -ForegroundColor Green
    git commit -m $CommitMessage
    
    Write-Host "Pushing to GitHub..." -ForegroundColor Green
    git push origin main
    
    if ($LASTEXITCODE -eq 0) {
        Write-Host "✓ Successfully pushed to GitHub!" -ForegroundColor Green
    } else {
        Write-Host "✗ Failed to push to GitHub" -ForegroundColor Red
        exit 1
    }
    Write-Host ""
}

# Deploy to server
Write-Host "Deploying to server..." -ForegroundColor Cyan
$SSH_HOST = "82.112.229.147"
$SSH_PORT = "65002"
$SSH_USER = "u698056983"

$deployCommand = "cd /home/$SSH_USER/public_html && git fetch origin && git reset --hard origin/main && git pull origin main"

Write-Host "Connecting to server..." -ForegroundColor Yellow
ssh -p $SSH_PORT ${SSH_USER}@${SSH_HOST} $deployCommand

if ($LASTEXITCODE -eq 0) {
    Write-Host ""
    Write-Host "✓ Successfully synced to server!" -ForegroundColor Green
    Write-Host "Frontend and backend are now in sync." -ForegroundColor Green
} else {
    Write-Host ""
    Write-Host "✗ Deployment failed. Please check SSH connection." -ForegroundColor Red
    Write-Host "You may need to run this manually:" -ForegroundColor Yellow
    Write-Host "ssh -p $SSH_PORT ${SSH_USER}@${SSH_HOST} `"$deployCommand`"" -ForegroundColor Gray
    exit 1
}

Write-Host ""
Write-Host "=== Sync Complete ===" -ForegroundColor Cyan

