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
if(isset($_POST['add'])){
     $subject=$_POST['subject'];
     $day=$_POST['day'];
     $time=$_POST['time'];
     $time = date("H:i:s", strtotime($time));
    //  $type2=$_POST['type2'];
    //  $booking_type=check($_POST['booking_type']);
    //  $no=$_POST['no'];
     $center=$_POST['center'];
     if(!empty($_POST['parent'])) $parent=$_POST['parent'];
     else $parent = NULL;
     $errr=array();



// if(empty($errr)){
//      $stmt=$con->prepare("INSERT INTO classes (day,time,s_id,parentid,type,no,type2,booking_type,center_name)
//          VALUES(:day,:time,:s_id,:parentid,:type,:no,:type2,:booking_type,:center_name)");
//      $stmt->execute(array('day'=>$day,'time'=>$time,'s_id'=>$subject,'parentid'=>$parent,'type'=>$type,'no'=>$no,'type2'=>$type2,'booking_type'=>$booking_type,'center_name'=>$center));
//      header("Location:classes.php?msgg=".urlencode("Done Adding Class"));
//      exit();
// }



if(empty($errr)){
     $stmt=$con->prepare("INSERT INTO classes (day,time,s_id,center_name)
         VALUES(:day,:time,:s_id,:center_name)");
     $stmt->execute(array('day'=>$day,'time'=>$time,'s_id'=>$subject,'center_name'=>$center));
     header("Location:classes.php?msgg=".urlencode("Done Adding Class"));
     exit();
}

}


?>
<!doctype html>
<html class="no-js" lang="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>E-Learning System | Add Class</title>
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
                    <h3>Add Class</h3>
                    <ul>
                        <li>
                            <a href="index2.php">Home</a>
                        </li>
                        <li>Add Class</li>
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
                                        <h3>Add New Class</h3>
                                        <?php if(!empty($errr)){  ?>
                                             <div class="alert alert-danger errors alert-dismissible fade show adminerror" role="alert">
                                                    <?php echo $errr['namee']; ?>
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

                                                <option value="<?php echo $subject['id'];?>"><?php echo $subject['name']." - ".$teacher['fullname']." - ".$edulevel['name'];?></option>

                                                <?php }?>
                                            </select>
                                        </div>

                                        <div class="col-6-xxxl col-lg-6 col-12 form-group">
                                                <label>Center</label>
                                            <select class="select2" name="center" required>
                                                <option> الراعى الخلفاوى</option>
                                                <option>الراعى روض الفرج</option>
                                                <option>البسمله التعاون</option>
                                                <option>A1</option>
                                                <option>learn الدقى</option>
                                            </select>
                                        </div>
                                        
                                        
                                        <div class="col-6-xxxl col-lg-6 col-12 form-group">
                                            <label>Day</label>
                                            <select class="select2" name="day" required>
                                                <option>Sunday</option>
                                                <option>Monday</option>
                                                <option>Tuesday</option>
                                                <option>Wednesday</option>
                                                <option>Thursday</option>
                                                <option>Friday</option>
                                                <option>Saturday</option>
                                            </select>
                                        </div>

                                        <div class="col-6-xxxl col-lg-6 col-12 form-group">
                                            <label>Time</label>
                                            <input type="time" class="form-control" name="time" required >
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