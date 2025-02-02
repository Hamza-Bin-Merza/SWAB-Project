<?php
session_start();
include 'db_connection.php';

// Generate CSRF Token if not already set
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Security headers to prevent XSS & Clickjacking
header("X-XSS-Protection: 1; mode=block");
header("X-Content-Type-Options: nosniff");
header("Referrer-Policy: no-referrer");
header("Content-Security-Policy: default-src 'self'; script-src 'self'; style-src 'self'");

// Function to sanitize user input
function sanitize_input($input) {
    return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
}

// Flag to indicate success or error messages
$successMessage = "";
$errorMessage = "";

// Insert grade
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    // Validate CSRF Token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF token validation failed!");
    }

    // Sanitize user input
    $student_id = sanitize_input($_POST['student_id']);
    $course = sanitize_input($_POST['course']);
    $module = sanitize_input($_POST['module']);
    $grade = sanitize_input($_POST['grade']);
    $score = sanitize_input($_POST['score']);
    $date_recorded = sanitize_input($_POST['date_recorded']);
    $course_end_date = sanitize_input($_POST['course_end_date']);

    // Validate Student ID Format (Only Letters & Numbers)
    if (!preg_match("/^[a-zA-Z0-9]+$/", $student_id)) {
        $errorMessage = "Error: Invalid Student ID format. Only letters and numbers are allowed.";
    } 
    // Validate Score and Grade Compatibility
    else if (($grade == 'A' && ($score < 80 || $score > 100)) ||
        ($grade == 'B' && ($score < 70 || $score > 79)) ||
        ($grade == 'C' && ($score < 60 || $score > 69)) ||
        ($grade == 'D' && ($score < 50 || $score > 59)) ||
        ($grade == 'F' && $score >= 50)) {
        $errorMessage = "Error: Please ensure the score is within the valid range for the selected grade.";
    } 
    else {
        // Secure SQL Query with Prepared Statements
        $sql = "INSERT INTO student_grades (student_id, course, module, grade, score, date_recorded, course_end_date) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = $con->prepare($sql);
        $stmt->bind_param("ssssiss", $student_id, $course, $module, $grade, $score, $date_recorded, $course_end_date);

        if ($stmt->execute()) {
            $successMessage = "Grade record created successfully!";
        } else {
            $errorMessage = "Error: " . $stmt->error;
        }

        $stmt->close(); // Close the statement
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grade Submission Form</title>
    <meta http-equiv="Content-Security-Policy" content="default-src 'self'; script-src 'self'; style-src 'self'">

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #1e3c72;
            color: white;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            flex-direction: column;
            text-align: center;
            position: relative;
            overflow-y: auto;
        }

        .logo {
            position: absolute;
            top: 20px;
            right: 20px;
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
        }

        .form-container {
            background-color: rgba(255, 255, 255, 0.1);
            padding: 30px;
            border-radius: 10px;
            width: 400px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(10px);
            margin-top: 50px;
        }

        .form-container h2 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 28px;
            font-weight: 600;
        }

        .form-container label {
            font-weight: 500;
            margin-bottom: 8px;
            display: block;
            color: #f0f4f8;
            text-align: left;
        }

        .form-container input[type="text"], 
        .form-container input[type="date"], 
        .form-container select, 
        .form-container input[type="submit"], 
        .link-button {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border-radius: 6px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }

        .form-container input[type="text"], 
        .form-container input[type="date"],
        .form-container select {
            background-color: #f8f9fa;
            color: #333;
        }

        .form-container input[type="submit"] {
            background-color: #664eae;
            color: white;
            border: none;
            font-size: 16px;
            cursor: pointer;
        }

        .form-container input[type="submit"]:hover {
            background-color: #5a42a3;
        }

        .success-message, .error-message {
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            font-weight: bold;
            text-align: center;
        }

        .success-message {
            background-color: #4CAF50;
            color: white;
        }

        .error-message {
            background-color: #f44336;
            color: white;
        }

        .link-button {
            background-color: #007bff;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            text-align: center;
            display: block;
            margin-top: 20px;
            text-decoration: none;
        }

        .link-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<img src="capy.png" alt="Logo" class="logo" />

<div class="form-container">
    <h2>Submit Grade</h2>

    <?php if ($successMessage): ?>
        <div class="success-message"><?= htmlspecialchars($successMessage, ENT_QUOTES, 'UTF-8'); ?></div>
    <?php endif; ?>

    <?php if ($errorMessage): ?>
        <div class="error-message"><?= htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8'); ?></div>
    <?php endif; ?>

    <form method="POST">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">

        <label for="student_id">Student ID:</label>
        <input type="text" id="student_id" name="student_id" required><br>

        <label for="score">Score:</label>
        <input type="text" id="score" name="score" required><br>

        <input type="submit" name="submit" value="Submit Grade">
    </form>

    <a href="read_grades.php" class="link-button">View Student Grades</a>
</div>

</body>
</html>
