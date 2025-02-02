<?php
session_start();
include 'db_connection.php';

// Generate CSRF Token if not set
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate CSRF Token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF token validation failed!");
    }

    // Sanitize and validate input
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $email = trim($_POST['email']);
    $role = trim($_POST['role']);

    // Input validation: prevent XSS & ensure only valid input
    if (!preg_match("/^[a-zA-Z0-9]+$/", $username)) {
        $error_message = "Error: Username can only contain letters and numbers!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Error: Invalid email format!";
    } elseif (strlen($password) < 6) {
        $error_message = "Error: Password must be at least 6 characters!";
    } elseif ($role !== "Admin" && $role !== "Faculty") {
        $error_message = "Error: Invalid role selection!";
    } else {
        // Hash the password
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        // Secure SQL Query with Prepared Statement
        $sql = "INSERT INTO users (username, password_hash, email, role) VALUES (?, ?, ?, ?)";

        if ($stmt = $con->prepare($sql)) {
            $stmt->bind_param('ssss', $username, $password_hash, $email, $role);
            if ($stmt->execute()) {
                // Redirect to login page after successful registration
                header("Location: login.php");
                exit;
            } else {
                $error_message = "Error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $error_message = "Error preparing statement: " . $con->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>

    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #1E3C72;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
            overflow-y: auto; /* Enable vertical scrolling */
        }

        h2 {
            text-align: center;
            margin-top: 40px;
            color: #1E3C72;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 32%;
            margin-left: auto;
            margin-right: auto;
        }

        .form-container {
            max-width: 600px;
            width: 100%;
            margin: 30px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .form-container label {
            font-size: 14px;
            color: #664EAE;
            display: block;
            margin-bottom: 8px;
        }

        .form-container input, .form-container select {
            width: 96%;
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

        .alert {
            padding: 10px;
            margin: 10px;
            border-radius: 4px;
        }

        .alert-success {
            background-color: #C4A8FF;
            color: #1A0554;
        }

        .alert-danger {
            background-color: #0C0B13;
            color: #C4A8FF;
        }

        .bottom-container {
            display: flex;
            justify-content: center;
            gap: 20px; /* Space between boxes */
            margin-top: 20px;
        }

        .already-account-box, .register-student-box {
            flex: 1;
            max-width: 300px;
            padding: 15px;
            border: 2px solid #664EAE;
            border-radius: 8px;
            background-color: #fff;
            text-align: center;
        }

        .student-button {
            display: block;
            margin-top: 10px;
            padding: 10px 20px;
            background-color: #664EAE;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }

        .student-button:hover {
            background-color: #1A0554;
        }



    </style>
</head>
<body>

<h2>Registration</h2>

<!-- Display Success or Error Message -->
<?php if ($success_message): ?>
    <div class="alert alert-success"><?php echo htmlspecialchars($success_message); ?></div>
<?php endif; ?>
<?php if ($error_message): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($error_message); ?></div>
<?php endif; ?>

<div class="form-container">
    <form method="POST">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">

        <label for="username">Username:</label>
        <input type="text" name="username" required>

        <label for="password">Password:</label>
        <input type="password" name="password" required>

        <label for="email">Email:</label>
        <input type="email" name="email" required>

        <label for="role">Role:</label>
        <select name="role" required>
            <option value="Admin">Admin</option>
            <option value="Faculty">Faculty</option>
        </select>

        <button type="submit">Register</button>
    </form>
</div>

<div class="bottom-container">
    <div class="already-account-box">
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>

    <div class="register-student-box">
        <p>Want to register as a student?</p>
        <a href="register_student.php" class="student-button">Register as Student</a>
    </div>
</div>



</body>
</html>
