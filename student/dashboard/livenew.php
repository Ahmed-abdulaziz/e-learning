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
    return htmlentities(trim(strip_tags(stripcslashes($var))));
  }
$name=$_SESSION['adminname'];
if(isset($_POST['add']))
{
$classes=$_POST['classes']; 
$link = check($_POST['link']);
$err2=array();

$stmt=$con->prepare("SELECT * FROM livevideos ");
$stmt->execute();
 
if($stmt->rowCount()>0){
$err2['a']=" Live NOW Already ";
}

if(empty($err2)){
    foreach($classes as $class){
        $stmt=$con->prepare("INSERT INTO livevideos (link,classid) VALUES(:link,:classid) ");
        $stmt->execute(array( 'link'=>$link,'classid'=>$class));
    }

header("Location:livenew.php?msag=".urlencode("Done Adding Live Video"));
exit();

}
}

if(isset($_GET['do'])){
    $do = check($_GET['do']);
    if($do=="delete"){
        $stmt=$con->prepare("DELETE FROM livevideos");
        $stmt->execute();
        header("Location:livenew.php?msag=".urlencode("Done Ending Live Video"));
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
    <title>E-Learning System | Add New Live</title>
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
                                <li>Add New Live</li>
                            </ul>
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
                                        <h3>Add New Live</h3>
                                      
                                        
                                    </div>
                                       <?php if(isset($_GET['msag'])){?>
                        <div class="alert alert-success  alert-dismissible fade show settingalert">
                            <?php echo check($_GET['msag']);?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <?php }?>
                              
                                </div>  
                                <form class="new-added-form" method="post" enctype="multipart/form-data">
                                <input type="text" hidden value="<?php echo check($_GET['edulevel']); ?>" name="edulevel">
                                      <?php if (isset($err2) && !empty($err2)){?>
                                        <div class="alert alert-danger errors alert-dismissible fade show" role="alert">
                                            <?php foreach ($err2 as $ee){?>
                                                <li><?php echo $ee;?></li>
                                            <?php } ?>
                                        </div>
                                        <?php }?>
                                    <div class="row">
                                       
                                    

                                        <div class="col-xl-4 col-lg-6 col-12 form-group">
                                            <label>Link</label>
                                            <input type="text" placeholder="Video Url" class="form-control" name="link" >
                                          
                                        </div>
                                      
                                        <div class="col-xl-12 col-lg-12 col-12 form-group">
                                           <label>Classes *</label>
                                            <?php
                                               
                                                $stmt=$con->prepare("SELECT * FROM classes  WHERE s_id =? ");
                                                $stmt->execute(array($_SESSION['subject']));
                                                $classes=$stmt->fetchAll();
                                            foreach($classes as $class){

                                                $stmt=$con->prepare("SELECT * FROM subjects WHERE id = ?");
                                                $stmt->execute(array($class['s_id']));
                                                $subject=$stmt->fetch();

                                                $stmt=$con->prepare("SELECT name FROM educationallevels WHERE id=?");
                                                $stmt->execute(array($subject['edu_id']));
                                                $edulevel=$stmt->fetch();

                                                $stmt=$con->prepare("SELECT fullname FROM admins WHERE id=?");
                                                $stmt->execute(array($subject['teacher_id']));
                                                $teacher=$stmt->fetch();
                                                ?>
                                                <input type="checkbox" name="classes[]" value="<?php  echo $class['id'];?>"> <bdi><?php  echo $class['day']." - ".$class['time']." - ".$class['type']." - <bdi>".$edulevel['name']."</bdi><br>";?></bdi>
                                            <?php }?>
                                        </div>
                                      
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