# PHP E-Commerce Application

A complete e-commerce solution with customer portal, admin dashboard, and POS system built with PHP.

## Features

### Customer Portal
- User registration and authentication
- Product browsing and searching
- Shopping cart functionality
- Order placement and tracking
- User profile management

### Admin Dashboard
- Product management (add, edit, delete)
- Category management
- Order management
- Customer management
- Sales reports and analytics

### POS System
- Point of Sale interface for physical stores
- Quick product search and checkout
- Session management for staff
- Receipt generation
- Sales reports

## Project Structure

```
ecommerce/
├── app/
│   ├── controllers/      # Application controllers
│   ├── models/           # Database models
│   ├── views/            # UI templates
│   │   ├── admin/        # Admin dashboard views
│   │   ├── customer/     # Customer portal views
│   │   └── pos/          # POS system views
│   └── helpers.php       # Helper functions
├── assets/
│   ├── css/              # CSS files
│   ├── js/               # JavaScript files
│   └── images/           # Image files
├── config/
│   ├── config.php        # Application configuration
│   └── database.php      # Database connection
├── database/
│   └── ecommerce_db.sql  # Database schema
└── public/
    └── index.php         # Entry point
```

## Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Web server (Apache/Nginx)

## Installation

1. Clone or download the repository to your web server directory
2. Create a MySQL database named `ecommerce_db`
3. Import the database schema from `database/ecommerce_db.sql`
4. Configure the database connection in `config/config.php`
5. Set your web server document root to the `public` directory
6. Access the application through your web browser

## Default Credentials

### Admin
- Username: admin
- Password: password

## Configuration

You can modify the application settings in the `config/config.php` file:

- Database connection details
- Base URL
- Error reporting
- Time zone

## License

This project is licensed under the MIT License.

## Credits

Developed by [Your Name]
