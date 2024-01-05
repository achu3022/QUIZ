<?php
include('database.php');

// Assuming you are receiving the data through POST method
$course_id = $_POST['course'];
$question_text = $_POST['exam_question'];
$options = array(
    $_POST['option_one'],
    $_POST['option_two'],
    $_POST['option_three'],
    $_POST['option_four']
);
$correct_answer = $_POST['correct_option'];
$added_date = date('Y-m-d H:i:s'); // Assuming the added_date is the current date and time

// Insert question into tbl_question
$sql_question = "INSERT INTO tbl_question (course_id, que_set, question, added_date, status)
                 VALUES ('$course_id', '1', '$question_text', '$added_date', '1')";

if ($con->query($sql_question) === TRUE) {
    $qstn_id = $con->insert_id;

    // Insert each option into tbl_question_answer
    foreach ($options as $option) {
       
echo "Course ID: $course_id<br>";
    echo "Question Text: $question_text<br>";
    echo "Options: " . print_r($options, true) . "<br>";

    // Determine if the current option is correct
    switch ($correct_answer) {
        case 'option_one':
            $isCorrect = ($option == $options[0]) ? 1 : 0;
            break;
        case 'option_two':
            $isCorrect = ($option == $options[1]) ? 1 : 0;
            break;
        case 'option_three':
            $isCorrect = ($option == $options[2]) ? 1 : 0;
            break;
        case 'option_four':
            $isCorrect = ($option == $options[3]) ? 1 : 0;
            break;
        default:
            $isCorrect = 0; // Default to incorrect if none of the cases match
    }
        $sql_answer = "INSERT INTO tbl_question_answer (quest_id, answer, is_correct, added_date, status)
                       VALUES ('$qstn_id', '$option', '$isCorrect', '$added_date', '1')";

        if ($con->query($sql_answer) !== TRUE) {
            echo "Error in answer insertion: " . $con->error;
        } else {
            echo "Answer inserted successfully. Quest_id: $qstn_id, Answer: $option, IsCorrect: $isCorrect <br>";
        }
    }
} else {
    echo "Error in question insertion: " . $con->error;
}




  

// Close the database connection
$con->close();
?>
