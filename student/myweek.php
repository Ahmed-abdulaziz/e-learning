<?php 
include_once 'connect.php';
session_start();

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

// $stmt=$con->prepare("SELECT * FROM studentssubjects WHERE studentid = ?");
// $stmt->execute(array($student['id']));
// $subject=$stmt->fetch();
$studentid = $student['id'];

// $_SESSION['subject']=$subject['subjectid'];
// $_SESSION['class']=$subject['classid'];

//show subject details
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
date_default_timezone_set('Africa/Cairo');

$cdate = date('Y-m-d');

$stmt=$con->prepare("SELECT * FROM weeks WHERE id = ?  AND  ( id  IN (SELECT product_id FROM student_wallet WHERE studentid = ? AND type = ? ) )");
$stmt->execute(array($wid ,$student['id'] ,'week'));
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
    
    
    $stmt=$con->prepare("SELECT * FROM student_wallet WHERE product_id = ? AND  studentid = ? AND type = ? ORDER BY date DESC ");
    $stmt->execute(array($wid ,$student['id'] ,'week'));
    $duration=$stmt->fetch();
    
    $end_date = date('Y-m-d', strtotime($duration['date']. "+". $week['duration'] ."days"));

// $cdate='2023-08-26';

    if($end_date<$cdate){
        header("Location:weeks.php?cid=".$week['chapter']."&msg=".urlencode("The Duration of the week end"));
        exit();
    }

