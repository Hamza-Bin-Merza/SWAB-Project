<?php
// Database connection
include 'db_connection.php';

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

        .table-container {
            background-color: rgba(255, 255, 255, 0.1); /* Semi-transparent background */
            padding: 30px;
            border-radius: 10px;
            width: 80%;
            max-width: 1200px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(10px); /* Blur effect for the background */
            overflow-x: auto;
        }

        .table-container h2 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 28px;
            font-weight: 600;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ccc;
        }

        th {
            background-color: #664eae;
            color: white;
        }

        tr:nth-child(even) {
            background-color:rgba(242, 242, 242, 0.4);
        }

        tr:hover {
            background-color: #ddd;
        }

        .no-records {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
        }

        .btn, .edit-btn, .delete-btn {
            background-color: #664eae; /* Button color */
            color: white;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            border-radius: 6px;
            display: inline-block;
            margin-top: 20px;
            cursor: pointer;
            font-size: 16px;
        }

        .btn:hover, .edit-btn:hover, .delete-btn:hover {
            background-color: #5a42a3; /* Darker shade on hover */
        }

        .btn:active, .edit-btn:active, .delete-btn:active {
            background-color: #4a3791; /* Even darker on active click */
        }
    </style>
</head>
<body>


<div class="table-container">
    <h2>Student Grades Records</h2>

    <?php
    if ($result->num_rows > 0) {
        echo "<table><tr><th>Student ID</th><th>Course ID</th><th>Grade</th><th>Score</th><th>Date Recorded</th><th>Actions</th></tr>";
        while($row = $result->fetch_assoc()) {
            // Add Edit and Delete buttons for each record
            echo "<tr><td>" . $row["student_id"]. "</td><td>" . $row["course_id"]. "</td><td>" . $row["grade"]. "</td><td>" . $row["score"]. "</td><td>" . $row["date_recorded"]. "</td>
                  <td>
                      <a href='UPDATE_grades.php?student_id=" . $row["student_id"] . "&course_id=" . $row["course_id"] . "' class='edit-btn'>Edit</a>
                      <a href='DELETE_grades.php?student_id=" . $row["student_id"] . "&course_id=" . $row["course_id"] . "' class='delete-btn' onclick='return confirm(\"Are you sure you want to delete this record?\")'>Delete</a>
                  </td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "<p class='no-records'>No records found</p>";
    }
    ?>

    <!-- Button to navigate to CREATE_insert.php -->
    <a href="S_CREATE_grades.php" class="btn">Enter New Grade</a>
</div>

</body>
</html>