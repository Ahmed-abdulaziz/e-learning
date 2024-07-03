<?php 
include_once 'connect.php';
session_start();

if(!isset($_SESSION['name'])){
header("location:index.php?msg=".urldecode("please login first"));
exit();
}
$email=$_SESSION['name'];
$stmt=$con->prepare("SELECT eductionallevel FROM students WHERE email = ?");
$stmt->execute(array($email));
$result=$stmt->fetch();
$edulevel=$result['eductionallevel'];

$stmt=$con->prepare("SELECT id FROM students WHERE email = ?");
$stmt->execute(array($_SESSION['name']));
$student=$stmt->fetch();

$stmt=$con->prepare("SELECT * FROM subjects WHERE id IN (SELECT subjectid FROM studentssubjects WHERE studentid = ? AND approve = ?)");
$stmt->execute(array($student['id'],"1"));
$subjects=$stmt->fetchAll();

$stmt=$con->prepare("SELECT * FROM notifications WHERE type = ?");
$stmt->execute(array("0"));
$allnoti=$stmt->fetchAll();

$stmt=$con->prepare("SELECT * FROM std_noti WHERE studentid = ?");
$stmt->execute(array($student['id']));
$allstdnoti=$stmt->fetchAll();



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
    <script data-ad-client="ca-pub-3130127193624323" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>    
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>E-Learning System | Subjects </title>
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
                    <h3>Student Dashboard</h3>
                    <ul>
                        <li>
                            <a href="index3.php">Subjects</a>
                        </li>
                        <li>Student</li>
                    </ul>
                </div>
                <!-- Breadcubs Area End Here -->
                <div class="row">
                    <?php if(!empty($allnoti) || !empty($allstdnoti)){?>
                    <div class="col-lg-6">
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul style="list-style: unset; padding: 0 0 0 20px;">
                                <?php foreach ($allnoti as $noti){?>
                                    <li><?php echo $noti['msg'];?></li>
                                <?php }foreach ($allstdnoti as $noti){
                                    $stmt=$con->prepare("SELECT * FROM notifications WHERE id = ?");
                                    $stmt->execute(array($noti['msg_id']));
                                    $noti=$stmt->fetch();
                                ?>
                                    <li><?php echo $noti['msg'];?></li>
                                <?php }?>
                            </ul>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>
                    <?php }?>
                    <div class="col-12-xxxl col-12">
                    <div class="row">
                            <!-- Summery Area Start Here -->
                            <?php foreach($subjects as $subject){
                            
                                $stmt=$con->prepare("SELECT name FROM educationallevels WHERE id=?");
                                $stmt->execute(array($subject['edu_id']));
                                $edulevel=$stmt->fetch();

                                $stmt=$con->prepare("SELECT fullname FROM admins WHERE id=?");
                                $stmt->execute(array($subject['teacher_id']));
                                $teacher=$stmt->fetch();

                                $stmt=$con->prepare("SELECT classid FROM studentssubjects WHERE studentid = ? AND approve = ? AND subjectid = ?");
                                $stmt->execute(array($student['id'],"1",$subject['id']));
                                $class=$stmt->fetch();

                                $stmt=$con->prepare("SELECT * FROM classes WHERE id=?");
                                $stmt->execute(array($class['classid']));
                                $class=$stmt->fetch();


                            
                            ?>
                            <div class="col-lg-3">
                                <a href="statistics.php?do=session&sid=<?php echo $subject['id']; ?>&classid=<?php echo $class['id']; ?>">
                                <div class="dashboard-summery-one subject">
                                    <div class="item-content">
                                        <div class="item-number"><?php echo $class['day']." - ".$class['time'];?></div>
                                        <div class="item-number"><?php echo $subject['name'];?></div>
                                        <div class="item-number"><span><?php echo $teacher['fullname']  ?></span></div>
                                        <div class="item-title"><span><?php echo $edulevel['name']  ?></span></div>

                                    </div>
                                </div>
                                </a>
                            </div>
                            <?php }?>

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