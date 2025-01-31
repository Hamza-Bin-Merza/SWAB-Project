<?php
session_start();
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare SQL query to fetch user
    $sql = "SELECT * FROM users WHERE username = ?";
    if ($stmt = $con->prepare($sql)) {
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();

            // Verify password
            if (password_verify($password, $user['password_hash'])) {
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                header("Location: dashboard.php");
                exit;
            } else {
                $error_message = "Invalid credentials!";
            }
        } else {
            $error_message = "User not found!";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Secure Robotic Course Management System</title>

    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #1E3C72;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .form-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #4CAF50;
        }

        .form-container label {
            font-size: 14px;
            color: #664EAE;
            display: block;
            margin-bottom: 8px;
        }

        .form-container input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #C4A8FF;
            border-radius: 4px;
            font-size: 14px;
        }

        .form-container button {
            width: 100%;
            padding: 12px;
            background-color: #664EAE;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .form-container button:hover {
            background-color: #1A0554;
        }

        .form-container a {
            text-align: center;
            display: block;
            margin-top: 15px;
            font-size: 14px;
            color: #664EAE;
            text-decoration: none;
        }

        .form-container a:hover {
            color: #1A0554;
        }

        .alert {
            color: red;
            text-align: center;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Login</h2>

    <!-- Display error message if credentials are wrong -->
    <?php if (isset($error_message)): ?>
        <div class="alert"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <!-- Login form -->
    <form method="POST">
        <label for="username">Username:</label>
        <input type="text" name="username" required>

        <label for="password">Password:</label>
        <input type="password" name="password" required>

        <button type="submit">Login</button>

        <!-- Forgot Password Link -->
        <a href="forgot_password.php">Forgot Password?</a>
    </form>

    <!-- Registration link -->
    <div style="text-align: center; margin-top: 20px;">
        <p>Need an account? <a href="register.php">Register here</a></p>
    </div>
</div>

</body>
</html>