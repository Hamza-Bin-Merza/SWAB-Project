<?php
include 'db1.php';

// Check if ID is passed in the URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    // Get class data for the given ID
    $stmt = $con->prepare("SELECT * FROM classes WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    // If the class exists, fetch its data
    if ($result->num_rows > 0) {
        $class = $result->fetch_assoc();
    } else {
        die("Class not found.");
    }
} else {
    die("Invalid class ID.");
}

// Update data if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $class_name = $_POST['class_name'];
    $class_type = $_POST['class_type'];
    $created_by = $_POST['created_by'];

    // Prepare and execute update query
    $stmt = $con->prepare("UPDATE classes SET class_name = ?, class_type = ?, created_by = ?, updated_at = NOW() WHERE id = ?");
    $stmt->bind_param("sssi", $class_name, $class_type, $created_by, $id);

    if ($stmt->execute()) {
        // Redirect back to the main page or the updated class list page
        header("Location: read1.php"); // Replace 'read_class.php' with your actual class list page
        exit;
    } else {
        echo "Error updating class: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Class</title>
    <style>
        /* Your CSS design from update_classes.php */
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(120deg, #1e3c72, #2a5298);
            margin: 0;
            padding: 0;
            color: #333;
        }

        .container {
            max-width: 700px;
            margin: 50px auto;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            padding: 30px;
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 25px;
        }

        form label {
            font-weight: bold;
            margin-bottom: 8px;
            display: block;
            color: #444;
        }

        form input, form select, form button {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        form button {
            background: linear-gradient(90deg, #1e90ff, #87ceeb);
            color: #fff;
            font-weight: bold;
            border: none;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        form button:hover {
            background: linear-gradient(90deg, #87ceeb, #1e90ff);
        }

        .back-link {
            text-align: center;
            margin-top: 20px;
        }

        .back-link a {
            text-decoration: none;
            background: linear-gradient(90deg, #56ccf2, #2f80ed);
            color: #fff;
            padding: 12px 25px;
            border-radius: 5px;
            font-weight: bold;
            transition: background 0.3s ease;
        }

        .back-link a:hover {
            background: linear-gradient(90deg, #2f80ed, #56ccf2);
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Update Class</h2>
    <form method="POST">
        <label for="class_name">Class Name:</label>
        <input type="text" id="class_name" name="class_name" value="<?php echo htmlspecialchars($class['class_name']); ?>" required>
        
        <label for="class_type">Class Type:</label>
        <select id="class_type" name="class_type" required>
            <option value="semester" <?php echo ($class['class_type'] == 'semester') ? 'selected' : ''; ?>>Semester</option>
            <option value="term" <?php echo ($class['class_type'] == 'term') ? 'selected' : ''; ?>>Term</option>
        </select>
        
        <label for="created_by">Created By:</label>
        <select id="created_by" name="created_by" required>
            <option value="Admin" <?php echo ($class['created_by'] == 'Admin') ? 'selected' : ''; ?>>Admin</option>
            <option value="Faculty" <?php echo ($class['created_by'] == 'Faculty') ? 'selected' : ''; ?>>Faculty</option>
        </select>
        
        <button type="submit">Update Class</button>
    </form>

    <div class="back-link">
        <a href="read1.php">Back to Classes</a>
    </div>
</div>
</body>
</html>
