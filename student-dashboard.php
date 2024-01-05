<?php 
include('student-header.php');
//include ('dbcon.php');
include('database.php');
//$user_id = $_SESSION['user_id'];
$user_id=5065;
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
   // echo "Hours until exam: $hoursUntilExam hours";
    //echo "<br> Timer In Seconds $timerSeconds";
} else {
    // Handle the case where exam data is not found
    echo "Exam data not found.";
}


/*$sql = "SELECT u.*,c.course_name,u.course_id as enrolled
FROM student_course u LEFT JOIN course_master c ON u.course_id = c.course_id
WHERE u.user_id =$user_id ";

$query = mysqli_query($con, $sql);
$row = mysqli_fetch_assoc($query);

$course_id = $row['enrolled'];
$course_name = $row['course_name'];

$sql_common = "SELECT COUNT(id) as total FROM `course_materials` WHERE course_id=0 AND cat_id='0'";
$query1 = mysqli_query($con, $sql_common);
$row1 = mysqli_fetch_assoc($query1);

$sql_course = "SELECT COUNT(id) as total FROM `course_materials` WHERE course_id='$course_id'";
$query2 = mysqli_query($con, $sql_course);
$row2 = mysqli_fetch_assoc($query2);

$sql_assignment = "SELECT COUNT(assignment_id) as total FROM `assignment_master` WHERE course_id='$course_id'";
$query3 = mysqli_query($con, $sql_assignment);
$row3 = mysqli_fetch_assoc($query3);

$sql_assignment_sql = "SELECT s.*,am.added_date,am.title FROM `assignment_submission` s
LEFT JOIN assignment_master am ON am.assignment_id  = s.assignment_id  
WHERE user_id='$user_id'";
$query4 = mysqli_query($con, $sql_assignment_sql);
//$row4 = mysqli_fetch_assoc($query4);



//$sql = ""*/
?>

<style type="text/css">
	.fc-toolbar-title{
		color:white;
	}
	.fc-scrollgrid-sync-inner a{
		color:#ff798f;
	}
	.fc .fc-daygrid-day-number {
		color:white;
	}
</style>
<!-- start page content -->
<div class="page-content-wrapper">
<div class="page-content">
<div class="page-bar">
<div class="page-title-breadcrumb">
<div class=" pull-left">
<div class="page-title">Dashboard</div>
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

                                    <button type="button" onclick="submitForm()">Submit Answers</button>
                                </form>
                            <?php endif; ?>

                            <div class="col-lg-4 col-md-12 col-sm-12 col-12">
                                <p>Marked Questions: <span id="markedQuestionsCount"><?php echo $markedQuestionsCount; ?></span></p>
                            </div>
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

$stmtExam->close();
$con->close();

?>


<script>
    /* document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            events: 'get-events.php',
            eventClick: function(info) {              
                $('#eventDetails').html('<p><strong>' + info.event.title + '</strong></p><p>' + info.event.extendedProps.description + '</p>');
                $('#eventModal').modal('show');
            },
            eventRender: function(info) {           
                info.el.querySelector('.fc-content').innerHTML += '<div class="fc-type">' + info.event.extendedProps.title + '</div>';
            }
        });
        calendar.render();
    }); */
	
	document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        events: 'get-events.php', // PHP script to fetch events
        eventClick: function(info) {
            // Display event details in modal
            $('#eventDetails').html('<p><strong>' + info.event.title + '</strong></p><p>' + info.event.extendedProps.description + '</p>');
            $('#eventModal').modal('show');
        },
        eventContent: function(arg) {
            // Customize the content of each event
            var html = '<div class="fc-title">' + arg.event.title + '</div>';
            return { html: html };
        }
    });
    calendar.render();
});

</script>

		<?php include('student-footer.php');?>
		
	<style>
    .fc-type {
        font-size: 10px;
        color: #fff;
        background-color: #007bff;
        padding: 2px 5px;
        border-radius: 3px;
        margin-top: 5px;
    }
	
	.fc-title {
    max-height: 40px; /* Adjust the maximum height as needed */
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: normal;
}
</style>
	
