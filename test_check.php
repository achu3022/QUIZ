<?php
//include('student-header.php');
include('database.php');
session_start();
$course_id = $_SESSION["course_id"];

// EXAM TABLE CONNECTION 
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Fetch exam date and time from the exam table
$examQuery = "SELECT exam_date, exam_time, duration FROM exam WHERE course_id = ?";
$stmtExam = $con->prepare($examQuery);

if (!$stmtExam) {
    die("Error preparing the exam query: " . $con->error);
}

$stmtExam->bind_param("s", $course_id);
$stmtExam->execute();
$resultExam = $stmtExam->get_result();

if ($resultExam->num_rows > 0) {
    $examData = $resultExam->fetch_assoc();
    
    // Combine exam_date and exam_time into a single datetime string
    $examDateTime = $examData['exam_date'] . ' ' . $examData['exam_time'];

    // Calculate exam start time in seconds
    $examStartTime = strtotime($examDateTime);

    // Calculate current time in seconds
    $currentTime = time();

    // Calculate time difference in seconds
    $timeDifference = ($examStartTime > $currentTime) ? ($examStartTime - $currentTime) : 0;

    // Convert time difference to hours
    $hoursUntilExam = round($timeDifference / 3600, 2); // 3600 seconds in an hour
    $timerSeconds=$examData['duration']*60;
    // Output the result
    echo "Hours until exam: $hoursUntilExam hours";
    echo "<br> Timer In Seconds $timerSeconds";
    echo "<br> Date and time $examDateTime";
} else {
    // Handle the case where exam data is not found
    echo "Exam data not found.";
}

// Close database connection
$stmtExam->close();
$con->close();
?>
