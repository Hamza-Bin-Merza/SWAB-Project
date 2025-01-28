<?php
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    // Hash password
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // Prepare SQL query
    $sql = "INSERT INTO users (username, password_hash, email, role)
            VALUES (?, ?, ?, ?)";

    if ($stmt = $con->prepare($sql)) {
        $stmt->bind_param('ssss', $username, $password_hash, $email, $role);
        if ($stmt->execute()) {
            echo "User registered successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $con->error;
    }
}
?>

<form method="POST">
    Username: <input type="text" name="username" required><br>
    Password: <input type="password" name="password" required><br>
    Email: <input type="email" name="email" required><br>
    Role: 
    <select name="role" required>
        <option value="Admin">Admin</option>
        <option value="Faculty">Faculty</option>
        <option value="Student">Student</option>
    </select><br>
    <button type="submit">Register</button>
</form>
