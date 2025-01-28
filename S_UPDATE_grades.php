<?php
// Database connection
$host = "localhost";
$username = "admin";
$password = "admin";
$database = "swap";

$con = new mysqli($host, $username, $password, $database);

// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

$errorMessage = ""; // Initialize error message

if (isset($_POST['update'])) {
    $student_id = $_POST['student_id'];
    $course_id = $_POST['course_id'];
    $grade = $_POST['grade'];
    $score = $_POST['score'];

    // Validate score and grade compatibility
    if (($grade == 'A' && ($score < 80 || $score > 100)) ||
        ($grade == 'B' && ($score < 70 || $score > 79)) ||
        ($grade == 'C' && ($score < 60 || $score > 69)) ||
        ($grade == 'D' && ($score < 50 || $score > 59)) ||
        ($grade == 'F' && $score >= 50)) {
        $errorMessage = "Error: The grade entered does not match the score. Please ensure the score is within the valid range for the selected grade.";
    } else {
        // Insert into the database if the validation passes
        $sql = "UPDATE student_grades 
                SET grade='$grade', score='$score'
                WHERE student_id='$student_id' AND course_id='$course_id'";

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
            background-color: #1e3c72; /* Background color */
            color: white;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            flex-direction: column;
            position: relative; /* Needed for logo positioning */
        }

        /* Logo Styling */
        .logo {
            position: absolute;
            top: 20px;
            right: 20px;
            width: 100px; /* Adjust size as needed */
            height: 100px; /* Set the same height as width for a perfect circle */
            border-radius: 50%; /* Make the image circular */
            object-fit: cover; /* Ensure the image doesn't distort when resizing */
        }

        .form-container {
            background-color: rgba(255, 255, 255, 0.1); /* Semi-transparent background */
            padding: 30px;
            border-radius: 10px;
            width: 400px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(10px); /* Blur effect for the background */
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

        .form-container input[type="text"], 
        .form-container select, 
        .form-container input[type="submit"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border-radius: 6px;
            border: 1px solid #ccc;
            box-sizing: border-box;
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
            background-color: #664eae; /* Button color changed */
            color: white;
            border: none;
            font-size: 16px;
            cursor: pointer;
        }

        .form-container input[type="submit"]:hover {
            background-color: #5a42a3; /* Darker shade on hover */
        }

        .form-container input[type="submit"]:active {
            background-color: #4a3791; /* Even darker on active click */
        }

        .form-container input[type="text"]:focus, 
        .form-container input[type="submit"]:focus, 
        .form-container select:focus {
            border-color: #1e3c72;
            outline: none;
        }

        /* Error message styling */
        .error-message {
            background-color: #f44336; /* Red background */
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

        <label for="course_id">Course ID:</label>
        <input type="text" id="course_id" name="course_id" required><br>

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

        <input type="submit" name="update" value="Update Grade">
    </form>
</div>

</body>
</html>
