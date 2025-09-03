#!/bin/bash

# Development script for Event Planner with Docker Watch Plugin

echo "🚀 Starting Event Planner development environment with watch plugin..."

# Check if Docker is running
if ! docker info > /dev/null 2>&1; then
    echo "❌ Docker is not running. Please start Docker first."
    exit 1
fi

# Check if Docker Compose v2 is available
if ! docker compose version > /dev/null 2>&1; then
    echo "❌ Docker Compose v2 is required for the watch plugin."
    echo "Please update to Docker Desktop 4.25+ or Docker Compose v2.4+"
    exit 1
fi

# Stop any existing containers
echo "🛑 Stopping existing containers..."
docker compose down

# Start the development environment with watch plugin
echo "🔍 Starting with watch plugin enabled..."
echo "📁 Watching for file changes in current directory..."
echo "🌐 Application will be available at: http://localhost:8080"
echo "🗄️  phpMyAdmin will be available at: http://localhost:8081"
echo ""
echo "Press Ctrl+C to stop the development environment"
echo ""

# Start with watch plugin
docker compose watch
