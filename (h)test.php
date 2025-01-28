<?php
include 'db_connection.php';
session_start();

// Check if the user is an Admin or Faculty
if ($_SESSION['role'] !== 'Admin' && $_SESSION['role'] !== 'Faculty') {
    die("You do not have permission to access this page.");
}

$success_message = '';
$error_message = '';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $student_number = $_POST['student_number'];
    $course = $_POST['course'];
    $department = $_POST['department'];

    // Insert student record into the database
    $sql = "INSERT INTO students (name, email, phone, student_number, course, department) 
            VALUES (?, ?, ?, ?, ?, ?)";

    if ($stmt = $con->prepare($sql)) {
        $stmt->bind_param('ssssss', $name, $email, $phone, $student_number, $course, $department);
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Student Profile</title>
</head>
<body>

<h2>Create New Student Profile</h2>

<!-- Display success or error message -->
<?php if ($success_message): ?>
    <p style="color: green;"><?php echo $success_message; ?></p>
<?php endif; ?>
<?php if ($error_message): ?>
    <p style="color: red;"><?php echo $error_message; ?></p>
<?php endif; ?>

<!-- Form to create a student profile -->
<form method="POST">
    Name: <input type="text" name="name" required><br>
    Email: <input type="email" name="email" required><br>
    Phone: <input type="text" name="phone"><br>
    Student Number: <input type="text" name="student_number" required><br>
    Course: <input type="text" name="course" required><br>
    Department: <input type="text" name="department" required><br>
    <button type="submit">Create Profile</button>
</form>

</body>
</html>
