<?php
include 'db_connection.php';

$success_message = '';  // Variable to store success message

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $course = $_POST['course'];
    $department = $_POST['department'];
    $student_number = $_POST['student_number'];
    $password = $_POST['password_hash'];

    // Hash the password before storing
    $password_hash = password_hash($password, PASSWORD_DEFAULT);


    // Prepare and execute SQL query
    $sql = "INSERT INTO students (name, email, phone, course, department, student_number, password_hash)
            VALUES (?, ?, ?, ?, ?, ?, ?)";

    // Use prepared statements to avoid SQL injection
    if ($stmt = $con->prepare($sql)) {
        $stmt->bind_param('sssssss', $name, $email, $phone, $course, $department, $student_number, $password_hash);
        if ($stmt->execute()) {
            $success_message = "Profile created successfully!";
        } else {
            $success_message = "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $success_message = "Error preparing statement: " . $con->error;
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

        .form-container input {
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

        .button-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 20px;
            width: 90%;
            margin-left: auto;
            margin-right: auto;
        }

        .button-container button {
            background-color: #664EAE;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .button-container button:hover {
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

        .home-icon {
            position: absolute;
            top: 15px;
            left: 20px;
            font-size: 24px;
            text-decoration: none;
            color: white;
            background-color: transparent;
            padding: 10px;
            border-radius: 5px;
        }

        .home-icon:hover {
            color: #ddd;
        }
    </style>
</head>
<body>

<h2>Create New Student Profile
<a href="dashboard.php" class="home-icon">🏠</a>
</h2>

<!-- Display Success or Error Message -->
<?php if ($success_message): ?>
    <div class="alert <?php echo $success_message == 'Profile created successfully!' ? 'alert-success' : 'alert-danger'; ?>">
        <?php echo $success_message; ?>
    </div>
<?php endif; ?>
<div class="form-container">
    <form method="POST">
        <div class="mb-3">
            <label for="name" class="form-label">Name:</label>
            <input type="text" id="name" name="name" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email:</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="mb-3">
            <label for="phone" class="form-label">Phone:</label>
            <input type="tel" id="phone" name="phone">
        </div>
        <div class="mb-3">
            <label for="course" class="form-label">Course:</label>
            <input type="text" id="course" name="course" required>
        </div>
        <div class="mb-3">
            <label for="department" class="form-label">Department:</label>
            <input type="text" id="department" name="department" required>
        </div>
        <div class="mb-3">
            <label for="student_number" class="form-label">Student Number:</label>
            <input type="text" id="student_number" name="student_number" required>
        </div>
        <div class="mb-3">
            <label for="password_hash" class="form-label">Password:</label>
            <input type="text" id="password_hash" name="password_hash" required>
        </div>
        <button type="submit">Create Profile</button>
    </form>
</div>

<div class="button-container">
    <button onclick="window.location.href='read.php'">View All Student Profiles</button>
</div>

</body>
</html>
