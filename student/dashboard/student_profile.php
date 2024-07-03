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
}

if(isset($_SESSION['adminname'])){
$name=$_SESSION['adminname'];
$subjectid = $_SESSION['subject'];


if(isset($_POST['show'])){
$studentid = check($_POST['studentid']);

$stmt=$con->prepare("SELECT * FROM students WHERE id = ?");
$stmt->execute(array($studentid));
$student=$stmt->fetch();
$check=$stmt->rowCount();

$stmt=$con->prepare("SELECT name FROM educationallevels WHERE id = ?");
$stmt->execute(array($student['eductionallevel']));
$eductionallevel=$stmt->fetch();

if($check < 1){
    header('Location: ' . $_SERVER['HTTP_REFERER'].'&msgg= Not Found');
    exit();
}
 
}else{
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit();
}
}



?>

<!doctype html>
<html class="no-js" lang="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>E-Learning System | Student Detalis</title>
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
                    <h3>Student Detalis</h3>
                    <ul>
                        <li>
                            <a href="index3.php">Home</a>
                        </li>
                        <li>Student Detalis</li>
                    </ul>
                </div>
                <!-- Breadcubs Area End Here -->
                <!-- Teacher Table Area Start Here -->
                <div class="card height-auto">
                    <div class="card-body">
                        <div class="heading-layout1">
                            <div class="item-title">
                                <h3>All Student Detalis</h3>
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

    
                        <div class="container">
                            <div class="row">

                                <div class="col-lg-3 bg-light p-2 m-2">
                                        <h3><span class="text-primary">Name:</span> <?php echo $student['name']; ?></h3>
                                </div>
                                <div class="col-lg-3  bg-light  p-2 m-2">
                                        <h3><span class="text-primary">Email:</span>  <?php echo $student['email']; ?></h3>
                                </div>
                                <div class="col-lg-3 bg-light  p-2 m-2">
                                        <h3> <span class="text-primary">phone:</span>   <?php echo $student['phone']; ?></h3>
                                </div>
                                <div class="col-lg-3  bg-light p-2 m-2">
                                        <h3> <span class="text-primary">Parent Phone:</span>  <?php echo $student['phone1']; ?></h3>
                                </div>
                                <div class="col-lg-3  bg-light p-2 m-2">
                                        <h3><span class="text-primary"> Gander:</span> <?php echo $student['gender']; ?></h3>
                                </div>
                                <div class="col-lg-3 bg-light  p-2 m-2">
                                        <h3> <span class="text-primary"> Eductional Level:</span> <?php echo $eductionallevel['name']; ?></h3>
                                </div>

                            </div>
                          
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