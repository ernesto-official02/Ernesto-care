<?php
require_once 'config.php';

try {
    // Default admin credentials
    $username = 'admin';
    $password = 'admin123'; // Default password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Check if admin exists
    $stmt = $pdo->prepare("SELECT id FROM admins WHERE username = ?");
    $stmt->execute([$username]);
    $admin = $stmt->fetch();
    
    if ($admin) {
        // Update admin password
        $update_stmt = $pdo->prepare("UPDATE admins SET password = ? WHERE username = ?");
        $update_stmt->execute([$hashed_password, $username]);
        
        echo "<h2>Password Reset Successful</h2>";
        echo "<p>The admin password has been reset to: <strong>admin123</strong></p>";
        echo "<p>Please change this password after logging in.</p>";
    } else {
        // Create admin user
        $stmt = $pdo->prepare("INSERT INTO admins (username, password, email) VALUES (?, ?, ?)");
        $stmt->execute([$username, $hashed_password, 'admin@ernesto-health.com']);
        
        echo "<h2>Admin User Created</h2>";
        echo "<p>Username: <strong>admin</strong></p>";
        echo "<p>Password: <strong>admin123</strong></p>";
        echo "<p>Please change this password after logging in.</p>";
    }
    
    echo "<p><a href='admin_login.php'>Go to Admin Login</a></p>";
    
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?> 