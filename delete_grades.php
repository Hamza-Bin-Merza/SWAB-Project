<?php
include 'db_connection.php';

header('Content-Type: application/json'); // Set response type to JSON

$response = ["success" => false, "error" => "Invalid request."];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['student_id'], $_POST['course'], $_POST['module'])) {
    $student_id = $_POST['student_id'];
    $course = $_POST['course'];
    $module = $_POST['module'];

    // Check if the record exists and if course has ended
    $sql_check = "SELECT course_end_date FROM student_grades WHERE student_id=? AND course=? AND module=?";
    $stmt = $con->prepare($sql_check);
    $stmt->bind_param("iss", $student_id, $course, $module);
    $stmt->execute();
    $result_check = $stmt->get_result();

    if ($result_check->num_rows > 0) {
        $row = $result_check->fetch_assoc();
        $course_end_date = $row['course_end_date'];

        if (strtotime($course_end_date) < time()) {
            // Proceed with deletion
            $sql_delete = "DELETE FROM student_grades WHERE student_id=? AND course=? AND module=?";
            $stmt_delete = $con->prepare($sql_delete);
            $stmt_delete->bind_param("iss", $student_id, $course, $module);
            if ($stmt_delete->execute()) {
                $response["success"] = true;
                $response["error"] = null;
            } else {
                $response["error"] = "Failed to delete record.";
            }
            $stmt_delete->close();
        } else {
            $response["error"] = "Cannot delete record before the course has ended.";
        }
    } else {
        $response["error"] = "Record not found.";
    }
    $stmt->close();
}

echo json_encode($response); // Return JSON response
?>
