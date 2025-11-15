# 🚀 Production Deployment Guide

> **📖 Back to:** [README.md](README.md) for project overview | [AUTO-DEPLOY-README.md](AUTO-DEPLOY-README.md) for automation details

## GitHub Secrets

**❌ No GitHub secrets required!**

The CI/CD pipeline only runs tests and builds - no secrets needed. All deployment happens directly on your server.

**✅ Your deployment is completely independent of GitHub secrets!**

## 🚀 Quick Start for Recruiters/Developers

**Want to test this project locally?**

👉 **See [README.md](README.md) for complete setup instructions**

```bash
# Quick test (requires Docker)
git clone https://github.com/KhaldounDamach2/customercrud.git
cd customercrud && cp .env.example .env
# Add SendGrid key to .env, then:
docker-compose up -d
# Visit http://localhost:8081
```

---

## 🛠️ Production Server Setup (Ubuntu)

```bash
# 1. Install Docker & Docker Compose
curl -fsSL https://get.docker.com -o get-docker.sh && sudo sh get-docker.sh
sudo usermod -aG docker $USER
sudo curl -L "https://github.com/docker/compose/releases/latest/download/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
sudo chmod +x /usr/local/bin/docker-compose

# 2. Setup application
sudo mkdir -p /opt/customercrud && sudo chown $USER:$USER /opt/customercrud
cd /opt/customercrud
git clone https://github.com/KhaldounDamach2/customercrud.git .

# 3. Configure environment (see README.md for SendGrid details)
cp .env.example .env && nano .env
```

## 📋 Deployment Options

### Option 1: 🤖 **Automatic Deployment (Recommended)**
Set up once, then deployments happen automatically every 5 minutes when CI/CD passes:

```bash
# On your Ubuntu server (one-time setup)
cd /opt/customercrud
sudo ./setup-auto-deploy.sh
```

**How it works:**
1. **Push to main branch** → Triggers CI/CD pipeline
2. **Tests run** → PHP syntax checks and Docker build
3. **Pipeline passes** → Deployment signal created
4. **Server checks** → Every 5 minutes for successful pipelines
5. **Auto-deploy** → Calls `./deploy.sh` automatically if new commits passed

### Option 2: 📋 **Manual Deployment**
```bash
# On your Ubuntu server
cd /opt/customercrud
./deploy.sh  # Deploy manually when needed
```

### Option 3: 🎯 **One-time Deployment**
```bash
# On your Ubuntu server
cd /opt/customercrud
./deploy.sh  # Or: git pull && docker-compose up -d --build
```

## 🤖 Auto-Deployment System

For detailed auto-deployment management, see **[AUTO-DEPLOY-README.md](AUTO-DEPLOY-README.md)**

### Quick Auto-Deploy Setup
```bash
cd /opt/customercrud
sudo ./setup-auto-deploy.sh
```

### Benefits
✅ **Fully automated** - Deploy successful changes automatically  
✅ **Safe** - Only deploys when CI/CD passes  
✅ **Fast** - 5-minute check interval  
✅ **Professional** - Production-ready deployment workflow

## 🌐 Production URLs

- **Application**: `http://your-server-ip:8081`
- **Database**: `your-server-ip:3307` (if needed)

## 🛡️ Security Notes

- Never commit `.env` files (already in `.gitignore`)
- Use GitHub Secrets for sensitive data
- Regularly rotate API keys and passwords
- Consider using HTTPS in production (add reverse proxy)

## 📞 Need Help?

- **[README.md](README.md)** - Main project documentation and troubleshooting
- **[AUTO-DEPLOY-README.md](AUTO-DEPLOY-README.md)** - Auto-deployment system details
- **GitHub Issues** - Report bugs or request features