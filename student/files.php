<?php
ob_start();
ini_set('session.cookie_lifetime', 60 * 60 * 24 );
ini_set('session.gc_maxlifetime', 60 * 60 * 24 );
session_start();
include_once 'connect.php';

if(!isset($_SESSION['name'])){
    header("location:index.php?msg=".urldecode("please login first"));
    exit();
}
if(isset($_GET['sid']) && isset($_GET['classid']) ){
    $_SESSION['subject']=$_GET['sid'];
}

$stmt=$con->prepare("SELECT * FROM files WHERE subjectid=?");
$stmt->execute(array($_SESSION['subject']));
$files=$stmt->fetchAll();

$stmt=$con->prepare("SELECT name FROM subjects WHERE id = ? ");   
$stmt->execute(array($_SESSION['subject']));
$subject=$stmt->fetch();

$stmt=$con->prepare("SELECT teacher_id FROM subjects WHERE id = ? ");
$stmt->execute(array($_SESSION['subject']));
$t=$stmt->fetch();

$stmt=$con->prepare("SELECT fullname FROM admins WHERE id = ? ");
$stmt->execute(array($t['teacher_id']));
$teacher=$stmt->fetch();

?>
    <!doctype html>
    <html class="no-js" lang="">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>E-Learning System | Material </title>
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
                    <div class="row">
                        <div class="col-lg-8">
                            <h3>Student Dashboard</h3>
                            <ul>
                                <li>
                                    <a href="files.php">Files</a>
                                </li>
                                <li>Student</li>
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
                        <div class="row">
                            <!-- Summery Area Start Here -->
                            <?php foreach($files as $file) { ?>
                                <div class="col-lg-3 pdf">
                                    <a href="dashboard/uploads/files/<?php echo $file['file'];?>" download>
                                        <img src="
                                            <?php 
                                                $exp = explode('.' , $file['file']);
                                                $imgext = strtolower(end($exp));
                                            if($imgext=="pdf") echo "img/pdf.png";
                                            elseif($imgext=="png" || $imgext=="jpeg" || $imgext=="jpg" ) echo "img/image.png";
                                            elseif($imgext=="docx" || $imgext=="doc" || $imgext=="odt" ) echo "img/word.png";
                                            elseif($imgext=="pptx" || $imgext=="ppt" || $imgext=="odp" ) echo "img/powerpoint.png";
                                            ?>
                                            
                                        ">
                                        <h4><?php echo $file['name'];?></h4>
                                    </a>
                                </div>

                            <?php } ?>
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
    <script type="text/javascript">

    </script>

    </body>

    </html>
<?php ob_end_flush();?>