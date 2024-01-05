
<?php include('student-header.php'); ?>

<!DOCTYPE html>
<html lang="en">

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
                                <i class="fa fa-clock"></i>&nbsp;<span id="examStopTimer" style="color:red;"></span>
                            </li>
                        </ol>
                    </div>
                </div>

                <!-- Exam Start Timer Script -->
                <script>
                    // Exam Start Time (replace with your scheduled time)
                    var examStartTime = new Date("2023-12-27T17:50:00"); // Replace with your scheduled time
                    var now = new Date();

                    // Calculate time difference in seconds
                    var timeDifference = Math.floor((examStartTime - now) / 1000);

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
                        var minutes = Math.floor(seconds / 60);
                        var remainingSeconds = seconds % 60;
                        return minutes + ":" + (remainingSeconds < 10 ? "0" : "") + remainingSeconds;
                    }

                    window.onload = function () {
                        if (timeDifference > 0) {
                            startExamStartTimer();
                        } else {
                            // If the scheduled time has already passed, show questions
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
                 $user_id = 30;
                 $cat_id = 10;
                 $type = 1;
        // Fetch questions and answers from the database
        $qry = "SELECT * FROM tbl_user_answer u LEFT JOIN tbl_question q ON q.quest_id=u.quest_id WHERE user_id='$user_id'";
        $result = $conn->query($qry);

        if ($result->num_rows > 0) {
            $questions = mysqli_fetch_all($result, MYSQLI_ASSOC);
        } else {
            $questions = array(); // Empty array if no questions found
        }

        // Fetch options for each question
        $options = array();
        foreach ($questions as $question) {
            $questionId = $question['quest_id'];
            $qryOptions = "SELECT * FROM `tbl_question_answer` WHERE quest_id = $questionId";
            $resultOptions = $conn->query($qryOptions);

            if ($resultOptions->num_rows > 0) {
                $options[$questionId] = mysqli_fetch_all($resultOptions, MYSQLI_ASSOC);
            } else {
                $options[$questionId] = array(); // Empty array if no options found
            }
        }

        $conn->close();
        ?>

               <!-- Questions Section -->
        <div id="questionsSection" style="display: none;">
            <div class="col-lg-8">
                <div class="row ">
                    <?php if (empty($questions)): ?>
                        <p>No questions found.</p>
                    <?php else: ?>
                        <form action="start-quiz-action.php" method="POST" id="markingForm">
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
                                                    // Check if the answer is marked
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

                            <!-- Hidden input fields to store marked questions count and user_id -->
                            <input type="hidden" name="markedQuestionsCount" value="<?php echo $markedQuestionsCount; ?>">
                            <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">

                            <button type="submit" onclick="submitForm()">Submit Answers</button>
                        </form>
                        <script>
            function submitForm() {
                document.getElementById('markingForm').submit();
            }
        </script>
                    <?php endif; ?>

                    <div class="col-lg-4 col-md-12 col-sm-12 col-12">
                        <!-- Display marked questions count -->
                        <p>Marked Questions: <?php echo $markedQuestionsCount; ?></p>
                    </div>
                </div>
                <!-- start new student list -->
                <div class="row">
                </div>
                <!-- end new student list -->
            </div>
        </div>
                </div>
            </div>
        </div>
        <!-- end page content -->
		<div class="page-footer">
			<?php include('student-footer.php');?>
			</div>
    </div>

</body>
<!-- TIMER SCRIPT -->

<script>
    // Timer for questions (in seconds)
    var timerSeconds = 300; // 5 minutes

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

    // Uncomment the line below to start the timer after the exam has started
    // window.onload = function () {
    //     startTimer();
    // };
    function submitForm() {
                                document.getElementById('markingForm').submit();
                            }
                        
</script>
</html>
