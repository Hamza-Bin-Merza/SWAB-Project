<?php
include 'db_connection.php';

// Flag to indicate whether the grade was successfully deleted
$successMessage = "";

// Delete grade
if (isset($_POST['delete'])) {
    $student_id = $_POST['student_id'];
    $course_id = $_POST['course_id'];

    // Check if course has ended before deletion
    $sql_check = "SELECT * FROM student_grades WHERE student_id='$student_id' AND course_id='$course_id'";
    $result_check = $con->query($sql_check);

    if ($result_check->num_rows > 0) {
        $row = $result_check->fetch_assoc();
        $course_end_date = $row['course_end_date'];

        // Compare if current date is after course end date
        if (strtotime($course_end_date) < time()) {
            // Proceed with deletion
            $sql_delete = "DELETE FROM student_grades WHERE student_id='$student_id' AND course_id='$course_id'";
            if ($con->query($sql_delete) === TRUE) {
                $successMessage = "Grade record deleted successfully.";
            } else {
                echo "Error: " . $sql_delete . "<br>" . $con->error;
            }
        } else {
            echo "Error: Cannot delete record before the course has ended.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Grade Record</title>
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
        .form-container input[type="submit"] {
            background-color: #664eae; /* Button color */
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
        .form-container input[type="submit"]:focus {
            border-color: #1e3c72;
            outline: none;
        }

        /* Success message styling */
        .success-message {
            background-color: #4CAF50; /* Green background */
            color: white;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            font-weight: bold;
            text-align: center;
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
    <h2>Delete Grade Record</h2>

    <!-- Display success or error message -->
    <?php if ($successMessage != ""): ?>
        <div class="success-message"><?= $successMessage; ?></div>
    <?php endif; ?>

    <?php if (isset($errorMessage)): ?>
        <div class="error-message"><?= $errorMessage; ?></div>
    <?php endif; ?>

    <form method="POST">
        <label for="student_id">Student ID:</label>
        <input type="text" id="student_id" name="student_id" required><br>

        <label for="course_id">Course ID:</label>
        <input type="text" id="course_id" name="course_id" required><br>

        <input type="submit" name="delete" value="Delete Grade Record">
    </form>
</div>


</body>
</html>
