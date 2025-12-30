@echo off
echo ========================================
echo   Deploying to Hostinger Server
echo ========================================
echo.

echo [1/2] Pushing to GitHub...
git push origin main
if %errorlevel% neq 0 (
    echo Error: Failed to push to GitHub
    pause
    exit /b 1
)

echo.
echo [2/2] Deploying to server...
ssh -p 65002 u698056983@82.112.229.147 "cd /home/u698056983/public_html && if [ -d .git ]; then git pull origin main; else git init && git remote add origin https://github.com/urbanadmark-stack/dynamic-vastu.git && git fetch origin && git checkout -b main origin/main; fi"

echo.
echo ========================================
echo   Deployment Complete!
echo ========================================
echo.
pause

