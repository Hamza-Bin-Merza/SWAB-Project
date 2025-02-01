<?php
include 'db_connection.php';

$success_message = '';
$error_message = '';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    // Hash the password
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // Prepare SQL query
    $sql = "INSERT INTO users (username, password_hash, email, role)
            VALUES (?, ?, ?, ?)";

    if ($stmt = $con->prepare($sql)) {
        $stmt->bind_param('ssss', $username, $password_hash, $email, $role);
        if ($stmt->execute()) {
            // Redirect to the login page after successful registration
            header("Location: login.php");
            exit; // Stop further execution
        } else {
            $error_message = "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $error_message = "Error preparing statement: " . $con->error;
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
            justify-content: center;
            align-items: center;
            height: 100vh;
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

        .already-account-box {
            display: inline-block;
            padding: 15px;
            border: 2px solid #664EAE;
            border-radius: 8px;
            background-color: #fff;
            text-align: center;
            margin-top: 20px;
            width: 15%;  /* Matches form width */
            margin-left: auto;
            margin-right: auto;
        }

        .already-account-box a {
            text-decoration: none;
            color: #664EAE;
            font-weight: bold;
            transition: color 0.3s ease;
        }

        .already-account-box a:hover {
            color: #1A0554;
        }
    </style>
</head>
<body>

<h2>Register here</h2>

<!-- Display Success or Error Message -->
<?php if ($success_message): ?>
    <div class="alert alert-success"><?php echo $success_message; ?></div>
<?php endif; ?>
<?php if ($error_message): ?>
    <div class="alert alert-danger"><?php echo $error_message; ?></div>
<?php endif; ?>

<div class="form-container">
    <form method="POST">
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

<!-- "Already have an account?" link to login page -->
<div class="already-account-box">
    <p>Already have an account? <a href="login.php">Login here</a></p>
</div>

</body>
</html>