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
exit();
}


 if(isset($_GET['id']) && isset($_GET['do'])){
 $id = $_GET['id'];
 $do = $_GET['do'];

if($do=='edit') {
$stmt= $con->prepare ("SELECT * FROM subjects WHERE id=?");
$stmt->execute(array($id));
$subjects=$stmt->fetch();
}     
 }

if(isset($_POST['edit']))
    {
     $id=$_POST['id'];
     $name=check($_POST['name']);  
     $edulevel=$_POST['educlevel'];
     $teacher=$_POST['teacher'];
     $term=$_POST['term'];
     $whatsapp=$_POST['whatsapp'];
     $err4=array();
    //  echo $whatsapp;die;



if(empty($err4)){
    $stmt=$con->prepare("UPDATE subjects SET name=? ,edu_id=? , teacher_id =? , term = ? ,whatsapp=? WHERE id=?");     
    $stmt->execute(array($name,$edulevel,$teacher , $term,$whatsapp,$id));
    header("Location:subjects.php?mssg=".urlencode("Done Editing Subject"));
    exit();
}
}

?>



<!doctype html>
<html class="no-js" lang="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>E-Learning System | Edit Subject</title>
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
                    <h3> Edit Subject</h3>
                    <ul>
                        <li>
                            <a href="subject.php">Subjects</a>
                        </li>
                        <li>Edit Subject</li>
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
                                        <h3>Edit Subject</h3>
                                       
                                    </div>
                               
                                </div>

                             
                                
                                <form class="new-added-form" method="post">
                                    <input type="text" hidden value="<?php echo $id;?>" name="id">
                                    <div class="row">
                                         
                                        <div class="col-xl-4 col-lg-6 col-12 form-group">
                                              
                                            <label>Name</label>
                                            <input type="text" placeholder="New Name" class="form-control" name="name" value=" <?php echo $subjects['name']; ?>">
                                           
                                        </div>
                                      
                                         <div class="col-4-xxxl col-lg-6 col-12 form-group">
                                            <label>Eductional Level</label>
                                            
                                              <select class="select2" name="educlevel" required >
                                              <?php 
                                                    $stmt=$con->prepare("SELECT * FROM educationallevels");
                                                    $stmt->execute();
                                                    $levels=$stmt->fetchAll();
                                                    $edulevel=$subjects['edu_id'];
                                                     foreach($levels as $level){
                                             ?>
                                               
                                                <option <?php  if($edulevel==$level['id']) echo "selected"; ?> value="<?php echo $level['id'];?>"><?php echo $level['name'];?></option>
                                                <?php }?>
                                               
                                            </select>
                                        </div>

                                        <div class="col-4-xxxl col-lg-6 col-12 form-group">
                                            <label>Teacher</label>
                                             <?php $edulevel=$subjects['edu_id'];?>
                                              <select class="select2" name="teacher" required>
                                                <?php 
                                                     $stmt=$con->prepare("SELECT * FROM admins WHERE type = ?");
                                                     $stmt->execute(array("teacher"));
                                                     $teachers=$stmt->fetchAll();
                                                 
                                                     foreach($teachers as $teacher){
                                                ?>
                                               
                                                    <option <?php if($subjects['teacher_id']==$teacher['id']) echo "selected";?> value="<?php echo $teacher['id'];?>"><?php echo $teacher['fullname']; ?></option>
                                                <?php }?>
                                            </select>
                                        </div>
                                          
                                   <div class="col-4-xxxl col-lg-6 col-12 form-group">
                                            <label>Term</label>
                                            <select class="select2" name="term" required>
                                                <option <?php if($subjects['term']==1) echo "selected";?> value="1">ترم اول</option>
                                                 <option <?php if($subjects['term']==2) echo "selected";?>  value="2">ترم تانى</option>
                                            </select>
                                        </div>
                                          <div class="col-xl-4 col-lg-6 col-12 form-group">
                                            <label>Whatsapp</label>
                                           <input type="text" name="whatsapp" class="form-control" value="<?php echo $subjects['whatsapp'];?>">
                                        </div>

                                         
                                         <div class="col-12 form-group mg-t-8">
                                         <button type="submit" class="btn-fill-lg btn-gradient-yellow btn-hover-bluedark" name= "edit">Edit</button>
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