<?php
include('student-header.php');
include('database.php');
session_start();
$course_id = 2;
$user_id= 2;
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
   // echo "Hours until exam: $hoursUntilExam hours";
    //echo "<br> Timer In Seconds $timerSeconds";
} else {
    // Handle the case where exam data is not found
    echo "Exam data not found.";
}



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Include your head content here -->
    <script>
        // Timer for questions (in seconds)
        var timerSeconds = <?php echo $timerSeconds;?>; // 5 minutes

        function startTimer() {
            var timer = setInterval(function () {
                timerSeconds--;

                if (timerSeconds <= 0) {
                    clearInterval(timer);
                    alert("Your time has ended. Answers will be submitted.");
                    document.getElementById('markingForm').submit();
                }

                document.getElementById('examStopTimer').innerText = formatTime(timerSeconds);
            }, 1000);
        }

        function formatTime(seconds) {
            var minutes = Math.floor(seconds / 60);
            var remainingSeconds = seconds % 60;
            return minutes + ":" + (remainingSeconds < 10 ? "0" : "") + remainingSeconds;
        }

        function submitForm() {
            var formData = new FormData(document.getElementById('markingForm'));

            fetch('start-quiz-action.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Responses saved successfully!');
                    // Optionally, update the page dynamically here
                } else {
                    alert('Error saving responses. Please try again.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error saving responses. Please try again.');
            });
        }
    </script>
</head>

<body class="page-header-fixed sidemenu-closed-hidelogo page-content-white page-md page-full-width header-white white-sidebar-color logo-indigo">
    <div class="page-wrapper">
        <!-- start header -->
        <!-- end sidebar menu -->
        <!-- start page content -->
        <div class="page-content-wrapper">
            <div class="page-content">
                <div class="page-bar">
                    <div class="page-title-breadcrumb">
                        <div class="pull-left">
                            <div class="page-title">Quiz</div>
                        </div>
                        <ol class="breadcrumb page-breadcrumb pull-right">
                            <li>
                                <li><i class="fa fa-clock"></i>&nbsp;
                                    Your exam ends in&nbsp; <span id="examStopTimer" style="color:red;font-style:bold;"></span></i>
                                </li>
                            </li>
                        </ol>
                    </div>
                </div>

                <!-- Exam Start Timer Script -->
                <script>
    // Exam Start Time (replace with PHP variable)
    var examStartTime = <?php echo json_encode($examDateTime); ?>; // Replace with your scheduled time
    var now = new Date();
    var timeDifference = Math.floor((new Date(examStartTime) - now) / 1000);

    function startExamStartTimer() {
        var examStartTimer = setInterval(function () {
            timeDifference--;

            if (timeDifference <= 0) {
                clearInterval(examStartTimer);
                document.getElementById('timerSection').style.display = 'none';
                document.getElementById('questionsSection').style.display = 'block';
                startTimer();
            } else {
                document.getElementById('examStartTimer').innerText = formatTime(timeDifference);
            }
        }, 1000);
    }

    function formatTime(seconds) {
        var hours = Math.floor(seconds / 3600);
        var minutes = Math.floor((seconds % 3600) / 60);
        var remainingSeconds = seconds % 60;
        
        return hours + ":" + (minutes < 10 ? "0" : "") + minutes + ":" + (remainingSeconds < 10 ? "0" : "") + remainingSeconds;
    }

    window.onload = function () {
        if (timeDifference > 0) {
            startExamStartTimer();
        } else {
            document.getElementById('timerSection').style.display = 'none';
            document.getElementById('questionsSection').style.display = 'block';
            startTimer();
        }
    };
</script>
                <div id="timerSection">
                    <p style="color:white;text-align:center;font-size:24px;">Exam will start in: <span id="examStartTimer" style="color:Green;text-align:center;font-size:24px;"></span></p>
                </div>

                <!-- DB CONNECTION -->
                <?php
                $qry = "SELECT * FROM tbl_question  WHERE  course_id = '$course_id' and status='0'";
                $result = $con->query($qry);

                if ($result->num_rows > 0) {
                    $questions = mysqli_fetch_all($result, MYSQLI_ASSOC);
                } else {
                    $questions = array(); // Empty array if no questions found
                }

                $options = array();
                foreach ($questions as $question) {
                    $questionId = $question['quest_id'];
                    $qryOptions = "SELECT * FROM `tbl_question_answer` WHERE quest_id = $questionId";
                    $resultOptions = $con->query($qryOptions);

                    if ($resultOptions->num_rows > 0) {
                        $options[$questionId] = mysqli_fetch_all($resultOptions, MYSQLI_ASSOC);
                    } else {
                        $options[$questionId] = array(); // Empty array if no options found
                    }
                }

                $con->close();
                ?>

                <!-- Questions Section -->
                <div id="questionsSection" style="display: none;">
                    <div class="col-lg-8">
                        <div class="row ">
                            <?php if (empty($questions)): ?>
                                <p>No questions found. </p>
                            <?php else: ?>
                                <form action="#" method="POST" id="markingForm">
                                    <?php $questionNumber = 1; ?>
                                    <?php $markedQuestionsCount = 0; ?>
                                    <?php foreach ($questions as $question): ?>
                                        <p style="color: white; font-size: 17px;"><?php echo $questionNumber . '. ' . $question['question']; ?></p>

                                        <?php $questionId = $question['quest_id']; ?>
                                        <?php if (isset($options[$questionId]) && !empty($options[$questionId])): ?>
                                            <?php foreach ($options[$questionId] as $option): ?>
                                                <p style="color: #bacccf; font-size: 17px;">
                                                    <input type="radio" onchange="submitForm()"
                                                        name="answer[<?php echo $questionId; ?>]"
                                                        value="<?php echo $option['ans_id']; ?>"
                                                        <?php
                                                            if (isset($_POST['answer'][$questionId]) && $_POST['answer'][$questionId] == $option['ans_id']) {
                                                                echo 'checked';
                                                                $markedQuestionsCount++;
                                                            }
                                                        ?>
                                                    >
                                                    <?php echo $option['answer']; ?>
                                                </p>
                                            <?php endforeach; ?>
                                        <?php endif; ?>

                                        <?php $questionNumber++; ?>
                                    <?php endforeach; ?>

                                    <input type="hidden" name="markedQuestionsCount" value="<?php echo $markedQuestionsCount; ?>">
                                    <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">

                                    <button type="button" class="btn btn-success" onclick="submitForm()">Submit Answers</button>
                                </form>
                            <?php endif;
                            // Close database connection
$stmtExam->close();
//$con->close(); ?>

                            
                        </div>
                        <div class="row">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
<?php

include("student-footer.php");

?>