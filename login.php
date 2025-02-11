<?php
session_start();
include 'db_connection.php';

// Generate CSRF Token if not set
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate CSRF Token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF token validation failed!");
    }

    // Sanitize and validate input
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Input validation: prevent XSS & ensure only valid input
    if (!preg_match("/^[a-zA-Z0-9@.]+$/", $username)) {
        $error_message = "Error: Invalid username format!";
    } elseif (strlen($password) < 6) {
        $error_message = "Error: Password must be at least 6 characters!";
    } else {
        // First, check in the `users` table (Admin/Faculty)
        $sql = "SELECT * FROM users WHERE username = ?";
        if ($stmt = $con->prepare($sql)) {
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows == 1) {
                $user = $result->fetch_assoc();
                if (password_verify($password, $user['password_hash'])) {
                    // Store user session securely
                    $_SESSION['user_id'] = htmlspecialchars($user['user_id']);
                    $_SESSION['username'] = htmlspecialchars($user['username']);
                    $_SESSION['role'] = htmlspecialchars($user['role']); // Admin or Faculty

                    // Redirect to Dashboard
                    header("Location: dashboard.php");
                    exit;
                }
            }
            $stmt->close();
        }

        // If not found in `users`, check `students` table
        $sql = "SELECT * FROM students WHERE email = ?";
        if ($stmt = $con->prepare($sql)) {
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows == 1) {
                $student = $result->fetch_assoc();
                if (password_verify($password, $student['password_hash'])) {
                    // Store student session securely
                    $_SESSION['user_id'] = htmlspecialchars($student['student_id']);
                    $_SESSION['username'] = htmlspecialchars($student['name']);
                    $_SESSION['role'] = 'Student';

                    // Redirect to Dashboard
                    header("Location: dashboard.php");
                    exit;
                }
            }
            $stmt->close();
        }

        // Invalid credentials
        $error_message = "Invalid username or password!";
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
            color: #664EAE;
        }

        .form-container label {
            font-size: 14px;
            color: #664EAE;
            display: block;
            margin-bottom: 8px;
        }

        .form-container input {
            width: 94%;
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
        <div class="alert"><?php echo htmlspecialchars($error_message); ?></div>
    <?php endif; ?>

    <!-- Login form -->
    <form method="POST">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">

        <label for="username">Email/Username:</label>
        <input type="text" name="username" required>

        <label for="password">Password:</label>
        <input type="password" name="password" required>

        <button type="submit">Login</button>

    </form>
</div>

</body>
</html>
