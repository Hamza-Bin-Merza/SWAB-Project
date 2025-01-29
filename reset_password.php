<?php
session_start();
include 'db_connection.php';
 
if (isset($_GET['token'])) {
    $reset_token = $_GET['token'];
 
    // Check if the reset token is valid and not expired
    $stmt = $con->prepare("SELECT * FROM users WHERE reset_token = ?");
    $stmt->bind_param("s", $reset_token);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
 
    if ($user && $user['reset_token_expiry'] > time()) {
        // Token is valid, allow user to reset password
        if (isset($_POST['new_password']) && isset($_POST['confirm_password'])) {
            $new_password = $_POST['new_password'];
            $confirm_password = $_POST['confirm_password'];
 
            if ($new_password == $confirm_password) {
                // Hash new password
                $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
 
                // Update password and reset token in the database
                $stmt = $con->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_token_expiry = NULL, first_time_login = 0 WHERE email = ?");
                $stmt->bind_param("ss", $hashed_password, $user['email']);
                $stmt->execute();
 
                echo "Password reset successful! You can now log in with your new password.";
            } else {
                echo "Passwords do not match.";
            }
        }
    } else {
        echo "Invalid or expired reset token.";
    }
}
?>
 
<form method="POST" action="">
    New Password: <input type="password" name="new_password" required><br>
    Confirm Password: <input type="password" name="confirm_password" required><br>
<button type="submit">Reset Password</button>
</form>