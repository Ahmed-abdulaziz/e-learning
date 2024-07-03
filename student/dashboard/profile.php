<?php    
ob_start();
ini_set('session.cookie_lifetime', 60 * 60 * 24 );
ini_set('session.gc_maxlifetime', 60 * 60 * 24 );
include_once 'connect.php';
ob_start();
 session_start();
 if(!isset($_SESSION['adminname'])){
    header("Location:index.php?msg=".urlencode("Please Login First"));
    exit();
}

 
if(isset($_SESSION['adminname']))
{
    
$name=$_SESSION['adminname'];

if(isset($_GET['id'])){
    $student_id = $_GET['id'];
}else{
       header("Location:allstudents.php?msg=".urlencode("Please Choice Student"));
         exit();
}


$stmt=$con->prepare("SELECT name,phone FROM students WHERE id = ? ");
$stmt->execute(array($student_id));
$student=$stmt->fetch();

}



?>

<!doctype html>
<html class="no-js" lang="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>E-Learning System | Profile</title>
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
                                <li>Profile</li>
                            </ul>
                        </div>
                  
                    </div>
                </div>
                <!-- Breadcubs Area End Here -->
                <!-- Teacher Table Area Start Here -->
                <div class="card height-auto">
                    <div class="card-body">
                        <!--<div class="heading-layout1">-->
                        <!--    <div class="item-title">-->
                        <!--        <h3>All Places Data</h3>-->
                        <!--    </div>-->
                        <!--</div>-->
                    
                        <!-- msg of delete--> 
                        <?php if(isset($_GET['msg'])){?>
                            <div class="alert alert-success  alert-dismissible fade show settingalert">
                                <?php echo $_GET['msg'];?>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div> 
                        <?php } ?>

                        <!--mssg for edit-->
                        <?php if(isset($_GET['mssg'])){?>
                            <div class="alert alert-success  alert-dismissible fade show settingalert">
                                <?php echo $_GET['mssg'];?>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        <?php } ?>

                        <!--msgg for adding-->
                        <?php if(isset($_GET['msgg'])){?>
                        <div class="alert alert-success  alert-dismissible fade show settingalert">
                            <?php echo $_GET['msgg'];?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <?php }?>

    
                                            
                        <form class="mg-b-20">
                            <div class="row gutters-8">
                             
                                <div class="col-12-xxxl col-xl-2 col-lg-3 col-12 form-group">
                                    <h5><?php echo "Name: ".$student['name']."<br>";?></h5>
                                     <h5><?php echo "Phone: ".$student['phone'];?></h5>
                                </div>

                            </div>
                        </form>

                       
                        <div class="table-responsive">
                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                  <li class="nav-item" role="presentation">
                                    <a class="nav-link active" id="exam-tab" data-toggle="tab" href="#exam" role="tab" aria-controls="exam" aria-selected="true">Exams</a>
                                  </li>
                                  <li class="nav-item" role="presentation">
                                    <a class="nav-link" id="monthexam-tab" data-toggle="tab" href="#monthexam" role="tab" aria-controls="monthexam" aria-selected="false">Monthly Exams</a>
                                  </li>
                                  <li class="nav-item" role="presentation">
                                    <a class="nav-link" id="homework-tab" data-toggle="tab" href="#homework" role="tab" aria-controls="homework" aria-selected="false">Homeworks</a>
                                  </li>
                                  
                                </ul>
                                <div class="tab-content" id="myTabContent">
                                  <div class="tab-pane fade show active" id="exam" role="tabpanel" aria-labelledby="exam-tab">
                                        
                                        <table class="table">
                                              <thead>
                                                <tr>
                                                  <th scope="col">Exam</th>
                                                  <th scope="col">Degree</th>
                                                 
                                                </tr>
                                              </thead>
                                              <tbody>
                                                  
                                                  <?php 
                                                  
                                                    $stmt=$con->prepare("SELECT * FROM studentexam WHERE studentid = ?");
                                                    $stmt->execute(array($student_id));
                                                    $exams=$stmt->fetchAll();
                                                    $checkexam = $stmt->rowCount();
                                                    
                                                    foreach($exams as $exam){
                                                    
                                                    $stmt=$con->prepare("SELECT * FROM exams WHERE id = ?");
                                                    $stmt->execute(array($exam['examid']));
                                                    $ex=$stmt->fetch();
                                                        
                                                  ?>
                                                <tr>
                                                    
                                                    
                                                  <th scope="row"><?php echo $ex['name']?></th>
                                                  <td><?php echo $exam['result']." / ". $ex['degree'];?></td>
                                                  
                                                </tr>
                                              <?php
                                              }?>
                                              </tbody>
                                            </table>
                                                                                  
                                  </div>
                                  <div class="tab-pane fade" id="monthexam" role="tabpanel" aria-labelledby="monthexam-tab">
                                        <table class="table">
                                              <thead>
                                                <tr>
                                                  <th scope="col">Quiz</th>
                                                  <th scope="col">Degree</th>
                                                 
                                                </tr>
                                              </thead>
                                              <tbody>
                                                  
                                                  <?php 
                                                  
                                                        $stmt=$con->prepare("SELECT * FROM quizstudent WHERE studentid = ?");
                                                        $stmt->execute(array($student_id));
                                                        $quizes=$stmt->fetchAll();
                                                        $checkquiz = $stmt->rowCount();
                                                        
                                                        foreach($quizes as $quize){
                                                        
                                                        $stmt=$con->prepare("SELECT * FROM quizes WHERE id = ?");
                                                        $stmt->execute(array($quize['quizid']));
                                                        $qui=$stmt->fetch();

                                                  ?>
                                                <tr>
                                                    
                                                    
                                                  <th scope="row"><?php echo $qui['name']?></th>
                                                  <td><?php echo $quize['degree'] ." / ".$qui['final_degree'];?></td>
                                                  
                                                </tr>
                                              <?php
                                                }?>
                                              </tbody>
                                            </table>
                                            
                                  </div>
                                  <div class="tab-pane fade" id="homework" role="tabpanel" aria-labelledby="homework-tab">
                                      
                                       <table class="table">
                                              <thead>
                                                <tr>
                                                  <th scope="col">Homework</th>
                                                  <th scope="col">Degree</th>
                                                 
                                                </tr>
                                              </thead>
                                              <tbody>
                                                  
                                                  <?php 
                                                  
                                                        $stmt=$con->prepare("SELECT * FROM studenthw WHERE studentid = ? ");
                                                        $stmt->execute(array($student_id));
                                                        $homeworks=$stmt->fetchAll();
                                                        $checkhomework = $stmt->rowCount();
                                                        
                                                        foreach($homeworks as $homework){
                                                        
                                                        $stmt=$con->prepare("SELECT * FROM homeworks WHERE id = ?");
                                                        $stmt->execute(array($homework['homeworkid']));
                                                        $home=$stmt->fetch();
                                                        
                                                  ?>
                                                <tr>
                                                    
                                                    
                                                  <th scope="row"><?php echo $home['name']?></th>
                                                  <td><?php echo $homework['result'] ." / ".$home['degree'];?></td>
                                                  
                                                </tr>
                                              <?php
                                                }?>
                                              </tbody>
                                            </table>
                                            
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
<?php ob_end_flush();?>