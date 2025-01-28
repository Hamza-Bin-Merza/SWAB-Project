<?php
include 'db.php';

// Check if editing
$edit_course = null;
if (isset($_GET['edit'])) {
    $course_id = intval($_GET['edit']);
    $stmt = $conn->prepare("SELECT * FROM courses WHERE course_id = ?");
    $stmt->bind_param("i", $course_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $edit_course = $result->fetch_assoc();
    } else {
        echo "<p class='message error'>Error: Course not found.</p>";
    }

    $stmt->close();
}

// Handle Update or Create
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $course_name = $_POST['course_name'];
    $course_code = $_POST['course_code'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $course_description = $_POST['course_description'];

    if (isset($_POST['update'])) {
        // Update existing course
        $course_id = intval($_POST['course_id']);
        $stmt = $conn->prepare("UPDATE courses SET course_name = ?, course_code = ?, start_date = ?, end_date = ?, course_description = ? WHERE course_id = ?");
        $stmt->bind_param("sssssi", $course_name, $course_code, $start_date, $end_date, $course_description, $course_id);
    } else {
        // Insert new course
        $stmt = $conn->prepare("INSERT INTO courses (course_name, course_code, start_date, end_date, course_description, date_created) VALUES (?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("sssss", $course_name, $course_code, $start_date, $end_date, $course_description);
    }

    if ($stmt->execute()) {
        header("Location: maincourse.php");
        exit();
    } else {
        echo "<p class='message error'>Error: " . $stmt->error . "</p>";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($edit_course) ? 'Edit Course' : 'Create New Course'; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #B0C4DE;
        }
        .container {
            width: 60%;
            margin: 50px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.15);
        }
        h2 {
            color: #1e3c72;
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin: 10px 0 5px;
            font-weight: 500;
        }
        input, textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #ddd;
            box-sizing: border-box;
        }
        textarea {
            resize: none;
        }
        button {
            background-color: #1e3c72;
            color: white;
            border: none;
            padding: 12px;
            font-weight: bold;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }
        button:hover {
            background-color: #163c63;
            transform: scale(1.05);
        }
        .btn-group {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            margin-top: 20px;
        }
        .btn-group button {
            flex: 1;
        }
        .btn-group .btn-back {
            background-color: #555;
        }
        .btn-group .btn-back:hover {
            background-color: #333;
        }
    </style>
</head>
<body>

<div class="container">
    <h2><?php echo isset($edit_course) ? 'Edit Course' : 'Create New Course'; ?></h2>
    <form method="POST">
        <?php if (isset($edit_course)): ?>
            <input type="hidden" name="course_id" value="<?php echo $edit_course['course_id']; ?>">
        <?php endif; ?>

        <label for="course_name">Course Name:</label>
        <input type="text" id="course_name" name="course_name" value="<?php echo isset($edit_course['course_name']) ? htmlspecialchars($edit_course['course_name']) : ''; ?>" required>

        <label for="course_code">Course Code:</label>
        <input type="text" id="course_code" name="course_code" value="<?php echo isset($edit_course['course_code']) ? htmlspecialchars($edit_course['course_code']) : ''; ?>" required>

        <label for="start_date">Start Date:</label>
        <input type="date" id="start_date" name="start_date" value="<?php echo isset($edit_course['start_date']) ? $edit_course['start_date'] : ''; ?>" required>

        <label for="end_date">End Date:</label>
        <input type="date" id="end_date" name="end_date" value="<?php echo isset($edit_course['end_date']) ? $edit_course['end_date'] : ''; ?>" required>

        <label for="course_description">Course Description:</label>
        <textarea id="course_description" name="course_description" rows="4" required><?php echo isset($edit_course['course_description']) ? htmlspecialchars($edit_course['course_description']) : ''; ?></textarea>

        <div class="btn-group">
            <button type="submit" name="<?php echo isset($edit_course) ? 'update' : 'create'; ?>">
                <?php echo isset($edit_course) ? 'Update Changes' : 'Create Course'; ?>
            </button>
            <button type="button" class="btn-back" onclick="window.location.href='maincourse.php';">Go Back</button>
        </div>
    </form>
</div>

</body>
</html>
