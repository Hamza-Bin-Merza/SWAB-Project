<link rel="stylesheet" href="create_g.css">
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

        <label for="course">Course:</label>
        <input type="text" id="course" name="course" required><br>

        <label for="module">Module:</label>
        <input type="text" id="module" name="module" required><br>

        <label for="grade">Grade:</label>
        <select id="grade" name="grade" required>
            <option value="A">A</option>
            <option value="B">B</option>
            <option value="C">C</option>
            <option value="D">D</option>
            <option value="F">F</option>
        </select><br>

        <label for="score">Score:</label>
        <input type="text" id="score" name="score" required><br>

        <label for="date_recorded">Date Recorded:</label>
        <input type="date" id="date_recorded" name="date_recorded" required><br>

        <label for="course_end_date">Course End Date:</label>
        <input type="date" id="course_end_date" name="course_end_date" required><br>

        <input type="submit" name="submit" value="Submit Grade">
    </form>

    <a href="read_grades.php" class="link-button">View Student Grades</a>
</div>

</body>
</html>
