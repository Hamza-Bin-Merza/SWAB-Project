<?php
session_start();

// Ensure the user is logged in and has a role
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$role = $_SESSION['role']; // Role of the logged-in user (Admin, Faculty, Student)

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Secure Robotic Course Management System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            text-align: center;
        }

        nav {
            display: flex;
            justify-content: center;
            background-color: #333;
        }

        nav a {
            color: white;
            padding: 14px 20px;
            text-decoration: none;
            text-align: center;
        }

        nav a:hover {
            background-color: #ddd;
            color: black;
        }

        .container {
            width: 80%;
            margin: 30px auto;
        }

        .card {
            background-color: white;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .card h3 {
            margin-top: 0;
        }

        .button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }

        .button:hover {
            background-color: #45a049;
        }

        .role-warning {
            color: red;
        }
    </style>
</head>
<body>

<header>
    <h1>Secure Robotic Course Management System</h1>
</header>

<nav>
    <a href="dashboard.php">Home</a>
    <?php if ($role == 'Admin' || $role == 'Faculty'): ?>
        <a href="create_student.php">Create Student Profile</a>
        <a href="view_students.php">View Students</a>
    <?php endif; ?>
    <?php if ($role == 'Admin' || $role == 'Faculty'): ?>
        <a href="update_student.php">Update Student Profile</a>
        <a href="delete_student.php">Delete Student Profile</a>
    <?php endif; ?>
    <a href="logout.php">Logout</a>
</nav>

<div class="container">
    <?php if ($role == 'Admin' || $role == 'Faculty'): ?>
        <div class="card">
            <h3>Admin/Faculty Dashboard</h3>
            <p>Welcome to the admin/faculty dashboard. Here you can manage student profiles, courses, and more.</p>
            
            <a href="create.php" class="button">Create Student Profile</a><br><br>
            <a href="read.php" class="button">View All Students</a><br><br>
            <a href="update.php" class="button">Update Student Profile</a><br><br>
            <a href="delete.php" class="button">Delete Student Profile</a><br><br>
        </div>
    <?php endif; ?>

    <?php if ($role == 'Student'): ?>
        <div class="card">
            <h3>Student Dashboard</h3>
            <p>Welcome, student! You can only view your own profile here.</p>
            
            <a href="view_students.php" class="button">View Your Profile</a><br><br>
        </div>
    <?php endif; ?>
</div>

</body>
</html>
