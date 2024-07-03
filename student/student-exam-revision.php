<?php 
ini_set('max_execution_time', 0);
include_once "connect.php";
date_default_timezone_set("Africa/Cairo");
session_start();
if(!isset($_SESSION['name'])){
    if(isset($_COOKIE['msu_email'])){
        $_SESSION['name']=$_COOKIE['msu_email'];
        $stmt=$con->prepare("SELECT id FROM students WHERE email = ?");
        $stmt->execute(array($_SESSION['name']));
        $student=$stmt->fetch();
        
        $stmt=$con->prepare("SELECT * FROM studentssubjects WHERE studentid = ?");
        $stmt->execute(array($student['id']));
        $subject=$stmt->fetch();
        
        $_SESSION['subject']=$subject['subjectid'];
        $_SESSION['class']=$subject['classid'];
    }else{
        header("Location:index.php?msg=".urlencode("Please Login First"));
        exit();
    }
}else{
    setcookie("msu_email", $_SESSION['name'], time()+3600*24);  /* this would expire in 1 year */
}

$email= $_SESSION['name'];
$stmt=$con->prepare("SELECT id FROM students WHERE email = ?");
$stmt->execute(array($email));
$result=$stmt->fetch();
$studentid=$result['id'];


if(isset($_GET['id'])){
    $examid=$_GET['id'];
    $page=$_GET['page'];
    $wid=$_GET['wid'];
    

    
    $stmt=$con->prepare("SELECT * FROM weeks WHERE id = ?");
    $stmt->execute(array($wid));
    $week=$stmt->fetch();
    

      
    if(!empty($week['exam1_id'])){
        $stmt=$con->prepare("SELECT * FROM studentexam WHERE studentid = ? AND examid = ?");
        $stmt->execute(array($studentid,$week['exam1_id']));
        $check_exam1=$stmt->rowCount();
        $exid = $week['exam1_id'];
        if($check_exam1 < 1 && $examid != $exid){
                header("Location:answersexam.php?id=$exid&wid=$wid&error=".urlencode("برجاء اجتياز هذا الامتحان اولا"));
                exit();
        }
    }
   
     
    $stmt=$con->prepare("SELECT * FROM exams WHERE id =?");
    $stmt->execute(array($examid));
    $t=$stmt->fetch();
    $timer = $t['timer'];
    $start_date=$t['start_date'];
    $end_date=$t['end_date'];
    $cdate=date("Y-m-d");

    if((   $cdate <  $start_date  || $cdate > $end_date )){
        
                if($cdate <  $start_date){
                        header("Location:myweek.php?wid=$wid&msg=".urlencode("Exam time Is Not Start Yet!"));
                        exit();
                }
                 if($cdate > $end_date){
                        header("Location:myweek.php?wid=$wid&error=".urlencode("Exam time is out!"));
                        exit();
                }
           
            
    }
    $currentTime = date("Y-m-d H:i:s");
    $currentTimeinsec=strtotime($currentTime);
    $endtimeanddate = date('Y-m-d H:i:s', strtotime("+$timer minutes", strtotime($currentTime)));
    $endtimeanddateinsec=strtotime($endtimeanddate);

    
    $stmt=$con->prepare("SELECT * FROM studentexam WHERE studentid = ? AND examid = ?");
    $stmt->execute(array($studentid,$examid));
    $solved=$stmt->fetch();
    $check = $stmt->rowCount();
    if($check==0){
        
        $stmt=$con->prepare("INSERT INTO studentexam (studentid,examid,end_time,result) VALUES (:zstudentid,:zexamid,:zend_time,:result) ");
        $stmt->execute(array("zstudentid"=>$studentid,"zexamid"=>$examid,'zend_time'=>$endtimeanddate,'result'=>0));
    }
    
    
    $stmt=$con->prepare("SELECT * FROM studentexam WHERE studentid = ? AND examid = ?");
    $stmt->execute(array($studentid,$examid));
    $solved=$stmt->fetch();
    $endtimerinmin=strtotime($solved['end_time']);

    
    if($currentTimeinsec > $endtimerinmin){
        header("Location:myweek.php?wid=$wid&error=".urlencode("Exam time is out!"));
        exit();
    }
    $endtimer = round(( $endtimerinmin - $currentTimeinsec) / 60 , 2);

    $stmt=$con->prepare("SELECT * FROM examquestions WHERE examid =?");
    $stmt->execute(array($examid));
    $questions=$stmt->fetchAll();
    
    $radiono=0;
    $givenno=0;

}

