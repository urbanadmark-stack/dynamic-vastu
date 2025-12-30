@echo off
REM Windows Batch Deployment Script for Hostinger
REM This script deploys your latest changes to the Hostinger server via SSH

echo ========================================
echo   Dynamic Vastu - Deployment Script
echo ========================================
echo.

REM SSH Connection Details
set SSH_PORT=65002
set SSH_USER=u698056983
set SSH_HOST=82.112.229.147
set REMOTE_PATH=/home/u698056983/public_html

echo Checking Git status...
git status --porcelain >nul 2>&1
if %errorlevel% equ 0 (
    echo Warning: You have uncommitted changes!
    echo.
    set /p commit="Do you want to commit these changes before deploying? (y/n): "
    if /i "%commit%"=="y" (
        set /p message="Enter commit message: "
        git add -A
        git commit -m "%message%"
        echo Changes committed.
    )
)

REM Check if we're on main branch
for /f "tokens=*" %%i in ('git branch --show-current') do set currentBranch=%%i
if not "%currentBranch%"=="main" (
    echo Warning: You're not on the main branch (current: %currentBranch%)
    set /p continue="Continue anyway? (y/n): "
    if /i not "%continue%"=="y" (
        echo Deployment cancelled.
        exit /b
    )
)

echo.
echo Pushing changes to GitHub...
git push origin main
if %errorlevel% neq 0 (
    echo Failed to push to GitHub. Please check your connection and try again.
    pause
    exit /b 1
)
echo Successfully pushed to GitHub!

echo.
echo Deploying to Hostinger server...
echo SSH: %SSH_USER%@%SSH_HOST%:%SSH_PORT%
echo Path: %REMOTE_PATH%
echo.

set deployCommand=cd %REMOTE_PATH% && git pull origin main
set sshCommand=ssh -p %SSH_PORT% %SSH_USER%@%SSH_HOST% "%deployCommand%"

echo Running deployment command...
echo Command: %sshCommand%
echo.

REM Execute SSH command
%sshCommand%

if %errorlevel% equ 0 (
    echo.
    echo ========================================
    echo   Deployment Successful!
    echo ========================================
    echo.
    echo Your changes are now live on the server.
) else (
    echo.
    echo ========================================
    echo   Deployment Failed
    echo ========================================
    echo.
    echo Please check:
    echo 1. SSH key is properly configured
    echo 2. Server has Git repository initialized
    echo 3. Network connection is stable
    echo.
    echo You can also deploy manually by running:
    echo ssh -p %SSH_PORT% %SSH_USER%@%SSH_HOST% "cd %REMOTE_PATH% && git pull origin main"
)

pause
