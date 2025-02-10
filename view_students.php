<?php
session_start();
include 'db_connection.php';

// Ensure the user is logged in and is a Student
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Student') {
    header("Location: login.php");
    exit;
}

$student_id = $_SESSION['user_id']; // Logged-in student's ID
$success_message = '';
$error_message = '';

// Fetch Student Details
$sql = "SELECT name, email, phone, student_number, course, department, bio FROM students WHERE student_id = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param('i', $student_id);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();
$stmt->close();

// Update Bio
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $bio = $_POST['bio'];

    $sql = "UPDATE students SET bio = ? WHERE student_id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param('si', $bio, $student_id);
    if ($stmt->execute()) {
        $success_message = "Profile updated successfully!";
        $student['bio'] = $bio; // Update local variable
    } else {
        $error_message = "Error updating profile.";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Profile</title>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            color: #333;
        }

        header {
            background-color: #1E3C72;
            color: white;
            padding: 20px;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            position: relative;
        }

        .profile-tab {
            position: absolute;
            top: 15px;
            right: 20px;
            background-color: white;
            color: #1E3C72;
            padding: 10px 15px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
        }

        .profile-tab:hover {
            background-color: #ddd;
        }

        .container {
            width: 90%;
            max-width: 800px;
            margin: 30px auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #1E3C72;
        }

        .profile-info {
            margin-bottom: 20px;
            font-size: 18px;
        }

        .bio-box {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .save-btn {
            background-color: #1E3C72;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .save-btn:hover {
            background-color: #0c2a56;
        }

        .alert {
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 10px;
        }

        .alert-success {
            background-color: #4CAF50;
            color: white;
        }

        .alert-danger {
            background-color: #FF4D4D;
            color: white;
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

<header>
    Student Profile
    <a href="dashboard.php" class="home-icon">🏠</a>
</header>

<div class="container">
    <h2>Welcome, <?php echo htmlspecialchars($student['name']); ?>!</h2>

    <?php if ($success_message): ?>
        <div class="alert alert-success"><?php echo $success_message; ?></div>
    <?php endif; ?>
    <?php if ($error_message): ?>
        <div class="alert alert-danger"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <div class="profile-info">
        <p><strong>Email:</strong> <?php echo htmlspecialchars($student['email']); ?></p>
        <p><strong>Phone:</strong> <?php echo htmlspecialchars($student['phone']); ?></p>
        <p><strong>Student Number:</strong> <?php echo htmlspecialchars($student['student_number']); ?></p>
        <p><strong>Course:</strong> <?php echo htmlspecialchars($student['course']); ?></p>
        <p><strong>Department:</strong> <?php echo htmlspecialchars($student['department']); ?></p>
    </div>

    <form method="POST">
        <label for="bio"><strong>About Me:</strong></label>
        <textarea name="bio" class="bio-box" rows="4"><?php echo htmlspecialchars($student['bio']); ?></textarea>
        <button type="submit" class="save-btn">Save Bio</button>
    </form>
</div>

</body>
</html>