if(isset($_POST['next-revision'])){
    
        $examid=$_POST['id'];
        $studentid=$_POST['studentid'];
        $wid=$_POST['weekid'];
        $essay=$_POST['essay'];
        $essayids=$_POST['essayids'];
        $multichoiceanswers=$_POST['radio'];
        $multichoiceids=$_POST['multichoiceids'];
        
        // $stmt=$con->prepare("SELECT * FROM examquestions WHERE examid =? ORDER BY id ASC");
        // $stmt->execute(array($examid));
        // $questions=$stmt->fetchAll();
        
        $stmt=$con->prepare("SELECT * FROM examsanswer WHERE examid =? AND studentid = ?");
        $stmt->execute(array($examid , $studentid));
        $questions=$stmt->fetchAll();
        
        $stmt=$con->prepare("SELECT * FROM exams WHERE id =?");
        $stmt->execute(array($examid));
        $main_exam=$stmt->fetch();
        
        
}

if(isset($_POST['previous'])){
    
    $examid = $_POST['id'];
    $weekid = $_POST['weekid'];
    // echo $weekid;die;
    header("Location:answersexam.php?id=$examid&wid=$weekid");
    exit();
        
}

if(isset($_POST['finish'])){
    
    $examid=$_POST['id'];
    $studentid=$_POST['studentid'];
    $wid=$_POST['weekid'];
    $total = 0;
    $stmt=$con->prepare("SELECT * FROM studentexam WHERE studentid = ? AND examid = ?");
    $stmt->execute(array($studentid,$examid));
    $solved=$stmt->fetchAll();
    
    if(COUNT($solved)==0){
        $stmt=$con->prepare("INSERT INTO studentexam (studentid,examid) VALUES (:zstudentid,:zexamid) ");
        $stmt->execute(array("zstudentid"=>$studentid,"zexamid"=>$examid));
    }



if(isset($_POST['essay']) && isset($_POST['essayids'])){
$essayanswers=$_POST['essay'];
$essayids=$_POST['essayids'];  
foreach (array_combine($essayids, $essayanswers) as $essayid => $essayanswer) {
    $stmt=$con->prepare("INSERT INTO examsanswer (studentid,questionid,type,examid,answer) VALUES (:zstudentid,:zquestionid,:ztype,:zexamid,:zanswer) ");
    $stmt->execute(array("zstudentid"=>$studentid,"zquestionid"=>$essayid,"ztype"=>"1","zexamid"=>$examid,"zanswer"=>$essayanswer));

  }
}

if(isset($_POST['radio']) && isset($_POST['multichoiceids'])){
$multichoiceanswers=$_POST['radio'];
$multichoiceids=$_POST['multichoiceids'];
  foreach (array_combine($multichoiceids, $multichoiceanswers) as $multichoiceid => $multichoiceanswer) {
      
    $stmt=$con->prepare("SELECT * FROM examsanswer WHERE examid=? AND studentid=? AND questionid=?");
    $stmt->execute(array($examid,$studentid,$multichoiceid));
    $oldan=$stmt->fetch();
    
        $stmt=$con->prepare("SELECT answer FROM questions WHERE id =?");
        $stmt->execute(array($multichoiceid));
        $question=$stmt->fetch();
        
        if($question['answer'] == $multichoiceanswer){
            $degree = 1;
            $total++;
        }else{
            $degree = 0;
        }
        
    
    if(!empty($oldan)){
         $oldan=$stmt->fetch();
         $stmt=$con->prepare("UPDATE examsanswer SET answer = ? , sent = ? ,degree = ?  WHERE id = ? ");
         $stmt->execute(array($multichoiceanswer,"ajax then finish",$degree,$oldan['id']));
    }else{
         $stmt=$con->prepare("INSERT INTO examsanswer (studentid,questionid,type,examid,answer,sent,degree) VALUES (:zstudentid,:zquestionid,:ztype,:zexamid,:zanswer,:zsent,:zdegree) ");
         $stmt->execute(array("zstudentid"=>$studentid,"zquestionid"=>$multichoiceid,"ztype"=>"0","zexamid"=>$examid,"zanswer"=>$multichoiceanswer,"zsent"=>"finish" ,'zdegree'=>$degree));
    }
      
  }
  
    $stmt=$con->prepare("SELECT phone2,name FROM students WHERE id = ?");
    $stmt->execute(array($studentid));
    $res=$stmt->fetch();
    $phone =$res['phone2'];
    $stname =$res['name'];
    
    $stmt=$con->prepare("SELECT name FROM exams WHERE id = ?");
    $stmt->execute(array($examid));
    $ex=$stmt->fetch();
    $exname =$ex['name'];
    $total_exam_degree = $ex['numofquestion'];
    
    $stmt=$con->prepare("SELECT * FROM weeks WHERE id = ?");
    $stmt->execute(array($wid));
    $we=$stmt->fetch();
    $wename =$we['name'];
    
    $stmt=$con->prepare("SELECT * FROM admins WHERE id  IN (SELECT teacher_id FROM subjects WHERE id = ?  )");
    $stmt->execute(array($we['subjectid']));
    $te=$stmt->fetch();
    $tename =$te['fullname'];
    
    // $msg = "ولى امر الطالب $stname نبلغ حضراتكم بحصول الطالب على درجه $total من $total_exam_degree وذلك فى حصة $wename لمستر $tename ";
    
    // $ch = curl_init();
    // $url= "https://smssmartegypt.com/sms/api/?username=easyphysics&password=75E824*tR&sendername=Academy&mobiles=2".$phone."&message=".urlencode($msg);
    // curl_setopt($ch, CURLOPT_URL,$url);
    // curl_setopt($ch, CURLOPT_POST, 1);
    // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // $server_output = curl_exec($ch);
    // curl_close ($ch);
}


// if(isset($_POST['given']) && isset($_POST['givenids'])){
// $givenanswers=$_POST['given'];
// $givenids=$_POST['givenids'];
//   foreach (array_combine($givenids, $givenanswers) as $givenid => $givenanswer) {
      
//     $stmt=$con->prepare("SELECT * FROM examsanswer WHERE examid=? AND studentid=? AND questionid=? AND type = ?");
//     $stmt->execute(array($examid,$studentid,$givenid,'2'));
//     $oldan=$stmt->fetch();
    
    
//     if(!empty($oldan)){
//          $oldan=$stmt->fetch();
//          $stmt=$con->prepare("UPDATE examsanswer SET answer = ? , sent = ? WHERE id = ? ");
//          $stmt->execute(array($givenanswers,"ajax then finish",$oldan['id']));
//     }else{
//          $stmt=$con->prepare("INSERT INTO examsanswer (studentid,questionid,type,examid,answer,sent) VALUES (:zstudentid,:zquestionid,:ztype,:zexamid,:zanswer,:zsent) ");
//          $stmt->execute(array("zstudentid"=>$studentid,"zquestionid"=>$givenid,"ztype"=>"2","zexamid"=>$examid,"zanswer"=>$givenanswers,"zsent"=>"finish"));
//     }
      
//   }
// }

    $stmt=$con->prepare("UPDATE studentexam  SET result = ?  , completed = ? WHERE studentid = ? AND examid = ?  ");
    $stmt->execute(array($total  , 1, $studentid,$examid));
    
    header("Location:myweek.php?wid=$wid&msg=".urlencode("Done Solving Exam"));
    exit();
     
// if($total<$t['min_degree']){
//     $stmt=$con->prepare("SELECT * FROM studentexam WHERE studentid = ? AND examid = ?");
//     $stmt->execute(array($studentid,$week['exam2_id']));
//     $check_exam2=$stmt->rowCount();
//     if($check_exam2 < 1){
//         header("Location:myweek.php?wid=$wid&erroror=".urlencode("برجاء المذاكرة مرة أخرى وتكرار المحاولة (لديك فرصة واحدة فقط)"));
//         exit();
//     }else{
//         header("Location:myweek.php?wid=$wid&erroror=".urlencode("لم يتم الاجتياز للمرة الثانية برجاء التواصل مع الإدارة 01112618279 - 01112618275 - 01019808992 - 01061675469"));
//         exit();
//     }
// }else{
//     header("Location:myweek.php?wid=$wid&msg=".urlencode("Done Solving Exam"));
//     exit();
// }




}
?>

