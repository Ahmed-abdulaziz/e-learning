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
if(isset($_SESSION['subject']))
$subject=$_SESSION['subject'];

$name=$_SESSION['adminname'];
$stmt=$con->prepare("SELECT * FROM homeworks WHERE subjectid= ?");
$stmt->execute(array($subject));
$exams=$stmt->fetchAll();


if(isset($_GET['do'])){
$do = $_GET['do'];
 if($do == 'dellete'){
    if($_GET['id']){
       $id = $_GET['id'];
    }
        $stmt = $con->prepare("DELETE  FROM homeworkquestions WHERE homeworkid = :zhomeworkid");
        $stmt->bindParam(":zhomeworkid", $id);
        $stmt->execute();
      
      $stmt = $con->prepare("DELETE  FROM homeworks WHERE id = :zid");
      $stmt->bindParam(":zid", $id);
      $stmt->execute();
      
      
      
      header("Location:homeworks.php?msg=".urlencode("Done Deleteing homework"));
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
    <title>E-Learning System | Homeworks </title>
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
                                <li>Homeworks</li>
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
                                <h3>Homeworks Data</h3>
                            </div>
                      
                        </div>
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
                             
                                <div class="col-1-xxxl col-xl-2 col-lg-3 col-12 form-group">
                                    <a href="addhomework.php" class="fw-btn-fill btn-gradient-yellow">  Add Homework</a>
                                </div>
<!--
                                <div class="col-1-xxxl col-xl-2 col-lg-3 col-12 form-group">
                                    <button type="submit" class="fw-btn-fill btn-gradient-yellow"><a href="account-settings.php">Edit </a></button>
                                </div>
-->
                            </div>
                        </form>

                       
                        <div class="table-responsive">
                            <table class="table display data-table text-nowrap">
                                <thead>
                                    <tr>
                                       
                                        
                                        <th>Name</th>
                                      
                                        <th>Controls</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                      
                                        <?php if(isset($_SESSION['adminname'])){ foreach ($exams as $exam){ ?>
                                       
                                        <td><?php echo $exam['name']  ?></td>
                                        
                                         <td>
                                               
                                            
                                        <a href="homeworks.php?do=dellete&id=<?php echo $exam["id"] ?>" class="fw-btn-fill btn-gradient-yellow">Delete</a>
                         
                                   <a href="edithomework.php?do=edit&id=<?php echo $exam["id"] ?>" class="fw-btn-fill btn-gradient-yellow">Edit</a>
                           
                                   <a href="solvehw.php?id=<?php echo $exam["id"] ?>" class="fw-btn-fill btn-gradient-yellow">Correct</a>
                        

                                        </td>
                                    </tr>
                                    <?php }}   ?>
                             
                                
    
                             
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