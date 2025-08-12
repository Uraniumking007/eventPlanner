# Event Planner - Setup Guide

## Quick Start (When Connection is Restored)

### 1. Start the Docker Environment

```bash
# Build and start all services
docker-compose up -d --build

# Check if services are running
docker-compose ps
```

### 2. Access the Application

Once the containers are running, you can access:

- **Website**: http://localhost:8080
- **phpMyAdmin**: http://localhost:8081
  - Username: `eventplanner`
  - Password: `eventplanner123`

### 3. Verify Installation

1. Open http://localhost:8080 in your browser
2. You should see the Event Planner homepage
3. Navigate through the different pages:
   - Home: http://localhost:8080
   - Events: http://localhost:8080/events.php
   - About: http://localhost:8080/about.php
   - Contact: http://localhost:8080/contact.php

### 4. Test the Contact Form

1. Go to the Contact page
2. Fill out the contact form
3. Submit the form
4. Check the database in phpMyAdmin to see if the message was saved

## Troubleshooting

### If containers fail to start:

```bash
# Check logs
docker-compose logs

# Check specific service logs
docker-compose logs app
docker-compose logs db

# Restart services
docker-compose down
docker-compose up -d
```

### If database connection fails:

```bash
# Check if MySQL is running
docker-compose exec db mysql -u eventplanner -p event_planner

# Recreate database
docker-compose down -v
docker-compose up -d
```

### If Apache configuration issues:

```bash
# Check Apache configuration
docker-compose exec app apache2ctl -t

# Restart Apache
docker-compose exec app service apache2 restart
```

## Development Commands

### View logs in real-time:
```bash
docker-compose logs -f app
```

### Access container shell:
```bash
docker-compose exec app bash
docker-compose exec db mysql -u root -p
```

### Update code without rebuilding:
```bash
# The code is mounted as a volume, so changes are reflected immediately
# Just refresh your browser
```

### Rebuild after major changes:
```bash
docker-compose down
docker-compose up -d --build
```

## File Structure Overview

```
eventPlanner/
├── index.php              # Homepage
├── events.php             # Events listing page
├── about.php              # About page
├── contact.php            # Contact page
├── process_contact.php    # Contact form handler
├── assets/
│   ├── css/style.css      # Custom styles
│   └── js/main.js         # Custom JavaScript
├── config/
│   ├── config.php         # Main configuration
│   └── database.php       # Database configuration
├── includes/
│   ├── header.php         # Common header
│   └── footer.php         # Common footer
├── docker/
│   ├── apache.conf        # Apache configuration
│   └── init.sql           # Database schema
├── Dockerfile             # PHP application container
├── docker-compose.yml     # Multi-container setup
└── README.md              # Project documentation
```

## Next Steps

1. **Customize the Design**: Edit `assets/css/style.css` to match your brand
2. **Add Content**: Update the pages with your specific content
3. **Configure Email**: Set up email notifications in `process_contact.php`
4. **Add Features**: Extend the application with additional functionality
5. **Deploy**: Use the same Docker setup for production deployment

## Production Deployment

For production, consider:

1. Using environment variables for sensitive data
2. Setting up SSL/TLS certificates
3. Configuring proper backup strategies
4. Setting up monitoring and logging
5. Using a reverse proxy (nginx) in front of Apache

## Support

If you encounter any issues:

1. Check the Docker logs: `docker-compose logs`
2. Verify all services are running: `docker-compose ps`
3. Check the README.md for detailed documentation
4. Review the configuration files for any misconfigurations
