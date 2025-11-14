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
# Edit .env and add a SendGrid API key (or leave default for testing)

# 3. Start with Docker (requires Docker installed)
docker-compose up -d

# 4. Open in browser
# http://localhost:8080
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

## 📋 Simple Deployment Workflow

**Option 1: Direct deployment (No GitHub Actions needed)**
```bash
# On your Ubuntu server
cd /opt/customercrud
./deploy.sh  # That's it!
```

**Option 2: With GitHub Actions (Optional)**
1. **Push to main branch** → Triggers CI/CD tests
2. **Tests run** → PHP syntax checks  
3. **Docker image built** → (Optional: Pushed to Docker Hub)
4. **Deploy on server** → Run `./deploy.sh` when ready

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

## 🎯 Why Pull-Based Deployment?

✅ **No SSH configuration needed**  
✅ **No firewall issues**  
✅ **You control deployment timing**  
✅ **More secure** (server pulls, GitHub doesn't push)  
✅ **Works with any hosting provider**

## 🌐 Production URLs

- **Application**: `http://your-server-ip:8080`
- **Database**: `your-server-ip:3307` (if needed)

## 🛡️ Security Notes

- Never commit `.env` files (already in `.gitignore`)
- Use GitHub Secrets for sensitive data
- Regularly rotate API keys and passwords
- Consider using HTTPS in production (add reverse proxy)

## 📞 Need Help?

Check the main README.md for troubleshooting and support information.