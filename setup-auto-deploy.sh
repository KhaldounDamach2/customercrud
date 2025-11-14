#!/bin/bash

# 🚀 Auto-Deployment Setup Script
# Run this once on your Ubuntu server to enable automatic deployments

set -e

echo "🤖 Setting up CustomerCRUD Auto-Deployment System..."

# Check if running as root
if [ "$EUID" -ne 0 ]; then
    echo "❌ This script must be run as root (use sudo)"
    echo "Usage: sudo ./setup-auto-deploy.sh"
    exit 1
fi

# Configuration
WORK_DIR="/opt/customercrud"
SERVICE_NAME="customercrud-autodeploy"

echo "📂 Working directory: $WORK_DIR"

# Check if application directory exists
if [ ! -d "$WORK_DIR" ]; then
    echo "❌ Application directory $WORK_DIR not found!"
    echo "Please ensure your application is deployed at $WORK_DIR"
    exit 1
fi

cd "$WORK_DIR"

# Make scripts executable
echo "🔧 Making scripts executable..."
chmod +x auto-deploy.sh
chmod +x deploy.sh

# Copy service files to systemd
echo "⚙️ Installing systemd service and timer..."
cp "$SERVICE_NAME.service" "/etc/systemd/system/"
cp "$SERVICE_NAME.timer" "/etc/systemd/system/"

# Reload systemd
echo "🔄 Reloading systemd..."
systemctl daemon-reload

# Enable and start the timer
echo "▶️ Enabling auto-deployment timer..."
systemctl enable "$SERVICE_NAME.timer"
systemctl start "$SERVICE_NAME.timer"

# Check status
echo "✅ Auto-deployment setup complete!"
echo ""
echo "📊 Status:"
systemctl status "$SERVICE_NAME.timer" --no-pager -l
echo ""
echo "📋 Commands to manage auto-deployment:"
echo "  • Check status:     systemctl status $SERVICE_NAME.timer"
echo "  • View logs:        journalctl -u $SERVICE_NAME.service -f"
echo "  • Stop auto-deploy: systemctl stop $SERVICE_NAME.timer"
echo "  • Start auto-deploy: systemctl start $SERVICE_NAME.timer"
echo "  • Manual run:       systemctl start $SERVICE_NAME.service"
echo ""
echo "🎯 Auto-deployment will now check for updates every 5 minutes!"
echo "📁 Logs will be saved to: $WORK_DIR/auto-deploy.log"