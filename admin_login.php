<?php
session_start();
require_once 'config.php';

// Check if admin is already logged in
if (isset($_SESSION['admin_id'])) {
    header("Location: admin_panel.php");
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    try {
        // Query to check if the admin exists
        $stmt = $pdo->prepare("SELECT id, username, password FROM admins WHERE username = ?");
        $stmt->execute([$username]);
        $admin = $stmt->fetch();

        if ($admin && password_verify($password, $admin['password'])) {
            // Update last login time if the column exists
            try {
                $update_stmt = $pdo->prepare("UPDATE admins SET last_login = CURRENT_TIMESTAMP WHERE id = ?");
                $update_stmt->execute([$admin['id']]);
            } catch(PDOException $e) {
                // Ignore error if last_login column doesn't exist
            }
            
            // Set session variables
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];
            
            // Set success message for popup
            $success = "Login successful! Redirecting to admin panel...";
            
            // Redirect after a short delay to show the popup
            header("refresh:2;url=admin_panel.php");
        } else {
            $error = "Invalid username or password";
        }
    } catch(PDOException $e) {
        $error = "Login failed: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Ernesto Health</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen-Sans, Ubuntu, Cantarell, 'Helvetica Neue', sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-image: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        }
        .admin-login-container {
            max-width: 400px;
            width: 90%;
            padding: 30px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }
        .admin-login-title {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
            font-size: 32px;
            font-weight: 600;
            text-transform: none;
        }
        .admin-form-group {
            margin-bottom: 20px;
        }
        .admin-form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #555;
            text-transform: none;
            font-size: 18px;
        }
        .admin-form-group input {
            width: 100%;
            padding: 14px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            box-sizing: border-box;
            font-family: inherit;
        }
        .admin-login-btn {
            width: 100%;
            padding: 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 18px;
            font-weight: 500;
            transition: background-color 0.3s;
            font-family: inherit;
            text-transform: none;
        }
        .admin-login-btn:hover {
            background-color: #45a049;
        }
        .admin-error {
            color: #f44336;
            margin-bottom: 20px;
            text-align: center;
            padding: 12px;
            background-color: #ffebee;
            border-radius: 5px;
            font-size: 16px;
        }
        .admin-success {
            color: #4CAF50;
            margin-bottom: 20px;
            text-align: center;
            padding: 12px;
            background-color: #e8f5e9;
            border-radius: 5px;
            font-size: 16px;
        }
        .back-to-home {
            text-align: center;
            margin-top: 25px;
            font-size: 16px;
        }
        .back-to-home a {
            color: #4CAF50;
            text-decoration: none;
            transition: color 0.3s;
        }
        .back-to-home a:hover {
            color: #45a049;
            text-decoration: underline;
        }
        .logo-container {
            text-align: center;
            margin-bottom: 20px;
        }
        .logo-container img {
            max-width: 150px;
            height: auto;
        }
    </style>
</head>
<body>
    <div class="admin-login-container">
        <div class="logo-container">
            <img src="assets/img/logo.png" alt="Ernesto Health Logo">
        </div>
        <h2 class="admin-login-title">Admin Login</h2>
        
        <?php if (!empty($error)): ?>
            <div class="admin-error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if (!empty($success)): ?>
            <div class="admin-success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="admin-form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required autofocus>
            </div>
            
            <div class="admin-form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <button type="submit" class="admin-login-btn">Login</button>
        </form>
        
        <div class="back-to-home">
            <a href="index.php"><i class="fas fa-arrow-left"></i> Back to Home</a>
        </div>
    </div>
    
    <script>
        // Show success popup if login was successful
        <?php if (!empty($success)): ?>
        setTimeout(function() {
            alert("Login successful! Welcome to the admin panel.");
        }, 500);
        <?php endif; ?>
    </script>
</body>
</html> 