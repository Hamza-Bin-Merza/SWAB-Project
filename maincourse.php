<?php
include 'db.php';

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
            margin: 0;
            padding: 0;
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
            box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.15);
        }
        h2 {
            color: #1e3c72;
            margin-bottom: 10px;
        }
        .search-export {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .search-form input {
            width: 70%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-right: 10px;
        }
        .search-form button {
            padding: 10px 20px;
            background-color: #1e3c72;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .button-group button {
            margin: 0 10px;
            padding: 10px 20px;
            background-color: #1e3c72;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #163c63;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #1e3c72;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
    </style>
</head>
<body>
    <header>
        <h1>XYZ Polytechnic - Course Management</h1>
        <img src="logo.png" alt="Logo" class="header-logo">
    </header>

    <div class="container">
        <div class="card">
            <h2>Course List</h2>

            <div class="search-export">
                <form method="GET" action="maincourse.php" class="search-form">
                    <input type="search" name="search" placeholder="Search by course name or code" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                    <button type="submit">Search</button>
                </form>
                <div class="button-group">
                    <button onclick="window.location.href='createcourse.php';">Create New Course</button>
                    <form method="POST" action="export.php" style="display: inline;">
                        <button type="submit">Export Course List to CSV</button>
                    </form>
                    <button onclick="window.location.href='maincourse.php';">Go Back</button>
                </div>
            </div>

            <form method="POST">
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
                                    <button onclick=\"window.location.href='createcourse.php?edit={$row['course_id']}'\">Edit</button>
                                    <button onclick=\"alert('Delete feature pending!')\">Delete</button>
                                  </td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </form>
        </div>
    </div>
</body>
</html>
