<?php
include 'db.php'; // Ensure this contains the $conn connection

$edit_course = null; // Default value for editing

// Handle Delete Request
if (isset($_GET['delete'])) {
    $course_id = intval($_GET['delete']); // Ensure course_id is treated as an integer
    $stmt = $conn->prepare("DELETE FROM courses WHERE course_id = ?");
    $stmt->bind_param("i", $course_id);

    if ($stmt->execute()) {
        echo "Course deleted successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();

    // Redirect to clear the delete parameter from the URL
    header("Location: create.php");
    exit();
}

// Handle Edit Request
if (isset($_GET['edit'])) {
    $course_id = intval($_GET['edit']); // Sanitize input
    $stmt = $conn->prepare("SELECT * FROM courses WHERE course_id = ?");
    $stmt->bind_param("i", $course_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $edit_course = $result->fetch_assoc(); // Fetch course data
    } else {
        echo "Error: Course not found.";
    }

    $stmt->close();
}

// Handle Insert or Update Course Data
if ($_SERVER['REQUEST_METHOD'] == 'POST' && (isset($_POST['create']) || isset($_POST['update']))) {
    $course_name = $_POST['course_name'];
    $course_code = $_POST['course_code'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $course_description = $_POST['course_description'];

    if (isset($_POST['update'])) {
        // Update course information
        $course_id = intval($_POST['course_id']);
        $stmt = $conn->prepare("UPDATE courses SET course_name = ?, course_code = ?, start_date = ?, end_date = ?, course_description = ? WHERE course_id = ?");
        $stmt->bind_param("sssssi", $course_name, $course_code, $start_date, $end_date, $course_description, $course_id);
    } else {
        // Insert new course
        $stmt = $conn->prepare("INSERT INTO courses (course_name, course_code, start_date, end_date, course_description, date_created) VALUES (?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("sssss", $course_name, $course_code, $start_date, $end_date, $course_description);
    }

    if ($stmt->execute()) {
        echo "Course " . (isset($_POST['update']) ? "updated" : "created") . " successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    header("Location: create.php");
    exit();
}

// Handle Export to CSV
if (isset($_POST['export_csv'])) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="courses.csv"');

    $output = fopen('php://output', 'w');
    fputcsv($output, ['Course ID', 'Course Name', 'Course Code', 'Start Date', 'End Date', 'Description', 'Created At']);

    $query = "SELECT * FROM courses";
    $result = $conn->query($query);

    while ($row = $result->fetch_assoc()) {
        fputcsv($output, $row);
    }

    fclose($output);
    exit();
}

// Handle Bulk Delete
if (isset($_POST['delete_bulk']) && !empty($_POST['selected_courses'])) {
    $selected_courses = $_POST['selected_courses'];
    $placeholders = implode(',', array_fill(0, count($selected_courses), '?'));
    $stmt = $conn->prepare("DELETE FROM courses WHERE course_id IN ($placeholders)");
    $stmt->bind_param(str_repeat('i', count($selected_courses)), ...$selected_courses);

    if ($stmt->execute()) {
        echo "Selected courses deleted successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    header("Location: create.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create, Update, and Delete Courses</title>
    <style>
        body {
            background-color: #1e3c72;
            color: white; /* Optional: Adjust text color for better visibility on dark background */
        }
        .highlight {
            background-color: yellow;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid white;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        button {
            background-color: #664eae;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #5b43a3;
        }
        input[type="text"], input[type="date"], textarea {
            width: 100%;
            padding: 8px;
            margin: 5px 0;
            box-sizing: border-box;
        }
        /* Logo position and circular shape */
        .logo {
            position: absolute;
            top: 10px;
            right: 10px;
            width: 70px; /* Adjust the size */
            height: 70px; /* Ensure the height matches the width for a circle */
            border-radius: 50%; /* Make the image circular */
            object-fit: cover; /* Ensures the image fills the circle */
        }
    </style>
</head>
<body>
    <!-- Logo -->
    <img src="image.png" alt="XYZ Polytechnic Logo" class="logo">

    <h2>Create or Edit Course</h2>
    <form method="POST">
        <input type="hidden" name="course_id" value="<?php echo isset($edit_course['course_id']) ? $edit_course['course_id'] : ''; ?>">

        <label>Course Name:</label>
        <input type="text" name="course_name" value="<?php echo isset($edit_course['course_name']) ? htmlspecialchars($edit_course['course_name']) : ''; ?>" required>
        <br>

        <label>Course Code:</label>
        <input type="text" name="course_code" value="<?php echo isset($edit_course['course_code']) ? htmlspecialchars($edit_course['course_code']) : ''; ?>" required>
        <br>

        <label>Start Date:</label>
        <input type="date" name="start_date" value="<?php echo isset($edit_course['start_date']) ? $edit_course['start_date'] : ''; ?>" required>
        <br>

        <label>End Date:</label>
        <input type="date" name="end_date" value="<?php echo isset($edit_course['end_date']) ? $edit_course['end_date'] : ''; ?>" required>
        <br>

        <label>Course Description:</label>
        <textarea name="course_description" rows="5" cols="30" required><?php echo isset($edit_course['course_description']) ? htmlspecialchars($edit_course['course_description']) : ''; ?></textarea>
        <br>

        <button type="submit" name="<?php echo isset($edit_course) ? 'update' : 'create'; ?>">
            <?php echo isset($edit_course) ? 'Update Course' : 'Create Course'; ?>
        </button>
    </form>

    <!-- Search Functionality -->
    <h2>Search Courses</h2>
    <form method="GET" action="create.php">
        <label>Search:</label>
        <input type="text" name="search" placeholder="Enter course name or code" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
        <button type="submit">Search</button>
    </form>

    <!-- Back Button -->
    <?php if (isset($_GET['search']) && !empty($_GET['search'])): ?>
        <form method="GET" action="create.php" style="margin-top: 10px;">
            <button type="submit">Back to All Courses</button>
        </form>
    <?php endif; ?>

    <h2>Course List</h2>
    <!-- Export to CSV -->
    <form method="POST">
        <button type="submit" name="export_csv">Export to CSV</button>
    </form>

    <!-- Bulk Operations -->
    <form method="POST">
        <table>
            <tr>
                <th>Select</th>
                <th>#</th>
                <th>Course Name</th>
                <th>Course Code</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Course Description</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
            <?php
            // Fetch courses with search functionality
            $search = isset($_GET['search']) ? $_GET['search'] : '';
            $sql = "SELECT * FROM courses";
            if (!empty($search)) {
                $sql .= " WHERE course_name LIKE ? OR course_code LIKE ?";
            }
            $stmt = $conn->prepare($sql);

            if (!empty($search)) {
                $searchTerm = "%$search%";
                $stmt->bind_param("ss", $searchTerm, $searchTerm);
            }
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $counter = 1; // Initialize counter for ascending display numbers
                while ($row = $result->fetch_assoc()) {
                    $highlight = function ($text) use ($search) {
                        if (!empty($search)) {
                            return str_ireplace($search, "<span class='highlight'>{$search}</span>", htmlspecialchars($text));
                        }
                        return htmlspecialchars($text);
                    };

                    echo "<tr>";
                    echo "<td><input type='checkbox' name='selected_courses[]' value='" . $row['course_id'] . "'></td>";
                    echo "<td>" . $counter++ . "</td>"; // Display ascending number
                    echo "<td>" . $highlight($row['course_name']) . "</td>";
                    echo "<td>" . $highlight($row['course_code']) . "</td>";
                    echo "<td>" . $row['start_date'] . "</td>";
                    echo "<td>" . $row['end_date'] . "</td>";
                    echo "<td>" . htmlspecialchars($row['course_description']) . "</td>";
                    echo "<td>
                            <a href='create.php?edit=" . $row['course_id'] . "'>Edit</a>
                          </td>";
                    echo "<td>
                            <a href='create.php?delete=" . $row['course_id'] . "' onclick='return confirm(\"Are you sure?\");'>Delete</a>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='9'>No courses found.</td></tr>";
            }
            $stmt->close();
            ?>
        </table>
        <button type="submit" name="delete_bulk">Delete Selected</button>
    </form>

    <?php $conn->close(); ?>
</body>
</html>

<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "student_management";

// Create a connection using mysqli
$conn = new mysqli($servername, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
