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
$stmt=$con->prepare("SELECT * FROM studenthw WHERE homeworkid=? ORDER BY result ");
$stmt->execute(array($id));
$solvedhws=$stmt->fetchAll();
}




if(isset($_GET['do'])){
    if($_GET['do']=="correctall"){
        if(isset($_GET['id'])){
            $id=$_GET['id'];
            
            $stmt=$con->prepare("SELECT * FROM homeworks WHERE id=? ");
            $stmt->execute(array($id));
            $myhw=$stmt->fetch();
            
            $stmt=$con->prepare("SELECT * FROM homeworks WHERE name=? ");
            $stmt->execute(array($myhw['name']));
            $myhws=$stmt->fetchAll();
            
            foreach($myhws as $myhw){
            
            $stmt=$con->prepare("SELECT * FROM homeworkquestions WHERE type = ? AND homeworkid = ?");
            $stmt->execute(array("1",$myhw['id']));
            if($stmt->rowCount()>0){
                header("Location:solvehw.php?id=".$id."&msg=".urlencode("Homework Contain essay questions, can't be solved"));
                exit(); 
            }else{
                $stmt=$con->prepare("SELECT * FROM studenthw WHERE homeworkid = ?");
                $stmt->execute(array($myhw['id']));
                $stds=$stmt->fetchAll();
                
                foreach($stds as $std){
                    $total=0;
                    
                    $stmt=$con->prepare("SELECT * FROM homeworkquestions WHERE homeworkid = ?");
                    $stmt->execute(array($myhw['id']));
                    $qs=$stmt->fetchAll();
              
                    foreach($qs as $q){
                        
                        // Multichoice  Question
                        if($q['type'] == 0){
                                $stmt=$con->prepare("SELECT * FROM answers WHERE homeworkid = ? AND studentid = ? AND questionid = ? AND type = ?");
                                $stmt->execute(array($myhw['id'],$std['studentid'],$q['questionid'],0));
                                $ans=$stmt->fetch();
                            
                                $stmt=$con->prepare("SELECT * FROM questions WHERE id = ? ");
                                $stmt->execute(array($ans['questionid']));
                                $modelan=$stmt->fetch();
                                
                                if($ans['answer']==$modelan['answer']){
                                    $total+=$q['degree'];
                                    $stmt=$con->prepare("UPDATE answers SET degree = ? WHERE questionid = ? AND studentid= ? AND homeworkid = ? AND type = ?");
                                    $stmt->execute(array($q['degree'],$q['questionid'], $std['studentid'],$myhw['id'],"0"));
                                }else{
                                    $stmt=$con->prepare("UPDATE answers SET degree = ? WHERE questionid = ? AND studentid= ? AND homeworkid = ? AND type = ?");
                                    $stmt->execute(array("0",$q['questionid'],$std['studentid'],$myhw['id'],"0"));
                                }
                        }elseif($q['type'] == 2){     // Given  Question
                            
                                $stmt=$con->prepare("SELECT * FROM questions WHERE givens = ? ");
                                $stmt->execute(array($q['questionid']));
                                $givens=$stmt->fetchAll();
                                
                                foreach($givens as $given){
                                    
                                        $stmt=$con->prepare("SELECT * FROM answers WHERE homeworkid = ? AND studentid = ? AND questionid = ? AND type = ?");
                                        $stmt->execute(array($myhw['id'],$std['studentid'],$given['id'],2));
                                        $ans=$stmt->fetch();
                                            
                                            
                                        if($ans['answer']==$given['answer']){
                                            $total+=$q['degree'];
                                            $stmt=$con->prepare("UPDATE answers SET degree = ? WHERE questionid = ? AND studentid= ? AND homeworkid = ? AND type = ?");
                                            $stmt->execute(array($q['degree'],$given['id'], $std['studentid'],$myhw['id'],2));
                                        }else{
                                             $stmt=$con->prepare("UPDATE answers SET degree = ? WHERE questionid = ? AND studentid= ? AND homeworkid = ? AND type = ?");
                                             $stmt->execute(array("0",$given['id'],$std['studentid'],$myhw['id'],2));
                                        }

                                }
                               
                                
                        }
                      
                        
                    }
                    
                              $stmt=$con->prepare("UPDATE studenthw SET result = ? WHERE homeworkid = ? AND studentid = ?");
                              $stmt->execute(array($total,$myhw['id'],$std['studentid']));
                  
                  
                $stmt=$con->prepare("SELECT * FROM students WHERE id =? ");
                $stmt->execute(array($std['studentid']));
                $student=$stmt->fetch();
                
                $stmt=$con->prepare("SELECT name FROM subjects WHERE id = (SELECT s_id FROM classes WHERE id = (SELECT classid FROM homeworks WHERE id = ?)) ");
                $stmt->execute(array($myhw['id']));
                $subject=$stmt->fetch();
                  
                  
                //   $msg = " نتيجة ".$student['name']."%0a"." في مادة ".$subject['name']."%0a"." في امتحان ".$exam['name']."%0a".$total." / ".$exam['degree'];
                
                //   $ch = curl_init();
                //   $url= "https://smssmartegypt.com/sms/api/?username=msuahmed777@gmail.com&password=Msu12345&sendername=MrAhmedSaid&mobiles=2".$student['phone1']."&message=".urlencode($msg);
                //   curl_setopt($ch, CURLOPT_URL,$url);
                //   curl_setopt($ch, CURLOPT_POST, 1);
                //   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                //   $server_output = curl_exec($ch);
                //   curl_close ($ch);

                }
            }
            }
            
            
                header("Location:solvehw.php?id=".$id."&msg=".urlencode("Homework corrected successfully"));
                exit(); 
                
        }
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
    <title>E-Learning System | Solved Homeworks </title>
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
                                <li>Solved Homework</li>
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
                                <h3>Solved Homeworks Data</h3>
                            </div>
                      
                        </div>
                        <a href="solvehw.php?do=correctall&id=<?php echo $id;?>" class="fw-btn-fill btn-gradient-yellow">Correct all</a>

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
                            <table class="table display data-table text-nowrap">
                                <thead>
                                    <tr>
                                        <th>Studnet Name</th>
                                        <th>Degree</th>
                                        <th>Controls</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                      
                                        <?php foreach ($solvedhws as $h){ 
                                            
                                            $stmt=$con->prepare("SELECT name FROM students WHERE id = ?");
                                            $stmt->execute(array($h['studentid']));
                                            $result=$stmt->fetch();
                                            $studentname=$result['name'];
                                            $studentid=$h['studentid'];
                                          
                                            
                                            
                                            $stmt=$con->prepare("SELECT result FROM studenthw WHERE studentid = ? AND homeworkid = ?");
                                            $stmt->execute(array($studentid,$id));
                                            $r=$stmt->fetch();
                                            $result=$r['result'];

                                            $stmt=$con->prepare("SELECT degree FROM homeworks WHERE id = ?");
                                            $stmt->execute(array($id));
                                            $r=$stmt->fetch();
                                            $degree=$r['degree'];
                                            
                                            ?>
                                        <td><?php echo $studentname;  ?></td>
                                        <td><?php if(!empty($result)){ echo $result ." / " . $degree;} else{ echo "Not Corrected Yet";} ?></td>
                                        <td><a href="correct.php?studentid=<?php echo $h['studentid']; ?>&hwid=<?php echo $h['homeworkid']; ?>" class="fw-btn-fill btn-gradient-yellow">Correct</a></td>
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

</body>

</html>