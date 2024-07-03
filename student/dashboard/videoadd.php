<?php    
ob_start();
ini_set('session.cookie_lifetime', 60 * 60 * 24 );
ini_set('session.gc_maxlifetime', 60 * 60 * 24 );
session_start();
include_once 'connect.php';
if(!isset($_SESSION['adminname'])){
    header("Location:index.php?msg=".urlencode("Please Login First"));
    exit();
}
function check($var){
  return trim(strip_tags(stripcslashes($var)));
}
$name=$_SESSION['adminname'];
if(isset($_POST['add']))
{
$subjectid=$_SESSION['subject'];
$name=check($_POST['name']);
$minutesview=check($_POST['minutesview']);
$edulevel = $_POST['edulevel'];
$free = $_POST['free'];
$no = $_POST['no'];
$type = $_POST['type'];

if(empty($free)){
   $free = 0;
}

$video = $_FILES['video']['name'];
   
$videosize = $_FILES['video']['size'];
$videotmp = $_FILES['video']['tmp_name'];

$videourl = $_POST['videourl'];
$err=array();

if (!empty($video)){


$Mimage = rand(0,100000) . '_' .$video ;
 move_uploaded_file($videotmp, "uploads/videos//". $Mimage);
}


if(empty($err)){
$stmt=$con->prepare("INSERT INTO videos (name,iframe,subjectid,no,minutesview) VALUES(:name,:videourl,:subjectid,:no,:minutesview) ");
$stmt->execute(array('name'=>$name ,'videourl'=>$videourl,'subjectid'=>$subjectid,'no'=>$no,'minutesview'=>$minutesview));
if($free == 1){
    $to= 'videos_free';
}else{
    $to = 'videos';
}
header("Location:$to.php?msgg=".urlencode("Done Adding Video"));
exit();

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
    <title>E-Learning System | Add Video</title>
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
    <!-- Animate CSS -->
    <link rel="stylesheet" href="css\animate.min.css">
    <!-- Select 2 CSS -->
    <link rel="stylesheet" href="css\select2.min.css">
    <!-- Date Picker CSS -->
    <link rel="stylesheet" href="css\datepicker.min.css">
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
<?php include_once 'navbar.php'; ?>

        <!-- Header Menu Area End Here -->
        <!-- Page Area Start Here -->
        <div class="dashboard-page-one">

            <!-- Sidebar Area Start Here -->
            <?php include_once 'sidebar.php'; ?>

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
                                <li>Add Video</li>
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
                <!-- Account Settings Area Start Here -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="heading-layout1">
                                    <div class="item-title">
                                        <h3>Add New Video</h3>
                                      
                                        
                                    </div>
                              
                                </div>  
                                <form class="new-added-form" method="post" enctype="multipart/form-data">
                                <input type="text" hidden value="<?php echo $_GET['edulevel']; ?>" name="edulevel">
                                    <div class="row">
                                        <div class="col-xl-3 col-lg-6 col-12 form-group">
                                            <label>Name *</label>
                                            <input type="text" placeholder="Enter Name" class="form-control" name="name"  value="<?php if(!empty($err)){echo $name;} ?>"required>
                                          
                                        </div>
                                 

                                        <div class="col-xl-3 col-lg-6 col-12 form-group">
                                            <label>Iframe</label>
                                            <input type="text" placeholder="Video Url" class="form-control" name="videourl" >
                                        </div>
                                        
                                        <div class="col-xl-3 col-lg-6 col-12 form-group">
                                            <label>No. of views</label>
                                            <input type="text" placeholder="Video Views" class="form-control" name="no" required >
                                        </div>
                                       
                                        <div class="col-xl-3 col-lg-6 col-12 form-group">
                                            <label>Minutes view</label>
                                            <input type="text" placeholder="Minutes view" class="form-control" name="minutesview" required>
                                        </div>
                                       
                                        <!--<div class="col-xl-3 col-lg-6 col-12 form-group">-->
                                        <!--    <label>Type</label>-->
                                        <!--    <select class="form-control" name="type" required>-->
                                        <!--        <option value="">Select Type</option>-->
                                        <!--        <option>vimeo</option>-->
                                        <!--         <option>youtube</option>-->
                                        <!--          <option>vdocipher</option>-->
                                        <!--    </select>-->
                                        <!--</div>-->
                                        
                                        
                                    <!--    <div class="col-12-xxxl col-lg-12 col-12 form-group">-->
                                    <!--         <div class="form-check form-check-inline">-->
                                    <!--              <input class="form-check-input" type="checkbox" id="inlineCheckbox2" name="free" value="1">-->
                                    <!--              <label class="form-check-label" for="inlineCheckbox2">Free</label>-->
                                    <!--        </div>-->
                                    <!--</div>-->
                                        
                                       
                                      
                                        <div class="col-12 form-group mg-t-8">
                                           
                                            <button type="submit" class="btn-fill-lg bg-blue-dark btn-hover-yellow" name="add">Add</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Account Settings Area End Here -->
              
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
    <!-- Scroll Up Js -->
    <script src="js\jquery.scrollUp.min.js"></script>
    <!-- Select 2 Js -->
    <script src="js\select2.min.js"></script>
    <!-- Date Picker Js -->
    <script src="js\datepicker.min.js"></script>
    <!-- Custom Js -->
    <script src="js\main.js"></script>

    <script>
        $(document).ready(function () {
        setTimeout(function () {
            $('.alert').fadeOut();
        }, 4000);
        });
    </script>

</body>

</html>
<?php ob_end_flush();?>