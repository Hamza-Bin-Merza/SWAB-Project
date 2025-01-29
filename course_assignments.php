<?php
include 'db.php';
session_start();
if ($_SESSION['role'] !== 'Faculty' && $_SESSION['role'] !== 'Admin') {
    header("Location: login.php");
    exit();
}

// Create Assignment
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create'])) {
    $student_id = $_POST['student_id'];
    $course_id = $_POST['course_id'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("SELECT * FROM course_assignments WHERE student_id = ? AND course_id = ?");
    $stmt->bind_param("ii", $student_id, $course_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        echo "Assignment already exists!";
    } else {
        $stmt = $conn->prepare("INSERT INTO course_assignments (student_id, course_id, status) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $student_id, $course_id, $status);
        if ($stmt->execute()) {
            echo "Student assigned successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }
    }
    $stmt->close();
}

// Read Assignments
$query = "SELECT ca.assignment_id, s.name as student_name, c.course_name, ca.status 
          FROM course_assignments ca
          JOIN students s ON ca.student_id = s.student_id
          JOIN courses c ON ca.course_id = c.course_id";
$result = $conn->query($query);

// Update Assignment
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $assignment_id = $_POST['assignment_id'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE course_assignments SET status = ? WHERE assignment_id = ?");
    $stmt->bind_param("si", $status, $assignment_id);
    if ($stmt->execute()) {
        echo "Status updated successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

// Delete Assignment
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $assignment_id = $_POST['assignment_id'];

    $stmt = $conn->prepare("DELETE FROM course_assignments WHERE assignment_id = ?");
    $stmt->bind_param("i", $assignment_id);
    if ($stmt->execute()) {
        echo "Assignment deleted successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assign Students</title>
</head>
<body>
    <h1>Assign Students to Courses</h1>
    <!-- Create Form -->
    <form method="POST">
        <select name="student_id">
            <?php
            $students = $conn->query("SELECT student_id, name FROM students");
            while ($row = $students->fetch_assoc()) {
                echo "<option value='{$row['student_id']}'>{$row['name']}</option>";
            }
            ?>
        </select>
        <select name="course_id">
            <?php
            $courses = $conn->query("SELECT course_id, course_name FROM courses");
            while ($row = $courses->fetch_assoc()) {
                echo "<option value='{$row['course_id']}'>{$row['course_name']}</option>";
            }
            ?>
        </select>
        <select name="status">
            <option value="start">Start</option>
            <option value="in-progress">In-progress</option>
            <option value="ended">Ended</option>
        </select>
        <button type="submit" name="create">Assign</button>
    </form>

    <!-- Display Assignments -->
    <h2>Assignments</h2>
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
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['student_name']); ?></td>
                <td><?php echo htmlspecialchars($row['course_name']); ?></td>
                <td><?php echo htmlspecialchars($row['status']); ?></td>
                <td>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="assignment_id" value="<?php echo $row['assignment_id']; ?>">
                        <select name="status">
                            <option value="start">Start</option>
                            <option value="in-progress">In-progress</option>
                            <option value="ended">Ended</option>
                        </select>
                        <button type="submit" name="update">Update</button>
                    </form>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="assignment_id" value="<?php echo $row['assignment_id']; ?>">
                        <button type="submit" name="delete">Delete</button>
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
