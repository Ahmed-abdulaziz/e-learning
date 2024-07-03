<?php 

include_once 'connect.php';
ob_start();
 session_start();
if(!isset($_SESSION['adminname'])){
    header("Location:index.php?msg=".urlencode("Please Login First"));
    exit();
}
if(isset($_SESSION['adminname']))
{
if(isset($_GET['id']))
$id=$_GET['id'];
$stmt=$con->prepare("SELECT * FROM studentexam WHERE examid=? ORDER BY result ");
$stmt->execute(array($id));
$solvedexams=$stmt->fetchAll();
}


if(isset($_GET['do'])){
    if($_GET['do']=="correctall"){
        if(isset($_GET['id'])){
            $id=$_GET['id'];
            $stmt=$con->prepare("SELECT * FROM examquestions WHERE type = ? AND examid = ?");
            $stmt->execute(array("1",$id));
            if($stmt->rowCount()>0){
                header("Location:solvedexam.php?id=".$id."&msg=".urlencode("Exam Contain essay questions, can't be solved"));
                exit(); 
            }else{
                $stmt=$con->prepare("SELECT * FROM studentexam WHERE examid = ?");
                $stmt->execute(array($id));
                $stds=$stmt->fetchAll();
                
                foreach($stds as $std){
                    $total=0;
                    
                    $stmt=$con->prepare("SELECT * FROM examquestions WHERE examid = ? ");
                    $stmt->execute(array($id));
                    $qs=$stmt->fetchAll();
                    
                    foreach($qs as $q){
                        if($q['type'] == 0){
                            
                                $stmt=$con->prepare("SELECT * FROM examsanswer WHERE examid = ?  AND studentid = ? AND questionid = ? AND type = ?");
                                $stmt->execute(array($id,$std['studentid'],$q['questionid'],"0"));
                                $ans=$stmt->fetch();
                    
                                $stmt=$con->prepare("SELECT * FROM questions WHERE id = ? ");
                                $stmt->execute(array($q['questionid']));
                                $modelan=$stmt->fetch();
                                
                                if($ans['answer']==$modelan['answer']){
                                    $total+=$q['degree'];
                                    $stmt=$con->prepare("UPDATE examsanswer SET degree = ? WHERE examid = ? AND questionid = ? AND studentid= ? AND type = ?");
                                    $stmt->execute(array($q['degree'],$id,$q['questionid'], $std['studentid'],"0"));
                                }else{
                                    $stmt=$con->prepare("UPDATE examsanswer SET degree = ? WHERE examid = ? AND questionid = ? AND studentid= ? AND type = ?");
                                    $stmt->execute(array("0",$id,$q['questionid'],$std['studentid'],"0"));
                                }
                        
                        }elseif($q['type'] == 2){     // Given  Question
                            
                                $stmt=$con->prepare("SELECT * FROM questions WHERE givens = ? ");
                                $stmt->execute(array($q['questionid']));
                                $givens=$stmt->fetchAll();
                                
                                foreach($givens as $given){
                                    
                                        $stmt=$con->prepare("SELECT * FROM examsanswer WHERE examid = ? AND studentid = ? AND questionid = ? AND type = ?");
                                        $stmt->execute(array($id,$std['studentid'],$given['id'],2));
                                        $ans=$stmt->fetch();
                                            
                                            
                                        if($ans['answer']==$given['answer']){
                                            $total+=$q['degree'];
                                            $stmt=$con->prepare("UPDATE examsanswer SET degree = ? WHERE questionid = ? AND studentid= ? AND examid = ? AND type = ?");
                                            $stmt->execute(array($q['degree'],$given['id'], $std['studentid'],$id,2));
                                        }else{
                                             $stmt=$con->prepare("UPDATE examsanswer SET degree = ? WHERE questionid = ? AND studentid= ? AND examid = ? AND type = ?");
                                             $stmt->execute(array("0",$given['id'],$std['studentid'],$id,2));
                                        }

                                }
                               
                                
                        }
                     
                        
                    }
                    
                  $stmt=$con->prepare("UPDATE studentexam SET result = ? WHERE examid = ? AND studentid = ?");
                  $stmt->execute(array($total,$id,$std['studentid']));
                  
                  $stmt=$con->prepare("SELECT name,degree FROM exams WHERE id =? ");
                  $stmt->execute(array($id));
                  $exam=$stmt->fetch();
                
                  $stmt=$con->prepare("SELECT * FROM students WHERE id =? ");
                  $stmt->execute(array($std['studentid']));
                  $student=$stmt->fetch();
                
                  $stmt=$con->prepare("SELECT name FROM subjects WHERE id = (SELECT s_id FROM classes WHERE id = (SELECT classid FROM exams WHERE id = ?)) ");
                  $stmt->execute(array($id));
                  $subject=$stmt->fetch();
                  
                  
                //   $msg = " نتيجة ".$student['name']."%0a"." في مادة ".$subject['name']."%0a"." في امتحان ".$exam['name']."%0a".$total." / ".$exam['degree'];

                //   $ch = curl_init();
                //   $url= "https://smssmartegypt.com/sms/api/?username=msuahmed777@gmail.com&password=Msu12345&sendername=MrAhmedSaid&mobiles=2".$student['phone1']."&message=".urlencode($msg);
                //   curl_setopt($ch, CURLOPT_URL,$url);
                //   curl_setopt($ch, CURLOPT_POST, 1);
                //   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                //   $server_output = curl_exec($ch);
                //   curl_close ($ch);
                
            
                $phone =$student['phone2'];
                $stname =$student['name'];
                
                $exname =$exam['name'];
                
                
                $msg = "EZY-STUDIES- مرحبا: تم تصحيح امتحان $exname وحصل $stname على درجه ( $total )";
                
                $ch = curl_init();
                $url= "https://smssmartegypt.com/sms/api/?username=easyphysics&password=easyphysics@1166&sendername=Academy&mobiles=2".$phone."&message=".urlencode($msg);
                curl_setopt($ch, CURLOPT_URL,$url);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $server_output = curl_exec($ch);
                curl_close ($ch);


                  
                }
                header("Location:solvedexam.php?id=".$id."&msg=".urlencode("Exam corrected successfully"));
                exit(); 
                
            }
                
            
        }
    }elseif($_GET['do']=="clear"){
        
    $sid=$_GET['studentid'];
    $exid=$_GET['exid'];
    
      $stmt = $con->prepare("DELETE  FROM studentexam WHERE studentid = ? AND examid = ?");
      $stmt->execute(array($sid,$exid)); 
      
      $stmt = $con->prepare("DELETE  FROM examsanswer WHERE studentid = ? AND examid = ?");
      $stmt->execute(array($sid,$exid)); 
      
            header("Location:solvedexam.php?id=".$exid."&msg=".urlencode("Exam Clear Student"));
            exit(); 

    }
}



