<?php  
/*session_start();
if (isset($_SESSION["user_id"]) && $_SESSION["user_id"] != "")
   { $user_id = $_SESSION["user_id"];
    $zone_id= $_SESSION["zone_id"];
date_default_timezone_set($zone_id);}
//echo $zone_id;*/
//include('header.php');
include ("database.php");
$user_id=4751;
//date_default_timezone_set("Asia/Calcutta");
$cat_id=1;
$type=25;
//echo $type;exit();

$q_questions = $mysqli->query("SELECT * FROM tbl_user_answer u left join tbl_question q on q.quest_id=u.quest_id where user_id='$user_id' and q.cat_id=$cat_id and is_current='1' and type=$type");

$timer_end="0000-00-00 00:00:00";
$attempt_his = $mysqli->query("select * from tbl_user_attend_history where user_id='$user_id' and cat_id='$cat_id' and is_current=1 and type=$type");
if ($attempt_his->num_rows > 0) {
        $result_at = $attempt_his->fetch_assoc();
        $timer_end = $result_at['timer_end_time'];
        $timer_start = $result_at['start_time'];
        $current_time = date('Y-m-d H:i:s');
    }
  
    $timestamp = date("m/d/Y h:i A", strtotime($timer_end));


$attempt_his1 = $mysqli->query("select * from tbl_user_attend_summary where user_id=$user_id and cat_id=$cat_id and result=1 and type=$type");
if ($attempt_his1->num_rows > 0)
{?>
<script type="text/javascript">
window.location = "result.php?cat_id=<?php echo $cat_id?>&type=<?php echo $type?>";
</script>
<?php }

else
{  

?>

<?php if($current_time>$timer_end){?>

<div class="container-fluid" id="container-wrapper">
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Certification</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
             <!--- <li class="breadcrumb-item">Forms</li>-->
              <li class="breadcrumb-item active" aria-current="page">Certification</li>
            </ol>
          </div>

<div class="row">
            <div class="col-lg-12">
               <div class="card mb-4">
                
                <div class="card-body">
                  <center>
                    <input type="hidden" value="<?=$cat_id?>" name="cat_id" id="cat_id">
                    <input type="hidden" value="<?=$type?>" name="type" id="type">
<?php echo "You are timed out from the last attempt."?> 
        <a href="javascript:void(0)" onclick="forTimeout()" class="btn btn-primary">Click here to proceed further</a> 
                                                  </center>
                                                  </div>
                                                </div>
                                                  </div>
                                                </div>
                                              </div>

          

<?php }   else {
if($current_time<$timer_end){
 ?>

<div class="container-fluid" id="container-wrapper">
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Certification</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <!--<li class="breadcrumb-item">Forms</li>-->
              <li class="breadcrumb-item active" aria-current="page">Certification</li>
            </ol>
          </div>
          <div class="row">
            <div class="col-lg-12">
               <div class="card mb-4">
                <center>
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Attend All Questions</h6>
                </div>
              </center>
                <?php // echo $timer_end; ?>
                <div class="card-body">
                  <center>
<script language="JavaScript">
                                                        TargetDate = "<?php echo $timer_end; ?>"; //"2021-10-14 10:49:26";
                                                        BackColor = "palegreen";
                                                        ForeColor = "navy";
                                                        CountActive = true;
                                                        CountStepper = -1;
                                                        LeadingZero = true;
                                                        DisplayFormat = " %%M%% Minutes, %%S%% Seconds.";
                                                        FinishMessage = "It is finally here!";
                                                    </script>
                                                    <script language="JavaScript" src="js/countdown.js"></script>
                                                  </center>
                                                  </div>
                                                </div>
                                                  </div>
                                                </div>
          <div class="row">
            <div class="col-lg-12">
              <form name="form_mcq" id="form_mcq" style="min-height: 215px;" method="post" action="user_attend_exam_save.php">
                <input type="hidden" value="<?=$cat_id?>" name="cat_id" id="cat_id">
                <input type="hidden" value="<?=$type?>" name="type" id="type">
              <?php
                      //if($result!=1){
                      $i = 1;$j=1;
                      while ($arr_questions = $q_questions->fetch_assoc()) { //print_r($arr_questions);exit;
                          $ans = $arr_questions['ans_id'];
                         // $is_cor = $arr_questions['is_correct'];
                      //    $ans_primary = $arr_questions['id'];
                          if ($i == 1)
                              $disp = "block";
                          else
                              $disp = "none";?>
              <!-- Form Basic -->
              
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  
                  <h6 class="m-0 font-weight-bold text-primary">
                   <?= $i ?>. <?= $arr_questions['question'] ?></h6>
                </div>
                

                 <?php
                                        $quest_id = $arr_questions['quest_id'];
                                        
                                        $options = get_options($quest_id);
                                        $var = "a";
                                        
                                        while ($arr_options = $options->fetch_assoc()) {
                                            ?>
                   
                      
                      <div class="custom-control custom-radio">
                        <input type="radio" <?php if ($ans == $arr_options['ans_id']) echo "checked" ?> onclick="cr_save_response(<?= $quest_id ?>,<?php echo $arr_options['ans_id'] ?>,<?= $cat_id ?>,<?= $type ?>)" name="q_<?= $quest_id ?>" > 
                       

                          <?php  $extension = pathinfo($arr_options['answer'], PATHINFO_EXTENSION);
                           if ($extension == 'png' || $extension == 'jpg') 
                           {?>
                           <img src="img/options/<?php echo $arr_options['answer'];?>">

                          <?php }
                          else{?>

                          <label> <?= ucfirst($arr_options['answer']); } ?>
                            

                          </label>
                      </div>
                    
                       <?php $var++;$j++;
    } ?>  
                      
                  
                 
              </div><?php $i++;
  } ?>
              
                <div class="card mb-4">
                    <div class="card-body text-center">
                <button  type="submit" class="btn btn-primary " onclick="completeMcq()" id="complete">Submit</button>

                
 <input type="button" class="submit-btn" style="display: none" id="waitmsg" value="Please Wait..." disabled="disabled">
                    </div>
                </div>

           
            </form>  
            </div>

            
          </div>
          <!--Row-->

   

          <!-- Modal Logout -->
          

        </div><?php } } }?>

        <?php function get_options($quest_id) {
    global $mysqli;
    return $q_video = $mysqli->query("SELECT * FROM `tbl_question_answer` where quest_id = $quest_id");
//$arr_data = $q_video->fetch_assoc();   return $arr_data;
}

 include 'footer.php';?>

 <script src="js/jquery.validate.js"></script>
 <script type="text/javascript">


