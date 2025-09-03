# Docker Watch Plugin Troubleshooting Guide

## 🚨 UI Not Updating? Here's How to Fix It

### Quick Fix Steps

1. **Stop all containers:**

   ```bash
   docker compose down
   ```

2. **Start with watch plugin:**

   ```bash
   docker compose watch
   ```

3. **Test the watch plugin:**
   - Visit: http://localhost:8080/test-watch.php
   - Change the timestamp in the file
   - Refresh the page
   - If you see the new timestamp, it's working! 🎉

### Common Issues & Solutions

#### Issue 1: "docker compose watch" command not found

**Solution:** Update Docker Desktop to version 4.25+ or Docker Compose to v2.4+

```bash
docker compose version
```

#### Issue 2: Files not syncing

**Solution:** Check if containers are running

```bash
docker compose ps
```

**If not running, restart:**

```bash
docker compose down
docker compose watch
```

#### Issue 3: Browser still showing old content

**Solutions:**

1. **Hard refresh:** Ctrl+F5 (Windows) or Cmd+Shift+R (Mac)
2. **Clear browser cache:** Clear browsing data
3. **Check browser dev tools:** Look for cached CSS/JS files

#### Issue 4: CSS changes not visible

**Solutions:**

1. Check browser dev tools → Network tab → look for CSS file
2. Verify the file path: `assets/css/style.css`
3. Check if the file has the new comment: `/* Event Planner Styles - Last Updated: 2024-12-19 */`

### Debug Commands

```bash
# Check container status
docker compose ps

# View container logs
docker compose logs -f app

# Check if files are synced (run inside container)
docker exec -it eventplanner_app ls -la /var/www/html/assets/css/

# Check file contents inside container
docker exec -it eventplanner_app cat /var/www/html/assets/css/style.css

# Restart just the app container
docker compose restart app
```

### File Structure Check

Make sure your project structure looks like this:

```
eventPlanner/
├── docker-compose.yml    ← Must have develop.watch section
├── Dockerfile           ← Optimized for watch plugin
├── .dockerignore        ← Excludes unnecessary files
├── assets/css/style.css ← Your CSS file
├── index.php           ← Main page
└── test-watch.php      ← Test file
```

### Watch Plugin Configuration

Your `docker-compose.yml` should have:

```yaml
develop:
  watch:
    - action: sync
      path: .
      target: /var/www/html
      ignore:
        - .git/
        - node_modules/
        - .env
        - docker/
        - uploads/
        - logs/
```

### Still Not Working?

1. **Check Docker version:**

   ```bash
   docker --version
   docker compose version
   ```

2. **Verify file permissions:**

   ```bash
   ls -la assets/css/style.css
   ```

3. **Test with a simple file change:**

   - Add a comment to your CSS file
   - Save it
   - Check if it appears in the container

4. **Alternative approach (if watch plugin fails):**

   ```bash
   # Use regular compose with volume mounting
   docker compose up -d

   # Then manually sync changes by restarting
   docker compose restart app
   ```

### Need Help?

If none of these solutions work:

1. Check Docker Desktop logs
2. Verify your Docker installation
3. Try restarting Docker Desktop
4. Check if your antivirus is blocking file watching
