<?php
session_start(); // Ensure session is started
include 'db_connection.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$role = $_SESSION['role']; // Get logged-in user role

// Fetch all records
$sql = "SELECT * FROM student_grades";
$result = $con->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Grades Records</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <style>
        /* General Page Styling */
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #B0C4DE; /* Light blue background like maincourse.php */
            margin: 0;
            padding: 0;
        }

        /* Header */
        header {
            background-color: #1e3c72;
            color: white;
            padding: 20px;
            text-align: center;
            position: relative;
        }

        .header-logo {
            position: absolute;
            top: 50%;
            right: 20px;
            transform: translateY(-50%);
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
        }

        .home-icon {
            position: absolute;
            top: 50%;
            left: 20px;
            transform: translateY(-50%);
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

        .container {
            width: 90%;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #1e3c72;
            font-size: 24px;
            margin-bottom: 15px;
        }

        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
        }

        th {
            background-color: #1e3c72;
            color: white;
        }

        tr:nth-child(even) {
            background-color: rgba(242, 242, 242, 0.4);
        }

        /* Button Styling */
        .button-group {
            text-align: center;
            margin-top: 20px;
        }

        .button {
            background-color: #1e3c72;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
            cursor: pointer;
        }

        .button:hover {
            background-color: #163c63;
        }

        .edit-btn {
            background-color: #664eae;
            color: white;
            padding: 8px 16px;
            text-decoration: none;
            border-radius: 6px;
            font-size: 14px;
            border: none;
        }

        .edit-btn:hover {
            background-color: #5a42a3;
        }

        .delete-btn {
            background-color: red;
            color: white;
            padding: 8px 16px;
            text-decoration: none;
            border-radius: 6px;
            font-size: 14px;
            border: none;
            cursor: pointer;
        }

        .delete-btn:hover {
            background-color: darkred;
        }
    </style>
</head>
<body>

<header>
    <a href="dashboard.php" class="home-icon">🏠</a>
    <h1>XYZ Polytechnic - Student Grades Management</h1>
    <img src="logo.png" alt="Logo" class="header-logo">
</header>

<!-- Main Container -->
<div class="container">
    <h2>Student Grades Records</h2>

    <?php
    if ($result->num_rows > 0) {
        echo "<table>
                <tr>
                    <th>Student ID</th>
                    <th>Course</th>
                    <th>Module</th>
                    <th>Grade</th>
                    <th>Score</th>
                    <th>Date Recorded</th>
                    <th>Actions</th>
                </tr>";
        while($row = $result->fetch_assoc()) {
            echo "<tr id='row_" . $row["student_id"] . "_" . $row["course"] . "_" . $row["module"] . "'>
                  <td>" . $row["student_id"]. "</td>
                  <td>" . $row["course"]. "</td>
                  <td>" . $row["module"]. "</td>
                  <td>" . $row["grade"]. "</td>
                  <td>" . $row["score"]. "</td>
                  <td>" . $row["date_recorded"]. "</td>
                  <td>";

            // "Edit" button for both Admin and Faculty
            echo "<a href='update_grades.php?student_id=" . $row["student_id"] . "&course=" . $row["course"] . "&module=" . $row["module"] . "' class='edit-btn'>Edit</a>";

            // Only Admins can see the Delete button
            if ($role == 'Admin') {
                echo "<button class='delete-btn' onclick='confirmDelete(\"" . $row["student_id"] . "\", `" . $row["course"] . "`, `" . $row["module"] . "`)'>Delete</button>";
            }

            echo "</td></tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No records found</p>";
    }
    ?>

    <!-- Buttons -->
    <div class="button-group">
        <a href="create_grades.php" class="button">Create New Student Grades</a>
    </div>
</div>

<!-- JavaScript for AJAX Delete Confirmation -->
<script>
function confirmDelete(studentId, course, module) {
    if (confirm("Are you sure you want to delete this record?")) {
        fetch("S_DELETE_grades.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: "student_id=" + studentId + "&course=" + encodeURIComponent(course) + "&module=" + encodeURIComponent(module)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("Record deleted successfully.");
                document.getElementById("row_" + studentId + "_" + course + "_" + module).remove();
            } else {
                alert("Error: " + data.error);
            }
        })
        .catch(error => {
            alert("Request failed. Please try again.");
        });
    }
}
</script>

</body>
</html>