if(isset($_POST['buy'])){
        $week_id = $_POST['week_id'];
        $studentid = $_POST['studentid'];
        
        $stmt=$con->prepare("SELECT * FROM weeks WHERE id = ?");
        $stmt->execute(array($week_id));
        $wek=$stmt->fetch();
        
        $stmt=$con->prepare("SELECT sum(money) as totalwallet FROM student_wallet WHERE studentid = ?");
        $stmt->execute(array($studentid));
        $total=$stmt->fetch();
        
        if($wek['price'] > $total['totalwallet']){
                            
            header("Location: " . $_SERVER['HTTP_REFERER']."&msg=".urldecode("Insufficient Balance. Please charge the wallet first"));
            exit();
        }else{
            
                $stmt = $con->prepare("INSERT INTO student_wallet (studentid,money,date ,product_id , type) VALUES (:studentid,:money,:date , :product_id , :type)");
                $stmt->execute(array(
                    'studentid' => $studentid,
                    'money' => $wek['price'] * -1,
                    'date'=>date('Y-m-d') , 
                    'product_id'=> $week_id,
                    'type'=>"Week"
                ));
            
                $stmt=$con->prepare("SELECT * FROM week_details WHERE   week_id = ? AND type = ? ");
                $stmt->execute(array($week_id ,'video'));
                $week_details=$stmt->fetch();
                
                $stmt=$con->prepare("DELETE FROM videocount WHERE videoid = ? AND studentid = ?");
                $stmt->execute(array($week_details['product_id'] ,$studentid));
            
                header("location:myweek.php?wid=$week_id&msg=".urldecode("Successfully purchased this week!"));
                exit();
         }
    
  
    
}

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
                                                if($week['arrange'] == 1){
                                                            $stmt=$con->prepare("SELECT * FROM week_details WHERE week_id = ? ORDER BY pro_order ASC");
                                                            $stmt->execute(array($wid));
                                                            $details=$stmt->fetchAll();
                                                }else{
                                                            $stmt=$con->prepare("SELECT * FROM week_details WHERE week_id = ?");
                                                            $stmt->execute(array($wid));
                                                            $details=$stmt->fetchAll();
                                                }
                                                
                                                
                                                foreach($details as $item){
                                                $url = $final_msg =  $model_answers_msg = '';
                                                if($item['type'] == 'video'){
                                                    
                                                    $icon = '<i class="fas fa-play"></i>';

                                                    $stmt=$con->prepare("SELECT * FROM videos WHERE id = ?");
                                                    $stmt->execute(array($item['product_id']));
                                                    $video=$stmt->fetch();
                                                    $name = $video['name'];
                                                    $vid =$video['id']; 
                                                   
                                                    $stmt=$con->prepare("SELECT * FROM videocount WHERE studentid = ? AND videoid = ? AND timer >= ?");
                                                    $stmt->execute(array($studentid,$item['product_id'],$video['minutesview']));
                                                    $viewscount=$stmt->rowCount();
                                                    
                                                    $views = $video['no'] - $viewscount;
                                                    $url = "showvideo.php?id=$vid&wid=$wid&fid=$fid";
                                                    $final_msg = "You have $views views";

                                                }elseif($item['type'] == 'exam'){
                                                            $icon = '<i class="fas fa-diagnoses"></i>';
                                                             
                                                            $stmt=$con->prepare("SELECT * FROM exams WHERE id = ?");
                                                            $stmt->execute(array($item['product_id']));
                                                            $exam=$stmt->fetch();
                                                            $name = $exam['name'];
                                                            $examid = $exam['id'];
                                                            
                                                            
                                                            $stmt=$con->prepare("SELECT * FROM examsanswer WHERE examid = ?  AND studentid = ? ");
                                                            $stmt->execute(array($examid,$studentid));
                                                            $degree=$stmt->rowCount();
                                                            // ------------------------------------------------------------
                                                            
                                                              
                                                              if(!empty($exam)){ 
                                                                  
                                                                $stmt=$con->prepare("SELECT * FROM studentexam WHERE studentid = ? AND examid = ?");
                                                                $stmt->execute(array($studentid,$examid));
                                                                $ex=$stmt->fetch();
                                                                $solveexam=$stmt->rowCount();
                                                                
                                                            if(empty($ex)){
                                                                 $url = "answersexam.php?id=$examid&wid=$wid";
                                                                 $msg = "Not Solved yet";
                                                            }else{
                                                                    // $examTime = strtotime($ex['end_time']); 
                                                                    // $examtime = date('H:i:s', $examTime);
                                                                    // $examfulltime=$ex['end_time'];
                                                                    // $currentfulltime=date('Y-m-d H:i:s');
                
                                                                    // $date = strtotime($ex['end_time']);
                                                                    // $examdate = date('Y-m-d', $examTime);
                                                                    
                                                                    // $days_model_answer = $exam['days_to_show_model_answer'];
                                                                    // $show_model_answer = date('Y-m-d', strtotime($examdate. " + $days_model_answer days"));
                                                                   
                                                                    // $exm = $examid;
                                                                    
                                                                    // Check Student Solve Exam 
                                                                    // $stmt=$con->prepare("SELECT * FROM examsanswer WHERE studentid = ? AND examid = ?");
                                                                    // $stmt->execute(array($studentid,$examid));
                                                                    // $solved=$stmt->fetch();
                                                                    // $numsolvedex=$stmt->rowCount();
                                                                    
                                                                    // if($numsolvedex > 0){
                                                                    //     $msgex = "تم الحل" .$ex['result']."/".$exam['degree'];
                                                                    // }else{
                                                                    //     $msgex = "لم يتم الحل";
                                                                    // }
                                                                    
                                                                    // ---------------------------------------------
                                                                   if( ( ($ex['result'] != NULL ) ||  ($ex['completed'] == 1) ) ){
                                                                            if(!empty($exam['days_to_show_model_answer'])){
                                                                                  $days_model_answer = $exam['days_to_show_model_answer'];
                                                                                   $show_model_answer = date('Y-m-d', strtotime( date('Y-m-d',strtotime($exam['created_at'])). " + $days_model_answer days"));
                                                                                   if($show_model_answer <= date('Y-m-d')){
                                                                                            $url = "exmodelanswer.php?id=$examid";
                                                                                            $msg = "Model Answer";
                                                                                   }else{
                                                                                        $url = "#";
                                                                                          $msg = "Model Answer";
                                                                                         $model_dayes =  round(abs(strtotime($show_model_answer) - strtotime(date('Y-m-d')))/86400);
                                                                                         $model_answers_msg = "<p class='btn-info p-2'>You can see the model Answer after $model_dayes days</p>";
                                                                                   }
                                                                            }else{
                                                                                    $url = "exmodelanswer.php?id=$examid";
                                                                                    $msg = "Model Answer";

                                                                            }
                                                                                $final_msg = "<span>( ".$ex['result']." / ".$degree." )</span>";

                                                                             if($exam['min_degree']!=0){
                                                                                 if($ex['result']<$exam['min_degree']){
                                                                                    $stmt=$con->prepare("SELECT * FROM exams WHERE parentexam = ?");
                                                                                    $stmt->execute(array($exam['id']));
                                                                                    $exam2=$stmt->fetch();
                                                                                    $name2=$exam2['name'];
                                                                                    
                                                                                    $stmt=$con->prepare("SELECT * FROM studentexam WHERE studentid = ? AND examid = ?");
                                                                                    $stmt->execute(array($studentid,$exam2['id']));
                                                                                    $ex2=$stmt->fetch();
                                                                                    
                                                                                    if(empty($ex2)){
                                                                                         $url2 = "answersexam.php?id=".$exam2['id']."&wid=$wid";
                                                                                         $msg = "Not Solved yet";
                                                                                    }else{
                                                                                        if( ( ($ex['result'] != NULL ) ||  ($ex['completed'] == 1) ) ){
                                                                                            
                                                                                              if(!empty($exam['days_to_show_model_answer'])){
                                                                                                        $days_model_answer = $exam['days_to_show_model_answer'];
                                                                                                           $show_model_answer = date('Y-m-d', strtotime( date('Y-m-d',strtotime($exam['created_at'])). " + $days_model_answer days"));
                                                                                                        
                                                                                                           if($show_model_answer <= date('Y-m-d')){
                                                                                                                $url2 = "exmodelanswer.php?id=".$exam2['id'];
                                                                                                                $msg = "Model Answer";
                                                                                                           }else{
                                                                                                                  $url = "#";
                                                                                                                  $msg = "Model Answer";
                                                                                                                $model_dayes =  round(abs(strtotime($show_model_answer) - strtotime(date('Y-m-d')))/86400);

                                                                                                                  $model_answers_msg = "<p class='btn-info p-2'>You can see the model Answer after $model_dayes days</p>";
                                                                                                           }
                                                                                                }else{
                                                                                                                $url2 = "exmodelanswer.php?id=".$exam2['id'];
                                                                                                                $msg = "Model Answer";
                    
                                                                                                }
                                                                                            
                                                                                            $final_msg2 = "<span>( ".$ex2['result']." / ".$exam2['numofquestion']." )</span>";
                                                                                        }
                                                                                    }
                                                                                    
                                                                                 }
                                                                             }
                                                                        
                                                                    }else{
                                                                       
                                                                           $url = "answersexam.php?id=$exm&wid=$wid";
                                                                           $msg = "Not Solved yet";
                                                                       
                                                                    
                                                                    }
                                                            
                                                            }  
                                                        }
                                                        
                                                    //   if($solveexam > 0 && ($ex['completed'] == 1)){ 
                                                    //       if($ex['result']==NULL){
                                                    //           $final_msg = "<span class='text-danger'>Not corrected Yet</span>";
                                                    //       }else{ $final_msg = "<span>( ".$ex['result']." / ".$exam['numofquestion']." )</span>";} 
                                                          
                                                    //   } 
                                                    // -------------------------------------------------------------

                                                }elseif($item['type'] == 'homework'){
                                                                 $icon = '<i class="fab fa-stack-exchange"></i>';
                                                                 
                                                                $stmt=$con->prepare("SELECT * FROM homeworks WHERE id = ?");
                                                                $stmt->execute(array($item['product_id']));
                                                                $quiz=$stmt->fetch();
                                                                $name = $quiz['name'];
            
                                                                if($stmt->rowCount() > 0){
                                                                    
                                                                if(!empty($quiz['answer'])){
                                                                    $q = $quiz['answer'];
                                                                    $url = "dashboard/uploads/homework model answer/$q";
                                                                }else{
                                                                    
                                                               
                                                                
                                                                $stmt=$con->prepare("SELECT * FROM studenthw WHERE studentid = ? AND homeworkid = ?");
                                                                $stmt->execute(array($studentid,$quiz['id']));
                                                                $homewrok=$stmt->fetch();
                                                                $check_homework = $stmt->rowCount();
                                                                $hmwid = $quiz['id'];
                                                                $wid = $week['id'];
                                                                
                                                                  if($homewrok['result']!=NULL){
                                                                        $url = "hwmodelanswer.php?id=$hmwid";
                                                                    }else{
                                                                        $url = "answers.php?id=$hmwid&wid=$wid";
                                                                    }
                                                                  
                                                                }
                                                            }
                                                    if($check_homework > 0 ){ if($homewrok['result']==NULL) $final_msg = "<span class='text-danger'>Not corrected Yet</span>"; else $final_msg= "<span>( ".$homewrok['result']." / ".$quiz['degree']." )</span>"; }
 
                                                }elseif($item['type'] == 'file'){
                                                     $icon = '<i class="fas fa-file-pdf"></i>';
                                                     
                                                    $stmt=$con->prepare("SELECT * FROM files WHERE id = ?");
                                                    $stmt->execute(array($item['product_id']));
                                                    $file=$stmt->fetch();
                                                    $name = $file['name'];
                                                    $url = "dashboard/uploads/files/".$file['file'];
                                                }
                                           ?>
                                           
                                        <li class="list-group-item">
                                             <a  class="d-flex justify-content-between" href="<?php if($end_date >= $cdate){echo $url;}else{echo "#";};?>" <?php if($item['type'] == 'file' && $end_date >= $cdate){echo "download";}?> >
                                                <span> 
                                                    <?=$icon;?>
                                                    <?=$name;?>
                                                    <?php if(!empty($model_answers_msg)){echo $model_answers_msg;} ?>
                                                </span>
                                                <?=$final_msg?>
                                             </a>
                                        </li>
                                        
                                        <?php if($item['type'] == 'exam' && isset($ex2)){ ?>
                                        
                                        <li class="list-group-item">
                                             <a  class="d-flex justify-content-between" href="<?php if($end_date >= $cdate){echo $url2;}else{echo "#";};?>" <?php if($item['type'] == 'file' && $end_date >= $cdate){echo "download";}?> >
                                                <span> 
                                                    <?=$icon;?>
                                                    <?=$name2;?>
                                                     <?php //if(!empty($model_answers_msg)){echo $model_answers_msg;} ?>
                                                </span>
                                                <?=$final_msg2?>
                                             </a>
                                        </li>
                                        
                                        <?php }?>
                                           
                                            <?php }?>
                                        <?php
                                        $stmt=$con->prepare("SELECT link from weeks WHERE id = ?");
                                        $stmt->execute(array($wid));
                                        $link=$stmt->fetch();
                                        ?>
                                      <?php if(!empty($link['link'])){?>
                                        <li class="list-group-item">
                                             <a  class="d-flex justify-content-between" href="<?php echo $link['link'];?>" target="_blank">
                                                <span> 
                                                   <?php echo '<i class="fas fa-link"></i>';?>
                                                 
                                                    <?= $link['link'];?>
                                                     <?php //if(!empty($model_answers_msg)){echo $model_answers_msg;} ?>
                                                </span>
                                          
                                             </a>
                                        </li>
                                        <?php }?>

                                         
                                            </ul>
                                    </div>
                                    <?php if($views < 1){ ?>
                                    <form method="post">
                                            <input type="text" hidden name="week_id" value="<?=$wid?>" />
                                             <input type="text" hidden name="studentid" value="<?=$studentid?>" />
                                            <button class="btn btn-lg btn-info my-5 confirm" name="buy">Buy Again</button>
                                    </form>
                                    <?php }?>
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
<script>
         
              $(".confirm").click(function(e){
                if(!confirm("Are you sure you want to Buy This Week Again?")){e.preventDefault();}
            });
            
    </script>
</body>

</html>