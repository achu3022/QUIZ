<?php
//session_start();
include ("database.php");
//include ("user_common_functions.php");
//$zone_id=$_SESSION['zone_id'];
date_default_timezone_set($zone_id);
$log_date = date('Y-m-d H:i:s');
$user_id=4751;
$type = 25;



if(isset($_POST['save_response'])&&$_POST['save_response']==1)
{
    
    $ans_id = $_POST['ans_id'];
    $cat_id = $_POST['cat_id'];
    $quest_id = $_POST['quest_id'];
    $is_correct=2;
    $q_questions = $mysqli->query("SELECT * FROM `tbl_question_answer` where ans_id=$ans_id");
    $arr_data = $q_questions->fetch_assoc();
    //echo $arr_data['is_correct'];return false;
    if($arr_data['is_correct']==1)
    $is_correct = 1; 
//echo $is_correct;return false;
    $mysqli->query("UPDATE `tbl_user_answer` SET `ans_id` = $ans_id,added_date='$log_date',is_correct=$is_correct WHERE quest_id=$quest_id and user_id='$user_id' and is_current=1 and type='$type'");

    
    //$arr_answers = $mysqli->query("select * from level2_user_credit_answers u where cr_id=$vd_id and user_id=$user_id and is_current=1");
     }



     if(isset($_POST['completed'])&&$_POST['completed']==1 && $type=='1'){
    $cat_id = $_POST['cat_id'];
    $quest_count = getConstant('quest_count');
//echo $quest_count;exit;

$pass_perc = getConstant('pass_percentage');
$passcount = round((($quest_count*$pass_perc)/100),0);
    ///*******To get the highest score if user preciously attended this credit before*******
    
  /*  $prev_attmpt = $mysqli->query("SELECT max(score) as score FROM `user_result_history` WHERE user_id='$user_id' and vd_id=$vd_id ");
    $arr_score = $prev_attmpt->fetch_assoc();
    $highest_score = $arr_score['score'];
    /***********Highest score fetch ends**************/
    $q_attempt = $mysqli->query("SELECT * FROM `tbl_user_attend_summary` where cat_id=$cat_id AND user_id='$user_id' and type='$type'");
$arr_attempt = $q_attempt->fetch_assoc();
$current_count = $arr_attempt['attempt'];
$result=$arr_attempt['result'];
//echo $result;exit;
$newcount=$current_count+1;
$arr_answers = $mysqli->query("select * from tbl_user_answer u where cat_id=$cat_id and user_id='$user_id' and is_current=1 and is_correct=1 and attempt=$newcount and type='$type'");
$score =  $arr_answers->num_rows;
 //echo $passcount;exit;   
    $newcount2 =$newcount+1;//for next set ans generation
   
    //***Assigning highest score
   // if(!empty($highest_score)&&$highest_score>$score){$score=$highest_score;}
    //***Highest score ends
    
    if($score>=$passcount){$is_pass=1;$status=1;}else {$is_pass=2;$status=2;}
if ($is_pass == 1) 
{    
$mysqli->query("UPDATE `tbl_user_attend_summary` SET `score` = $score,`result` = $is_pass,`attempt` = $newcount,`admin_status`='complete' WHERE user_id='$user_id' and cat_id=$cat_id and type='$type'");
   
   $mysqli->query("UPDATE `tbl_user_attend_history` SET   
         `score`=$score,`compl_time`='$log_date',`result`=$is_pass,`admin_status`='complete'
         WHERE user_id='$user_id' and cat_id=$cat_id and is_current=1 and attempt=$newcount and type='$type'");
  } 

if ($is_pass == 2) {

$mysqli->query("UPDATE `tbl_user_attend_summary` SET `score` = $score,`result` = $is_pass,`attempt` = $newcount,`admin_status`='pass' WHERE user_id='$user_id' and cat_id=$cat_id and type='$type'");
   
   $mysqli->query("UPDATE `tbl_user_attend_history` SET   
         `score`=$score,`compl_time`='$log_date',`result`=$is_pass,`admin_status`='pass'
         WHERE user_id='$user_id' and cat_id=$cat_id and is_current=1 and attempt=$newcount and type='$type'");

$mysqli->query("UPDATE `tbl_user_answer` SET `is_current` = '0' WHERE is_current=1 and user_id='$user_id' and cat_id=$cat_id and type='$type'");

$q_questions = $mysqli->query("SELECT * FROM tbl_question where cat_id=$cat_id and status=1 and quest_id NOT IN (SELECT quest_id FROM tbl_user_answer WHERE type='1' and is_current='1' and user_id='$user_id')ORDER BY RAND() LIMIT $quest_count");
        
        $newcount2 =$newcount+1;
        while ($arr_questions = $q_questions->fetch_assoc()) {
            $quest_id = $arr_questions['quest_id'];
            $mysqli->query("INSERT INTO `tbl_user_answer`(`user_id`,`cat_id`,`quest_id`, `attempt`,`added_date`,`type`) VALUES ('$user_id','$cat_id','$quest_id','$newcount2','$added_date','$type')");
        }

        $mysqli->query("UPDATE `tbl_user_attend_history` SET `is_current` = 0 WHERE user_id='$user_id' and cat_id=$cat_id and type='$type'");
       $mysqli->query("INSERT INTO `tbl_user_attend_history`( `cat_id`, `user_id`,`is_current`,`attempt`,`type`) "
                    . " VALUES ($cat_id,'$user_id',1,$newcount2,'$type')");

       

}

}

     if(isset($_POST['completed'])&&$_POST['completed']==1 && $type=='2'){
    $cat_id = $_POST['cat_id'];
    $quest_count = getConstant('quest_count_specific');
//echo $quest_count;exit;

$pass_perc = getConstant('pass_percentage_specific');
$passcount = round((($quest_count*$pass_perc)/100),0);
    ///*******To get the highest score if user preciously attended this credit before*******
    
  /*  $prev_attmpt = $mysqli->query("SELECT max(score) as score FROM `user_result_history` WHERE user_id='$user_id' and vd_id=$vd_id ");
    $arr_score = $prev_attmpt->fetch_assoc();
    $highest_score = $arr_score['score'];
    /***********Highest score fetch ends**************/
    $q_attempt = $mysqli->query("SELECT * FROM `tbl_user_attend_summary` where cat_id=$cat_id AND user_id='$user_id' and type='$type'");
$arr_attempt = $q_attempt->fetch_assoc();
$current_count = $arr_attempt['attempt'];
$result=$arr_attempt['result'];
//echo $result;exit;
$newcount=$current_count+1;
$arr_answers = $mysqli->query("select * from tbl_user_answer u where cat_id=$cat_id and user_id='$user_id' and is_current=1 and is_correct=1 and attempt=$newcount and type='$type'");
$score =  $arr_answers->num_rows;
    
    $newcount2 =$newcount+1;//for next set ans generation
   
    //***Assigning highest score
   // if(!empty($highest_score)&&$highest_score>$score){$score=$highest_score;}
    //***Highest score ends
    
    if($score>=$passcount){$is_pass=1;$status=1;}else {$is_pass=2;$status=2;}

if ($is_pass == 1) 
{    
$mysqli->query("UPDATE `tbl_user_attend_summary` SET `score` = $score,`result` = $is_pass,`attempt` = $newcount,`admin_status`='complete' WHERE user_id='$user_id' and cat_id=$cat_id and type='$type'");
   
   $mysqli->query("UPDATE `tbl_user_attend_history` SET   
         `score`=$score,`compl_time`='$log_date',`result`=$is_pass,`admin_status`='complete'
         WHERE user_id='$user_id' and cat_id=$cat_id and is_current=1 and attempt=$newcount and type='$type'");
}   

if ($is_pass == 2) 
{

$mysqli->query("UPDATE `tbl_user_attend_summary` SET `score` = $score,`result` = $is_pass,`attempt` = $newcount,`admin_status`='pass' WHERE user_id='$user_id' and cat_id=$cat_id and type='$type'");
   
   $mysqli->query("UPDATE `tbl_user_attend_history` SET   
         `score`=$score,`compl_time`='$log_date',`result`=$is_pass,`admin_status`='pass'
         WHERE user_id='$user_id' and cat_id=$cat_id and is_current=1 and attempt=$newcount and type='$type'");

$mysqli->query("UPDATE `tbl_user_answer` SET `is_current` = '0' WHERE is_current=1 and user_id='$user_id' and cat_id=$cat_id and type='$type'");

$q_questions = $mysqli->query("SELECT * FROM tbl_question where cat_id=$cat_id and status=1 and quest_id NOT IN (SELECT quest_id FROM tbl_user_answer WHERE type='1' and is_current='1' and user_id='$user_id')ORDER BY RAND() LIMIT $quest_count");
        
        $newcount2 =$newcount+1;
        while ($arr_questions = $q_questions->fetch_assoc()) 
        {
            $quest_id = $arr_questions['quest_id'];
            $mysqli->query("INSERT INTO `tbl_user_answer`(`user_id`,`cat_id`,`quest_id`, `attempt`,`added_date`,`type`) VALUES ('$user_id','$cat_id','$quest_id','$newcount2','$added_date','$type')");
        }

        $mysqli->query("UPDATE `tbl_user_attend_history` SET `is_current` = 0 WHERE user_id='$user_id' and cat_id=$cat_id and type='$type'");
       $mysqli->query("INSERT INTO `tbl_user_attend_history`( `cat_id`, `user_id`,`is_current`,`attempt`,`type`) "
                    . " VALUES ($cat_id,'$user_id',1,$newcount2,'$type')");

}




}