<?php
session_start();

// Function to check if the user is logged in and has the appropriate role
function check_authentication($required_role = null) {
    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php'); // Redirect to login page
        exit();
    }

    // Check if the user has the correct role (optional)
    if ($required_role && $_SESSION['role'] !== $required_role) {
        echo "You do not have permission to access this page.";
        exit();
    }
}

// Function to generate a CSRF token and store it in the session
function generate_csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Generate a random token
    }
    return $_SESSION['csrf_token'];
}

// Function to validate CSRF token from form submission
function validate_csrf_token($token) {
    if (!isset($_SESSION['csrf_token']) || $token !== $_SESSION['csrf_token']) {
        echo "Invalid CSRF token.";
        exit(); // If CSRF token is invalid, stop the form submission
    }
}

// Function to sanitize input to prevent XSS attacks
function sanitize_input($input) {
    return htmlspecialchars($input, ENT_QUOTES, 'UTF-8'); // Escape special characters to prevent XSS
}

// Function to hash passwords securely before storing in the database
function hash_password($password) {
    return password_hash($password, PASSWORD_DEFAULT); // Hash the password using bcrypt
}

// Function to verify password against the hashed value
function verify_password($password, $hashed_password) {
    return password_verify($password, $hashed_password); // Verify the password with the hash
}

// Function to handle errors securely
function handle_error($error_message) {
    // You can log the detailed error to a file or database for internal use
    error_log($error_message, 3, 'error_log.txt'); // Example: Log error message

    // Show a generic message to the user to avoid revealing system details
    echo "An error occurred. Please try again later.";
    exit(); // Stop script execution
}

// Function to prevent SQL injection by using prepared statements
function prepare_sql_statement($conn, $query, $types, ...$params) {
    $stmt = $conn->prepare($query);
    if ($stmt === false) {
        handle_error('Error preparing SQL statement');
    }
    $stmt->bind_param($types, ...$params);
    return $stmt;
}
?>
