<?php 

include_once 'connect.php';
ob_start();
 session_start();
 if(!isset($_SESSION['adminname'])){
    header("Location:index.php?msg=".urlencode("Please Login First"));
    exit();
}
function check($var){
    return htmlentities(trim(strip_tags(stripcslashes($var))));
  }
$name=$_SESSION['adminname'];
$stmt=$con->prepare("SELECT type FROM admins WHERE name=?");
$stmt->execute(array($name));
$result=$stmt->fetch();
$type=$result['type'];
if($type!="super admin" && $type!="admin"){
header("Location:index3.php?msg=You Not Have A Permission");
exit();
}

if(isset($_SESSION['adminname'])){
$name=$_SESSION['adminname'];
$subjectid = $_SESSION['subject'];

if(isset($_GET['do'])){
$do = check($_GET['do']);

 if($do == 'pass'){
    if($_GET['sid']){
        $sid = check($_GET['sid']);
        $wid = check($_GET['wid']);
        $exam2 = check($_GET['ex2']);
    }
    
           $stmt = $con->prepare("INSERT INTO studentexam (studentid,examid ,result,status) VALUES (:studentid,:examid,:result,:status)");
            $stmt->execute(array(
            'studentid' => $sid,
            'examid' =>$exam2,
            'result'=>0,
            'status' => 1,
         ));
   
      
        header("Location:students_pass_week.php?wid=$wid&msg=".urlencode("Done Pass Student"));
        exit();
  }}
    
    
if(isset($_GET['wid'])){
    
    $wid = $_GET['wid'];

        
        $stmt=$con->prepare("SELECT edu_id FROM subjects WHERE id = ? ");
        $stmt->execute(array($_SESSION['subject']));
        $level=$stmt->fetch();
        
        $stmt=$con->prepare("SELECT exam1_id , exam2_id FROM weeks WHERE id = ? ");
        $stmt->execute(array($wid));
        $exams_week=$stmt->fetch();
        
        
        $stmt=$con->prepare("SELECT * FROM codes WHERE level = ? AND weekid = ? AND type = ? AND studentid IS NOT NULL AND studentid  NOT IN (SELECT studentid FROM studentexam WHERE examid = ? OR examid = ?) ");
        $stmt->execute(array($level['edu_id'],$wid , 1 ,$exams_week['exam1_id'] , $exams_week['exam2_id']));
        $students=$stmt->fetchAll();
        
        $exam2  = $exams_week['exam2_id'];

}else{
    header("Location:index3.php?msg=Please Select Week");
    exit();
}
// $stmt=$con->prepare("SELECT min_degree FROM exams WHERE id = ? ");
// $stmt->execute(array($exam2));
// $exam=$stmt->fetch();

// $stmt=$con->prepare("SELECT * FROM studentexam WHERE examid = ? AND result < ? AND status IS NULL ");
// $stmt->execute(array($exam2,$exam['min_degree']));
// $students_exam=$stmt->fetchAll();


}



?>

<!doctype html>
<html class="no-js" lang="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>E-Learning System |  Students Fail</title>
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
        <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@600&display=swap" rel="stylesheet">
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
                    <h3>Students Fail</h3>
                    <ul>
                        <li>
                            <a href="index3.php">Students Fail</a>
                        </li>
                        <li>All Students Fail</li>
                    </ul>
                </div>
                <!-- Breadcubs Area End Here -->
                <!-- Teacher Table Area Start Here -->
                <div class="card height-auto">
                    <div class="card-body">
                        <div class="heading-layout1">
                            <div class="item-title">
                                <h3>All Students Fail Data</h3>
                            </div>
                        </div>
                    
                        <!-- msg of delete--> 
                        <?php if(isset($_GET['msg'])){?>
                            <div class="alert alert-success  alert-dismissible fade show settingalert">
                                <?php echo check($_GET['msg']);?>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div> 
                        <?php } ?>

                        <!--mssg for edit-->
                        <?php if(isset($_GET['mssg'])){?>
                            <div class="alert alert-success  alert-dismissible fade show settingalert">
                                <?php echo check($_GET['mssg']);?>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        <?php } ?>

                        <!--msgg for adding-->
                        <?php if(isset($_GET['msgg'])){?>
                        <div class="alert alert-success  alert-dismissible fade show settingalert">
                            <?php echo check($_GET['msgg']);?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <?php }?>

    
                      

                       
                        <div class="table-responsive">
                            <table class="table display data-table text-nowrap">
                                <thead>
                                    <tr>
                                        <th>Student Name</th>
                                        <th>Student Phone</th>
                                        <th>Student Parent Phone</th>
                                        <th>Controls</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                      <?php foreach($students as $student){ 
                                      
                                            $stmt=$con->prepare("SELECT name,phone,phone1 FROM students WHERE id = ? ");
                                            $stmt->execute(array($student['studentid']));
                                            $details=$stmt->fetch();

                                        
                                      ?>
                                        
                                        <td><?php echo $details['name']  ?></td>
                                        <td><?php echo $details['phone']  ?></td>
                                        <td><?php echo $details['phone1']  ?></td>

                                        <td>
                                      
                                            <a class="fw-btn-fill btn-gradient-yellow " href="?do=pass&sid=<?php echo $student["studentid"] ?>&wid=<?php echo $wid.'&ex2='.$exam2;?>">Pass</a>
                                        </td>
                                       
                                        
                                         
                                    </tr>            
                                
                                     <?php } ?>
                             
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