<?php
include('header.php');
include('database.php');

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

$course_qry = "SELECT * FROM course_master WHERE status='1'";
$course_result = $con->query($course_qry);

if ($course_result->num_rows > 0) {
    $options = mysqli_fetch_all($course_result, MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <style>
      input[type="text"],
textarea {
    color: black;
}
        option {
            color: #000;
        }

        .form-label {
            color: black;
        }
    </style>
    <!-- Include jQuery and jQuery Validation Plugin -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
</head>

<body class="page-header-fixed sidemenu-closed-hidelogo page-content-white page-md page-full-width header-white white-sidebar-color logo-indigo">
    <div class="page-wrapper">
        <div class="page-content-wrapper">
            <div class="page-content">
                <div class="page-bar">
                    <div class="page-title-breadcrumb">
                        <div class="pull-left">
                            <div class="page-title">Add Questions</div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-12 col-sm-12 col-12">
                    <form class="form-class" action="tutor-add-question-action.php" method="POST" id="examForm">
                </div>

                <div class="row">
                    <div class="container">
                        <div class="row mx-0 justify-content-center">
                            <div class="col-md-7 col-lg-5 px-lg-2 col-xl-4 px-xl-0 px-xxl-3">
                                <div class="form-group" data-placement="left" data-align="top" data-autoclose="true">
                                    <label class="d-block mb-4">
                                        <span class="form-label d-block text-dark">Select your course for exam</span>
                                        <select
                                            name="course"
                                            class="form-control"
                                            required
                                        >
                                            <option>Choose course</option>
                                            <?php foreach ($options as $option) {
                                                echo "<option value=" . $option['course_id'] . ">" . $option['course_name'] . "</option>";
                                            } ?>
                                        </select>
                                    </label>
                                </div>

                                <div class="form-group" data-placement="left"  data-autoclose="true">
                                    <label class="d-block mb-4" style="color:black;">
                                        <span class="form-label d-block text-dark">Question</span>
                                        <textarea
                                            name="exam_question"
                                            class="form-control "
                                            placeholder=""
                                            required>
                                        </textarea>
                                    </label>
                                </div>

                                <div class="form-group" data-placement="left" data-align="top" data-autoclose="true">
                                    <label class="d-block mb-4">
                                        <span class="form-label d-block text-dark">Option one</span>
                                        <input
                                            name="option_one"
                                            type="text"
                                            class="form-control"
                                            placeholder="Option one"
                                            required
                                        />
                                        <label for="radiobg3">
                                            Is correct
                                            
                                            <input type="radio"id="radiobg3" name="correct_option" value="option_one">
                                            
                                        </label>
                                    </label>
                                </div>

                                <div class="form-group" data-placement="left" data-align="top" data-autoclose="true">
                                    <label class="d-block mb-4">
                                        <span class="form-label d-block text-dark">Option two</span>
                                        <input
                                            name="option_two"
                                            type="text"
                                            class="form-control"
                                            placeholder="option two"
                                            required
                                        />
                                        <label for="radiobg3">
                                            Is correct
                                            <input type="radio" name="correct_option" value="option_two">
                                        </label>
                                    </label>
                                </div>

                                <div class="form-group" data-placement="left" data-align="top" data-autoclose="true">
                                    <label class="d-block mb-4">
                                        <span class="form-label d-block text-dark">Option three</span>
                                        <input
                                            name="option_three"
                                            type="text"
                                            class="form-control"
                                            placeholder="Option three"
                                            required
                                        />
                                        <label for="radiobg3">
                                            Is correct
                                            <input type="radio" name="correct_option" value="option_three">
                                        </label>
                                    </label>
                                </div>

                                <div class="form-group" data-placement="left" data-align="top" data-autoclose="true">
                                    <label class="d-block mb-4">
                                        <span class="form-label d-block text-dark">Option four</span>
                                        <input
                                            name="option_four"
                                            type="text"
                                            class="form-control"
                                            placeholder="Option four"
                                            required
                                        />
                                        <label for="radiobg3">
                                            Is correct
                                            <input type="radio" name="correct_option" value="option_four">
                                        </label>
                                    </label>



                                    
                                </div>

                                <div class="btn-wrap">
                                    <!-- Remove the onclick attribute -->
                                    <button type="submit" class="btn btn-circle btn-success m-b-10">Add Question</button>
                                </div>

                                <div class="d-block text-end">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="page-footer">
        <?php include('student-footer.php'); ?>
    </div>
</body>

<script>
    $(document).ready(function () {
        $("#examForm").validate({
            rules: {
                course: "required",
                exam_question: "required",
                option_one: "required",
                option_two: "required",
                option_three: "required",
                option_four: "required",
                correct_option: "required"
            },
            messages: {
                course: "Please select a course",
                exam_question: "Please enter a question",
                option_one: "Please enter option one",
                option_two: "Please enter option two",
                option_three: "Please enter option three",
                option_four: "Please enter option four",
                correct_option: "Please select the correct option"
            },
            submitHandler: function (form) {
                var correctOption = $("input[name='correct_option']:checked").val();
                $("#correctOption").val(correctOption);
                submitForm();
            }
        });
    });

    function submitForm() {
        document.getElementById('scheduleButton').disabled = true;
        document.getElementById('scheduleButton').innerHTML = 'Please Wait...';

        var formData = new FormData(document.getElementById('examForm'));

        fetch('tutor-add-exam-action.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                console.log(data);

                document.getElementById('scheduleButton').disabled = false;
                document.getElementById('scheduleButton').innerHTML = 'Schedule it!';
            })
            .catch(error => {
                console.error('Error:', error);

                document.getElementById('scheduleButton').disabled = false;
                document.getElementById('scheduleButton').innerHTML = 'Schedule it!';
            });
    }
</script>

</html>
