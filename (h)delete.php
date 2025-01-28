<?php
include 'db_connection.php';

$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect student ID to delete
    $student_id = $_POST['student_id'];

    // Prepare SQL to delete the student record
    $stmt = $con->prepare("DELETE FROM students WHERE student_id=?");
    $stmt->bind_param("i", $student_id);

    if ($stmt->execute()) {
        $success_message = "Student profile deleted successfully.";
    } else {
        $error_message = "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Student Profile</title>

    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #1E3C72;
            margin: 0;
            padding: 0;
        }

        h2 {
            text-align: center;
            margin-top: 40px;
            color: #1E3C72;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 26%;
            margin-left: auto;
            margin-right: auto;
        }

        .form-container {
            max-width: 500px;
            width: 100%;
            margin: 30px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .form-container label {
            font-size: 14px;
            color: #664EAE;
            display: block;
            margin-bottom: 8px;
        }

        .form-container input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #C4A8FF;
            border-radius: 4px;
            font-size: 14px;
        }

        .form-container button {
            width: 100%;
            padding: 12px;
            background-color: #664EAE;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .form-container button:hover {
            background-color: #1A0554;
        }

        .alert {
            padding: 10px;
            margin: 10px;
            border-radius: 4px;
        }

        .alert-success {
            background-color: #C4A8FF;
            color: #1A0554;
        }

        .alert-danger {
            background-color: #0C0B13;
            color: #C4A8FF;
        }

        .button-container {
            text-align: center;
            margin-top: 20px;
        }

        .button-container button {
            background-color: #664EAE;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .button-container button:hover {
            background-color: #1A0554;
        }
    </style>
</head>
<body>

<h2>Delete Student Profile</h2>

<?php if ($success_message): ?>
    <div class="alert alert-success">
        <?php echo $success_message; ?>
    </div>
<?php endif; ?>

<?php if ($error_message): ?>
    <div class="alert alert-danger">
        <?php echo $error_message; ?>
    </div>
<?php endif; ?>

<!-- Confirmation Form -->
<div class="form-container">
    <form id="delete-form" method="POST" onsubmit="return confirmDelete()">
        <div class="mb-3">
            <label for="student_id" class="form-label">Student ID:</label>
            <input type="text" id="student_id" name="student_id" required>
        </div>
        <button type="submit">Delete Profile</button>
    </form>
</div>

<!-- Confirmation Prompt -->
<script>
    function confirmDelete() {
        const studentId = document.getElementById('student_id').value;
        if (confirm('Are you sure you want to delete the profile with Student ID: ' + studentId + '?')) {
            return true; // Proceed with form submission
        } else {
            return false; // Cancel form submission
        }
    }
</script>

<!-- Button container to go back to the student profiles list -->
<div class="button-container">
    <button onclick="window.location.href='read.php'">View All Student Profiles</button>
</div>

</body>
</html>
