<?php 
include_once 'connect.php';
session_start();
if(!isset($_SESSION['adminname'])){
    header("Location:index.php?msg=".urlencode("Please Login First"));
    exit();
}
function check($var){
  return trim(strip_tags(stripcslashes($var)));
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
$oldpassword=$_SESSION['adminname'];
$stmt=$con->prepare("SELECT * FROM admins WHERE password= ? ");
$stmt->execute(array($oldpassword));
$input=$stmt->fetch();
$id=$input['id'];
$oldname=$input['name'];
}

 if(isset($_GET['id']) && isset($_GET['do'])){
 $id = $_GET['id'];
 $do = $_GET['do'];

if($do=='edit') {
$stmt= $con->prepare ("SELECT * FROM classes WHERE id=?");
$stmt->execute(array($id));
$currentclass=$stmt->fetch();
}     
 }
if(isset($_POST['edit']))
    {
     $subject=$_POST['subject'];
     $day=$_POST['day'];
     $time=$_POST['time'];
     $time = date("H:i:s", strtotime($time));
     $type2=$_POST['type2'];
     $booking_type = check($_POST['booking_type']);
     $no=$_POST['no'];
     $center=$_POST['center'];
     if(!empty($_POST['parent'])) $parent=$_POST['parent'];
     else $parent = NULL;
     $err4=array();

if(empty($err4)){
    $stmt=$con->prepare("UPDATE  classes SET day=? , time=? ,s_id=?,no=?,type2=? , booking_type = ? , center_name = ? WHERE id=?");     
    $stmt->execute(array($day,$time,$subject,$no,$type2, $booking_type,$center,$id));
    header("Location:classes.php?mssg=".urlencode("Done Editing Classes"));
    exit();
}
}

?>



<!doctype html>
<html class="no-js" lang="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>E-Learning System | Edit Teacher</title>
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
        <?php  include_once 'navbar.php' ?>
        <!-- Header Menu Area End Here -->
        <!-- Page Area Start Here -->
        <div class="dashboard-page-one">
            <!-- Sidebar Area Start Here -->
          <?php include_once 'sidebar.php' ?>
            <!-- Sidebar Area End Here -->
            <div class="dashboard-content-one">
                <!-- Breadcubs Area Start Here -->
                <div class="breadcrumbs-area">
                    <h3> Edit Class</h3>
                    <ul>
                        <li>
                            <a href="classes.php">Classes</a>
                        </li>
                        <li>Edit Classes</li>
                    </ul>
                </div>
                <!-- Breadcubs Area End Here -->
                <!-- Account Settings Area Start Here -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="heading-layout1">
                                    <div class="item-title">
                                        <h3>Edit Classes</h3>
                                        <?php if(!empty($err4)){  ?>
                                            <div class="alert alert-danger errors alert-dismissible fade show" role="alert">
                                                <?php echo $err4['name']; ?>
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                    <span aria-hidden="true">×</span>
                                                </button>
                                            </div>
                                        <?php }?>
                                    </div>
                               
                                </div>

                             
                                
                                <form class="new-added-form" method="post">
                                    <div class="row">
                                        <div class="col-6-xxxl col-lg-6 col-12 form-group">
                                            <label>Subject</label>
                                            <select class="select2" name="subject" required>
                                                <!-- <option >Please Select</option> -->
                                                <?php 
                                                    $stmt=$con->prepare("SELECT * FROM subjects");
                                                    $stmt->execute();
                                                    $subjects=$stmt->fetchAll();

                                                    foreach ($subjects as $subject){

                                                        $stmt=$con->prepare("SELECT fullname FROM admins WHERE id = ?");
                                                        $stmt->execute(array($subject['teacher_id']));
                                                        $teacher=$stmt->fetch();

                                                        $stmt=$con->prepare("SELECT name FROM educationallevels WHERE id = ?");
                                                        $stmt->execute(array($subject['edu_id']));
                                                        $edulevel=$stmt->fetch();
                                                ?>
                                                    
                                            

                                                
                                                <option <?php if($currentclass['s_id']==$subject['id']) echo "selected"; ?>  value="<?php echo $subject['id'];?>"><?php echo $subject['name']." - ".$teacher['fullname']." - ".$edulevel['name'];?></option>

                                                <?php }?>
                                            </select>
                                        </div>

                                   <div class="col-6-xxxl col-lg-6 col-12 form-group">
                                                <label>Center</label>
                                            <select class="select2" name="center" required>
                                                    <option <?php if($currentclass['center_name']=="الراعى الخلفاوى") echo "selected"; ?> > الراعى الخلفاوى</option>
                                                    <option <?php if($currentclass['center_name']=="الراعى روض الفرج") echo "selected"; ?> >الراعى روض الفرج</option>
                                                    <option <?php if($currentclass['center_name']=="البسمله التعاون ") echo "selected"; ?> >البسمله التعاون</option>
                                                    <option <?php if($currentclass['center_name']=="AL") echo "selected"; ?> >A1</option>
                                                    <option <?php if($currentclass['center_name']=="learn الدقى") echo "selected"; ?> >learn الدقى</option> 
                                            </select>
                                        </div>
                                        
                                        
                                        
                                        <div class="col-6-xxxl col-lg-6 col-12 form-group">
                                            <label>Day</label>
                                            <select class="select2" name="day"  required>
                                                <option <?php if($currentclass['day']=="Sunday") echo "selected"; ?>>Sunday</option>
                                                <option <?php if($currentclass['day']=="Monday") echo "selected"; ?>>Monday</option>
                                                <option <?php if($currentclass['day']=="Tuesday") echo "selected"; ?>>Tuesday</option>
                                                <option <?php if($currentclass['day']=="Wednesday") echo "selected"; ?>>Wednesday</option>
                                                <option <?php if($currentclass['day']=="Thursday") echo "selected"; ?>>Thursday</option>
                                                <option <?php if($currentclass['day']=="Friday") echo "selected"; ?>>Friday</option>
                                                <option <?php if($currentclass['day']=="Saturday") echo "selected"; ?>>Saturday</option>
                                            </select>
                                        </div>

                                        <div class="col-6-xxxl col-lg-6 col-12 form-group">
                                            <label>Time</label>
                                            <input type="time" class="form-control" name="time" value="<?php echo $currentclass['time']; ?>" required >
                                        </div>

                                         <div class="col-12 form-group mg-t-8">
                                            <button type="submit" class="btn-fill-lg bg-blue-dark btn-hover-yellow" name="edit">Edit</button>
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

</body>

</html>