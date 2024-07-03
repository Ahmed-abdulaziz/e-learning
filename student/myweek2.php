<?php 
include_once 'connect.php';
session_start();
date_default_timezone_set('Africa/Cairo');

if(!isset($_SESSION['name'])){
header("location:index.php?msg=".urldecode("please login first"));
exit();
}
function check($var){
  return trim(strip_tags(stripcslashes($var)));
}
$stmt=$con->prepare("SELECT id FROM students WHERE email = ?");
$stmt->execute(array($_SESSION['name']));
$student=$stmt->fetch();

$stmt=$con->prepare("SELECT * FROM studentssubjects WHERE studentid = ?");
$stmt->execute(array($student['id']));
$subject=$stmt->fetch();
$studentid = $student['id'];

$_SESSION['subject']=$subject['subjectid'];
$_SESSION['class']=$subject['classid'];

//show subject details
$cdate=date("Y-m-d"); 
$stmt=$con->prepare("SELECT name FROM subjects WHERE id = ? ");   
$stmt->execute(array($_SESSION['subject']));
$subject=$stmt->fetch();

$stmt=$con->prepare("SELECT teacher_id FROM subjects WHERE id = ? ");
$stmt->execute(array($_SESSION['subject']));
$t=$stmt->fetch();

$stmt=$con->prepare("SELECT fullname FROM admins WHERE id = ? ");
$stmt->execute(array($t['teacher_id']));
$teacher=$stmt->fetch();

$email=$_SESSION['name'];
$stmt=$con->prepare("SELECT eductionallevel FROM students WHERE email = ?");
$stmt->execute(array($email));
$result=$stmt->fetch();
$edulevel=$result['eductionallevel'];


if(isset($_GET['wid'])){
    $wid = check($_GET['wid']);
}else{
    header("location:weeks.php?msg=".urldecode("please Select Week"));
   exit();
}

$stmt=$con->prepare("SELECT * FROM weeks WHERE id = ?   AND  ( free = ? OR id  IN (SELECT weekid FROM codes WHERE studentid = ?  AND type = ?) )");
$stmt->execute(array($wid,1,$student['id'],1));
$week=$stmt->fetch();
$check = $stmt->rowCount();
if($check < 1){
        header("location:weeks.php?msg=".urldecode("You Not Have A Week"));
        exit();
}
   $currentTime = date("Y-m-d H:i:s");
    
    $currentTime = strtotime($currentTime);
    $currenttimeonly = date('H:i:s', $currentTime);
    
    $currentdate = strtotime($currentTime);
    $currentdateonly = date('Y-m-d', $currentTime);
    
    

?>
<!doctype html>
<html class="no-js" lang="">

<head>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-145403987-2"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        
        gtag('config', 'UA-145403987-2');
    </script>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>E-Learning System | Weeks </title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="img\favicon.png">
    <!-- Normalize CSS -->
    <link rel="stylesheet" href="css\normalize.css">
    <!-- Main CSS -->
    <link rel="stylesheet" href="css\main.css">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css\bootstrap.min.css">
    <!-- Fontawesome CSS -->
    <link rel="stylesheet" href="css\all.min.css">
    <!-- Flaticon CSS -->
    <link rel="stylesheet" href="fonts\flaticon.css">
    <!-- Full Calender CSS -->
    <link rel="stylesheet" href="css\fullcalendar.min.css">
    <!-- Animate CSS -->
    <link rel="stylesheet" href="css\animate.min.css">
    <!-- Data Table CSS -->
    <link rel="stylesheet" href="css\jquery.dataTables.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="css/style.css">
    
     <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;600;900&display=swap" rel="stylesheet">


    <!-- Modernize js -->
    <script src="js\modernizr-3.6.0.min.js"></script>
</head>

