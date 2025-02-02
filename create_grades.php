<?php
session_start();
include 'db_connection.php';

// Generate CSRF Token if not already set
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Flag to indicate whether the grade was successfully inserted
$successMessage = "";
$errorMessage = "";

// Insert grade
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    // Validate CSRF Token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF token validation failed!");
    }

    // Sanitize user input
    $student_id = htmlspecialchars(trim($_POST['student_id']));
    $course = htmlspecialchars(trim($_POST['course']));
    $module = htmlspecialchars(trim($_POST['module']));
    $grade = htmlspecialchars(trim($_POST['grade']));
    $score = htmlspecialchars(trim($_POST['score']));
    $date_recorded = htmlspecialchars(trim($_POST['date_recorded']));
    $course_end_date = htmlspecialchars(trim($_POST['course_end_date']));

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
            min-height: 100vh; /* ✅ Changed from height: 100vh; */
            flex-direction: column;
            text-align: center;
            position: relative;
            overflow-y: auto; /* ✅ Allows scrolling if content overflows */
        }


        /* Logo Styling */
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

        .form-container input[type="submit"]:active {
            background-color: #4a3791;
        }

        .form-container input[type="text"]:focus, 
        .form-container input[type="date"]:focus, 
        .form-container select:focus {
            border-color: #1e3c72;
            outline: none;
        }

        /* Success message styling */
        .success-message {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            font-weight: bold;
            text-align: center;
        }

        /* Error message styling */
        .error-message {
            background-color: #f44336;
            color: white;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            font-weight: bold;
            text-align: center;
        }

        /* Link button styling */
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

<!-- Logo -->
<img src="capy.png" alt="Logo" class="logo" />

<div class="form-container">
    <h2>Submit Grade</h2>

    <!-- Display success or error message -->
    <?php if ($successMessage != ""): ?>
        <div class="success-message"><?= $successMessage; ?></div>
    <?php endif; ?>

    <?php if ($errorMessage != ""): ?>
        <div class="error-message"><?= $errorMessage; ?></div>
    <?php endif; ?>

    <form method="POST">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">

        <label for="student_id">Student ID:</label>
        <input type="text" id="student_id" name="student_id" required><br>

        <label for="course">Course:</label>
        <select id="course" name="course" required>
            <option value="AAI">Applied Artifical Intelligence</option>
            <option value="BDA">Big Data & Analytics</option>
            <option value="CDF">Cybersecurity & Digital Forensics</option>
            <option value="ITO">Information Technology</option>
            <option value="IGD">Immersive Media & Game Development</option>
        </select><br>

        <label for="module">Module:</label>
        <select id="module" name="module" required>
            <option value="CYFUN">Cybersecurity Fundamentals</option>
            <option value="LOMA">Logic & Mathematics</option>
            <option value="DAVA">Data Analytics & Visualisation</option>
            <option value="COMT">Computational Thinking</option>
            <option value="UXID">User Experience & Interface Design</option>
        </select><br>

        <label for="grade">Grade:</label>
        <select id="grade" name="grade" required>
            <option value="A">A (80-100)</option>
            <option value="B">B (70-79)</option>
            <option value="C">C (60-69)</option>
            <option value="D">D (50-59)</option>
            <option value="F">F (Below 50)</option>
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
