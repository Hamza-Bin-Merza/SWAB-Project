<?php
include 'db_connection.php';

$errorMessage = ""; // Initialize error message

if (isset($_POST['update'])) {
    $student_id = $_POST['student_id'];
    $course = $_POST['course'];
    $module = $_POST['module'];
    $grade = $_POST['grade'];
    $score = $_POST['score'];
    $course_end_date = $_POST['course_end_date'];

    // Validate score and grade compatibility
    if (($grade == 'A' && ($score < 80 || $score > 100)) ||
        ($grade == 'B' && ($score < 70 || $score > 79)) ||
        ($grade == 'C' && ($score < 60 || $score > 69)) ||
        ($grade == 'D' && ($score < 50 || $score > 59)) ||
        ($grade == 'F' && $score >= 50)) {
        $errorMessage = "Error: The grade entered does not match the score. Please ensure the score is within the valid range for the selected grade.";
    } else {
        // Update the database if validation passes
        $sql = "UPDATE student_grades 
                SET grade='$grade', score='$score', course_end_date='$course_end_date'
                WHERE student_id='$student_id' AND course='$course' AND module='$module'";

        if ($con->query($sql) === TRUE) {
            echo "Grade record updated successfully.";
        } else {
            echo "Error: " . $sql . "<br>" . $con->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Grade Record</title>
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
            height: 100vh;
            flex-direction: column;
            position: relative;
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
        }

        /* General input styling */
        .form-container input[type="text"], 
        .form-container select, 
        .form-container input[type="submit"], 
        .form-container input[type="date"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border-radius: 8px;
            border: 1px solid #ccc;
            box-sizing: border-box;
            background-color: #f8f9fa;
            color: #333;
            font-size: 16px;
            font-family: Arial, sans-serif;
        }

        /* Specific styling for date input */
        .form-container input[type="date"] {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            position: relative;
        }

        /* Ensures the calendar icon is inside the input field */
        .form-container input[type="date"]::-webkit-calendar-picker-indicator {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            opacity: 0.8;
        }

        /* On hover and focus */
        .form-container input[type="date"]:hover,
        .form-container input[type="date"]:focus {
            border-color: #1e3c72;
            outline: none;
            background-color: #fff;
        }

        .form-container input[type="text"] {
            background-color: #f8f9fa;
            color: #333;
        }

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
    </style>
</head>
<body>

<!-- Logo -->
<img src="capy.png" alt="Logo" class="logo" />

<div class="form-container">
    <h2>Update Grade Record</h2>

    <!-- Display error message if validation fails -->
    <?php if ($errorMessage != ""): ?>
        <div class="error-message"><?= $errorMessage; ?></div>
    <?php endif; ?>

    <form method="POST">
        <label for="student_id">Student ID:</label>
        <input type="text" id="student_id" name="student_id" required><br>

        <label for="course">Course:</label>
        <select id="course" name="course" required>
            <option value="AAI">Applied Artificial Intelligence</option>
            <option value="BDA">Big Data & Analytics</option>
            <option value="CDF">Cybersecurity & Digital Forensics</option>
            <option value="ITO">Information Technology</option>
            <option value="IGD">Immersive Media & Game Development</option>
        </select><br>

        <label for="module">Module:</label>
        <select id="module" name="module" required>
            <option value="cyfun">Cybersecurity Fundamentals</option>
            <option value="loma">Logic & Mathematics</option>
            <option value="dava">Data Analytics & Visualisation</option>
            <option value="comt">Computational Thinking</option>
            <option value="uxid">User Experience & Interface Design</option>
        </select><br>

        <label for="grade">New Grade:</label>
        <select id="grade" name="grade" required>
            <option value="A">A (80-100)</option>
            <option value="B">B (70-79)</option>
            <option value="C">C (60-69)</option>
            <option value="D">D (50-59)</option>
            <option value="F">F (Below 50)</option>
        </select><br>

        <label for="score">New Score:</label>
        <input type="text" id="score" name="score" required><br>

        <label for="course_end_date">Course End Date:</label>
        <input type="date" id="course_end_date" name="course_end_date" required><br>

        <input type="submit" name="update" value="Update Grade">
    </form>
</div>

</body>
</html>
