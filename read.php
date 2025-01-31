<?php
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Admin can view all student records
    $sql = "SELECT * FROM students";
    $result = $con->query($sql);

    if ($result->num_rows > 0) {
        // Display HTML with CSS and PHP logic
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>View Student Profiles</title>
            <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
            <style>
                body {
                    font-family: 'Roboto', sans-serif;
                    background-color: #B0C4DE; /* Background color */
                    margin: 0;
                    padding: 0;
                }

                header {
                    background-color: #1e3c72; /* Dark blue header */
                    color: white;
                    padding: 20px;
                    text-align: center;
                    position: relative;
                }

                h2 {
                    color: #1e3c72;
                    text-align: center;
                    background-color: #fff;
                    padding: 20px;
                    margin: 40px auto;
                    width: 80%;
                    border-radius: 8px;
                    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                }

                table {
                    width: 80%;
                    margin: 30px auto;
                    border-collapse: collapse;
                    background-color: #fff; /* White background for the table */
                    border-radius: 8px;
                    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                }

                th, td {
                    padding: 12px 15px;
                    text-align: left;
                    border-bottom: 1px solid #ddd;
                }

                th {
                    background-color: #1e3c72; /* Dark blue for header */
                    color: white;
                }

                tr:nth-child(even) {
                    background-color: #f9f9f9;
                }

                tr:hover {
                    background-color: #e0e0e0;
                }

                .button-container {
                    text-align: center;
                    margin-top: 20px;
                }

                .button-container button {
                    background-color: #1e3c72; /* Dark blue background */
                    color: white;
                    padding: 12px 24px;
                    border: none;
                    border-radius: 5px;
                    font-size: 16px;
                    cursor: pointer;
                    transition: background-color 0.3s ease;
                }

                .button-container button:hover {
                    background-color: #163c63; /* Darker blue on hover */
                }

                .btn-link {
                    background-color: #F44336; /* Red for delete button */
                    color: white;
                    padding: 5px 10px;
                    text-decoration: none;
                    border-radius: 4px;
                    transition: background-color 0.3s ease;
                }

                .btn-link:hover {
                    background-color: #d32f2f; /* Darker red on hover */
                }

                .alert {
                    background-color: #d4edda;
                    color: #155724;
                    border: 1px solid #c3e6cb;
                    padding: 10px;
                    margin: 10px auto;
                    width: 80%;
                    border-radius: 5px;
                    text-align: center;
                }
            </style>
        </head>
        <body>

        <header>
            <h2>Student Profiles</h2>
        </header>

        <!-- Display Success or Error Message -->
        <?php if (isset($success_message)): ?>
            <div class="alert"><?php echo $success_message; ?></div>
        <?php endif; ?>

        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Course</th>
                <th>Department</th>
                <th>Actions</th> <!-- Add an Actions column -->
            </tr>

            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['student_id'] ?></td>
                <td><?= $row['name'] ?></td>
                <td><?= $row['email'] ?></td>
                <td><?= $row['phone'] ?></td>
                <td><?= $row['course'] ?></td>
                <td><?= $row['department'] ?></td>
                <td>
                    <a href="delete.php?student_id=<?= $row['student_id'] ?>" class="btn-link">Delete</a>
                    <a href="update.php?student_id=<?= $row['student_id'] ?>" class="btn-link">Update</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>

        <!-- Create New Student Profile Button -->
        <div class="button-container">
            <button onclick="window.location.href='create.php'">Create New Student Profile</button>
        </div>

        </body>
        </html>
        <?php
    } else {
        echo "<div class='alert'>No student records found.</div>";
    }
}
?>
