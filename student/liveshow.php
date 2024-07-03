<?php    
ob_start();
ini_set('session.cookie_lifetime', 60 * 60 * 24 );
ini_set('session.gc_maxlifetime', 60 * 60 * 24 );
//set cookie lifetime for 100 days (60sec * 60mins * 24hours * 100days)
ini_set('session.cookie_lifetime',0);
ini_set('session.gc_maxlifetime', 60 * 60 * 24 * 100);
ini_set('session.gc_probability',0);

//then start the session
session_start();
include_once 'connect.php';

if(!isset($_SESSION['name'])){
header("location:index.php?msg=".urldecode("please login first"));
exit();
}
function check($var){
    return htmlentities(trim(strip_tags(stripcslashes($var))));
  }
if(isset($_GET['l'])){
$cat=check($_GET['cat']);
$stmt=$con->prepare("SELECT * FROM videos WHERE subjectid=? AND type = ? AND category = ?");
$stmt->execute(array($_SESSION['subject'],"0",$cat));
$videos=$stmt->fetchAll();
}else{
$stmt=$con->prepare("SELECT * FROM videos WHERE subjectid=? AND type = ?");
$stmt->execute(array($_SESSION['subject'],"0"));
$videos=$stmt->fetchAll();
}

$email=$_SESSION['name'];
$stmt=$con->prepare("SELECT id FROM students WHERE email = ?");
$stmt->execute(array($email));
$results=$stmt->fetch();

$stmt=$con->prepare("SELECT name FROM subjects WHERE id = ? ");   
$stmt->execute(array($_SESSION['subject']));
$subject=$stmt->fetch();

$stmt=$con->prepare("SELECT teacher_id FROM subjects WHERE id = ? ");
$stmt->execute(array($_SESSION['subject']));
$t=$stmt->fetch();

$stmt=$con->prepare("SELECT fullname FROM admins WHERE id = ? ");
$stmt->execute(array($t['teacher_id']));
$teacher=$stmt->fetch();

// if (empty($_SESSION['token'])) {
//     $_SESSION['token'] = bin2hex(random_bytes(32));
// }
// $token = $_SESSION['token'];

// if (!empty($_POST['token'])) {
//     if (hash_equals($_SESSION['token'], $_POST['token'])) {
//          // Proceed to process the form data
    
// if(isset($_POST['send']))
// {
//      $videoid=check($_POST['videoid']);
//      $studentid=check($_POST['studentid']);
//      $question=$_POST['question'];
//     //  echo $question;
//      $error=array();

    
//      if(empty($error)){
//      $stmt=$con->prepare("INSERT INTO videoquestions (videoid,question,studentid)
//          VALUES(:videoid,:question,:studentid)");
//      $stmt->execute(array('videoid'=>$videoid ,'question'=>$question,'studentid'=>$studentid));
//     //  header("Location:videos.php?mssg=".urlencode("Done Send Question"));
//     //   exit();
// }}
// } else {
//       //  error_log("CSRF Failed from file Videos.php", 0);

// }
?>
<!doctype html>
<html class="no-js" lang="">

<head>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-176753539-1"></script>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'UA-176753539-1');
    </script>

    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>E-Learning System | Videos </title>
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
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
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
                            <div class="col-12-xxxl col-12">
                                <iframe src="<?php echo $_GET['l']; ?>"  width="80%" height="100%" style="display: block; margin: 0  auto;" allowfullscreen></iframe>
                            </div>
                            <div class="col-12-xxxl col-12">
                                <iframe src="<?php echo $_GET['c']; ?>"  width="80%" height="300px" style="display: block; margin: 0  auto;"></iframe>
                            </div>
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
    <script>
        $('.ask').click(function(){
            var question= $(this).closest('form').find('input[name="question"]');
            var q=question.val(); 
            var vid=$(this).closest('form').find('input[name="videoid"]').val(); 
            var sid=$(this).closest('form').find('input[name="studentid"]').val(); 
            $.ajax({
               type: "POST",
               url: "askq.php",
               data: {
                   auth: true,
                   videoid: vid,
                   studentid: sid,
                   question: q ,
               },
               success: function(data){
                question.val("");
                swal("Done sending question", "Feel free to ask us anytime", "success");
                   
               }
            });
            
            
            return false;
            
        });
            
        
    </script>

</body>

</html>
<?php ob_end_flush();?>