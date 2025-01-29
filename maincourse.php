<?php
include 'db.php';

// Display confirmation message
$success_message = '';
if (isset($_GET['success']) && $_GET['success'] === 'created') {
    $success_message = "Course successfully created!";
} elseif (isset($_GET['success']) && $_GET['success'] === 'updated') {
    $success_message = "Course successfully updated!";
}


// Handle CSV Export
if (isset($_GET['action']) && $_GET['action'] == 'export') {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=courses.csv');
    $output = fopen('php://output', 'w');
    fputcsv($output, ['Course Name', 'Course Code', 'Start Date', 'End Date', 'Description']);
    $stmt = $conn->query("SELECT course_name, course_code, start_date, end_date, course_description FROM courses");
    while ($row = $stmt->fetch_assoc()) {
        fputcsv($output, $row);
    }
    fclose($output);
    exit();
}

// Handle Delete Course
if (isset($_GET['delete'])) {
    $course_id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM courses WHERE course_id = ?");
    $stmt->bind_param("i", $course_id);
    if ($stmt->execute()) {
        header("Location: maincourse.php");
        exit();
    } else {
        echo "<script>alert('Error deleting the course!');</script>";
    }
}

// Handle Search Query
$search = isset($_GET['search']) ? "%" . $_GET['search'] . "%" : '';
$query = $search ? "SELECT * FROM courses WHERE course_name LIKE ? OR course_code LIKE ?" : "SELECT * FROM courses";
$stmt = $conn->prepare($query);
if ($search) {
    $stmt->bind_param("ss", $search, $search);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Management</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #B0C4DE;
        }
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
        .container {
            width: 90%;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
        }
        .success-message {
            margin: 10px auto;
            padding: 10px;
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            border-radius: 5px;
            text-align: center;
            width: 90%;
        }
        .search-export {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .search-form input {
            width: 92%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        .search-form button {
            padding: 7px 13px;
            background-color: #1e3c72;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .button-group button {
            padding: 10px 15px;
            background-color: #1e3c72;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
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
        /* Hover Effects */
        button {
            transition: transform 0.3s ease, background-color 0.3s ease, box-shadow 0.3s ease;
        }
        button:hover {
            transform: scale(1.1);
            background-color: #163c63;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
        }
        .button-group button:hover {
            transform: scale(1.05);
        }
    </style>
</head>
<body>
    <header>
        <h1>XYZ Polytechnic - Course Management</h1>
        <img src="logo.png" alt="Logo" class="header-logo">
    </header>

    <?php if ($success_message): ?>
        <div class="success-message">
            <?php echo $success_message; ?>
        </div>
    <?php endif; ?>

    <div class="container">
        <h2>Course List</h2>
        <div class="search-export">
            <form method="GET" class="search-form">
                <input type="search" name="search" placeholder="Search by course name or course code" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                <button type="submit">Search</button>
            </form>
            <div class="button-group">
                <button onclick="window.location.href='createcourse.php';">Create New Course</button>
                <form method="GET" style="display: inline;">
                    <button type="submit" name="action" value="export">Export Course List to CSV</button>
                </form>
                <button onclick="window.location.href='maincourse.php';">Go Back</button>
            </div>
        </div>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Course Name</th>
                    <th>Course Code</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $counter = 1;
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $counter++ . "</td>";
                    echo "<td>" . htmlspecialchars($row['course_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['course_code']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['start_date']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['end_date']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['course_description']) . "</td>";
                    echo "<td>
                            <form method='GET' action='createcourse.php' style='display: inline;'>
                                <input type='hidden' name='edit' value='{$row['course_id']}'>
                                <button type='submit'>Edit</button>
                            </form>
                            <form method='GET' action='maincourse.php' style='display: inline;'>
                                <input type='hidden' name='delete' value='{$row['course_id']}'>
                                <button type='submit' onclick=\"return confirm('Are you sure you want to delete this course?');\">Delete</button>
                            </form>
                          </td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
