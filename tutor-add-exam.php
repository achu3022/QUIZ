<?php
include('header.php');
include('database.php');
//$user_id=$_SESSION["user_id"];
// EXAM TABLE CONNECTION 
if ($con->connect_error) {
  die("Connection failed: " . $con->connect_error);
}

#Select course from the course table
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
    option {
      color: #000;
    }
  </style>
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
              <div class="page-title">Exam Scheduling</div>
            </div>
          </div>
        </div>

        <!--###### FORM FOR ENTERING QUESTIONS AND ANSWERS ######-->
        <div class="col-lg-4 col-md-12 col-sm-12 col-12">
          <form class="form-class" action="tutor-add-exam-action.php" method="POST" id="examForm">
        </div>

        <!-- start new student list -->
        <div class="row">
          <div class="container">
            <div class="row mx-0 justify-content-center">
              <div class="col-md-7 col-lg-5 px-lg-2 col-xl-4 px-xl-0 px-xxl-3">
                <!--Exam course selector-->
                <div class="form-group" data-placement="left" data-align="top" data-autoclose="true">

                  <label class="d-block mb-4">
                    <span class="form-label d-block text-dark">Select your course for exam</span>
                    <select
                      name="course"
                      class="form-control "
                      required
                    >
                      <option>Choose course</option>
                      <?php foreach ($options as $option) {
                        echo "<option value=" . $option['course_id'] . ">" . $option['course_name'] . "</option>";
                      } ?>
                    </select>
                  </label>
                </div>
                <div class="form-group" data-placement="left" data-align="top" data-autoclose="true">

                  <label class="d-block mb-4">
                    <span class="form-label d-block text-dark">Exam name</span>
                    <input
                      name="exam_name"
                      type="text"
                      class="form-control"
                      placeholder="Name of examination"
                      required
                    />
                  </label>
                </div>
                <div class="form-group row" data-placement="left" data-align="top" data-autoclose="true">

                  <label class="d-block mb-4">
                    <span class="form-label d-block text-dark">Exam description</span>
                    <textarea
                      name="exam_description"
                      class="form-control" rows="3"
                      placeholder="Exam description"
                      required>
                    </textarea>
                  </label>
                </div>

                <!--Exam Date selector-->
                <div class="form-group row" data-placement="left" data-align="top" data-autoclose="true">

                  <label class="d-block mb-4">
                    <span class="form-label d-block text-dark">Exam Date</span>
                    <input
                      name="exam_date"
                      type="date"
                      class="form-control"
                      placeholder="Exam Date"
                      required
                    />
                </div>
                </label>
                
                <!--Exam start timer-->
                <div class="form-group  clockpicker" data-placement="left" data-align="top" data-autoclose="true">
                  <label class="d-block mb-4">
                    <span class="form-label d-block text-dark">Exam Start Time</span>
                    <input
                      name="exam_start_time"
                      type="text"
                      class="form-control"
                      placeholder="Exam Start Time"
                      required
                    />
                  </label>
                </div>
                <!--Exam Stop timer-->
                <div class="form-group" data-placement="left" data-align="top" data-autoclose="true">

                  <label class="d-block mb-4">
                    <span class="form-label d-block text-dark">Exam duration (minutes)</span>
                    <input
                      name="duration"
                      type="text"
                      class="form-control"
                      placeholder="Exam Duration in minutes"
                      required
                    />
                  </label>
                </div>

                <div class="mb-3">
                  <button type="submit" class="btn btn-primary px-3 rounded-3" id="scheduleButton">
                    Schedule it!
                  </button>
                </div>

                <div class="d-block text-end">
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- end new student list -->
      </div>
    </div>
  </div>
  <!-- end page content -->
  <div class="page-footer">
    <?php include('student-footer.php'); ?>
  </div>
  </div>
</body>

<script type="text/javascript" src="assets/dist/bootstrap-clockpicker.js"></script>
<script type="text/javascript">
  $('.clockpicker').clockpicker()
    .find('input').change(function () {
      console.log(this.value);
    });
  var input = $('#single-input').clockpicker({
    placement: 'bottom',
    align: 'left',
    autoclose: true,
    'default': 'now'
  });

  $('.clockpicker-with-callbacks').clockpicker({
    donetext: 'Done',
    init: function () {
      console.log("colorpicker initiated");
    },
    beforeShow: function () {
      console.log("before show");
    },
    afterShow: function () {
      console.log("after show");
    },
    beforeHide: function () {
      console.log("before hide");
    },
    afterHide: function () {
      console.log("after hide");
    },
    beforeHourSelect: function () {
      console.log("before hour selected");
    },
    afterHourSelect: function () {
      console.log("after hour selected");
    },
    beforeDone: function () {
      console.log("before done");
    },
    afterDone: function () {
      console.log("after done");
    }
  })
    .find('input').change(function () {
      console.log(this.value);
    });

  // Manually toggle to the minutes view
  $('#check-minutes').click(function (e) {
    // Have to stop propagation here
    e.stopPropagation();
    input.clockpicker('show')
      .clockpicker('toggleView', 'minutes');
  });
  if (/mobile/i.test(navigator.userAgent)) {
    $('input').prop('readOnly', true);
  }
</script>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    // Function to handle the form submission
    function submitForm() {
      // Disable the button to prevent multiple submissions
      document.getElementById('scheduleButton').disabled = true;
      // Change the button text to indicate "Please Wait"
      document.getElementById('scheduleButton').innerHTML = 'Please Wait...';

      // Get form data
      var formData = new FormData(document.getElementById('examForm'));

      // Your custom form submission logic goes here
      // Example using fetch API for a POST request
      fetch('tutor-add-exam-action.php', {
        method: 'POST',
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        // Handle the response data as needed
        console.log(data);

        // Re-enable the button and restore its original text after the action is complete
        document.getElementById('scheduleButton').disabled = false;
        document.getElementById('scheduleButton').innerHTML = 'Schedule it!';
      })
      .catch(error => {
        // Handle errors
        console.error('Error:', error);

        // Re-enable the button and restore its original text after an error
        document.getElementById('scheduleButton').disabled = false;
        document.getElementById('scheduleButton').innerHTML = 'Schedule it!';
      });
    }

    // Attach the submitForm function to the form submission event
    document.getElementById('examForm').addEventListener('submit', function (event) {
      event.preventDefault(); // Prevent the default form submission
    
      setTimeout(function() {
      document.getElementById('scheduleButton').innerHTML = 'Schedule it!';
    }, 5000);  submitForm(); // Call your custom submitForm function
    });
   
  });
</script>

</html>
