# 🤖 CustomerCRUD Auto-Deployment System

This system provides **automatic deployment** for the CustomerCRUD application, triggered by successful GitHub Actions CI/CD pipeline runs.

> **📖 See also:** [DEPLOYMENT.md](DEPLOYMENT.md) for general deployment options | [README.md](README.md) for project overview

## 🎯 How It Works

```
Developer Push → GitHub Actions → Tests Pass → Auto-Deploy (every 5min) → Live Server
                                      ↓
                               Tests Fail → No Deployment
```

## 🚀 Quick Setup

**1. On your Ubuntu server (ONE-TIME ONLY):**
```bash
cd /opt/customercrud
sudo ./setup-auto-deploy.sh  # Only run this ONCE - survives reboots!
```

**2. Push code to GitHub:**
```bash
git push origin main
```

**3. Watch automatic deployment:**
```bash
tail -f /opt/customercrud/auto-deploy.log
```

## 📋 Files Overview

- `auto-deploy.sh` - Main deployment logic (checks GitHub API every 5min)
- `setup-auto-deploy.sh` - One-time server setup script
- `customercrud-autodeploy.service` - Systemd service definition
- `customercrud-autodeploy.timer` - Systemd timer (5-minute intervals)

## 🔧 Management Commands

### Status Monitoring
```bash
# Check timer status
systemctl status customercrud-autodeploy.timer

# View real-time logs
journalctl -u customercrud-autodeploy.service -f

# View deployment history  
tail -f /opt/customercrud/auto-deploy.log

# See next scheduled run
systemctl list-timers customercrud-autodeploy.timer
```

### Service Control
```bash
# Enable auto-deployment
sudo systemctl start customercrud-autodeploy.timer

# Disable auto-deployment
sudo systemctl stop customercrud-autodeploy.timer

# Manual deployment trigger (test)
sudo systemctl start customercrud-autodeploy.service

# Restart service after config changes
sudo systemctl restart customercrud-autodeploy.timer
```

## 🛡️ Safety Features

✅ **Only deploys successful pipelines** - Failed CI/CD = No deployment  
✅ **Commit tracking** - Prevents duplicate deployments  
✅ **Comprehensive logging** - Full audit trail  
✅ **Graceful failure** - Continues checking if deployment fails  
✅ **No secrets required** - Uses public GitHub API  

## 🎉 Benefits

- **Zero manual intervention** needed for deployments
- **Professional CI/CD workflow** 
- **Safe and reliable** - Only successful changes go live
- **Fast feedback loop** - 5-minute deployment window
- **Production ready** - Systemd service management
- **Survives reboots** - Auto-starts after server restarts (no re-setup needed)

## 🐛 Troubleshooting

### Common Issues

**Service not running:**
```bash
sudo systemctl status customercrud-autodeploy.timer
sudo journalctl -u customercrud-autodeploy.service --since "1 hour ago"
```

**Deployment not triggering:**
```bash
# Check if CI/CD pipeline passed
# View GitHub Actions in your repository

# Check auto-deploy logs
tail -n 20 /opt/customercrud/auto-deploy.log
```

**Permission issues:**
```bash
# Fix file permissions
sudo chown -R $USER:$USER /opt/customercrud
chmod +x /opt/customercrud/auto-deploy.sh
```

### Log Analysis
- ✅ `Already up to date` = Working correctly, no new commits
- ✅ `Deploying commit` = New deployment in progress  
- ❌ `Error:` = Check GitHub API limits or network issues