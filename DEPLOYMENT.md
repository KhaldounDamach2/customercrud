# 🚀 GitHub Repository Setup Guide

## GitHub Secrets

**❌ No GitHub secrets required!**

The CI/CD pipeline only runs tests and builds - no secrets needed. All deployment happens directly on your server.

**✅ Your deployment is completely independent of GitHub secrets!**

## 🚀 Quick Start for Recruiters/Developers

**Want to test this project locally? Here's the fastest way:**

```bash
# 1. Clone the repository
git clone https://github.com/KhaldounDamach2/customercrud.git
cd customercrud

# 2. Set up environment
cp .env.example .env
# Edit .env and add your SendGrid API key + verified sender email

# 3. Start with Docker (requires Docker installed)
docker-compose up -d

# 4. Open in browser
# http://localhost:8081
```

**That's it! The application will run with a MySQL database included.**

### 🧪 Test Features:
- ✅ Create/Edit/Delete customers
- ✅ Email notifications (with SendGrid key)
- ✅ Responsive Bootstrap UI
- ✅ Docker containerization
- ✅ Database persistence

### 📋 What you'll see:
- Customer management interface
- Form validation
- Email integration
- Clean URLs and security features

---

## 🛠️ Production Server Setup (Ubuntu)

For production deployment on Ubuntu server:

```bash
# Install Docker
curl -fsSL https://get.docker.com -o get-docker.sh
sudo sh get-docker.sh
sudo usermod -aG docker $USER

# Install Docker Compose
sudo curl -L "https://github.com/docker/compose/releases/latest/download/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
sudo chmod +x /usr/local/bin/docker-compose

# Create application directory
sudo mkdir -p /opt/customercrud
sudo chown $USER:$USER /opt/customercrud
cd /opt/customercrud

# Clone repository
git clone https://github.com/KhaldounDamach2/customercrud.git .

# First manual setup
cp .env.example .env
# Edit .env with your production values
nano .env
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
# Simple git pull and restart
cd /opt/customercrud
git pull origin main
docker-compose up -d --build
```

## 🔧 Deploy to Your Server

**Ultra-simple deployment:**

```bash
# On your Ubuntu server
cd /opt/customercrud
./deploy.sh
```

**Or manually:**
```bash
git pull origin main
docker-compose up -d --build
```

## 🤖 Auto-Deployment Management

After setting up automatic deployment, use these commands on your server:

```bash
# Check auto-deployment status
systemctl status customercrud-autodeploy.timer

# View deployment logs (live)
journalctl -u customercrud-autodeploy.service -f

# View deployment history
tail -f /opt/customercrud/auto-deploy.log

# Stop auto-deployment
sudo systemctl stop customercrud-autodeploy.timer

# Start auto-deployment
sudo systemctl start customercrud-autodeploy.timer

# Manual deployment trigger
sudo systemctl start customercrud-autodeploy.service
```

## 🎯 Why This Auto-Deployment Approach?

✅ **Fully automated** - Deploy successful changes automatically  
✅ **Safe** - Only deploys when CI/CD passes  
✅ **Fast** - 5-minute check interval  
✅ **Reliable** - Systemd service ensures it's always running  
✅ **Logged** - Full deployment history and status  
✅ **No secrets needed** - Server pulls from public repo  
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

Check the main README.md for troubleshooting and support information.