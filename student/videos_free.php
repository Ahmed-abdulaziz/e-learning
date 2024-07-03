<?php    
ob_start();
date_default_timezone_set('Africa/Cairo');
ini_set('session.cookie_lifetime', 60 * 60 * 24 );
ini_set('session.gc_maxlifetime', 60 * 60 * 24 );
session_start();
include_once 'connect.php';

if(!isset($_SESSION['name'])){
header("location:index.php?msg=".urldecode("please login first"));
exit();
}
function check($var){
  return trim(strip_tags(stripcslashes($var)));
}

$email=$_SESSION['name'];
$stmt=$con->prepare("SELECT id FROM students WHERE email = ?");
$stmt->execute(array($email));
$student=$stmt->fetch();

$stmt=$con->prepare("SELECT * FROM videos WHERE subjectid=?  AND free = ?");
$stmt->execute(array($_SESSION['subject'],1));
$videos=$stmt->fetchAll();

$stmt=$con->prepare("SELECT * FROM classes WHERE id=?");
$stmt->execute(array($_SESSION['class']));
$class=$stmt->fetch();

if($class['type2']=="online"){
$today=date('Y-m-d');

$stmt=$con->prepare("SELECT * FROM videos WHERE subjectid=? AND type = ? AND ( ( (date1=? OR date2=?) AND month IN (SELECT month FROM video_request WHERE studentid = ?) OR id IN (SELECT videoid FROM video_old_requests WHERE studentid =? AND date = ? AND status = ? ) )) ");
$stmt->execute(array($_SESSION['subject'],"online",$today,$today,$student['id'],$student['id'],$today,"1"));
$onlinevideos=$stmt->fetchAll();

$stmt=$con->prepare("SELECT * FROM videos WHERE subjectid=? AND type = ? AND (date1<? AND date2<?)");
$stmt->execute(array($_SESSION['subject'],"online",$today,$today));
$oldvideos=$stmt->fetchAll();
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

if(isset($_GET['id']) && isset($_GET['stdid'])){
    $videoid=$_GET['id'];
    $studentid=$_GET['stdid'];
    
     $stmt=$con->prepare("INSERT INTO video_old_requests (videoid,studentid,status,date) VALUES (:videoid,:studentid,:status,:date)");
     $stmt->execute(array('videoid'=>$videoid ,'studentid'=>$studentid,'status'=>"0",'date'=>$today));
     header("Location:videos.php?mssg=".urlencode("Done Send Request"));
     exit();
}

if(isset($_POST['send']))
{
     $videoid=check($_POST['videoid']);
     $studentid=check($_POST['studentid']);
     $question=$_POST['question'];
    //  echo $question;
     $error=array();

    
     if(empty($error)){
     $stmt=$con->prepare("INSERT INTO videoquestions (videoid,question,studentid)
         VALUES(:videoid,:question,:studentid)");
     $stmt->execute(array('videoid'=>$videoid ,'question'=>$question,'studentid'=>$studentid));
    //  header("Location:videos.php?mssg=".urlencode("Done Send Question"));
    //   exit();
}}
?>
<!doctype html>
<html class="no-js" lang="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>E-Learning System| Videos </title>
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
    <link rel="stylesheet" href="style.css?v=0.1">
    <link rel="stylesheet" href="css/style.css?v=0.2">
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
                                    <a href="videos.php">Videos</a>
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
                  <?php if(isset($_GET['mssg'])){?>
                            <div class="alert alert-success  alert-dismissible fade show settingalert">
                                <?php echo $_GET['mssg'];?>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        <?php } ?>
                <!-- Breadcubs Area End Here -->
                <div class="row">
                    <!-- Student Info Area Start Here -->
                    <!-- Student Info Area End Here -->
                    <div class="col-12-xxxl col-12">
                        <div class="row">
                            <!-- Summery Area Start Here -->
                            
                            
                            
                            
                            
                            
                        <div id="accordion" style="width: 100%;">
                            
                          <?php if(!empty($videos)){?>
                          <div class="card">
                            <div class="card-header" id="headingOne">
                              <h5 class="mb-0">
                                <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne" style="color: black; font-size: 24px; width: 100%; text-align: left;">
                                  Free Videos<span style="float: right;">&#8595;</span>
                                </button>
                                
                              </h5>
                            </div>
                        
                            <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                              <div class="card-body">
                                  <div class="row">
                                  <?php foreach($videos as $video) { ?>
                                    <div class="col-lg-4">
                                         <form class="new-added-form" method="post" action="videos.php">
                                        <?php if(!empty($video['video'])){ ?>
                                                <video width="100%" controls>
                                                  <source src="<?php echo "dashboard/uploads/videos/".$video['video']; ?>" type="video/mp4">
                                                </video>
                                                
        
                                           <?php } elseif(!empty($video['iframe'])) {?>
                                        <iframe src="<?php echo $video['iframe'] ?>"  width="100%" allowfullscreen></iframe>
                                        <?php } ?>
                                        <h2 class="vname"> <?php echo $video['name']; ?></h2>
                                        <!--    <input type="text"  class="form-control" name="videoid"  value="<?php //echo $video['id']; ?>" hidden>-->
                                        <!--    <input type="text"  class="form-control" name="studentid"  value="<?php //echo $results['id']; ?>" hidden>-->
        
                                        <!--<div class="input-group input-group-lg mb-3">-->
                                        <!--  <input type="text" class="form-control" name="question" placeholder="type your question here">-->
                                        <!--  <div class="input-group-append">-->
                                        <!--    <button class="btn btn-outline-secondary" type="submit" name="send">Ask</button>-->
                                        <!--  </div>-->
                                        <!--</div>-->
                                        </form>
                                    </div>
                               <?php } ?>
                              </div>
                              </div>
                            </div>
                          </div>
                          
                          <?php }?>
                          
                           <?php if(!empty($onlinevideos)){?>
                          <div class="card">
                            <div class="card-header" id="headingTwo">
                              <h5 class="mb-0">
                                <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo" style="color: black; font-size: 24px; width: 100%; text-align: left;">
                                  Current Online Videos<span style="float: right;">&#8595;</span>
                                </button>
                              </h5>
                            </div>
                            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                              <div class="card-body">
                                  <div class="row">
                                  <?php foreach($onlinevideos as $video) { ?>
                                
                                <div class="col-lg-4">
                                    <a href="showvideo.php?id=<?php echo $video['id']; ?>">
                                        <img src="img/video.png" style="width: 50%; display: block; margin: 15px auto;">
                                        <h2 class="vname"> <?php echo $video['name']; ?></h2>
                                    </a>
                                </div>
                            
                                <?php }?>
                                </div>
                              </div>
                            </div>
                          </div>
                          <?php }?>
                          
                          
                          <?php if(!empty($oldvideos)){?>
                          <div class="card">
                            <div class="card-header" id="headingThree">
                              <h5 class="mb-0">
                                <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree" style="color: black; font-size: 24px; width: 100%; text-align: left;">
                                  Request Old Videos<span style="float: right;">&#8595;</span>
                                </button>
                              </h5>
                            </div>
                            <div id="collapseThree" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                              <div class="card-body">
                                  <div class="row">
                                  <?php foreach($oldvideos as $video) { ?>
                                
                                <div class="col-lg-4">
                                    <div class="blackbg">
                                        <i class="fa fa-lock" aria-hidden="true"></i>
                                        <?php 
                                            $stmt=$con->prepare("SELECT * FROM video_old_requests WHERE studentid = ? AND videoid = ? AND status = ?");   
                                            $stmt->execute(array($student['id'],$video['id'],"0"));
                                            $request=$stmt->fetch();
                                            if(!empty($request)){
                                        ?>
                                        <div><a  class="btn btn-warning">Request Pending</a></div>
                                        <?php }else{?>
                                        <div><a href="videos.php?id=<?php echo $video['id']; ?>&stdid=<?php echo $student['id']; ?>" class="btn btn-info">Request Video</a></div>
                                        <?php }?>
                                        <h2 class="vname"> <?php echo $video['name']; ?></h2>
                                    </div>
                                        <img src="img/video.png" style="width: 50%; display: block; margin: 15px auto;">
                                </div>
                            
                                <?php }?>
                                </div>
                              </div>
                            </div>
                          </div>
                          <?php }?>
                          
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
<?php ob_end_flush();?>