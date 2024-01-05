<?php
include('database.php');
session_start();

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Assuming form is submitted with POST method
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $course_id = $_POST["course"];
    $exam_name = $_POST["exam_name"];
    $exam_description = $_POST["exam_description"];

    // Convert exam_date to "yy-mm-dd" format
    $originalDate = $_POST["exam_date"];
    $exam_date = date("Y-m-d", strtotime($originalDate));

    $exam_time = $_POST["exam_start_time"];
    $duration = $_POST["duration"];

    // You might want to validate and sanitize user inputs before inserting into the database

    $insertQuery = "INSERT INTO exam (course_id, exam_name, exam_description, exam_date, exam_time, duration, exam_status)
                    VALUES (?, ?, ?, ?, ?, ?, 0)";

    $stmt = $con->prepare($insertQuery);

    if (!$stmt) {
        die("Error preparing the insert query: " . $con->error);
    }

    $stmt->bind_param("issssi", $course_id, $exam_name, $exam_description, $exam_date, $exam_time, $duration);

    if ($stmt->execute()) {
        echo "Exam scheduled successfully!";
        header("location:tutor-add-question.php");
    } else {
        echo "Error scheduling exam: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Invalid request method.";
}

$con->close();
?>
