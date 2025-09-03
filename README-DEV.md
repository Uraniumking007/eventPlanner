# Event Planner - Development Setup with Docker Watch Plugin

This project now includes Docker watch plugin support for automatic file synchronization during development.

## Prerequisites

- Docker Desktop 4.25+ or Docker Compose v2.4+
- Docker running on your system

## Quick Start

### Windows (PowerShell)

```powershell
.\dev.ps1
```

### Linux/macOS (Bash)

```bash
chmod +x dev.sh
./dev.sh
```

### Manual Start

```bash
docker compose watch
```

## What the Watch Plugin Does

The watch plugin automatically:

1. **Syncs file changes** from your host machine to the container
2. **Rebuilds the container** when Dockerfile or docker-compose.yml changes
3. **Ignores unnecessary files** like .git, node_modules, uploads, etc.

## File Watching Configuration

The watch plugin is configured to:

- **Sync action**: Automatically sync all PHP, CSS, JS, and other source files
- **Rebuild action**: Rebuild container when Docker configuration changes
- **Ignore patterns**: Skip syncing of .git, node_modules, .env, uploads, logs, etc.

## Development Workflow

1. Start the development environment with `docker compose watch`
2. Make changes to your PHP, CSS, or other source files
3. Changes are automatically synced to the container
4. Refresh your browser to see changes
5. No need to rebuild containers for code changes!

## Access Points

- **Main Application**: http://localhost:8080
- **phpMyAdmin**: http://localhost:8081
- **Database**: Accessible only within Docker network

## Stopping the Environment

Press `Ctrl+C` in the terminal where `docker compose watch` is running.

## Troubleshooting

### Watch Plugin Not Working

- Ensure Docker Desktop is version 4.25+ or Docker Compose v2.4+
- Check that Docker is running
- Verify the `develop.watch` section exists in docker-compose.yml

### File Changes Not Syncing

- Check that the file path is not in the ignore list
- Ensure the container is running with `docker compose ps`
- Restart with `docker compose watch`

### Performance Issues

- The `.dockerignore` file optimizes build context
- Large files in uploads/ or logs/ are excluded from syncing
- Consider excluding additional directories if needed

## Manual Commands

```bash
# Start without watch plugin
docker compose up -d

# Stop all services
docker compose down

# View logs
docker compose logs -f app

# Rebuild and start
docker compose up --build
```