<!doctype html>
<html class="no-js" lang="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>E-Learning System | Answers</title>
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
    <!-- Animate CSS -->
    <link rel="stylesheet" href="css\animate.min.css">
    <!-- Date Picker CSS -->
    <link rel="stylesheet" href="css\datepicker.min.css">
    <!-- Select 2 CSS -->
    <link rel="stylesheet" href="css\select2.min.css">
    <!-- Data Table CSS -->
    <link rel="stylesheet" href="css\jquery.dataTables.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="css\style.css">
    <!-- Modernize js -->
    <script src="js\modernizr-3.6.0.min.js"></script>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-145403987-2"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        
        gtag('config', 'UA-145403987-2');
    </script>
</head>

<body>
    <p id="endtime" style="display:none;"><?php echo $endtimer;?></p>
    <!-- Preloader Start Here -->
    <div id="preloader"></div>
    <!-- Preloader End Here -->
    <div id="wrapper" class="wrapper bg-ash">
        <!-- Header Menu Area Start Here -->
<?php include_once 'navbar.php' ?>
        <!-- Header Menu Area End Here -->
        <!-- Page Area Start Here -->
        <div class="dashboard-page-one">
            <!-- Sidebar Area Start Here -->
      <?php include_once'sidebar.php' ?>
            <!-- Sidebar Area End Here -->
            <div class="dashboard-content-one">
                <!-- Breadcubs Area Start Here -->
                <div class="breadcrumbs-area">
                    <h3>Answers</h3>
                    <ul>
                        <li>
                            <a href="index3.php">Home</a>
                        </li>
                        <li>Answers</li>
                    </ul>
                </div>
                 
                        
                <!-- Breadcubs Area End Here -->
                <!-- Student Table Area Start Here -->
                <div class="card height-auto">
                    <div class="card-body">
                        <div class="heading-layout1">
                            <div class="item-title">
                                <h3>Answers</h3>
                            </div>

                        </div>
                        
                         <div class="heading-layout1 text-center">
                            <div class="item-title w-100">
                                <h3><?php echo $main_exam['name'];?></h3>
                            </div>

                        </div>
                        
                          <?php if(isset($_GET['msg'])){?>
                            <div class="alert alert-success  alert-dismissible fade show settingalert">
                                <h2><?php echo $_GET['msg'];?></h2>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        <?php } ?>
                        
                         <?php if(isset($_GET['error'])){?>
                            <div class="alert alert-danger errors alert-dismissible fade show" role="alert">
                               <h4 class="text-danger"> <i class="fas fa-exclamation-triangle"></i> <?php echo $_GET['error'];?></h4>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <?php }?>
                        
                          <div class="heading-layout1">
                            <div class="item-title">
                                <!--<h3>Answers</h3>-->
                                <div class="mytimer"><i class="far fa-clock"></i> <span id="timeer"></span> </div>
                                <input type="number" value="<?php echo  $endtimer;?>" hidden  id="time">
                            </div>
                        </div>
                        
                        
                    <form method="post" id="answers" class="form">
                        <input hidden name="id" value="<?php echo $examid;?>" type="text">
                        <input hidden name="studentid" value="<?php echo $studentid;?>" type="text">
                        <input hidden name="weekid" value="<?php echo $wid;?>" type="text">
                        
            
                                
                        <?php
                            $i=1;
                            foreach($questions as $q){
                            $type=$q['type'];
                            $questionid=$q['questionid'];
                            
                            if($type=='0') { 
                                            $stmt=$con->prepare("SELECT * FROM questions WHERE id = ?");
                                            $stmt->execute(array($questionid));
                                            $currentquestion=$stmt->fetch();
                                            $type = "Multichoice";
                                            
                                            $stmt=$con->prepare("SELECT * FROM examsanswer WHERE examid = ? AND studentid = ? AND questionid = ?");
                                            $stmt->execute(array($examid , $studentid , $questionid));
                                            $answer=$stmt->fetch();
                                            
                                            if($answer['answer'] == 1){
                                                $ans = 'A )'.$currentquestion['choice1']; 
                                            }elseif($answer['answer'] == 2){
                                                $ans = 'B )'.$currentquestion['choice2']; 
                                            }elseif($answer['answer'] == 3){
                                                $ans = 'C )'.$currentquestion['choice3']; 
                                            }elseif($answer['answer'] == 4){
                                                $ans = 'D )'.$currentquestion['choice4']; 
                                            }else{
                                                $ans = "Not Answered";
                                            }
                                
                                ?>
                                <div>
                                <input hidden type="text" class="type" value="0">
                                <input type="text" name="multichoiceids[]" hidden value="<?php echo $questionid; ?>" class="qid">
                                
                                <pre class="pp "><?php echo $i.") ".$currentquestion['question'];?> </pre>
                                
                                <?php if(!empty($currentquestion['image'])) {?>
                                <img class="qimg" src="dashboard/img/questions/<?php echo $currentquestion['image'];?>">
                                <?php }?>

                                <label class="container "><?php echo  "A) ".$currentquestion['choice1'];?>
                                <input type="radio" readonly name="radio[<?php echo $radiono; ?>]" value="1" <?php if(!empty($answer) && $answer['answer']==1) echo "checked";?>>
                                <span class="checkmark"></span>
                                </label>

                                <label class="container "><?php echo "B) ".$currentquestion['choice2'];?>
                                <input type="radio" readonly name="radio[<?php echo $radiono; ?>]" value="2" <?php if(!empty($answer) && $answer['answer']==2) echo "checked";?>>
                                <span class="checkmark" ></span>
                                </label>
                                <label class="container "><?php echo "C) ".$currentquestion['choice3'];?>
                                <input type="radio" readonly name="radio[<?php echo $radiono; ?>]" value="3" <?php if(!empty($answer) && $answer['answer']==3) echo "checked";?>>
                                <span class="checkmark"></span>
                                </label>
                                <label class="container "><?php echo "D) ".$currentquestion['choice4'];?>
                                <input type="radio" readonly name="radio[<?php echo $radiono; ?>]" value="4" <?php if(!empty($answer) && $answer['answer']==4) echo "checked";?>>
                                <span class="checkmark"></span>
                                </label>
                                
                                <label class="container" Style="display:none;"> 
                                <input type="radio" name="radio[<?php echo $radiono; ?>]" value="5" <?php if(empty($answer)) echo "checked";?>>
                                <span class="checkmark <?php if(empty($answer)){ echo "true";}?>"></span>
                                </label>
                                </div>
                            
                            <?php $radiono++;} $i++;
                             }
                            ?>
                     

                         <div class="row gutters-8">
                           <div class="col-12 form-group mg-t-8 d-flex justify-content-between">
                            <button type="submit" class="btn-fill-lg bg-primary  btn-primary" name="previous"  >Previous</button>

                            </div>
                        <div class="row gutters-8">
                            <div class="col-12 form-group mg-t-8">
                                <button type="submit" class="btn-fill-lg btn-gradient-yellow btn-hover-bluedark confirmation" name="finish" >Finish Exam</button>
                            </div>
                        </div>
                    </form>
                        


                    </div>
                    </div>
                </div>
                <!-- Student Table Area End Here -->
               
            </div>
       
        <!-- Page Area End Here -->
    </div>

    <?php include_once "footer.php";?>

    <!-- jquery-->
    <script src="js\jquery-3.3.1.min.js"></script>
    <!-- Plugins js -->
    <script src="js\plugins.js"></script>
    <!-- Popper js -->
    <script src="js\popper.min.js"></script>
    <!-- Bootstrap js -->
    <script src="js\bootstrap.min.js"></script>
    <!-- Select 2 Js -->
    <script src="js\select2.min.js"></script>
    <!-- Scroll Up Js -->
    <script src="js\jquery.scrollUp.min.js"></script>
    <!-- Date Picker Js -->
    <script src="js\datepicker.min.js"></script>
    <!-- Data Table Js -->
    <script src="js\jquery.dataTables.min.js"></script>
    <!-- Custom Js -->
    <script src="js\main.js"></script>

   





</body>

</html>