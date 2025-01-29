<?php
session_start();
include 'db_connection.php';
 
if (isset($_POST['email'])) {
    $email = $_POST['email'];
 
    // Check if email exists in the database
    $stmt = $con->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
 
    if ($user) {
        // Generate reset token and expiration time (1 hour)
        $reset_token = bin2hex(random_bytes(16)); // Generate a secure token
        $reset_token_expiry = time() + 3600; // 1 hour from now
 
        // Store the token and expiry in the database
        $stmt = $con->prepare("UPDATE users SET reset_token = ?, reset_token_expiry = ? WHERE email = ?");
        $stmt->bind_param("sis", $reset_token, $reset_token_expiry, $email);
        $stmt->execute();
 
        // Send reset link to user's email
        $reset_link = "http://localhost/ASSIGNMENT/reset_password.php?token=" . $reset_token;
        mail($email, "Password Reset", "Click the following link to reset your password: $reset_link");
 
        echo "Password reset link has been sent to your email.";
    } else {
        echo "Email not registered.";
    }
}
?>
 
<form method="POST" action="">
    Enter your registered email address: <input type="email" name="email" required><br>
    <button type="submit">Send Password Reset Link</button>
</form>