$stmt=$con->prepare("SELECT name FROM subjects WHERE id = ? ");   
$stmt->execute(array($_SESSION['subject']));
$subject=$stmt->fetch();

$stmt=$con->prepare("SELECT teacher_id FROM subjects WHERE id = ? ");
$stmt->execute(array($_SESSION['subject']));
$t=$stmt->fetch();

$stmt=$con->prepare("SELECT fullname FROM admins WHERE id = ? ");
$stmt->execute(array($t['teacher_id']));
$teacher=$stmt->fetch();

$stmt=$con->prepare("SELECT edu_id FROM subjects WHERE id = ? ");
$stmt->execute(array($_SESSION['subject']));
$edu=$stmt->fetch();

$stmt=$con->prepare("SELECT name FROM educationallevels WHERE id = ? ");
$stmt->execute(array($edu['edu_id']));
$edulevel=$stmt->fetch();
?>
<!doctype html>
<html class="no-js" lang="">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>E-Learning System | Solved Exams </title>
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
    <!-- Custom CSS -->
    <link rel="stylesheet" href="style.css">
    <!-- Modernize js -->
    <script src="js\modernizr-3.6.0.min.js"></script>
</head>

<body>
    <!-- Preloader Start Here -->
    <div id="preloader"></div>
    <!-- Preloader End Here -->
    <div id="wrapper" class="wrapper bg-ash">
       <!-- Header Menu Area Start Here -->
        <?php include_once 'navbar.php' ?>

        <!-- Header Menu Area End Here -->
        <!-- Page Area Start Here -->
        <div class="dashboard-page-one">
          

            <?php  include_once 'sidebar.php'; ?>
            <!-- Sidebar Area End Here -->
            <div class="dashboard-content-one">
                <!-- Breadcubs Area Start Here -->
     <div class="breadcrumbs-area">
                    <div class="row">
                        <div class="col-lg-8">
                            <h3>Admin Dashboard</h3>
                            <ul>
                                <li>
                                    <a href="statistics.php">Statistics</a>
                                </li>
                                <li>Solved Exam</li>
                            </ul>
                        </div>
                        <div class="col-lg-4">
                            <div class="dashboard-summery-one subject">
                                <div class="item-content">
                                    <div class="item-number"><?php echo $subject['name']; echo " / ". $teacher['fullname'];  ?></div>
                                     <div class="item-number"><?php echo $edulevel['name'];  ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Breadcubs Area End Here -->
                <!-- Teacher Table Area Start Here -->
                <div class="card height-auto">
                    <div class="card-body">
                        <div class="heading-layout1">
                            <div class="item-title">
                                <h3>Solved Exams Data</h3>
                            </div>
                        </div>
                        <a href="solvedexam.php?do=correctall&id=<?php echo $id;?>" class="fw-btn-fill btn-gradient-yellow">Correct all</a>
