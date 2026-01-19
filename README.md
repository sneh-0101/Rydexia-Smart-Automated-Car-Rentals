# Rydexia Smart Automated Car Rentals System

A complete PHP-based car rental management system with user and admin panels.

## Features

### User Features
- ✅ User Registration & Login
- ✅ Browse Available Cars
- ✅ Car Booking System
- ✅ View Booking History
- ✅ User Dashboard with Profile Information
- ✅ Booking Status Tracking

### Admin Features
- ✅ Admin Dashboard with Statistics
- ✅ Car Fleet Management (CRUD Operations)
- ✅ Booking Management & Approval System
- ✅ Revenue Tracking
- ✅ Quick Statistics Overview

## System Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache Server with mod_rewrite
- Bootstrap 5.3.0 (CDN included)

## Installation Steps

### 1. Extract Files
Extract the project files to your `htdocs` folder:
```
C:\xampp\htdocs\Rydexia-Smart-Automated-Car-Rentals\
```

### 2. Create Database
1. Open phpMyAdmin (http://localhost/phpmyadmin)
2. Create a new database: `rydexia_db`
3. Import the SQL file: `database/rydexia.sql`

### 3. Configure Database Connection
Edit `includes/db.php` if needed:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'rydexia_db');
```

### 4. Access the Application
- **User Site**: http://localhost/Rydexia-Smart-Automated-Car-Rentals/
- **Admin Panel**: http://localhost/Rydexia-Smart-Automated-Car-Rentals/admin/

## Default Admin Credentials
You'll need to insert an admin account into the database. Use phpMyAdmin to insert:

```sql
INSERT INTO admin (username, email, password, role) 
VALUES ('admin', 'admin@rydexia.com', '$2y$10$...', 'admin');
```

Or use this PHP to generate a password hash:
```php
<?php
$password = 'admin123';
echo password_hash($password, PASSWORD_BCRYPT);
?>
```

## Directory Structure

```
Rydexia-Smart-Automated-Car-Rentals/
├── admin/                      # Admin panel
│   ├── index.php
│   ├── login.php
│   ├── dashboard.php
│   ├── manage-cars.php
│   ├── add-car.php
│   ├── edit-car.php
│   ├── manage-bookings.php
│   └── logout.php
├── assets/
│   └── css/
│       └── style.css           # Main stylesheet
├── database/
│   └── rydexia.sql             # Database schema
├── includes/
│   ├── db.php                  # Database connection
│   └── functions.php           # Helper functions
├── index.php                   # Homepage
├── login.php                   # User login
├── register.php                # User registration
├── cars.php                    # Car listing
├── booking.php                 # Booking form
├── booking-details.php         # Booking details
├── user-dashboard.php          # User dashboard
├── logout.php                  # Logout
└── README.md                   # This file
```

## Database Tables

- **users** - User account information
- **admin** - Admin account information
- **cars** - Car fleet details
- **bookings** - Booking records
- **rental_records** - Rental history
- **payments** - Payment information

## Key Features Implemented

### Security
- Password hashing using bcrypt
- SQL injection prevention with prepared statements
- Session-based authentication
- Input sanitization

### User Experience
- Responsive design (mobile-friendly)
- Modern UI with Bootstrap 5
- Smooth transitions and animations
- Real-time booking calculations
- Status badges for bookings and cars

### Admin Management
- Dashboard statistics
- CRUD operations for cars
- Booking approval workflow
- Revenue tracking
- Quick action cards

## Usage Guide

### For Users
1. Register with email and driver's license number
2. Login to access the system
3. Browse available cars
4. Select dates and book a car
5. Wait for admin approval
6. View booking history in dashboard

### For Admin
1. Login to admin panel
2. View dashboard statistics
3. Manage car fleet (add/edit/delete)
4. Review and approve bookings
5. Track revenue and metrics

## API-like Functions

Helper functions in `includes/functions.php`:
- `isUserLoggedIn()` - Check user login status
- `isAdminLoggedIn()` - Check admin login status
- `hashPassword()` - Hash password securely
- `verifyPassword()` - Verify password hash
- `sanitize()` - Sanitize user input
- `formatCurrency()` - Format currency values
- `formatDate()` - Format date display
- `calculateDays()` - Calculate rental days

## Customization

### Change Color Theme
Edit CSS variables in `assets/css/style.css`:
```css
:root {
    --primary-color: #003366;
    --secondary-color: #0066cc;
    --accent-color: #ff6b35;
}
```

### Add More Car Features
Extend the cars table in database with additional columns for:
- Insurance options
- Mileage limits
- Condition status
- GPS/Additional features

## Troubleshooting

### Issue: Database Connection Failed
- Check MySQL is running
- Verify credentials in `includes/db.php`
- Ensure database exists

### Issue: Session Not Working
- Enable PHP sessions in php.ini
- Check file permissions on session directory

### Issue: Images Not Displaying
- Use full URLs for car images
- Or create an uploads folder

## Future Enhancements

- Payment gateway integration
- Email notifications
- SMS alerts
- Advanced reporting
- Multi-language support
- Rating and review system
- Insurance options
- GPS tracking

## Support

For issues or questions, contact: support@rydexia.com

## License

This project is proprietary software. All rights reserved.

---

**Version**: 1.0.0  
**Last Updated**: January 2026
