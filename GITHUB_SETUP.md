# GitHub Setup Guide for Cursor

## Step 1: Configure Git (First Time Only)

Open Terminal in Cursor and run these commands with your information:

```bash
git config --global user.name "Your Name"
git config --global user.email "your.email@example.com"
```

## Step 2: Create a GitHub Repository

1. Go to [GitHub.com](https://github.com) and sign in
2. Click the **+** icon in the top right → **New repository**
3. Name your repository (e.g., `dynamic-vastu` or `real-estate-website`)
4. **Don't** initialize with README, .gitignore, or license (we already have files)
5. Click **Create repository**

## Step 3: Connect Your Local Repository to GitHub

After creating the repository, GitHub will show you commands. Run these in Cursor's terminal:

```bash
git remote add origin https://github.com/YOUR_USERNAME/YOUR_REPO_NAME.git
git branch -M main
git push -u origin main
```

Replace:
- `YOUR_USERNAME` with your GitHub username
- `YOUR_REPO_NAME` with your repository name

## Step 4: Commit and Push Changes from Cursor

### Option A: Using Cursor's Source Control Panel (Recommended)

1. Click the **Source Control** icon in the left sidebar (or press `Ctrl+Shift+G`)
2. Type a commit message in the box (e.g., "Update homepage design")
3. Click the **✓** (checkmark) button or press `Ctrl+Enter` to commit
4. Click the **...** (three dots) menu → **Push** to upload to GitHub

### Option B: Using Terminal in Cursor

1. Open Terminal (`Ctrl+` ` or `View → Terminal`)
2. Run these commands:

```bash
git add .
git commit -m "Your commit message here"
git push
```

## Quick Commands Reference

```bash
# Check status
git status

# Add all changes
git add .

# Commit changes
git commit -m "Your message"

# Push to GitHub
git push

# Pull latest changes from GitHub
git pull
```

## Authentication Note

If you're asked for credentials when pushing:
- Use a **Personal Access Token** (not your password)
- Create one at: GitHub → Settings → Developer settings → Personal access tokens → Tokens (classic)
- Generate token with `repo` permissions
- Use token as password when prompted

## Using GitHub Desktop (Alternative)

If you prefer a GUI:
1. Download [GitHub Desktop](https://desktop.github.com/)
2. File → Add Local Repository → Select your project folder
3. Publish repository to GitHub
4. Make changes, commit, and push using the GUI