<body  style="font-family: 'Cairo', sans-serif;">
    <!-- Preloader Start Here -->
    <div id="preloader"></div>
    <!-- Preloader End Here -->
    <div id="wrapper" class="wrapper bg-ash">
        <!-- Header Menu Area Start Here -->
        <?php include_once "navbar.php";?>
        <!-- Header Menu Area End Here -->

        <!-- Page Area Start Here -->
        <div class="dashboard-page-one">
            <!-- Sidebar Area Start Here -->
            <?php include_once "sidebar.php";?>
            <!-- Sidebar Area End Here -->
            <div class="dashboard-content-one">
                <!-- Breadcubs Area Start Here -->
              <div class="breadcrumbs-area">
                    <div class="row">
                        <div class="col-lg-8">
                            <h3>Student Dashboard</h3>
                            <ul>
                                <li>
                                    <a href="statistics.php">Statistics</a>
                                </li>
                                <li>Weeks</li>
                            </ul>
                        </div>
                        <div class="col-lg-4">
                            <div class="dashboard-summery-one subject">
                                <div class="item-content">
                                    <div class="item-number"><?php echo $subject['name']; echo " / ". $teacher['fullname']  ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Breadcubs Area End Here -->
                <div class="row">
                    <!-- Student Info Area Start Here -->
                    <!-- Student Info Area End Here -->
                    <div class="col-12-xxxl col-12">
                          <?php if(isset($_GET['msg'])){?>
                            <div class="alert alert-success errors alert-dismissible fade show" role="alert">
                                <h2><?php echo check($_GET['msg']);?></h2>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <?php }?>
                            
                             <?php if(isset($_GET['error'])){?>
                            <div class="alert alert-danger errors alert-dismissible fade show" role="alert">
                               <h4 class="text-danger"> <i class="fas fa-exclamation-triangle"></i> <?php echo check($_GET['error']);?></h4>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <?php }?>
                            
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="dashboard-summery-one subject">
                                        <div class="item-content">
                                            <div class="item-number">Week Content</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            
                        <div class="row">
                          
                   
                   
                            <!-- Summery Area Start Here -->
                            <?php
                            $wid=$week['id'];
                            ?>
                                       
                                     <div class="col-lg-4">
                                        
                                        
                                        <div class="card myweeks">
                                            
                                          <h5 class="card-header text-center py-3 text-dark font-weight-bold"><?php echo $week['name'];?></h5>
                                          <ul class="list-group list-group-flush">
                                              
                                              
                                               <?php 
                                               if(!empty($week['homework_model_answer'])){
                                                $stmt=$con->prepare("SELECT * FROM files WHERE id = ?");
                                                $stmt->execute(array($week['homework_model_answer']));
                                                $file_model=$stmt->fetch();
                                                  if($stmt->rowCount() > 0){
                                                          
                                               
                                               
                                                ?>
                                                
                                          
                                            <li class="list-group-item">
                                       
                                             <a href="dashboard/uploads/files/<?php echo $file_model['file']?>" download>
                                               <i class="fas fa-file-pdf"></i> 
                                                <?php echo $file_model['name'] ?>
                                             </a>
                                            
                                          </li>
                                          
                                           <?php } 
                                           
                                           }?>
                                           
                                                      
                                          
                                             
                                              
                                                  <?php 
                                                        $stmt=$con->prepare("SELECT * FROM exams WHERE id = ?");
                                                        $stmt->execute(array($week['exam1_id']));
                                                        $exam1=$stmt->fetch();
                                                         $examoneid = $exam1['id'];
                                                      if($stmt->rowCount() > 0){
                                                    
                                                  
                                                        $stmt=$con->prepare("SELECT * FROM studentexam WHERE studentid = ? AND examid = ?");
                                                        $stmt->execute(array($studentid,$week['exam1_id']));
                                                        $ex1=$stmt->fetch();
                                                        $solveexam1=$stmt->rowCount();
                                                    if($solveexam1 < 1){
                                                         $url = "answersexam.php?id=$examoneid&wid=$wid";
                                                         $msg = "Not Solved yet";
                                                    }else{
                                                            $examTime = strtotime($ex1['end_time']); ///----------------------------
                                                            $examtime1 = date('H:i:s', $examTime);
                                                            $examfulltime=$ex1['end_time'];
                                                            $currentfulltime=date('Y-m-d H:i:s');
        
                                                            $date = strtotime($ex1['end_time']);
                                                            $examdate1 = date('Y-m-d', $examTime);
                                                            
                                                            $days_model_answer = $exam1['days_to_show_model_answer'];
                                                            $show_model_answer = date('Y-m-d', strtotime($examdate1. " + $days_model_answer days"));
                                                           
                                                            $exm1 = $week['exam1_id'];
                                                            
                                                            // Check Student Solve Exam 
                                                            $stmt=$con->prepare("SELECT * FROM examsanswer WHERE studentid = ? AND examid = ?");
                                                            $stmt->execute(array($studentid,$week['exam1_id']));
                                                            $solved=$stmt->fetch();
                                                            $numsolvedex1=$stmt->rowCount();
                                                            
                                                            if($numsolvedex1 > 0){
                                                                $msgex1 = "تم الحل" .$ex1['result']."/".$exam1['degree'];
                                                            }else{
                                                                $msgex1 = "لم يتم الحل";
                                                            }
                                                            
                                                            // ---------------------------------------------
                                                           if( ( ($examtime1 < $currenttimeonly   &&  $ex1['result'] != NULL ) ||  ($ex1['completed'] == 1) ) ){
                                                             
                                                               if($show_model_answer <= $currentdateonly){
                                                                     $url = "exmodelanswer.php?id=$exm1";
                                                                     $msg = "Model Answer";
                                                                }else{
                                                                    $url="#";
                                                                    $msg = "Answer On ".$days_model_answer." Days";
                                                                }
                                                                
                                                            }else{
                                                               
                                                                   $url = "answersexam.php?id=$exm1&wid=$wid";
                                                                   $msg = "Not Solved yet";
                                                               
                                                            
                                                            }
                                                    
                                                    }  
                                                          
                                                ?>
                                           <li class="list-group-item d-flex justify-content-between">
                                       
                                            
                                          
                                             <a href="<?php echo $url?>">
                                                 <i class="fas fa-diagnoses"></i> 
                                                 <?php echo $exam1['name'] . "<br>[" . $msg ."]"; ?>
                                                 
                                             </a>
                                             
                                       <?php if($solveexam1 > 0 && ($examtime1 < $currenttimeonly || $ex1['completed'] == 1)){ if($ex1['result']==NULL){ echo " <span class='text-danger'>Not corrected Yet</span>";}elseif($ex1['result'] == 0){echo " <span class='text-danger'>$msgex1</span>";}else{ echo "<span>( ".$ex1['result']." / ".$exam1['degree']." )</span>";} } ?>

                                             
                                          </li>
                                             <?php }?>
                                           <?php
                                                // echo $ex1['result']."<br>";
                                                // echo $exam1['min_degree']."<br>";
                                                // echo $ex1['result'] ."<br>";
                                                // echo $examtime1 ."<br>";
                                                // echo $currenttimeonly ."<br>";
                                           
                                                    if($ex1['result'] < $exam1['min_degree'] &&  $ex1['result'] != NULL  &&  ($examfulltime < $currentfulltime  || $ex1['completed'] == 1) ){
                                                        
                                                    
                                                    $stmt=$con->prepare("SELECT * FROM exams WHERE id = ?");
                                                    $stmt->execute(array($week['exam2_id']));
                                                    $exam2=$stmt->fetch();
                                                    $examtwoid = $exam2['id'];
                                                    if($stmt->rowCount() > 0){
                                                          
                                                    
                                                    $stmt=$con->prepare("SELECT * FROM studentexam WHERE studentid = ? AND examid = ?");
                                                    $stmt->execute(array($studentid,$week['exam2_id']));
                                                    $ex2=$stmt->fetch();
                                                    $solveexam2=$stmt->rowCount();
                                                    if($solveexam2 < 1){
                                                          $url = "answersexam.php?id=$examtwoid&wid=$wid";
                                                    }else{
                                                    $examTime = strtotime($ex2['end_time']);
                                                    $examtime2 = date('H:i:s', $examTime);
                                                    
                                                    $date = strtotime($ex2['end_time']);
                                                    $examdate = date('Y-m-d', $examTime);
                                                    
                                                    $days_model_answer = $exam2['days_to_show_model_answer'];
                                                    $show_model_answer = date('Y-m-d', strtotime($examdate. " + $days_model_answer days"));
                                                    
                                                    $exm2 = $week['exam2_id'];
                                                    
                                                     // Check Student Solve Exam 
                                                    $stmt=$con->prepare("SELECT * FROM examsanswer WHERE studentid = ? AND examid = ?");
                                                    $stmt->execute(array($studentid,$week['exam2_id']));
                                                    $solved2=$stmt->fetch();
                                                    $numsolvedex2=$stmt->rowCount();
                                                    
                                                    if($numsolvedex2 > 0){
                                                        $msgex2 = "تم الحل" .$ex2['result']."/".$exam2['degree'];
                                                    }else{
                                                        $msgex2 = "لم يتم الحل";
                                                    }
                                                    
                                                    // ---------------------------------------------
                                                    
                                                    
                                                   if( ( $examtime2 < $currenttimeonly  && $ex2['result'] != NULL) || ($ex2['completed'] == 1)){
                                                     
                                                        if($show_model_answer <= $currentdateonly){
                                                             $url = "exmodelanswer.php?id=$exm2";
                                                        }else{
                                                            $url="#";
                                                        }
                                                       
                                                    }else{
                                                       
                                                           
                                                           $url = "answersexam.php?id=$exm2&wid=$wid";
                                                       
                                                    
                                                    }
                                                    }
                                                    
                                                ?>
                                                
                                          
                                            <li class="list-group-item d-flex justify-content-between">
                                       
                                            
                                            
                                             <a href="<?php echo $url?>">
                                               <i class="fas fa-diagnoses"></i>
                                                <?php echo $exam2['name'] ?>
                                             </a>
                                             
                                         <?php if($solveexam2 > 0 && ($examtime2 < $currenttimeonly || $ex2['completed'] == 1) ){ if($ex2['result']==NULL){ echo " <span class='text-danger'>Not corrected Yet</span>";}elseif($ex2['result'] == 0){echo " <span class='text-danger'>$msgex2</span>";} else{ echo "<span>( ".$ex2['result']." / ".$exam2['degree']." )</span>";} } ?>

                                           
                                             
                                          </li>
                                          <?php }
                                                    }
                                          ?>
                                          
                                          
                                          
                                           <?php 
                                                $stmt=$con->prepare("SELECT * FROM files WHERE id = ?");
                                                $stmt->execute(array($week['files']));
                                                $file=$stmt->fetch();
                                                if($stmt->rowCount() > 0){
                                              
                                                ?>
                                                
                                          
                                            <li class="list-group-item">
                                       
                                                
                                             <a href="dashboard/uploads/files/<?php echo $file['file']?>" <?php echo $download;?>>
                                                 <i class="fas fa-file-pdf"></i>  
                                                <?php echo $file['name'] ?>
                                             </a>
                                            
                                          
                                           <?php }?>
                                           
                                           
                                          
                                          
                                        <?php 
                                            $stmt=$con->prepare("SELECT name,type FROM videos WHERE id = ?");
                                            $stmt->execute(array($week['video_id']));
                                            $video=$stmt->fetch();
                                            if($stmt->rowCount() > 0){
                                            
                                            $video_type = $video['type'];
                                                            
                                            $stmt=$con->prepare("SELECT * FROM studentexam WHERE studentid = ? AND examid = ?");
                                            $stmt->execute(array($studentid,$week['exam2_id']));
                                             $studentexam2=$stmt->fetch();
                                            $check_exam2=$stmt->rowCount();
                                            if($week['exam1_id'] != NULL || $week['exam2_id'] != NULL){
                                                
                                                  if((($ex1['result'] >= $exam1['min_degree'] &&  $ex1['result'] != NULL && ($examtime1 < $currenttimeonly || $ex1['completed'] == 1)) ) || ( ( ($ex2['result'] >= $exam2['min_degree'] &&  $ex2['result'] != NULL && ($examtime2 < $currenttimeonly || $ex2['completed'] == 1)  && $week['exam2_id'] != NULL)) || ($studentexam2['status'] == 1) )  ){
                                                 $download = 'download';
                                                     }else{
                                                        $download = '';
                                                    }
                                            
                                            }else{
                                                  $download = 'download';
                                            }
                                            
                                            if($edulevel == 10){
                                                 $download = 'download';
                                            }
                                         ?>
                                         
                                         <li class="list-group-item">
                                        
                                            <?php if($download == 'download'){
                                                
                                                $vid = $week['video_id'];
                                                $hwid = $week['quiz_id'];
                                                $wid = $week['id'];
                                                if($video_type == 'youtube'){
                                                     $url = "showvideo2.php?id=$vid&homeworkid=$hwid&wid=$wid";
                                                }else{
                                                      $url = "showvideo.php?id=$vid&homeworkid=$hwid&wid=$wid";
                                                }
                                               
                                            }else{
                                                $url='#';
                                            }
                                            ?>
                                             <a href="<?php echo $url;?>">
                                               <i class="fas fa-play"></i>
                                                <?php echo $video['name'] ?>
                                             </a>
                                             
                                           
                                             
                                         </li>
                                          <?php }?>
                                           <?php 
                                                    $stmt=$con->prepare("SELECT * FROM homeworks WHERE id = ?");
                                                    $stmt->execute(array($week['quiz_id']));
                                                    $quiz=$stmt->fetch();
                                                   
                                                    if($stmt->rowCount() > 0){
                                                        
                                                    if(!empty($quiz['answer'])){
                                                        $q = $quiz['answer'];
                                                        $url = "dashboard/uploads/homework model answer/$q";
                                                    }else{
                                                        
                                                   
                                                    
                                                    $stmt=$con->prepare("SELECT * FROM studenthw WHERE studentid = ? AND homeworkid = ?");
                                                    $stmt->execute(array($studentid,$week['quiz_id']));
                                                    $homewrok=$stmt->fetch();
                                                    $check_homework = $stmt->rowCount();
                                                    $hmwid = $week['quiz_id'];
                                                    $wid = $week['id'];
                                                    
                                                      if($download == 'download'){
                                                
                                                             if($homewrok['result']!=NULL){
                                                                    $url = "hwmodelanswer.php?id=$hmwid";
                                                                }else{
                                                                    $url = "answers.php?id=$hmwid&wid=$wid";
                                                                }
                                                        }else{
                                                            $url='#';
                                                        }
                                                    }
                                                   
                                            ?>
                                        <li class="list-group-item d-flex justify-content-between">
                                       
                                          
                                             <a href="<?php echo $url?>">
                                                <i class="fab fa-stack-exchange"></i>
                                                <?php echo $quiz['name']." ";?>
                                                
                                             </a>
                                            <?php if($check_homework > 0 ){ if($homewrok['result']==NULL) echo " <span class='text-danger'>Not corrected Yet</span>"; else echo "<span>( ".$homewrok['result']." / ".$quiz['degree']." )</span>"; }?>

                                          
                                             
                                          </li>
                                           <?php }?>
                                        

                                         
                                            </ul>
                                    </div>
                                </div>
                        
                          
                           
                            <!-- Exam Result Area End Here -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Page Area End Here -->
    </div>
    <?php include_once "footer.php"; ?>
    <!-- jquery-->
    <script src="js\jquery-3.3.1.min.js"></script>
    <!-- Plugins js -->
    <script src="js\plugins.js"></script>
    <!-- Popper js -->
    <script src="js\popper.min.js"></script>
    <!-- Bootstrap js -->
    <script src="js\bootstrap.min.js"></script>
    <!-- Counterup Js -->
    <script src="js\jquery.counterup.min.js"></script>
    <!-- Moment Js -->
    <script src="js\moment.min.js"></script>
    <!-- Waypoints Js -->
    <script src="js\jquery.waypoints.min.js"></script>
    <!-- Scroll Up Js -->
    <script src="js\jquery.scrollUp.min.js"></script>
    <!-- Full Calender Js -->
    <script src="js\fullcalendar.min.js"></script>
    <!-- Chart Js -->
    <script src="js\Chart.min.js"></script>
    <!-- Data Table Js -->
    <script src="js\jquery.dataTables.min.js"></script>
    <!-- Custom Js -->
    <script src="js\main.js"></script>

</body>

</html>