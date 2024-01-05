<?php
include('database.php');

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Retrieve marked questions count from the form submission
$markedQuestionsCount = isset($_POST['markedQuestionsCount']) ? intval($_POST['markedQuestionsCount']) : 0;

// Retrieve user responses
$userResponses = isset($_POST['answer']) ? $_POST['answer'] : array();

// Fetch questions and options for marked questions
$markedQuestions = array();
foreach ($userResponses as $questionId => $userAnswerId) {
    $qryQuestion = "SELECT * FROM tbl_question WHERE quest_id = ?";
    $stmtQuestion = $con->prepare($qryQuestion);
    $stmtQuestion->bind_param("i", $questionId);
    $stmtQuestion->execute();
    $resultQuestion = $stmtQuestion->get_result();

    $qryOptions = "SELECT * FROM tbl_question_answer WHERE quest_id = ?";
    $stmtOptions = $con->prepare($qryOptions);
    $stmtOptions->bind_param("i", $questionId);
    $stmtOptions->execute();
    $resultOptions = $stmtOptions->get_result();

    $qryCorrect = "SELECT * FROM tbl_question_answer WHERE quest_id = ? AND ans_id = ?";
    $stmtCorrect = $con->prepare($qryCorrect);
    $stmtCorrect->bind_param("ii", $questionId, $userAnswerId);
    $stmtCorrect->execute();
    $resultCorrect = $stmtCorrect->get_result();

    if ($resultQuestion->num_rows > 0 && $resultOptions->num_rows > 0) {
        $questionData = $resultQuestion->fetch_assoc();
        $optionsData = $resultOptions->fetch_all(MYSQLI_ASSOC);

        $markedQuestions[] = array(
            'question' => htmlspecialchars($questionData['question']),
            'user_answer' => getUserAnswerText($optionsData, $userAnswerId),
            'options' => $optionsData
        );
        $date = date('y-m-d');
        if ($resultCorrect->num_rows > 0) {
            $answerData = $resultCorrect->fetch_assoc();
            echo $answerData['is_correct'];
            if ($answerData['is_correct'] == 1) {
                $is_correct = 1;
                echo "correct answer";
            } else {
                $is_correct = 0;
                echo "wrong answer";
            }
                // Save the user response to tbl_user_answer
                $ansqry = "INSERT INTO tbl_user_answer (cat_id, user_id, quest_id, ans_id, attempt, is_correct, added_date, type, is_current)
                           VALUES ('1', '1', ?, ?, '1', ?, ?, '1', '');";
                $statementcorrect = $con->prepare($ansqry);
                $statementcorrect->bind_param("iiis", $questionId, $userAnswerId, $is_correct, $date);
                $statementcorrect->execute();
            
        }
    }
}

$con->close();

// Helper function to get user answer text based on the answer ID
function getUserAnswerText($options, $userAnswerId)
{
    foreach ($options as $option) {
        if ($option['ans_id'] == $userAnswerId) {
            return htmlspecialchars($option['answer']);
        }
    }
    return 'Not answered';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marked Questions</title>
</head>

<body>
    <h1>Marked Questions</h1>

    <?php if (!empty($markedQuestions)): ?>
        <?php foreach ($markedQuestions as $markedQuestion): ?>
            <div class="marked-question">
                <p>Question: <?php echo $markedQuestion['question']; ?></p>
                <p>User Answer: <?php echo $markedQuestion['user_answer']; ?></p>
                <p>Options:</p>
                <ul>
                    <?php foreach ($markedQuestion['options'] as $option): ?>
                        <li><?php echo htmlspecialchars($option['answer']); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No marked questions found.</p>
    <?php endif; ?>
</body>

</html>
