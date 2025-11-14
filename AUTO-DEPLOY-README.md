# 🤖 CustomerCRUD Auto-Deployment System

This system provides **automatic deployment** for the CustomerCRUD application, triggered by successful GitHub Actions CI/CD pipeline runs.

## 🎯 How It Works

```
Developer Push → GitHub Actions → Tests Pass → Auto-Deploy (every 5min) → Live Server
                                      ↓
                               Tests Fail → No Deployment
```

## 🚀 Quick Setup

**1. On your Ubuntu server:**
```bash
cd /opt/customercrud
sudo ./setup-auto-deploy.sh
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

```bash
# Status and logs
systemctl status customercrud-autodeploy.timer
journalctl -u customercrud-autodeploy.service -f
tail -f /opt/customercrud/auto-deploy.log

# Control
sudo systemctl start customercrud-autodeploy.timer   # Enable auto-deploy
sudo systemctl stop customercrud-autodeploy.timer    # Disable auto-deploy
sudo systemctl start customercrud-autodeploy.service # Manual deployment
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