# Ernesto Health - Hospital Website

A responsive hospital website with user authentication, appointment booking, and contact forms.

## Features

- User registration and login system
- Appointment booking functionality
- Contact form with database storage
- Responsive design for all devices
- Interactive UI elements

## Setup Instructions

### Prerequisites

- XAMPP (or any PHP server with MySQL)
- Web browser

### Installation Steps

1. **Start XAMPP**
   - Open XAMPP Control Panel
   - Start Apache and MySQL services

2. **Database Setup**
   - Open phpMyAdmin (http://localhost/phpmyadmin)
   - Create a new database named `ernesto_health`
   - Import the `database.sql` file or run the following SQL queries:

```sql
-- Create the database if it doesn't exist
CREATE DATABASE IF NOT EXISTS ernesto_health;
USE ernesto_health;

-- Create users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create appointments table
CREATE TABLE IF NOT EXISTS appointments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    patient_name VARCHAR(100) NOT NULL,
    patient_email VARCHAR(100) NOT NULL,
    patient_phone VARCHAR(20) NOT NULL,
    appointment_date DATE NOT NULL,
    appointment_time TIME NOT NULL,
    doctor VARCHAR(100) NOT NULL,
    reason TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Create contact_messages table
CREATE TABLE IF NOT EXISTS contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

3. **Project Setup**
   - Place all project files in the `htdocs` folder of XAMPP
   - The path should be: `C:\xampp\htdocs\ernesto-health\`

4. **Access the Website**
   - Open your browser and go to: `http://localhost/ernesto-health`

## File Structure

- `index.php` - Main website file with all forms and content
- `config.php` - Database connection configuration
- `logout.php` - Handles user logout
- `style.css` - CSS styles for the website
- `main.js` - JavaScript functionality
- `database.sql` - SQL queries for database setup

## Security Features

- Passwords are hashed using PHP's secure password_hash function
- SQL injection prevention using prepared statements
- Session management for logged-in users
- Email uniqueness check during registration
- Password confirmation check during registration

## Troubleshooting

- If you encounter database connection issues, check your MySQL credentials in `config.php`
- Make sure XAMPP services are running before accessing the website
- Check file permissions if you encounter any file access issues

**Screen shots**

![Screenshot 2025-04-23 103927](https://github.com/user-attachments/assets/17c42727-a53a-4209-bd5a-50923f151fb4)

![Screenshot 2025-04-23 105413](https://github.com/user-attachments/assets/bbc7a03f-8aee-442d-a924-9a304bc96e7b)

![Screenshot 2025-04-23 105610](https://github.com/user-attachments/assets/38023424-30aa-4ccb-8240-ed4b3861fa35)

![Screenshot 2025-04-23 105749](https://github.com/user-attachments/assets/60a106b0-afe0-45fa-9c3a-0ed8416d76cf)

**Admin Panel**
![Screenshot 2025-04-23 105855](https://github.com/user-attachments/assets/5fa824b9-49d7-4999-a016-13a90e9c42d3)





