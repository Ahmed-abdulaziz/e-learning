<?php
include_once 'connect.php';
session_start();

if(!isset($_SESSION['adminname'])){
    header("location:index.php?msg=".urldecode("please login first"));
    exit();
}

if(isset($_SESSION['adminname']))
{
    if(isset($_GET['sid'])){
        $_SESSION['subject']=$_GET['sid'];
    }
}

$stmt=$con->prepare("SELECT COUNT(id) FROM students ");
$stmt->execute();
$result=$stmt->fetch();
$students=$result['COUNT(id)'];

$stmt=$con->prepare("SELECT COUNT(id) FROM students WHERE  approve = ?");
$stmt->execute(array("1"));
$result=$stmt->fetch();
$approvstudents=$result['COUNT(id)'];

$stmt=$con->prepare("SELECT COUNT(id) FROM students WHERE  approve = ?");
$stmt->execute(array("2"));
$result=$stmt->fetch();
$disapprovstudents=$result['COUNT(id)'];

$stmt=$con->prepare("SELECT COUNT(id) FROM students WHERE  approve = ?");
$stmt->execute(array("0"));
$result=$stmt->fetch();
$pendingstudents=$result['COUNT(id)'];


$stmt=$con->prepare("SELECT COUNT(id) FROM admins WHERE type=? ");
$stmt->execute(array("admin"));
$result=$stmt->fetch();
$admins=$result['COUNT(id)'];


$stmt=$con->prepare("SELECT COUNT(id) FROM admins WHERE type=? ");
$stmt->execute(array("teacher"));
$result=$stmt->fetch();
$teachers=$result['COUNT(id)'];

$stmt=$con->prepare("SELECT COUNT(id) FROM videos");
$stmt->execute();
$result=$stmt->fetch();
$videos=$result['COUNT(id)'];

$stmt=$con->prepare("SELECT COUNT(id) FROM homeworks");
$stmt->execute();
$result=$stmt->fetch();
$homeworks=$result['COUNT(id)'];

$stmt=$con->prepare("SELECT COUNT(id) FROM studenthw");
$stmt->execute();
$result=$stmt->fetch();
$studenthw=$result['COUNT(id)'];

$stmt=$con->prepare("SELECT COUNT(id) FROM exams");
$stmt->execute();
$result=$stmt->fetch();
$exams=$result['COUNT(id)'];


$stmt=$con->prepare("SELECT COUNT(id) FROM studentexam");
$stmt->execute();
$result=$stmt->fetch();
$studentexam=$result['COUNT(id)'];

$stmt=$con->prepare("SELECT COUNT(id) FROM questions");
$stmt->execute();
$result=$stmt->fetch();
$questions=$result['COUNT(id)'];

$stmt=$con->prepare("SELECT COUNT(id) FROM essay");
$stmt->execute();
$result=$stmt->fetch();
$essay=$result['COUNT(id)'];

$stmt=$con->prepare("SELECT COUNT(id) FROM classes");
$stmt->execute();
$result=$stmt->fetch();
$classes=$result['COUNT(id)'];


$stmt=$con->prepare("SELECT COUNT(id) FROM files");
$stmt->execute();
$result=$stmt->fetch();
$files=$result['COUNT(id)'];


$stmt=$con->prepare("SELECT COUNT(id) FROM subjects");
$stmt->execute();
$result=$stmt->fetch();
$subjects=$result['COUNT(id)'];

?>
<!doctype html>
<html class="no-js" lang="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>E-Learning System | All Statistics</title>
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
    <!-- Modernize js -->
    <script src="js\modernizr-3.6.0.min.js"></script>
</head>

