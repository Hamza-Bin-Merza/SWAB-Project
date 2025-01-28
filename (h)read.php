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
            <style>
                body {
                    background-color: #1E3C72; /* Set background color */
                    color: black; /* Default black text */
                    font-family: Arial, sans-serif; /* Default font */
                    margin: 0;
                    padding: 0;
                }

                h2 {
                    text-align: center;
                    margin-top: 40px;
                    color: #000; /* Black text for the title */
                    background-color: #fff; /* White background for header */
                    padding: 20px;
                    border-radius: 8px;
                    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                    width: 90%;
                    margin-left: auto;
                    margin-right: auto;
                }

                table {
                    width: 90%;
                    margin: 30px auto;
                    border-collapse: collapse;
                    background-color: #fff; /* White background for the table */
                    border-radius: 8px;
                    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                }

                th, td {
                    padding: 12px 15px;
                    text-align: left;
                }

                th {
                    background-color: #ddd; /* Light gray for header */
                }

                tr:nth-child(even) {
                    background-color: #f9f9f9; /* Light gray for even rows */
                }

                tr:hover {
                    background-color: #e0e0e0; /* Slightly darker gray on hover */
                }

                td {
                    border-bottom: 1px solid #ddd;
                }

                .button-container {
                    text-align: center;
                    margin-top: 20px;
                }

                .button-container button {
                    background-color: #664EAE; /* Purple background */
                    color: white;
                    padding: 12px 24px;
                    border: none;
                    border-radius: 4px;
                    font-size: 16px;
                    cursor: pointer;
                    transition: background-color 0.3s ease;
                }

                .button-container button:hover {
                    background-color: #1A0554; /* Darker purple on hover */
                }

                .btn-link {
                    background-color: #F44336; /* Red background for delete button */
                    color: white;
                    padding: 5px 10px;
                    text-decoration: none;
                    border-radius: 4px;
                    transition: background-color 0.3s ease;
                }

                .btn-link:hover {
                    background-color: #d32f2f; /* Darker red on hover */
                }
            </style>
        </head>
        <body>

        <h2>All Student Profiles</h2>

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
        echo "<div class='alert alert-danger'>No student records found.</div>";
    }
}
?>
