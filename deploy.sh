#!/bin/bash

# 🚀 Customer CRUD Deployment Script
# Run this script on your Ubuntu server to deploy the latest version

set -e  # Exit on any error

echo "🚀 Starting Customer CRUD deployment..."

# Colors for output
GREEN='\033[0;32m'
BLUE='\033[0;34m'
RED='\033[0;31m'
NC='\033[0m' # No Color

# Check if we're in the right directory
if [ ! -f "docker-compose.yml" ]; then
    echo -e "${RED}❌ Error: docker-compose.yml not found!"
    echo -e "Please run this script from the /opt/customercrud directory${NC}"
    exit 1
fi

# Pull latest code
echo -e "${BLUE}📥 Pulling latest code from GitHub...${NC}"
git pull origin main

# Check if .env exists
if [ ! -f ".env" ]; then
    echo -e "${BLUE}🔧 Creating .env file...${NC}"
    cp .env.example .env
    echo -e "${RED}⚠️  Please edit .env file with your SendGrid API key before continuing!${NC}"
    echo -e "${BLUE}Run: nano .env${NC}"
    exit 1
fi

# Pull latest Docker images
echo -e "${BLUE}🐳 Pulling Docker images...${NC}"
docker-compose pull

# Build and start containers
echo -e "${BLUE}🏗️  Building and starting containers...${NC}"
docker-compose up -d --build

# Wait a moment for containers to start
sleep 5

# Check container status
echo -e "${BLUE}📋 Checking container status...${NC}"
docker-compose ps

# Check if web container is healthy
if docker-compose ps | grep -q "customercrud-web-1.*Up"; then
    echo -e "${GREEN}✅ Deployment successful!${NC}"
    echo -e "${GREEN}🌐 Application is running at: http://$(hostname -I | awk '{print $1}'):8080${NC}"
    echo -e "${GREEN}📊 Database is running at: $(hostname -I | awk '{print $1}'):3307${NC}"
else
    echo -e "${RED}❌ Deployment failed! Check logs:${NC}"
    echo -e "${BLUE}docker-compose logs${NC}"
    exit 1
fi

echo -e "${GREEN}🎉 Deployment completed successfully!${NC}"