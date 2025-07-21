
<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'rakth_setu');

// Create database connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set character set
$conn->set_charset("utf8mb4");

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Site configuration
define('SITE_NAME', 'Rakth Setu');
define('SITE_URL', 'http://localhost'); // Change this to your domain

// Error reporting (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

/**
 * Function to sanitize user input
 * @param string $data
 * @return string
 */
function sanitize($data) {
    global $conn;
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $conn->real_escape_string($data);
}

/**
 * Function to check if user is logged in
 * @return bool
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

/**
 * Function to redirect user
 * @param string $location
 */
function redirect($location) {
    header("Location: $location");
    exit;
}

/**
 * Function to set flash message
 * @param string $name
 * @param string $message
 * @param string $type
 */
function setFlashMessage($name, $message, $type = 'success') {
    if (!isset($_SESSION['flash_messages'])) {
        $_SESSION['flash_messages'] = array();
    }
    $_SESSION['flash_messages'][$name] = [
        'message' => $message,
        'type' => $type
    ];
}

/**
 * Function to display flash message
 * @param string $name
 * @return string|null
 */
function getFlashMessage($name) {
    if (isset($_SESSION['flash_messages'][$name])) {
        $flash_message = $_SESSION['flash_messages'][$name];
        unset($_SESSION['flash_messages'][$name]);
        return $flash_message;
    }
    return null;
}

/**
 * Function to generate CSRF token
 * @return string
 */
function generateCSRFToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Function to verify CSRF token
 * @param string $token
 * @return bool
 */
function verifyCSRFToken($token) {
    if (!isset($_SESSION['csrf_token']) || empty($token)) {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Function to check if a user is admin
 * @return bool
 */
function isAdmin() {
    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        return false;
    }
    
    // First check if user email is admin@rakthsetu.com
    if (isset($_SESSION['user_email']) && $_SESSION['user_email'] === 'admin@rakthsetu.com') {
        return true;
    }
    
    // You can also check for admin role in database
    global $conn;
    $user_id = $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT is_admin FROM users WHERE id = ?");
    
    // Check if the is_admin column exists in the users table
    $result = $conn->query("SHOW COLUMNS FROM users LIKE 'is_admin'");
    if ($result->num_rows > 0) {
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            return isset($user['is_admin']) && $user['is_admin'] == 1;
        }
    }
    
    return false;
}

/**
 * Function to get user data by ID
 * @param int $user_id
 * @return array|null
 */
function getUserById($user_id) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 1) {
        return $result->fetch_assoc();
    }
    return null;
}

/**
 * Function to send email
 * @param string $to
 * @param string $subject
 * @param string $message
 * @return bool
 */
function sendEmail($to, $subject, $message) {
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= 'From: ' . SITE_NAME . ' <noreply@rakthsetu.com>' . "\r\n";
    
    return mail($to, $subject, $message, $headers);
}
