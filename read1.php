<?php
include 'db1.php';

// Query to fetch all classes from the database
$result = $con->query("SELECT * FROM classes");

echo "<h2>Class List</h2>";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Class List</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(to bottom right, #1e3c72, #2a5298);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            min-height: 100vh;
            color: #f0f8ff;
        }

        h2 {
            color: #f0f8ff;
            margin-bottom: 20px;
            font-size: 2rem;
            text-shadow: 1px 1px 5px rgba(0, 0, 0, 0.5);
        }

        table {
            width: 90%;
            max-width: 1200px;
            margin: 20px auto;
            border-collapse: collapse;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
        }

        table th, table td {
            text-align: left;
            padding: 15px;
        }

        table th {
            background-color: #3c6997;
            color: #f0f8ff;
            font-size: 16px;
            text-transform: uppercase;
        }

        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        table tr:nth-child(odd) {
            background-color: #eaf4f9;
        }

        table tr:hover {
            background-color: #d9edf7;
        }

        table td {
            color: #1e3c72;
        }

        table td a {
            text-decoration: none;
            color: #1e3c72;
            font-weight: bold;
            transition: color 0.3s ease;
        }

        table td a:hover {
            text-decoration: underline;
            color: #3c6997;
        }

        .btn {
            display: inline-block;
            background-color: #3c6997;
            color: white;
            padding: 12px 25px;
            margin: 20px 0;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .btn:hover {
            background-color: #295a87;
            transform: translateY(-2px);
        }

        .container {
            text-align: center;
            padding: 20px;
            backdrop-filter: blur(8px);
            background: rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }

        .no-classes {
            font-size: 18px;
            color: #f0f8ff;
        }

        @media (max-width: 768px) {
            table {
                width: 100%;
            }

            h2 {
                font-size: 1.8rem;
            }
        }
    </style>
</head>
<body>
    <img src="logo.png" alt="Logo" class="logo">
    <div class="container">
        <?php
        if ($result->num_rows > 0) {
            // Start table and set headers
            echo "<table>
            <tr>
                <th>ID</th>
                <th>Class Name</th>
                <th>Class Type</th>
                <th>Created By</th>
                <th>Created At</th>
                <th>Updated At</th>
                <th>Actions</th>
            </tr>";

            // Loop through each class and display its data in the table
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['class_name']}</td>
                    <td>{$row['class_type']}</td>
                    <td>{$row['created_by']}</td>
                    <td>{$row['created_at']}</td>
                    <td>{$row['updated_at']}</td>
                    <td>
                        <a href='update1.php?id={$row['id']}'>Edit</a> |
                        <a href='delete1.php?id={$row['id']}'>Delete</a>
                    </td>
                </tr>";
            }

            // End table
            echo "</table>";
        } else {
            echo "<p class='no-classes'>No classes found.</p>";
        }
        ?>

        <!-- Add the button again below the table -->
        <a href="create1.php" class="btn">Create New Class</a>
    </div>
</body>
</html>
