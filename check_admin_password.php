<?php
require_once 'config.php';

try {
    // Get admin user
    $stmt = $pdo->query("SELECT id, username, password FROM admins WHERE username = 'admin'");
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($admin) {
        echo "<h2>Admin User Found:</h2>";
        echo "<p>ID: " . $admin['id'] . "</p>";
        echo "<p>Username: " . $admin['username'] . "</p>";
        echo "<p>Password Hash: " . $admin['password'] . "</p>";
        
        // Test password verification
        $test_password = 'admin123'; // Default password from setup_admin.php
        $is_valid = password_verify($test_password, $admin['password']);
        
        echo "<p>Password 'admin123' is " . ($is_valid ? "valid" : "invalid") . "</p>";
        
        if (!$is_valid) {
            echo "<p>Would you like to reset the password to 'admin123'? <a href='reset_admin_password.php'>Yes, reset password</a></p>";
        }
    } else {
        echo "<h2>Admin User Not Found</h2>";
        echo "<p>Would you like to create an admin user? <a href='setup_admin.php'>Yes, create admin</a></p>";
    }
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?> 