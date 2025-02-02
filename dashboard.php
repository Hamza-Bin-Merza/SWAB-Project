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
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            color: #333;
        }

        header {
            background-color: #1E3C72; /* Original Blue header */
            color: white;
            padding: 20px;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
        }

        nav {
            display: flex;
            justify-content: center;
            background-color: #1E3C72; /* Original Blue navigation bar */
            padding: 10px 0;
        }

        nav a {
            color: white;
            padding: 14px 20px;
            text-decoration: none;
            text-align: center;
            font-size: 16px;
        }

        nav a:hover {
            background-color: #ddd;
            color: #333;
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 30px auto;
        }

        .card {
            background-color: white;
            padding: 25px;
            margin-bottom: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card h3 {
            font-size: 22px;
            margin-bottom: 15px;
            color: #1E3C72; /* Original Blue */
        }

        .card p {
            font-size: 16px;
            color: #666;
            margin-bottom: 20px;
        }

        .button {
            background-color: #1E3C72; /* Original Blue button */
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
            text-align: center;
            display: inline-block;
            transition: background-color 0.3s ease;
        }

        .button:hover {
            background-color: #0c2a56; /* Darker blue on hover */
        }

        .button-container {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        .role-warning {
            color: red;
        }

        @media (max-width: 768px) {
            .button-container {
                flex-direction: column;
                align-items: center;
            }
        }
    </style>
</head>
<body>

<header>
    XYZ Polytechnic
</header>

<nav>
    <a href="dashboard.php">Home</a>
    <?php if ($role == 'Admin' || $role == 'Faculty'): ?>
        <a href="read.php">View Student Records</a>
        <a href="classes.php">View Classes</a>
        <a href="maincourse.php">View Course Information</a>
        <a href="read_grades.php">View Student Grades</a>
    <?php endif; ?>
    <a href="logout.php">Logout</a>
</nav>

<div class="container">
    <?php if ($role == 'Admin'): ?>
        <div class="card">
            <h3>Admin Dashboard</h3>
            <p>Welcome to the admin dashboard. Here you can manage student profiles, courses, and more.</p>
            <div class="button-container">
                <a href="read.php" class="button">View Student Records</a>
                <a href="classes.php" class="button">View Classes</a>
                <a href="maincourse.php" class="button">View Course Information</a>
                <a href="read_grades.php" class="button">View Student Grades</a>
                <a href="course_assignments.php" class="button">Assigned Courses</a>
            </div>
        </div>
    <?php endif; ?>

<div class="container">
    <?php if ($role == 'Faculty'): ?>
        <div class="card">
            <h3>Admin Dashboard</h3>
            <p>Welcome to the faculty dashboard. Here you can manage student profiles, courses, and more.</p>
            <div class="button-container">
                <a href="read.php" class="button">View Student Records</a>
                <a href="classes.php" class="button">View Classes</a>
                <a href="maincourse.php" class="button">View Course Information</a>
                <a href="read_grades.php" class="button">View Student Grades</a>
                <a href="course_assignments.php" class="button">Assigned Courses</a>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($role == 'Student'): ?>
        <div class="card">
            <h3>Student Dashboard</h3>
            <p>Welcome, student! You can view your profile and see your course details.</p>
            <div class="button-container">
                <a href="view_students.php" class="button">View Your Profile</a>
                <a href="course_assignments.php" class="button">Assigned Courses</a>
            </div>
        </div>
    <?php endif; ?>
</div>

</body>
</html>
