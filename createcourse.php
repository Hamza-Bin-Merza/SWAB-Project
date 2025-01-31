<?php
// Include database connection
include 'db_connection.php';

// Check if we are editing an existing course
$edit_course = null;
if (isset($_GET['edit'])) {
    $course_id = intval($_GET['edit']);

    // Fetch the course details from the database
    $stmt = $con->prepare("SELECT * FROM courses WHERE course_id = ?");
    $stmt->bind_param("i", $course_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the course exists
    if ($result->num_rows > 0) {
        $edit_course = $result->fetch_assoc();
    } else {
        echo "<p class='message error'>Error: Course not found.</p>";
    }

    $stmt->close();
}

// Initialize error messages
$error_message = '';
$course_code_error = '';

// Handle form submission for creating or updating a course
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form values
    $course_name = $_POST['course_name'];
    $course_code = $_POST['course_code'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $course_description = $_POST['course_description'];

    // Validate input fields
    if (empty($course_name)) {
        $error_message = 'Course Name is required.';
    } elseif (empty($course_code)) {
        $course_code_error = 'Course Code is required.';
    } elseif (empty($start_date)) {
        $error_message = 'Start Date is required.';
    } elseif (empty($end_date)) {
        $error_message = 'End Date is required.';
    } elseif ($start_date > $end_date) {
        $error_message = 'Start date must be before the end date.';
    } elseif (empty($course_description)) {
        $error_message = 'Course Description is required.';
    } else {
        // Check if we are updating an existing course or creating a new one
        if (isset($_POST['update'])) {
            $course_id = intval($_POST['course_id']);

            // Ensure the course code is unique (except for the current course)
            $stmt = $con->prepare("SELECT COUNT(*) FROM courses WHERE course_code = ? AND course_id != ?");
            $stmt->bind_param("si", $course_code, $course_id);
        } else {
            // Ensure the course code is unique for a new course
            $stmt = $con->prepare("SELECT COUNT(*) FROM courses WHERE course_code = ?");
            $stmt->bind_param("s", $course_code);
        }

        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        if ($count > 0) {
            $course_code_error = 'Course Code already exists. Please use a unique code.';
        } else {
            // Perform insert or update operation
            if (isset($_POST['update'])) {
                // Update existing course details
                $stmt = $con->prepare("UPDATE courses SET course_name = ?, course_code = ?, start_date = ?, end_date = ?, course_description = ? WHERE course_id = ?");
                $stmt->bind_param("sssssi", $course_name, $course_code, $start_date, $end_date, $course_description, $course_id);
            } else {
                // Insert a new course into the database
                $stmt = $con->prepare("INSERT INTO courses (course_name, course_code, start_date, end_date, course_description) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("sssss", $course_name, $course_code, $start_date, $end_date, $course_description);
            }

            // Execute the query and handle success or failure
            if ($stmt->execute()) {
                // Redirect to maincourse.php with a success message
                header("Location: maincourse.php?success=" . (isset($_POST['update']) ? 'updated' : 'created'));
                exit();
            } else {
                $error_message = 'Error: ' . htmlspecialchars($stmt->error);
            }
        }
    }
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
        .error-message {
            color: #d9534f;
            font-weight: bold;
            text-align: center;
            padding: 10px;
            margin-bottom: 15px;
            background-color: #f2dede;
            border: 1px solid #ebccd1;
            border-radius: 5px;
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

    <!-- Display error message if validation fails -->
    <?php if (!empty($error_message)): ?>
        <div class="error-message">
            <?php echo htmlspecialchars($error_message); ?>
        </div>
    <?php endif; ?>

    <!-- Course creation/editing form -->
    <form method="POST">
        <?php if (isset($edit_course)): ?>
            <input type="hidden" name="course_id" value="<?php echo $edit_course['course_id']; ?>">
        <?php endif; ?>

        <label for="course_name">Course Name:</label>
        <input type="text" id="course_name" name="course_name" value="<?php echo isset($edit_course['course_name']) ? htmlspecialchars($edit_course['course_name']) : ''; ?>" required>

        <label for="course_code">Course Code:</label>
        <input type="text" id="course_code" name="course_code" value="<?php echo isset($edit_course['course_code']) ? htmlspecialchars($edit_course['course_code']) : ''; ?>" required>
        <?php if (!empty($course_code_error)): ?>
            <span style="color: red; font-size: 0.9em;"><?php echo htmlspecialchars($course_code_error); ?></span>
        <?php endif; ?>

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
