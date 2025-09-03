# Development script for Event Planner with Docker Watch Plugin (PowerShell)

Write-Host "🚀 Starting Event Planner development environment with watch plugin..." -ForegroundColor Green

# Check if Docker is running
try {
    docker info | Out-Null
} catch {
    Write-Host "❌ Docker is not running. Please start Docker first." -ForegroundColor Red
    exit 1
}

# Check if Docker Compose v2 is available
try {
    docker compose version | Out-Null
} catch {
    Write-Host "❌ Docker Compose v2 is required for the watch plugin." -ForegroundColor Red
    Write-Host "Please update to Docker Desktop 4.25+ or Docker Compose v2.4+" -ForegroundColor Yellow
    exit 1
}

# Stop any existing containers
Write-Host "🛑 Stopping existing containers..." -ForegroundColor Yellow
docker compose down

# Start the development environment with watch plugin
Write-Host "🔍 Starting with watch plugin enabled..." -ForegroundColor Green
Write-Host "📁 Watching for file changes in current directory..." -ForegroundColor Cyan
Write-Host "🌐 Application will be available at: http://localhost:8080" -ForegroundColor Cyan
Write-Host "🗄️  phpMyAdmin will be available at: http://localhost:8081" -ForegroundColor Cyan
Write-Host ""
Write-Host "Press Ctrl+C to stop the development environment" -ForegroundColor Yellow
Write-Host ""

# Start with watch plugin
Write-Host "🔍 Starting Docker Compose with watch plugin..." -ForegroundColor Green
docker compose watch

# If watch plugin fails, provide fallback
if ($LASTEXITCODE -ne 0) {
    Write-Host "⚠️  Watch plugin failed, trying regular compose..." -ForegroundColor Yellow
    docker compose up -d
    Write-Host "📝 To see logs: docker compose logs -f app" -ForegroundColor Cyan
    Write-Host "🛑 To stop: docker compose down" -ForegroundColor Cyan
}
