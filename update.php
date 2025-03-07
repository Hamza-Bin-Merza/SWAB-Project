<?php
include 'db_connection.php';

$success_message = '';
$error_message = '';

// Fetch student ID if it's passed via query string
if (isset($_GET['student_id'])) {
    $student_id = $_GET['student_id'];

    // Retrieve existing data for the student
    $stmt = $con->prepare("SELECT * FROM students WHERE student_id = ?");
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $student = $result->fetch_assoc();
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect form data
    $student_id = $_POST['student_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $student_number = $_POST['student_number'];
    $course = $_POST['course'];
    $department = $_POST['department'];

    // Prepare SQL to update the student record
    $stmt = $con->prepare("UPDATE students SET name=?, email=?, phone=?, student_number=?, course=?, department=? WHERE student_id=?");
    $stmt->bind_param("ssssssi", $name, $email, $phone, $student_number, $course, $department, $student_id);

    if ($stmt->execute()) {
        $success_message = "Student profile updated successfully.";
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
    <title>Update Student Profile</title>

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

        .home-icon {
            position: absolute;
            top: 15px;
            left: 20px;
            font-size: 24px;
            text-decoration: none;
            color: white;
            background-color: transparent;
            padding: 10px;
            border-radius: 5px;
        }

        .home-icon:hover {
            color: #ddd;
        }
    </style>
</head>
<body>

<h2>Update Student Profile
<a href="dashboard.php" class="home-icon">🏠</a>
</h2>

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

<div class="form-container">
    <form method="POST">
        <input type="hidden" name="student_id" value="<?php echo $student['student_id']; ?>">

        <div class="mb-3">
            <label for="name" class="form-label">First Name:</label>
            <input type="text" id="name" name="name" value="<?php echo $student['name']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo $student['email']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="phone" class="form-label">Phone:</label>
            <input type="tel" id="phone" name="phone" value="<?php echo $student['phone']; ?>">
        </div>
        <div class="mb-3">
            <label for="student_number" class="form-label">Student Number:</label>
            <input type="text" id="student_number" name="student_number" value="<?php echo $student['student_number']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="course" class="form-label">Course ID:</label>
            <input type="text" id="course" name="course" value="<?php echo $student['course']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="department" class="form-label">Department:</label>
            <select id="department" name="department" class="form-control" required>
                <option value="Engineering" <?php if($student['department'] == 'Engineering') echo 'selected'; ?>>Engineering</option>
                <option value="HSS" <?php if($student['department'] == 'HSS') echo 'selected'; ?>>HSS</option>
                <option value="IIT" <?php if($student['department'] == 'IIT') echo 'selected'; ?>>IIT</option>
                <option value="Applied Science" <?php if($student['department'] == 'Applied Science') echo 'selected'; ?>>Applied Science</option>
                <option value="Business" <?php if($student['department'] == 'Business') echo 'selected'; ?>>Business</option>
                <option value="Design" <?php if($student['department'] == 'Design') echo 'selected'; ?>>Design</option>
            </select>
        </div>
        <div>
        <button type="submit">Update Profile</button>
    </form>
        </div>
</div>

<div class="button-container">
    <button onclick="window.location.href='read.php'">View All Student Profiles</button>
</div>

</body>
</html>
