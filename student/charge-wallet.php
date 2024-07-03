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
date_default_timezone_set('Africa/Cairo');
$stmt=$con->prepare("SELECT id FROM students WHERE email = ?");
$stmt->execute(array($_SESSION['name']));
$student=$stmt->fetch();

$stmt=$con->prepare("SELECT * FROM studentssubjects WHERE studentid = ?");
$stmt->execute(array($student['id']));
$subject=$stmt->fetch();

$stmt=$con->prepare("SELECT sum(money) as totalwallet FROM student_wallet WHERE studentid = ?");
$stmt->execute(array($student['id']));
$total=$stmt->fetch();

$_SESSION['subject']=$subject['subjectid'];
$_SESSION['class']=$subject['classid'];

//show subject details

$stmt=$con->prepare("SELECT name FROM subjects WHERE id = ? ");   
$stmt->execute(array($_SESSION['subject']));
$subject=$stmt->fetch();

$stmt=$con->prepare("SELECT teacher_id FROM subjects WHERE id = ? ");
$stmt->execute(array($_SESSION['subject']));
$t=$stmt->fetch();

$stmt=$con->prepare("SELECT fullname FROM admins WHERE id = ? ");
$stmt->execute(array($t['teacher_id']));
$teacher=$stmt->fetch();

$email=$_SESSION['name'];
$stmt=$con->prepare("SELECT eductionallevel FROM students WHERE email = ?");
$stmt->execute(array($email));
$result=$stmt->fetch();
$edulevel=$result['eductionallevel'];



if(isset($_POST['submit'])){
            $code = check($_POST['code']);
            $wid = check($_POST['wid']);
            $studentid = check($_POST['studentid']);

            $stmt=$con->prepare("SELECT * FROM codes WHERE code = ? AND studentid IS NULL  ");
            $stmt->execute(array($code));
            $data=$stmt->fetch();
            $check = $stmt->rowCount();
            if($check > 0){
                    
                    $stmt=$con->prepare("UPDATE codes SET studentid = ?   WHERE code = ? ");
                    $stmt->execute(array($studentid,$code));
                    
                    
                    $stmt = $con->prepare("INSERT INTO student_wallet (studentid,money,date) VALUES (:studentid,:money,:date)");
                    $stmt->execute(array(
                        'studentid' => $studentid,
                        'money' => $data['price'],
                        'date'=>date('Y-m-d')
                        ));
                        
                    header("location:charge-wallet.php?msg=".urldecode("Done Charge Your Wallet "));
                    exit();
                    
                
            }else{
                    header("location:charge-wallet.php?wid=$wid&msg=".urldecode("Invalid Code"));
                    exit();
            }
            
            
}





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
    <title>E-Learning System | Charge Wallte </title>
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
                                    <a href="statistics.php">Statistics</a>
                                </li>
                                <li>Charge Wallte</li>
                            </ul>
                        </div>
                        
                    </div>
                </div>
                <!-- Breadcubs Area End Here -->
                   <?php if(isset($_GET['msg'])){?>
                            <div class="alert alert-success errors alert-dismissible fade show" role="alert">
                                <?php echo check($_GET['msg']);?>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <?php }?>
                            
                <div class="row">
                        
                   <div class="col-12 my-5 text-right">
                       <alert class="alert alert-success h2 p-5">Your Balance Is <?= $total['totalwallet'] != NULL ? $total['totalwallet'] : 0?> EGP</alert>
                   </div>
                   
                    <!-- Student Info Area End Here -->
                    <div class="col-12-xxxl col-12">
                        <div class="row">
                        <div class="col-lg-6">
                                      <form  method="post">
                                            <input type="text" class="form-control" hidden value="<?php echo $student['id']?>" name="studentid">
                                      <div class="form-group">
                                            <label for="exampleInputEmail1">Code</label>
                                            <input type="text" class="form-control" name="code"  aria-describedby="emailHelp" placeholder="Enter Code">
                                      </div>
                                    
                                      <button type="submit" class="btn btn-primary btn-lg" name="submit">Charge</button>
                                    </form>
                        </div>
                       
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