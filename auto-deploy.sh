#!/bin/bash

# 🤖 Auto-Deployment Script for CustomerCRUD Application
# This script checks GitHub for successful pipeline runs every 5 minutes
# and automatically deploys when new commits pass all tests

# Configuration
REPO_OWNER="KhaldounDamach2"
REPO_NAME="customercrud"
BRANCH="main"
WORK_DIR="/opt/customercrud"
LAST_DEPLOYED_FILE="$WORK_DIR/.last-deployed-commit"
LOG_FILE="$WORK_DIR/auto-deploy.log"

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Logging function
log() {
    echo -e "$(date '+%Y-%m-%d %H:%M:%S') - $1" | tee -a "$LOG_FILE"
}

# Check if we're in the right directory
if [ ! -d "$WORK_DIR" ]; then
    log "${RED}❌ Work directory $WORK_DIR not found!${NC}"
    exit 1
fi

cd "$WORK_DIR" || exit 1

# Function to get the latest commit SHA from GitHub
get_latest_commit() {
    curl -s "https://api.github.com/repos/$REPO_OWNER/$REPO_NAME/commits/$BRANCH" | \
    grep '"sha":' | head -1 | cut -d'"' -f4
}

# Function to check if workflow passed for a specific commit
check_workflow_status() {
    local commit_sha=$1
    
    # Get the latest workflow run for this commit
    local api_response=$(curl -s "https://api.github.com/repos/$REPO_OWNER/$REPO_NAME/actions/runs?head_sha=$commit_sha&status=completed&per_page=1")
    
    # Debug: log the API response (first 200 chars) to separate log line
    # Temporarily disable debug output that's interfering with parsing
    # log "🐛 DEBUG: API Response: $(echo "$api_response" | cut -c1-200)..."
    
    # Check if we have any workflow runs
    local total_count=$(echo "$api_response" | grep -o '"total_count": *[0-9]*' | sed 's/.*: *\([0-9]*\).*/\1/')
    
    if [ "$total_count" = "0" ] || [ -z "$total_count" ]; then
        echo "not_found"
        return
    fi
    
    # Extract conclusion from the response
    local status=$(echo "$api_response" | grep -o '"conclusion": *"[^"]*"' | head -1 | sed 's/.*"conclusion": *"\([^"]*\)".*/\1/')
    
    # If no conclusion found, check if workflow is still running
    if [ -z "$status" ]; then
        local running_status=$(echo "$api_response" | grep -o '"status": *"[^"]*"' | head -1 | sed 's/.*"status": *"\([^"]*\)".*/\1/')
        if [ "$running_status" = "in_progress" ] || [ "$running_status" = "queued" ]; then
            echo "running"
        else
            echo "not_found"
        fi
    else
        echo "$status"
    fi
}

# Function to get last deployed commit
get_last_deployed_commit() {
    if [ -f "$LAST_DEPLOYED_FILE" ]; then
        cat "$LAST_DEPLOYED_FILE"
    else
        echo ""
    fi
}

# Function to save deployed commit
save_deployed_commit() {
    echo "$1" > "$LAST_DEPLOYED_FILE"
}

# Main deployment logic
main() {
    log "${BLUE}🔍 Checking for new deployments...${NC}"
    
    # Get current commit from GitHub
    latest_commit=$(get_latest_commit)
    if [ -z "$latest_commit" ]; then
        log "${RED}❌ Failed to fetch latest commit from GitHub${NC}"
        return 1
    fi
    
    # Get last deployed commit
    last_deployed=$(get_last_deployed_commit)
    
    # Check if there's a new commit
    if [ "$latest_commit" = "$last_deployed" ]; then
        log "${GREEN}✅ Already up to date ($(echo $latest_commit | cut -c1-8))${NC}"
        return 0
    fi
    
    log "${YELLOW}📋 New commit detected: $(echo $latest_commit | cut -c1-8)${NC}"
    
    # Check workflow status
    log "${BLUE}🔍 Checking CI/CD pipeline status...${NC}"
    workflow_status=$(check_workflow_status "$latest_commit")
    
    case "$workflow_status" in
        "success")
            log "${GREEN}✅ Pipeline passed! Starting deployment...${NC}"
            ;;
        "running"|"in_progress"|"queued")
            log "${YELLOW}⏳ Pipeline is still running for commit $(echo $latest_commit | cut -c1-8)${NC}"
            return 0
            ;;
        "failure"|"cancelled"|"timed_out")
            log "${RED}❌ Pipeline failed for commit $(echo $latest_commit | cut -c1-8) (status: $workflow_status)${NC}"
            return 1
            ;;
        "not_found"|"")
            log "${YELLOW}🔍 No pipeline found yet for commit $(echo $latest_commit | cut -c1-8), waiting...${NC}"
            return 0
            ;;
        *)
            log "${YELLOW}❓ Unknown pipeline status: $workflow_status for commit $(echo $latest_commit | cut -c1-8)${NC}"
            return 1
            ;;
    esac
    
    log "${GREEN}✅ Pipeline passed! Starting deployment...${NC}"
    
    # Run deployment
    log "${BLUE}🚀 Executing deployment script...${NC}"
    
    if ./deploy.sh >> "$LOG_FILE" 2>&1; then
        save_deployed_commit "$latest_commit"
        log "${GREEN}🎉 Deployment successful! Commit $(echo $latest_commit | cut -c1-8) deployed${NC}"
        
        # Send deployment notification (optional)
        log "${BLUE}📧 Deployment completed at $(date)${NC}"
        
        return 0
    else
        log "${RED}❌ Deployment failed! Check deploy.sh logs${NC}"
        return 1
    fi
}

# Run the main function
main