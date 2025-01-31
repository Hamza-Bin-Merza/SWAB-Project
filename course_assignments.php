<?php
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['update'])) {
        $assignment_id = $_POST['assignment_id'];
        $student_id = $_POST['student_id'];
        $course_id = $_POST['course_id'];
        $status = $_POST['status'];

        $sql = "UPDATE course_assignments SET student_id = ?, course_id = ?, status = ? WHERE assignment_id = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("iisi", $student_id, $course_id, $status, $assignment_id);
        
        if ($stmt->execute()) {
            echo "<div class='alert alert-success'>Assignment updated successfully.</div>";
        } else {
            echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
        }
        
        $stmt->close();
    } elseif (isset($_POST['delete'])) {
        $assignment_id = $_POST['assignment_id'];
        
        $sql = "DELETE FROM course_assignments WHERE assignment_id = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("i", $assignment_id);
        
        if ($stmt->execute()) {
            echo "<div class='alert alert-success'>Assignment deleted successfully.</div>";
        } else {
            echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
        }
        
        $stmt->close();
    } else {
        $student_id = $_POST['student_id'];
        $course_id = $_POST['course_id'];
        $status = $_POST['status'];

        $sql = "INSERT INTO course_assignments (student_id, course_id, status) VALUES (?, ?, ?)";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("iis", $student_id, $course_id, $status);
        
        if ($stmt->execute()) {
            echo "<div class='alert alert-success'>Student assigned to course successfully.</div>";
        } else {
            echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
        }
        
        $stmt->close();
    }
}

$sql_students = "SELECT student_id, name FROM students";
$result_students = $con->query($sql_students);

$sql_courses = "SELECT course_id, course_name FROM courses";
$result_courses = $con->query($sql_courses);

$sql_assignments = "SELECT course_assignments.assignment_id, students.student_id, students.name AS student_name, courses.course_id, courses.course_name, course_assignments.status FROM course_assignments 
                    JOIN students ON course_assignments.student_id = students.student_id 
                    JOIN courses ON course_assignments.course_id = courses.course_id";
$result_assignments = $con->query($sql_assignments);

$con->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assign Student to Course</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #B0C4DE;
            text-align: center;
        }
        .container {
            width: 60%;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
        }
        h2 {
            color: #1e3c72;
        }
        select, button, input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        button {
            background-color: #1e3c72;
            color: white;
            cursor: pointer;
            transition: transform 0.3s ease;
        }
        button:hover {
            transform: scale(1.05);
            background-color: #163c63;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
        }
        th {
            background-color: #1e3c72;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Assign Student to Course</h2>
        <form method="post">
            <label for="student_id">Select Student:</label>
            <select name="student_id" required>
                <?php
                $result_students->data_seek(0); // Reset result set
                while ($row = $result_students->fetch_assoc()) { ?>
                    <option value="<?php echo $row['student_id']; ?>"><?php echo $row['name']; ?></option>
                <?php } ?>
            </select>

            <label for="course_id">Select Course:</label>
            <select name="course_id" required>
                <?php
                $result_courses->data_seek(0); // Reset result set
                while ($row = $result_courses->fetch_assoc()) { ?>
                    <option value="<?php echo $row['course_id']; ?>"><?php echo $row['course_name']; ?></option>
                <?php } ?>
            </select>

            <label for="status">Status:</label>
            <select name="status" required>
                <option value="start">Start</option>
                <option value="in-progress">In-Progress</option>
                <option value="ended">Ended</option>
            </select>

            <button type="submit">Assign</button>
        </form>

        <h2>Assigned Students</h2>
        <table>
            <thead>
                <tr>
                    <th>Student Name</th>
                    <th>Course Name</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result_assignments->fetch_assoc()) { ?>
                    <tr>
                        <td>
                            <form method='post' style='display:inline;'>
                                <input type='hidden' name='assignment_id' value='<?php echo $row['assignment_id']; ?>'>
                                <select name='student_id' required>
                                    <?php $result_students->data_seek(0); while ($student = $result_students->fetch_assoc()) { ?>
                                        <option value='<?php echo $student['student_id']; ?>' <?php echo ($row['student_id'] == $student['student_id']) ? 'selected' : ''; ?>><?php echo $student['name']; ?></option>
                                    <?php } ?>
                                </select>
                        </td>
                        <td>
                                <select name='course_id' required>
                                    <?php $result_courses->data_seek(0); while ($course = $result_courses->fetch_assoc()) { ?>
                                        <option value='<?php echo $course['course_id']; ?>' <?php echo ($row['course_id'] == $course['course_id']) ? 'selected' : ''; ?>><?php echo $course['course_name']; ?></option>
                                    <?php } ?>
                                </select>
                        </td>
                        <td>
                                <select name='status' required>
                                    <option value='start' <?php echo ($row['status'] == 'start') ? 'selected' : ''; ?>>Start</option>
                                    <option value='in-progress' <?php echo ($row['status'] == 'in-progress') ? 'selected' : ''; ?>>In-Progress</option>
                                    <option value='ended' <?php echo ($row['status'] == 'ended') ? 'selected' : ''; ?>>Ended</option>
                                </select>
                        </td>
                        <td>
                                <button type='submit' name='update'>Update</button>
                                <button type='submit' name='delete' onclick="return confirm('Are you sure you want to delete this assignment?');">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>
