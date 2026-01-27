# Event Planner

A modern, full-stack web application built for a college web development course project. Event Planner enables users to create, manage, and discover events of all types - from conferences and workshops to concerts and gatherings.

![License](https://img.shields.io/badge/license-MIT-blue.svg)
![PHP](https://img.shields.io/badge/PHP-7.4%2B-blue.svg)
![MySQL](https://img.shields.io/badge/MySQL-8.0-orange.svg)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3.3-purple.svg)

## 📝 Project Background

This project was developed as the final assignment for a college **Web Development** course. The goal was to build a complete, production-ready web application demonstrating proficiency in:

- Full-stack PHP development
- Database design and management
- RESTful API architecture
- User authentication and authorization
- Responsive frontend design
- Containerization with Docker

The application features role-based access control, real-time analytics, and a complete event management lifecycle - from creation to attendee management.

## ✨ Features

### 🎯 Event Management
- **Create Events**: Organizers can create events with title, description, date, location, capacity, and custom images
- **Edit & Delete**: Full CRUD operations for event management
- **Event Browsing**: Public listing with search and filter functionality
- **Registration System**: Attendees can register for events with automatic capacity tracking
- **Dynamic Carousel**: Homepage carousel automatically populates with event images

### 👤 User Features
- **Authentication**: Secure login, logout, and registration system
- **Remember Me**: Persistent login with secure cookie-based authentication
- **Profile Management**: Users can update their profile and avatar
- **Role-Based Access**: Three user roles:
  - **Organizers**: Create and manage their own events
  - **Attendees**: Browse and register for events
  - **Admins**: Manage users, events, and access audit logs

### 📊 Analytics & Dashboard
- **Real-Time Statistics**: Animated counters showing events, attendees, and platform visits
- **Event Analytics**: Organizers can track registrations and attendee data
- **Visitor Tracking**: Basic analytics system tracking page visits and session data
- **Export Data**: Download attendee lists as CSV files

### 🔔 Communication
- **Contact Forms**: Integrated contact page with email notifications
- **PHPMailer Integration**: Professional email delivery using PHPMailer library
- **Newsletter Subscription**: Optional newsletter signup in contact form

### 🛡️ Security Features
- **Password Hashing**: Secure password storage using PHP's `password_hash()`
- **Session Management**: Secure session handling with regeneration
- **CSRF Protection**: Built-in protection against cross-site request forgery
- **SQL Injection Prevention**: Prepared statements throughout the application
- **Admin Audit Logs**: Complete audit trail of all admin actions on events

### 🎨 User Interface
- **Responsive Design**: Mobile-first, fully responsive interface
- **Modern UI**: Clean, professional design with gradient themes
- **Bootstrap 5.3**: Utilizing the latest Bootstrap framework
- **Font Awesome Icons**: Comprehensive icon set throughout the application
- **Animated Components**: Smooth animations and transitions for better UX

## 🏗️ Architecture

### Technology Stack

**Backend:**
- **PHP 7.4+** (Recommended: PHP 8.2 in Docker)
- **MySQL 8.0** - Relational database with full foreign key constraints
- **Apache** - Web server with .htaccess support

**Frontend:**
- **Bootstrap 5.3.3** - CSS framework for responsive design
- **Font Awesome 6.4.0** - Icon library
- **Vanilla JavaScript** - No frontend framework dependencies
- **Custom CSS** - Gradient themes and custom styling

**Third-Party Libraries:**
- **PHPMailer 6.9** - Email delivery via Composer

**Development & Deployment:**
- **Docker & Docker Compose** - Containerization
- **phpMyAdmin** - Database management UI
- **Docker Watch Plugin** - Live file synchronization during development

### Database Schema

The application uses a normalized database with 6 tables:

1. **users** - User accounts with role-based access
2. **events** - Event details linked to organizers
3. **registrations** - Attendee registrations with capacity tracking
4. **feedback** - Contact form submissions
5. **visits** - Analytics tracking for page visits
6. **event_audit_logs** - Admin action audit trail

### API Structure

RESTful API endpoints built with PHP:

- **`/api/auth.php`** - Authentication (login, register, logout, profile update, avatar upload)
- **`/api/events.php`** - Event CRUD operations and audit logs
- **`/api/users.php`** - User management (admin only)
- **`/api/registrations.php`** - Event registration management
- **`/api/feedback.php`** - Contact form submissions
- **`/api/visits.php`** - Visit tracking for analytics
- **`/api/stats.php`** - Real-time statistics
- **`/api/contact.php`** - Contact form email delivery

### File Structure

```
eventPlanner/
├── api/                 # Backend API endpoints
│   ├── auth.php         # Authentication & user management
│   ├── events.php       # Event CRUD operations
│   ├── users.php        # Admin user management
│   ├── registrations.php # Event registrations
│   ├── feedback.php     # Contact form handling
│   ├── visits.php       # Analytics tracking
│   ├── stats.php       # Statistics aggregation
│   ├── contact.php     # Email notifications
│   └── init.php        # Database connection & helpers
├── includes/           # Reusable components
│   ├── navbar.php      # Navigation bar
│   └── footer.php      # Footer component
├── admin/             # Admin panel pages
├── assets/            # Static files
│   ├── css/          # Custom stylesheets
│   └── images/       # Event images
├── config/           # Configuration files
├── docker/           # Docker configuration
├── uploads/          # User-uploaded content
├── index.php         # Landing page
├── login.php         # User login
├── register.php      # User registration
├── events.php        # Event listing
├── event.php         # Event detail page
├── my-events.php    # Organizer dashboard
├── dashboard.php     # Admin dashboard
├── profile.php       # User profile
├── about.php        # About page
└── contact.php      # Contact form
```

## 🚀 Getting Started

### Prerequisites

- **Docker Desktop 4.25+** or **Docker Compose v2.4+**
- **Docker running** on your system

### Quick Start (Docker - Recommended)

**Windows (PowerShell):**
```powershell
.\dev.ps1
```

**Linux/macOS (Bash):**
```bash
chmod +x dev.sh
./dev.sh
```

This will:
1. Start MySQL and phpMyAdmin containers
2. Initialize the database schema
3. Launch the application server
4. Enable live file synchronization

**Access Points:**
- **Application**: http://localhost:8080
- **phpMyAdmin**: http://localhost:8081
- **Database**: `event_planner` (accessible only within Docker network)

### Manual Setup (Without Docker)

1. **Install Dependencies:**
   ```bash
   composer install
   ```

2. **Configure Database:**
   - Import `schema.sql` into your MySQL database
   - Update database credentials in `config/db.php`

3. **Configure Email:**
   - Update SMTP settings in `config/env.php`
   - Set your email provider credentials

4. **Start Web Server:**
   ```bash
   php -S localhost:8000
   ```

5. **Access Application:**
   - Open http://localhost:8000 in your browser

## 📚 Development

### Docker Watch Plugin

The project includes Docker watch plugin for automatic file synchronization during development:

- Changes to PHP, CSS, JS files sync automatically
- No need to rebuild containers for code changes
- `.dockerignore` excludes unnecessary files
- Press `Ctrl+C` to stop the watch process

### Manual Docker Commands

```bash
# Start without watch
docker compose up -d

# Stop all services
docker compose down

# View logs
docker compose logs -f app

# Rebuild and start
docker compose up --build

# Access MySQL container
docker compose exec db mysql -u root -proot event_planner
```

## 🎓 Learning Outcomes

This project demonstrates mastery of:

1. **Full-Stack Development**: Complete application from database to frontend
2. **REST API Design**: Clean, maintainable API architecture
3. **Database Management**: Normalized schema with foreign keys and constraints
4. **Authentication & Authorization**: Role-based access control
5. **Security Best Practices**: Password hashing, prepared statements, CSRF protection
6. **Containerization**: Docker setup for development and deployment
7. **Responsive Design**: Mobile-first approach with modern CSS frameworks
8. **Integration**: Third-party libraries (PHPMailer, Bootstrap, Font Awesome)

## 📄 License

This project is open source and available under the **MIT License**. See the [LICENSE](LICENSE) file for details.

## 🤝 Contributing

Contributions are welcome! Since this was a college project, feel free to:
- Fork the repository
- Create a feature branch
- Make your improvements
- Submit a pull request

All contributors agree to license their contributions under the MIT License.

## 📧 Contact

For questions, feedback, or inquiries about the project:
- Open an issue on GitHub
- Use the contact form in the application (if deployed)

## 🙏 Acknowledgments

- **Bootstrap Team** - For the amazing CSS framework
- **Font Awesome** - For the comprehensive icon set
- **PHPMailer Team** - For reliable email delivery
- **Docker** - For containerization technology

---

**Built with ❤️ for a Web Development College Project**
