<?php
session_start();
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        $stmt = $pdo->prepare("SELECT id, full_name, password FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['full_name'] = $user['full_name'];
            echo json_encode(['status' => 'success', 'message' => 'Login successful']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid email or password']);
        }
    } catch(PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Login failed: ' . $e->getMessage()]);
    }
}
?> 

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Ernesto Health</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .admin-login-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #4CAF50;
            text-decoration: none;
            font-weight: 600;
        }
        .admin-login-link:hover {
            text-decoration: underline;
        }
        .login-buttons {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        .login-btn, .admin-btn {
            flex: 1;
            padding: 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            text-align: center;
            text-decoration: none;
        }
        .login-btn {
            background-color: #4CAF50;
            color: white;
        }
        .login-btn:hover {
            background-color: #45a049;
        }
        .admin-btn {
            background-color: #2196F3;
            color: white;
        }
        .admin-btn:hover {
            background-color: #0b7dda;
        }
        .form-divider {
            display: flex;
            align-items: center;
            margin: 20px 0;
            color: #666;
        }
        .form-divider::before, .form-divider::after {
            content: "";
            flex: 1;
            border-bottom: 1px solid #ddd;
        }
        .form-divider span {
            padding: 0 10px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        
        <div class="login-buttons">
            <button type="button" class="login-btn" id="userLoginBtn">User Login</button>
            <a href="admin_login.php" class="admin-btn"><i class="fas fa-user-shield"></i> Admin Login</a>
        </div>
        
        <div class="form-divider">
            <span>or</span>
        </div>
        
        <form id="loginForm" method="POST" action="">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="login-btn">Login</button>
        </form>
        <div id="loginMessage"></div>
        <p class="register-link">Don't have an account? <a href="register.php">Register</a></p>
    </div>

    <script>
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch('login.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                const messageDiv = document.getElementById('loginMessage');
                messageDiv.textContent = data.message;
                
                if (data.status === 'success') {
                    messageDiv.style.color = 'green';
                    setTimeout(() => {
                        window.location.href = 'index.php';
                    }, 1000);
                } else {
                    messageDiv.style.color = 'red';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('loginMessage').textContent = 'An error occurred. Please try again.';
                document.getElementById('loginMessage').style.color = 'red';
            });
        });
    </script>
</body>
</html> 