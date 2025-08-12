# Event Planner

A modern, responsive event planning website built with PHP, HTML, CSS, jQuery, and Bootstrap. Features a clean design, user-friendly interface, and comprehensive event management capabilities.

## Features

- 🎯 **Modern Design**: Clean, responsive design with Bootstrap 5
- 📱 **Mobile Friendly**: Optimized for all devices
- 🎨 **Beautiful UI**: Custom CSS with smooth animations
- 🔍 **Event Search**: Search and filter events
- 📧 **Contact Form**: AJAX-powered contact form with validation
- 🗄️ **Database Ready**: Complete database schema with MySQL
- 🐳 **Docker Support**: Easy deployment with Docker and Docker Compose
- 🔒 **Security**: CSRF protection, input validation, and sanitization
- 📊 **Analytics Ready**: Built-in statistics and tracking

## Technology Stack

- **Backend**: PHP 8.2
- **Frontend**: HTML5, CSS3, JavaScript, jQuery
- **Framework**: Bootstrap 5.3
- **Database**: MySQL 8.0
- **Web Server**: Apache
- **Containerization**: Docker & Docker Compose
- **Icons**: Font Awesome 6.4

## Project Structure

```
eventPlanner/
├── assets/
│   ├── css/
│   │   └── style.css
│   ├── js/
│   │   └── main.js
│   └── images/
├── config/
│   ├── config.php
│   └── database.php
├── docker/
│   ├── apache.conf
│   └── init.sql
├── includes/
│   ├── header.php
│   └── footer.php
├── logs/
├── uploads/
├── pages/
├── .gitignore
├── Dockerfile
├── docker-compose.yml
├── README.md
├── index.php
├── events.php
├── about.php
├── contact.php
└── process_contact.php
```

## Quick Start with Docker

### Prerequisites

- Docker
- Docker Compose

### Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd eventPlanner
   ```

2. **Start the application**
   ```bash
   docker-compose up -d
   ```

3. **Access the application**
   - Website: http://localhost:8080
   - phpMyAdmin: http://localhost:8081
     - Username: `eventplanner`
     - Password: `eventplanner123`

### Docker Services

- **App**: PHP application on port 8080
- **Database**: MySQL on port 3306
- **phpMyAdmin**: Database management on port 8081

## Manual Installation

### Prerequisites

- PHP 8.0 or higher
- MySQL 8.0 or higher
- Apache web server
- Composer (optional)

### Installation Steps

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd eventPlanner
   ```

2. **Set up the database**
   ```bash
   mysql -u root -p
   CREATE DATABASE event_planner;
   USE event_planner;
   SOURCE docker/init.sql;
   ```

3. **Configure the application**
   - Copy `config/config.php.example` to `config/config.php`
   - Update database credentials in `config/database.php`
   - Set proper permissions for `logs/` and `uploads/` directories

4. **Set up web server**
   - Point your web server to the project directory
   - Ensure Apache mod_rewrite is enabled
   - Set proper file permissions

## Configuration

### Environment Variables

The application uses environment variables for configuration:

```bash
# Database
DB_HOST=localhost
DB_NAME=event_planner
DB_USER=root
DB_PASS=

# Application
APP_ENV=development
APP_URL=http://localhost
DISPLAY_ERRORS=1
HTTPS_ENABLED=0
```

### Database Configuration

Update the database settings in `config/database.php`:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'event_planner');
define('DB_USER', 'your_username');
define('DB_PASS', 'your_password');
```

## Features in Detail

### Event Management
- Create and manage events
- Event categories and filtering
- Attendee registration
- Event status tracking

### User Interface
- Responsive design
- Modern animations
- Interactive elements
- Search functionality

### Security Features
- CSRF protection
- Input validation and sanitization
- SQL injection prevention
- XSS protection

### Contact System
- AJAX-powered contact form
- Email notifications
- Message management
- Newsletter subscription

## Development

### Adding New Pages

1. Create a new PHP file in the root directory
2. Include the header and footer:
   ```php
   <?php
   $page_title = 'Page Title';
   require_once 'includes/header.php';
   ?>
   
   <!-- Your content here -->
   
   <?php require_once 'includes/footer.php'; ?>
   ```

### Customizing Styles

- Main styles: `assets/css/style.css`
- Bootstrap customization: Override Bootstrap variables in CSS
- Responsive design: Use Bootstrap's grid system

### Adding JavaScript

- Main JavaScript: `assets/js/main.js`
- Page-specific scripts: Use `$page_scripts` variable in PHP files

## Database Schema

The application includes a complete database schema with the following tables:

- `users` - User accounts and profiles
- `events` - Event information and details
- `event_attendees` - Event registration and attendance
- `contact_messages` - Contact form submissions
- `event_categories` - Event categories and types
- `settings` - Application configuration

## API Endpoints

The application includes several API endpoints for AJAX functionality:

- `POST /process_contact.php` - Process contact form submissions
- Additional endpoints can be added in the `pages/` directory

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test thoroughly
5. Submit a pull request

## License

This project is licensed under the MIT License - see the LICENSE file for details.

## Support

For support and questions:
- Email: info@eventplanner.com
- Create an issue on GitHub
- Check the documentation

## Changelog

### Version 1.0.0
- Initial release
- Basic event management
- Contact form
- Responsive design
- Docker support

## Roadmap

- [ ] User authentication system
- [ ] Event creation and management
- [ ] Payment integration
- [ ] Email notifications
- [ ] Advanced search and filtering
- [ ] Event analytics
- [ ] Mobile app
- [ ] API for third-party integrations
