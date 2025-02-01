<?php
include 'db_connection.php';
session_start();

$success_message = '';
$error_message = '';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $student_number = $_POST['student_number'];
    $course = $_POST['course'];
    $department = $_POST['department'];
    $password = $_POST['password'];

    // Hash the password before storing
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // Insert into students table
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Student Profile</title>

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

<h2>Create Student Profile</h2>

<!-- Display Success or Error Message -->
<?php if ($success_message): ?>
    <div class="alert alert-success"><?php echo $success_message; ?></div>
<?php endif; ?>
<?php if ($error_message): ?>
    <div class="alert alert-danger"><?php echo $error_message; ?></div>
<?php endif; ?>

<div class="form-container">
    <form method="POST">
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

<!-- "Already have an account?" link to login page -->
<div class="already-account-box">
    <p>Already have an account? <a href="login.php">Login here</a></p>
</div>

</body>
</html>