<body>
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
                <h3>Admin Dashboard</h3>
                <ul>
                    <li>
                        <a href="index3.php">Home</a>
                    </li>
                    <li>All Statistics</li>
                </ul>
            </div>
            <!-- Breadcubs Area End Here -->
            <div class="row">
                <div class="col-12-xxxl col-12">
                    <div class="row">
                        <!-- Summery Area Start Here -->

                        <div class="col-lg-3">
                            <a href="allstudents">
                            <div class="dashboard-summery-one">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="item-icon bg-light-blue">
                                            <i class="flaticon-classmates text-blue"></i>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="item-content">
                                            <div class="item-title">All Students</div>
                                            <div class="item-number"><span class="counter" data-num="<?php echo $students;?>"><?php echo $students;?></span></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </a>
                        </div>
                        <div class="col-lg-3">
                            <div class="dashboard-summery-one">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="item-icon bg-light-green">
                                            <i class="flaticon-classmates text-green"></i>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="item-content">
                                            <div class="item-title">Approved Students</div>
                                            <div class="item-number"><span class="counter" data-num="<?php echo $approvstudents;?>"><?php echo $approvstudents;?></span></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="dashboard-summery-one">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="item-icon bg-light-red">
                                            <i class="flaticon-classmates text-red"></i>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="item-content">
                                            <div class="item-title">Disapproved Students</div>
                                            <div class="item-number"><span class="counter" data-num="<?php echo $disapprovstudents;?>"><?php echo $disapprovstudents;?></span></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">

                            <div class="dashboard-summery-one">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="item-icon bg-light-yellow">
                                            <i class="flaticon-classmates text-yellow"></i>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="item-content">
                                            <div class="item-title">Pending Students</div>
                                            <div class="item-number"><span class="counter" data-num="<?php echo $pendingstudents;?>"><?php echo $pendingstudents;?></span></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="dashboard-summery-one">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="item-icon bg-light-magenta">
                                            <i class="flaticon-multiple-users-silhouette text-magenta"></i>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="item-content">
                                            <div class="item-title">Admins</div>
                                            <div class="item-number"><span class="counter" data-num="<?php echo $admins;?>"><?php echo $admins;?></span></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="dashboard-summery-one">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="item-icon bg-light-blue">
                                            <i class="flaticon-multiple-users-silhouette text-blue"></i>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="item-content">
                                            <div class="item-title">Teachers</div>
                                            <div class="item-number"><span class="counter" data-num="<?php echo $teachers;?>"><?php echo $teachers;?></span></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="dashboard-summery-one">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="item-icon bg-light-magenta">
                                            <i class="flaticon-maths-class-materials-cross-of-a-pencil-and-a-ruler text-magenta"></i>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="item-content">
                                            <div class="item-title">Subjects</div>
                                            <div class="item-number"><span class="counter" data-num="<?php echo $subjects;?>"><?php echo $subjects;?></span></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="dashboard-summery-one">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="item-icon bg-light-magenta">
                                            <i class="flaticon-calendar text-magenta"></i>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="item-content">
                                            <div class="item-title">Classes</div>
                                            <div class="item-number"><span class="counter" data-num="<?php echo $classes;?>"><?php echo $classes;?></span></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="col-lg-3">
                            <div class="dashboard-summery-one">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="item-icon bg-light-blue myicon">
                                            <i class="far fa-file-pdf text-blue"></i>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="item-content">
                                            <div class="item-title">Material</div>
                                            <div class="item-number"><span class="counter" data-num="<?php echo $files;?>"><?php echo $files;?></span></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3">
                            <div class="dashboard-summery-one">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="item-icon bg-light-blue myicon">
                                            <i class="fas fa-video text-blue"></i>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="item-content">
                                            <div class="item-title">Videos</div>
                                            <div class="item-number"><span class="counter" data-num="<?php echo $videos;?>"><?php echo $videos;?></span></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-3">
                            <div class="dashboard-summery-one">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="item-icon bg-light-blue myicon">
                                            <i class="fas fa-list-ol text-blue"></i>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="item-content">
                                            <div class="item-title">MultiChoice Questions</div>
                                            <div class="item-number"><span class="counter" data-num="<?php echo $questions;?>"><?php echo $questions;?></span></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="dashboard-summery-one">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="item-icon bg-light-blue myicon">
                                            <i class="fas fa-stream text-blue"></i>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="item-content">
                                            <div class="item-title">Essay Questions</div>
                                            <div class="item-number"><span class="counter" data-num="<?php echo $essay;?>"><?php echo $essay;?></span></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-3">
                            <div class="dashboard-summery-one">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="item-icon bg-light-blue">
                                            <i class="flaticon-open-book text-blue"></i>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="item-content">
                                            <div class="item-title">All Homeworks</div>
                                            <div class="item-number"><span class="counter" data-num="<?php echo $homeworks;?>"><?php echo $homeworks;?></span></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="dashboard-summery-one">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="item-icon bg-light-green">
                                            <i class="flaticon-open-book text-green"></i>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="item-content">
                                            <div class="item-title">Sloved Homeworks</div>
                                            <div class="item-number"><span class="counter" data-num="<?php echo $studenthw;?>"><?php echo $studenthw;?></span></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="dashboard-summery-one">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="item-icon bg-light-blue">
                                            <i class="flaticon-shopping-list text-blue"></i>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="item-content">
                                            <div class="item-title">All Exams</div>
                                            <div class="item-number"><span class="counter" data-num="<?php echo $exams;?>"><?php echo $exams;?></span></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="dashboard-summery-one">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="item-icon bg-light-green">
                                            <i class="flaticon-shopping-list text-green"></i>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="item-content">
                                            <div class="item-title">Sloved Exams</div>
                                            <div class="item-number"><span class="counter" data-num="<?php echo $studentexam;?>"><?php echo $studentexam;?></span></div>
                                        </div>
                                    </div>
                                </div>
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
<!-- Data Table Js -->
<script src="js\jquery.dataTables.min.js"></script>
<!-- Custom Js -->
<script src="js\main.js"></script>

</body>

</html>