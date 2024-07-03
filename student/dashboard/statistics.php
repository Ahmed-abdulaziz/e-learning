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
        $stmt=$con->prepare("SELECT edu_id FROM subjects WHERE id = ? ");
        $stmt->execute(array($_SESSION['subject']));
        $edu=$stmt->fetch();
        
        $_SESSION['level']=$edu['edu_id'];
    }
}

$stmt=$con->prepare("SELECT COUNT(id) FROM weeks WHERE subjectid = ?");
$stmt->execute(array($_SESSION['subject']));
$result=$stmt->fetch();
$weeks=$result['COUNT(id)'];

$stmt=$con->prepare("SELECT COUNT(id) FROM questions WHERE subjectid = ?");
$stmt->execute(array($_SESSION['subject']));
$result=$stmt->fetch();
$questions=$result['COUNT(id)'];

$stmt=$con->prepare("SELECT COUNT(id) FROM essay WHERE subjectid = ?");
$stmt->execute(array($_SESSION['subject']));
$result=$stmt->fetch();
$questions+=$result['COUNT(id)'];

$stmt=$con->prepare("SELECT COUNT(id) FROM exams WHERE subjectid = ?");
$stmt->execute(array($_SESSION['subject']));
$result=$stmt->fetch();
$exams=$result['COUNT(id)'];

$stmt=$con->prepare("SELECT COUNT(id) FROM homeworks WHERE  subjectid = ? ");
$stmt->execute(array($_SESSION['subject']));
$result=$stmt->fetch();
$homeworks=$result['COUNT(id)'];

$stmt=$con->prepare("SELECT id FROM videos WHERE subjectid = ?");
$stmt->execute(array($_SESSION['subject']));
$results=$stmt->fetchAll();
$videosno=COUNT($results);

$stmt=$con->prepare("SELECT id FROM files WHERE subjectid = ?");
$stmt->execute(array($_SESSION['subject']));
$results=$stmt->fetchAll();
$files=COUNT($results);

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

  <?php include('header/head.php'); ?>

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
                    <div class="row">
                        <div class="col-lg-8">
                            <h3>Admin Dashboard</h3>
                            <ul>
                                <li>
                                    <a href="statistics.php">Statistics</a>
                                </li>
                                <li>Statistics</li>
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
                <div class="row">
                    <div class="col-12-xxxl col-12">
                        <div class="row">
                            <!-- Summery Area Start Here -->
                            <div class="col-lg-6">
                                <a href="weeks.php">
                                <div class="dashboard-summery-one">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="item-icon bg-light-green">
                                                <i class="far fa-calendar text-green"></i>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="item-content">
                                                <div class="item-title">Weeks</div>
                                                <div class="item-number"><span class="counter" data-num="<?php echo $weeks;?>"><?php echo $weeks;?></span></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                </a>
                            </div>
                            <div class="col-lg-6">
                                <a href="multichoice.php">
                                <div class="dashboard-summery-one">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="item-icon bg-light-red">
                                                <i class="flaticon-script text-red"></i>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="item-content">
                                                <div class="item-title">Questions</div>
                                                <div class="item-number"><span class="counter" data-num="<?php echo $questions;?>"><?php echo $questions;?></span></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                </a>
                            </div>

                            <div class="col-lg-6">
                                <a href="homeworks.php">
                                <div class="dashboard-summery-one">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="item-icon bg-light-magenta">
                                                <i class="flaticon-maths-class-materials-cross-of-a-pencil-and-a-ruler text-magenta"></i>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="item-content">
                                                <div class="item-title">Homeworks</div>
                                                <div class="item-number"><span class="counter" data-num="<?php echo $homeworks;?>"><?php echo $homeworks;?></span></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                </a>
                            </div>

                           <div class="col-lg-6">
                               <a href="exams.php">
                                <div class="dashboard-summery-one">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="item-icon bg-light-blue">
                                                <i class="flaticon-shopping-list text-blue"></i>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="item-content">
                                                <div class="item-title">Quizes</div>
                                                <div class="item-number"><span class="counter" data-num="<?php echo $exams;?>"><?php echo $exams;?></span></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                               </a>
                            </div>

                            <div class="col-lg-6">
                                <a href="videos.php">
                                <div class="dashboard-summery-one">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="item-icon bg-light-blue videocounter">
                                                <i class="fa fa-play-circle text-blue" ></i>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="item-content">
                                                <div class="item-title">Videos</div>
                                                <div class="item-number"><span class="counter" data-num="<?php echo $videosno;?>"><?php echo $videosno;?></span></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                </a>
                            </div>
                                    <div class="col-lg-6">
                                        <a href="files.php">
                                <div class="dashboard-summery-one">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="item-icon bg-light-blue">
                                                <i class="flaticon-shopping-list text-blue"></i>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="item-content">
                                                <div class="item-title">Material</div>
                                                <div class="item-number"><span class="counter" data-num="<?php echo $files;?>"><?php echo $files;?></span></div>
                                            </div>
                                        </div>
                                    </div>
                                </div></a>
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