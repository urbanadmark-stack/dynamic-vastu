# PowerShell Deployment Script for Hostinger
# This script deploys your latest changes to the Hostinger server via SSH

# SSH Connection Details
$SSH_PORT = "65002"
$SSH_USER = "u698056983"
$SSH_HOST = "82.112.229.147"
$REMOTE_PATH = "/home/u698056983/public_html"

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  Dynamic Vastu - Deployment Script" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# Check if git is available
Write-Host "Checking Git status..." -ForegroundColor Yellow
$gitStatus = git status --porcelain
if ($gitStatus) {
    Write-Host "Warning: You have uncommitted changes!" -ForegroundColor Red
    Write-Host "Uncommitted files:" -ForegroundColor Yellow
    Write-Host $gitStatus
    Write-Host ""
    $commit = Read-Host "Do you want to commit these changes before deploying? (y/n)"
    if ($commit -eq "y" -or $commit -eq "Y") {
        $message = Read-Host "Enter commit message"
        git add -A
        git commit -m $message
        Write-Host "Changes committed." -ForegroundColor Green
    }
}

# Check if we're on main branch
$currentBranch = git branch --show-current
if ($currentBranch -ne "main") {
    Write-Host "Warning: You're not on the main branch (current: $currentBranch)" -ForegroundColor Yellow
    $continue = Read-Host "Continue anyway? (y/n)"
    if ($continue -ne "y" -and $continue -ne "Y") {
        Write-Host "Deployment cancelled." -ForegroundColor Red
        exit
    }
}

# Push to GitHub
Write-Host ""
Write-Host "Pushing changes to GitHub..." -ForegroundColor Yellow
git push origin main
if ($LASTEXITCODE -ne 0) {
    Write-Host "Failed to push to GitHub. Please check your connection and try again." -ForegroundColor Red
    exit 1
}
Write-Host "Successfully pushed to GitHub!" -ForegroundColor Green

# Deploy to server
Write-Host ""
Write-Host "Deploying to Hostinger server..." -ForegroundColor Yellow
Write-Host "SSH: ${SSH_USER}@${SSH_HOST}:${SSH_PORT}" -ForegroundColor Gray
Write-Host "Path: $REMOTE_PATH" -ForegroundColor Gray
Write-Host ""

$deployCommand = "cd $REMOTE_PATH && git pull origin main"
$sshCommand = "ssh -p $SSH_PORT $SSH_USER@$SSH_HOST `"$deployCommand`""

Write-Host "Running deployment command..." -ForegroundColor Yellow
Write-Host "Command: $sshCommand" -ForegroundColor Gray
Write-Host ""

# Execute SSH command
Invoke-Expression $sshCommand

if ($LASTEXITCODE -eq 0) {
    Write-Host ""
    Write-Host "========================================" -ForegroundColor Green
    Write-Host "  Deployment Successful!" -ForegroundColor Green
    Write-Host "========================================" -ForegroundColor Green
    Write-Host ""
    Write-Host "Your changes are now live on the server." -ForegroundColor Green
} else {
    Write-Host ""
    Write-Host "========================================" -ForegroundColor Red
    Write-Host "  Deployment Failed" -ForegroundColor Red
    Write-Host "========================================" -ForegroundColor Red
    Write-Host ""
    Write-Host "Please check:" -ForegroundColor Yellow
    Write-Host "1. SSH key is properly configured" -ForegroundColor Yellow
    Write-Host "2. Server has Git repository initialized" -ForegroundColor Yellow
    Write-Host "3. Network connection is stable" -ForegroundColor Yellow
    Write-Host ""
    Write-Host "You can also deploy manually by running:" -ForegroundColor Cyan
    Write-Host "ssh -p ${SSH_PORT} ${SSH_USER}@${SSH_HOST} `"cd ${REMOTE_PATH} && git pull origin main`"" -ForegroundColor Gray
}

