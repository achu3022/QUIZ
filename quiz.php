<?php include('student-header.php'); 
      //include('dbcon.php');
      include('database.php');
      session_start();
     //$user_id= $_SESSION['user_id'];
     $user_id = 2;
     $user_qry = "SELECT * FROM tbl_users WHERE user_id=$user_id";
      $user_result = mysqli_query($con,$user_qry);
      if (!$user_result) {
        // Handle the case when the query fails
        echo "Error in fetching user details: " . mysqli_error($con);
        exit;}
        $row = mysqli_fetch_assoc($user_result);
        
        $emp_type = $row['emp_type'];
        $emp_name = $row['full_name'];
        if ($emp_type == '2') {
           $stud_qry ="SELECT * FROM student_course WHERE user_id=$user_id";
           $stud_reslut = mysqli_query($con,$stud_qry);
           $studrow=mysqli_fetch_assoc($stud_reslut);
           $user_id = $studrow["user_id"];
           $course_id = $studrow["course_id"];
           echo $course_id;
           $_SESSION["course_id"]=$course_id;
           header('location:start_quiz.php');

        }
        else{
            echo "You are not eligible for this quiz";
        }
// Display the user details
//echo "User ID: $user_id<br>";
//echo "Employee Type: $emp_type<br>";
//echo "Employee name: $emp_name <br>";
$_SESSION["full_name"] = $emp_name;
?>

