<?php
// Start the session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Unset all session variables
$_SESSION = array();

// Destroy the session cookie
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time()-3600, '/');
}

// Destroy the session
session_destroy();

// Clear any other cookies that might be set
$cookies = array('user_id', 'username', 'remember_me', 'session_expired');
foreach ($cookies as $cookie) {
    if (isset($_COOKIE[$cookie])) {
        setcookie($cookie, '', time()-3600, '/');
    }
}

// Ensure all output buffers are cleared
while (ob_get_level()) {
    ob_end_clean();
}

// Redirect to the original index page with a parameter to indicate logout
header("Location: index.php?logout=success");
exit();
?> 