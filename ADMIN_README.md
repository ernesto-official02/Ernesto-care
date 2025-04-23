# Ernesto Health Admin Panel

This document provides instructions on how to set up and use the admin panel for the Ernesto Health application.

## Setup Instructions

1. **Database Setup**
   - Make sure your XAMPP MySQL server is running
   - The admin panel will automatically create the necessary tables when you run the setup script

2. **Admin User Setup**
   - Navigate to `setup_admin.php` in your browser (e.g., http://localhost/ernesto-health/setup_admin.php)
   - This will create the admin user with the following credentials:
     - Username: `admin`
     - Password: `admin123`
   - **Important:** Change this password immediately after your first login for security reasons

3. **Accessing the Admin Panel**
   - Go to the login page (http://localhost/ernesto-health/login.php)
   - Click on the "Admin Login" link at the bottom of the login form
   - Enter your admin credentials

## Admin Panel Features

### Dashboard
- View all database tables
- Select a table to view its data
- Add, edit, or delete records (functionality to be implemented)

### Settings
- Change your admin password

## Security Considerations

1. **Password Security**
   - Always use a strong password
   - Change your password regularly
   - Never share your admin credentials

2. **Access Control**
   - The admin panel is protected by session-based authentication
   - Only users with valid admin credentials can access the panel

3. **Data Protection**
   - Be careful when modifying or deleting data
   - Consider backing up your database before making significant changes

## Troubleshooting

If you encounter any issues:

1. **Cannot access admin panel**
   - Make sure you're logged in with valid admin credentials
   - Check that your session hasn't expired
   - Try logging out and logging back in

2. **Database connection issues**
   - Verify that your XAMPP MySQL server is running
   - Check your database credentials in `config.php`

3. **Password reset**
   - If you've forgotten your admin password, you may need to reset it directly in the database
   - Contact the system administrator for assistance

## Future Enhancements

The following features are planned for future updates:

1. User management (add, edit, delete admin users)
2. Database backup and restore functionality
3. Activity logs to track admin actions
4. Enhanced data visualization and reporting
5. Role-based access control for different admin levels 