function completeMcq(){
     var brochure = $("#form_mcq").validate(
         {
              errorPlacement: function(error, element) {
      var placement = $(element).data('error');
      if (placement) {
        $(placement).append(error)
      } else {
        error.insertAfter(element);
      }
    },
     submitHandler: function(form) {
           


            var names = {};
            $('input:radio').each(function () { // find unique names
                names[$(this).attr('name')] = true;
            });
            var count = 0;
            $.each(names, function () { // then count them
                count++;
            });
            if ($('input:radio:checked').length == count) 
            {
              $('#complete').hide();
            $('#waitmsg').show();
            //$("#form_mcq").submit(); 
            return true;
            }


           else
           {

 alert("Please answer all questions!");
                return false;
           }
           /*  $("#form_mcq").submit();
           
             return true;*/

        }
     });
 }




 $(document).ready(function () {


setInterval(function () {  //alert("lo");return false;
         var cat_id = $("#cat_id").val();
         var type = $("#type").val();
           // alert(type); return false;

            //var user_id = $("#user_id").val();
            var data = "check_end_time=1&cat_id=" + cat_id+"&type="+type;
           // alert(data);return false;
                                   // $.post('refresh_session.php');//For session refreshing
                                    result = $.ajax({
                                        url: 'user_refresh_timer.php',
                                        data: data,                   
                                        type: "POST",
                                        async: false,
                                        success: function (dat) { //alert(dat);
                                            if (dat == 'No Connection')
                                            {
                                                alert("No Network Connection!");
                                            } 
                                            else if (dat == 'logout')
                                            {
                                                alert("Session Exired. Please login again");
                                            } 
                                           else if(dat=="timer_end")
                                            {

                                            forTimeout();
                                            }
                                            else {
                                              return true;
                                            } 
                                        },
                                        error: function () {
                                            alert("Network Issue Please Wait!")
                                        }
                                    });
                                }, 30000);



 });


function forTimeout() { ///***This function will be called from the timeout master js to show the result **************
            //****THE RESULT DISPLAY SECTION***
            var cat_id = $("#cat_id").val();
            var type = $("#type").val();
           //alert(type);return false;
            var data = "completed=1&cat_id=" + cat_id+"&type="+type;
            result = $.ajax({
                url: 'user_exam_save_response.php',
                data: data,
//                    data:{doAction:'jobComments',job_comments:job_comments},//can use this format too for special charcter display
                type: "POST",
                async: false,
                success: function (dat) {

               //   alert(dat);return false;
//                   location.href = 'result.php';return false;

                 window.location = 'result.php?cat_id=' + cat_id+'&type='+type;
                }
            });
        }


   function cr_save_response(quest_id, ans_id, cat_id,type) {
    var data = "save_response=1&cat_id=" + cat_id + "&quest_id=" + quest_id + "&ans_id=" + ans_id+"&type="+type;
//alert(data)
  //  $("#ival").val(i);
    result = $.ajax({
        url: 'user_exam_save_response.php',
        data: data,
        type: "POST",
        async: false,
        success: function (dat) 
        {

            //alert(dat);return;false;
           /* if(dat == 'No Connection')
            {
                alert("Network Issue Please Wait!");
            }
            else if(dat == 'logout')
            {
                alert("Session Exired. Please login again");}
            else{*/
                
           // }
        },
        error: function () {
            alert("Network Issue Please Wait!");
        }
    });
}
 </script>
<script type="text/javascript">
        window.history.forward();
        function noBack() {
            window.history.forward();
        }
    </script>
 <script type="text/javascript">
   

           function killCopy(e){
return false;
}
function reEnable(){
return true;
}
document.onselectstart=new Function ("return false")
if (window.sidebar){
document.onmousedown=killCopy;
document.onclick=reEnable;
}
window.addEventListener("keydown",function (e) {
    if (e.keyCode === 114 || (e.ctrlKey && e.keyCode === 70)) { 
        e.preventDefault();
    }
});

document.onkeydown = function(e) {
        if (e.ctrlKey && 
            (e.keyCode === 67 || 
             e.keyCode === 86 || 
             e.keyCode === 85 || 
             e.keyCode === 117 ||
             e.keyCode === 80)) {
            alert('Not allowed!');
            return false;
        } else {
            return true;
        }
};
    


     function copyToClipboard() {

  var aux = document.createElement("input");
  aux.setAttribute("value", "print screen disabled!");      
  document.body.appendChild(aux);
  aux.select();
  document.execCommand("copy");
  // Remove it from the body
  document.body.removeChild(aux);
  alert("Print screen disabled!");
}

$(window).keyup(function(e){
  if(e.keyCode == 44){
    copyToClipboard();
  }
});

 </script>