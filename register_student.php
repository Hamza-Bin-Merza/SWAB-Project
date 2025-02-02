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
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $student_number = trim($_POST['student_number']);
    $course = trim($_POST['course']);
    $department = trim($_POST['department']);
    $password = trim($_POST['password']);

    // Input validation: prevent XSS & ensure only valid input
    if (!preg_match("/^[a-zA-Z\s]+$/", $name)) {
        $error_message = "Error: Name can only contain letters and spaces!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Error: Invalid email format!";
    } elseif (!preg_match("/^\d{8}$/", $phone)) {
        $error_message = "Error: Phone number must be exactly 8 digits!";
    } elseif (!preg_match("/^[a-zA-Z0-9]+$/", $student_number)) {
        $error_message = "Error: Student Number can only contain letters and numbers!";
    } elseif (strlen($password) < 6) {
        $error_message = "Error: Password must be at least 6 characters!";
    } elseif (!in_array($department, ["Engineering", "HSS", "IIT", "Applied Science", "Business", "Design"])) {
        $error_message = "Error: Invalid department selection!";
    } else {
        // Hash the password securely
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        // Secure SQL Query with Prepared Statement
        $sql = "INSERT INTO students (name, email, phone, student_number, course, department, password_hash) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";

        if ($stmt = $con->prepare($sql)) {
            $stmt->bind_param('sssssss', $name, $email, $phone, $student_number, $course, $department, $password_hash);
            if ($stmt->execute()) {
                $success_message = "Student profile created successfully!";
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
    <title>Student Registration</title>

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

        .already-account-box {
            display: inline-block;
            padding: 10px;
            border: 1px solid #664EAE;
            border-radius: 5px;
            background-color: #fff;
            text-align: center;
            margin-top: 10px;
            width: 15%;
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

<h2>Student Registration</h2>

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

        <label for="name">Name:</label>
        <input type="text" name="name" required>

        <label for="email">Email:</label>
        <input type="email" name="email" required>

        <label for="phone">Phone:</label>
        <input type="text" name="phone" required>

        <label for="student_number">Student Number:</label>
        <input type="text" name="student_number" required>

        <label for="course">Course:</label>
        <input type="text" name="course" required>

        <label for="department">Department:</label>
        <select name="department" required>
            <option value="Engineering">Engineering</option>
            <option value="HSS">HSS</option>
            <option value="IIT">IIT</option>
            <option value="Applied Science">Applied Science</option>
            <option value="Business">Business</option>
            <option value="Design">Design</option>
        </select>

        <label for="password">Password:</label>
        <input type="password" name="password" required>

        <button type="submit">Create Student</button>
    </form>
</div>

</body>
</html>