<!-- msg of edit-->
                        
                          <?php if(isset($_GET['mssg'])){?>
                                        <div class="alert alert-success  alert-dismissible fade show settingalert">
                                            <?php echo $_GET['mssg'];?>
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div> <?php } ?>
<!--mssg for delete-->
                              <?php if(isset($_GET['msg'])){?>
                                        <div class="alert alert-success  alert-dismissible fade show settingalert">
                                            <?php echo $_GET['msg'];?>
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                       <?php } ?>

                         <?php if(isset($_GET['msgg'])){?>
                                        <div class="alert alert-success  alert-dismissible fade show settingalert">
                                            <?php echo $_GET['msgg'];?>
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                       <?php } ?>
                                     
                          
                        <form class="mg-b-20">
                            <div class="row gutters-8">

                            </div>
                        </form>

                       
                        <div class="table-responsive">
                            <table class="table display data-table text-nowrap" id='myTable'>
                                <thead>
                                    <tr>
                                        <th>Studnet Name</th>
                                        <th>Degree</th>
                                        <?php if($_SESSION['type']=="assistant"){ ?>
                                        <th>Parent Phone</th>
                                        <?php }?>
                                        <th>Controls</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                      
                                        <?php foreach ($solvedexams as $e){ 
                                            
                                            $stmt=$con->prepare("SELECT id,name,phone2 FROM students WHERE id = ?");
                                            $stmt->execute(array($e['studentid']));
                                            $result=$stmt->fetch();
                                            $studentname=$result['name'];
                                            $studentid=$result['id'];
                                            $parentphone=$result['phone2'];
                                            
                                            $stmt=$con->prepare("SELECT result FROM studentexam WHERE studentid = ? AND examid = ?");
                                            $stmt->execute(array($studentid,$id));
                                            $r=$stmt->fetch();
                                            $result=$r['result'];
                                            
                                            $stmt=$con->prepare("SELECT * FROM examsanswer WHERE examid = ?  AND studentid = ? ");
                                            $stmt->execute(array($id,$studentid));
                                            $degree=$stmt->rowCount();
                                            
                                            ?>
                                        <td><?php echo $studentname  ?></td>
                                        <td><?php echo $result ." / " . $degree; ?></td>
                                        
                                        <?php if($_SESSION['type']=="assistant"){ ?>
                                        <td><?php echo $parentphone?></td>
                                        <?php }?>
                                         <td>
                                             <a href="correctexam.php?studentid=<?php echo $e['studentid']; ?>&exid=<?php echo $e['examid']; ?>" class="fw-btn-fill btn-gradient-yellow">Correct</a>
                                             <a href="?do=clear&studentid=<?php echo $e['studentid']; ?>&exid=<?php echo $e['examid']; ?>" class="fw-btn-fill btn-gradient-yellow clear">Clear</a>
                                        </td>

                                    </tr>
                                    <?php }   ?>
                                </tbody>
                            </table>
                           
                        </div>
                    </div>
                </div>
                
                <!-- Breadcubs Area End Here -->
               
            </div>
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
    <!-- Custom Js -->
    <script src="js\main.js"></script>
     <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready( function () {
            $('#myTable').dataTable();
        
        });
    </script>
     <script>
         
              $(".clear").click(function(e){
                if(!confirm("Are you sure you want to Clear Exam From This Student ?")){e.preventDefault();}
            });
            
    </script>

</body>

</html>