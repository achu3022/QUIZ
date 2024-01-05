<?php
//print_r($_POST);exit;
session_start();
include ("connect.inc.php");
include ("user_common_functions.php");
//ini_set('display_errors', 1);
$user_id=$_SESSION['user_id'];
$zone_id=$_SESSION['zone_id'];
date_default_timezone_set($zone_id);
$log_date = date('Y-m-d H:i:s');
$cat_id = $_POST['cat_id'];
$type = $_POST['type'];
//echo $type;exit();

if($type=='1')
{
if($cat_id==14){
echo $quest_count = getConstant('leak_quest_count_general');
$timerStart=getConstant('timer');
} else {
$quest_count = getConstant('quest_count');
$timerStart=getConstant('timer');
}

//echo $quest_count;exit;
$pass_perc = getConstant('pass_percentage');
$passcount = round((($quest_count*$pass_perc)/100),0);
//echo $passcount;return false;
//$passcount="3";
//echo $passcount;return false;
$q_attempt = $mysqli->query("SELECT * FROM `tbl_user_attend_summary` where cat_id=$cat_id AND user_id='$user_id' and type='$type'");
$arr_attempt = $q_attempt->fetch_assoc();
$current_count = $arr_attempt['attempt'];
$result=$arr_attempt['result'];
//echo $result;exit;
$newcount=$current_count+1;
$arr_answers = $mysqli->query("select * from tbl_user_answer u where cat_id=$cat_id and user_id='$user_id' and is_current=1 and is_correct=1 and attempt=$newcount and type='$type'");
$score =  $arr_answers->num_rows;
if($score>=$passcount)
    {$is_pass=1;$status=1;
       //echo $is_pass;return false; 
    }else 
    {
    $is_pass=2;$status=2;
    //echo $is_pass;return false;
    }
if ($is_pass == 1) {
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

$q_questions = $mysqli->query("SELECT * FROM tbl_question where cat_id=$cat_id and status=1 and quest_id NOT IN (SELECT quest_id FROM tbl_user_answer WHERE type='2' and is_current='1' and user_id=$user_id)ORDER BY RAND() LIMIT $quest_count");
        
        $newcount2 =$newcount+1;
        while ($arr_questions = $q_questions->fetch_assoc()) {
            $quest_id = $arr_questions['quest_id'];
            $mysqli->query("INSERT INTO `tbl_user_answer`(`user_id`,`cat_id`,`quest_id`, `attempt`,`added_date`,`type`) VALUES ('$user_id','$cat_id','$quest_id','$newcount2','$added_date','$type')");
        }

        $mysqli->query("UPDATE `tbl_user_attend_history` SET `is_current` = 0 WHERE user_id='$user_id' and cat_id=$cat_id and type='$type'");
       $mysqli->query("INSERT INTO `tbl_user_attend_history`( `cat_id`, `user_id`,`is_current`,`attempt`,`type`) "
                    . " VALUES ($cat_id,'$user_id',1,$newcount2,'$type')");

   

}
//echo $cat_id;    exit();
?>
<script type="text/javascript">
window.location = "result.php?cat_id=<?php echo $cat_id?>&type=1";
</script> 



<?php } if($type=='2'){

if($cat_id==14){
$quest_count = getConstant('leak_quest_count_specific');
$timerStart=getConstant('timer_specific');
} else {
$quest_count = getConstant('quest_count_specific');
$timerStart=getConstant('timer_specific');
}

//echo $quest_count;exit;
$pass_perc = getConstant('pass_percentage');
$passcount = round((($quest_count*$pass_perc)/100),0);
//echo $passcount;return false;
//$passcount="3";
//echo $passcount;return false;
$q_attempt = $mysqli->query("SELECT * FROM `tbl_user_attend_summary` where cat_id=$cat_id AND user_id='$user_id' and type='$type'");
$arr_attempt = $q_attempt->fetch_assoc();
$current_count = $arr_attempt['attempt'];
$result=$arr_attempt['result'];
//echo $result;exit;
$newcount=$current_count+1;
$arr_answers = $mysqli->query("select * from tbl_user_answer u where cat_id=$cat_id and user_id='$user_id' and is_current=1 and is_correct=1 and attempt=$newcount and type='$type'");
$score =  $arr_answers->num_rows;
if($score>=$passcount)
    {$is_pass=1;$status=1;
       //echo $is_pass;return false; 
    }else 
    {
    $is_pass=2;$status=2;
    //echo $is_pass;return false;
    }
if ($is_pass == 1) {
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

$q_questions = $mysqli->query("SELECT * FROM tbl_question where cat_id=$cat_id and status=1 and quest_id NOT IN (SELECT quest_id FROM tbl_user_answer WHERE type='1' and is_current='1' and user_id=$user_id)ORDER BY RAND() LIMIT $quest_count");


        
        $newcount2 =$newcount+1;
        while ($arr_questions = $q_questions->fetch_assoc()) {
            $quest_id = $arr_questions['quest_id'];
            $mysqli->query("INSERT INTO `tbl_user_answer`(`user_id`,`cat_id`,`quest_id`, `attempt`,`added_date`,`type`) VALUES ('$user_id','$cat_id','$quest_id','$newcount2','$added_date','$type')");
        }

        $mysqli->query("UPDATE `tbl_user_attend_history` SET `is_current` = 0 WHERE user_id='$user_id' and cat_id=$cat_id and type='$type'");
       $mysqli->query("INSERT INTO `tbl_user_attend_history`( `cat_id`, `user_id`,`is_current`,`attempt`,`type`) "
                    . " VALUES ($cat_id,'$user_id',1,$newcount2,'$type')");

   

}
//echo $cat_id;    exit();
?>
<script type="text/javascript">
window.location = "result.php?cat_id=<?php echo $cat_id?>&type=2";
</script> 

<?php }    ?>


