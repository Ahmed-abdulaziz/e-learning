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
if(isset($_GET['sid'])) $sid=$_GET['sid'];

$_SESSION['subject']=$sid;

//show subject details

$stmt=$con->prepare("SELECT name FROM subjects WHERE id = ? ");   
$stmt->execute(array($sid));
$subject=$stmt->fetch();

$stmt=$con->prepare("SELECT teacher_id FROM subjects WHERE id = ? ");
$stmt->execute(array($sid));
$t=$stmt->fetch();

$stmt=$con->prepare("SELECT fullname FROM admins WHERE id = ? ");
$stmt->execute(array($t['teacher_id']));
$teacher=$stmt->fetch();

$email=$_SESSION['name'];
$stmt=$con->prepare("SELECT eductionallevel,id FROM students WHERE email = ?");
$stmt->execute(array($email));
$result=$stmt->fetch();
$edulevel=$result['eductionallevel'];
$stdid=$result['id'];


$stmt=$con->prepare("SELECT DISTINCT chapter FROM weeks WHERE subjectid = ?  AND chapter IS NOT NULL AND id IN (SELECT product_id FROM student_wallet WHERE studentid = ? AND type = ?)  ");
$stmt->execute(array($_SESSION['subject'],$stdid,"Week"));
$weeks=$stmt->fetchAll();



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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;600;900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="css/style.css">
    <!-- Modernize js -->
    <script src="js\modernizr-3.6.0.min.js"></script>
</head>

<body style="font-family: 'Cairo', sans-serif;">
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
                            <div class="alert alert-danger errors alert-dismissible fade show" role="alert">
                                <?php echo check($_GET['msg']);?>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <?php }?>
                            
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="dashboard-summery-one subject">
                                        <div class="item-content">
                                            <div class="item-number">Units & Chapters</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            
                        <div class="row">
                    
                            <!-- Summery Area Start Here -->
                            <?php foreach($weeks as $week){
                            
                                if(!empty($week['chapter'])){
                                    $stmt=$con->prepare("SELECT * FROM week_chapters WHERE id = ?");
                                    $stmt->execute(array($week['chapter']));
                                    $chapter=$stmt->fetch();

                            ?>
                            <div class="col-lg-4">
                                <a href="myweeks.php?cid=<?php echo $week['chapter'] ?>" class="w-100">
                                <div class="dashboard-summery-one">
                                        <?php 
                                            $stmt=$con->prepare("SELECT COUNT(chapter) AS total FROM weeks WHERE chapter = ?");
                                            $stmt->execute(array($week['chapter']));
                                            $noWeeks=$stmt->fetch();
                                        ?>
                                        <div class="item-content text-center py-3">
                                            <h4 class="item-title text-dark font-weight-bold"><?php echo $chapter['name'];?> <?php //echo $noWeeks['total']; ?></h4>
                                        </div>
                                         <!--<button class="btn btn-warning text-dark"><a href="weeks.php?cid=<?php echo $week['chapter'] ?>" class="text-dark font-weight-bold">Enroll</a></button>-->
                                    </div>
                                </a>
                            </div>
                           
                           <?php } }?>
                       
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