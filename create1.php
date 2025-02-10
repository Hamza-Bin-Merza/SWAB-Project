<?php
include 'db1.php';

// Insert data if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $class_name = $_POST['class_name'];
    $class_type = $_POST['class_type'];
    $created_by = $_POST['created_by'];

    $stmt = $con->prepare("INSERT INTO classes (class_name, class_type, created_by) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $class_name, $class_type, $created_by);

    if ($stmt->execute()) {
        echo "Class created successfully.";
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
    <title>Create Class</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(#1e3c72, #2a5298);
            color: #333;
        }

        h2 {
            text-align: center;
            color: #fff;
            margin-top: 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            margin-top: 20px;
        }

        form label {
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
            color: #664eae;
        }

        form input, form select, form button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        form button {
            background: #664eae;
            color: #fff;
            font-weight: bold;
            border: none;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        form button:hover {
            background: #5a4396;
        }

        .message {
            text-align: center;
            font-weight: bold;
            padding: 10px;
            color: #fff;
            margin-bottom: 10px;
            border-radius: 5px;
        }

        .message.success {
            background-color: #28a745;
        }

        .message.error {
            background-color: #dc3545;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        table th, table td {
            text-align: center;
            padding: 10px;
            border: 1px solid #ddd;
        }

        table thead {
            background: #664eae;
            color: #fff;
        }

        table tbody tr:nth-child(odd) {
            background: #f9f9f9;
        }

        table tbody tr:nth-child(even) {
            background: #e9f5ff;
        }

        table tbody tr:hover {
            background: #00c6ff;
            color: #fff;
        }

        .back-button {
            text-align: center;
            margin: 20px 0;
        }

        .back-button a {
            text-decoration: none;
        }

        .back-button button {
            background: #664eae;
            color: #fff;
            font-size: 16px;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .back-button button:hover {
            background: #5a4396;
        }
    </style>
</head>
<body>

<h2>Create Class</h2>

<div class="container">
    <?php if (isset($message)): ?>
        <p class="message <?= strpos($message, 'success') !== false ? 'success' : 'error' ?>">
            <?= $message ?>
        </p>
    <?php endif; ?>

    <form method="POST">
        <label for="class_name">Class Name:</label>
        <input type="text" id="class_name" name="class_name" required>
        
        <label for="class_type">Class Type:</label>
        <select id="class_type" name="class_type" required>
            <option value="semester">Semester</option>
            <option value="term">Term</option>
        </select>
        
        <label for="created_by">Created By:</label>
        <select id="created_by" name="created_by" required>
            <option value="Admin">Admin</option>
            <option value="Faculty">Faculty</option>
        </select>

        <button type="submit">Create Class</button>
    </form>
</div>

<div class="back-button">
    <a href="read1.php">
        <button>Go Back</button>
    </a>
</div>

<h2>Class List</h2>
<div class="container">
    <table>
        <thead>
            <tr>
                <th>Class Name</th>
                <th>Class Type</th>
                <th>Created By</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $result = $con->query("SELECT * FROM classes");
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td>{$row['class_name']}</td>
                        <td>{$row['class_type']}</td>
                        <td>{$row['created_by']}</td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='3'>No classes found.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

</body>
</html>
