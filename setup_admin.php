<?php
require_once 'config.php';

// Check if admins table exists, if not create it
try {
    $pdo->query("SELECT 1 FROM admins LIMIT 1");
} catch(PDOException $e) {
    // Table doesn't exist, create it
    $pdo->exec("CREATE TABLE IF NOT EXISTS `admins` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `username` varchar(50) NOT NULL,
        `password` varchar(255) NOT NULL,
        `email` varchar(100) NOT NULL,
        `full_name` varchar(100) DEFAULT NULL,
        `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
        `last_login` timestamp NULL DEFAULT NULL,
        PRIMARY KEY (`id`),
        UNIQUE KEY `username` (`username`),
        UNIQUE KEY `email` (`email`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
    
    echo "Admins table created successfully.<br>";
}

// Default admin credentials
$username = 'admin';
$password = 'admin123'; // Default password
$email = 'admin@ernesto-health.com';
$full_name = 'System Administrator';

// Check if admin user exists
$stmt = $pdo->prepare("SELECT id FROM admins WHERE username = ?");
$stmt->execute([$username]);
$admin = $stmt->fetch();

if (!$admin) {
    // Create admin user
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    $stmt = $pdo->prepare("INSERT INTO admins (username, password, email, full_name) VALUES (?, ?, ?, ?)");
    $stmt->execute([$username, $hashed_password, $email, $full_name]);
    
    echo "Admin user created successfully.<br>";
    echo "Username: admin<br>";
    echo "Password: admin123<br>";
    echo "<strong>Please change this password after first login!</strong><br>";
} else {
    // Update admin password to ensure it's correct
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    $stmt = $pdo->prepare("UPDATE admins SET password = ? WHERE username = ?");
    $stmt->execute([$hashed_password, $username]);
    
    echo "Admin user already exists. Password has been reset.<br>";
    echo "Username: admin<br>";
    echo "Password: admin123<br>";
    echo "<strong>Please change this password after login!</strong><br>";
}

echo "<br><a href='admin_login.php'>Go to Admin Login</a>";
?> 