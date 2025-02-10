<?php
include 'db1.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Check if the class with the provided ID exists in the database
    $stmt = $con->prepare("SELECT id FROM classes WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    // If the class exists, delete it
    if ($result->num_rows > 0) {
        $stmt = $con->prepare("DELETE FROM classes WHERE id = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            echo "<script>alert('Class deleted successfully.');</script>";
        } else {
            echo "<script>alert('Error: Unable to delete class.');</script>";
        }
    } else {
        echo "<script>alert('Class ID not found in the database.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Class</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(#1e3c72, #2a5298);
            margin: 0;
            padding: 0;
            color: #333;
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            padding: 20px;
        }

        h2, h3 {
            text-align: center;
            color: #664eae;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        table th, table td {
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
        }

        table thead {
            background: #664eae;
            color: #fff;
        }

        table tbody tr:nth-child(odd) {
            background: #f0f8ff;
        }

        table tbody tr:nth-child(even) {
            background: #e6f7ff;
        }

        table tbody tr:hover {
            background: #a1c4fd;
            color: #fff;
        }

        .action-link a {
            text-decoration: none;
            padding: 8px 15px;
            border-radius: 5px;
            margin: 0 5px;
            display: inline-block;
            background: #664eae;
            color: #fff;
            font-weight: bold;
            transition: background 0.3s ease;
        }

        .action-link a:hover {
            background: #5a4396;
        }

        .go-back {
            display: block;
            text-align: center;
            margin: 20px 0;
        }

        .go-back button {
            padding: 10px 20px;
            font-size: 16px;
            font-weight: bold;
            border: none;
            border-radius: 5px;
            background: #664eae;
            color: #fff;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .go-back button:hover {
            background: #5a4396;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Delete Class</h2>

    <h3>Class List</h3>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Class Name</th>
                <th>Class Type</th>
                <th>Created By</th>
                <th>Created At</th>
                <th>Updated At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $result = $con->query("SELECT * FROM classes");
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['class_name']}</td>
                        <td>{$row['class_type']}</td>
                        <td>{$row['created_by']}</td>
                        <td>{$row['created_at']}</td>
                        <td>{$row['updated_at']}</td>
                        <td class='action-link'>
                            <a href='delete1.php?id={$row['id']}' onclick=\"return confirm('Are you sure you want to delete this class?');\">Delete</a>
                        </td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='7'>No classes found.</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <div class="go-back">
        <a href="read1.php">
            <button>Go Back</button>
        </a>
    </div>
</div>

</body>
</html>
