<?php
require_once 'config.php';

try {
    // Get table structure
    $stmt = $pdo->query("DESCRIBE admins");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h2>Admins Table Structure:</h2>";
    echo "<pre>";
    print_r($columns);
    echo "</pre>";
    
    // Check if there are any admin users
    $stmt = $pdo->query("SELECT id, username, email FROM admins");
    $admins = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h2>Admin Users:</h2>";
    echo "<pre>";
    print_r($admins);
    echo "</pre>";
    
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?> 