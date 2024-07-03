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

 if(isset($_GET['id']) && isset($_GET['do'])){
 $id = $_GET['id'];
 $do = $_GET['do'];

if($do=='ediit') {
$stmt= $con->prepare ("SELECT * FROM students WHERE id=?");
$stmt->execute(array($id));
$students=$stmt->fetch();
}}
if(isset($_POST['edit']))
    {
     $id=$_POST['id'];
     $username=check($_POST['username']);
     $name=check($_POST['name']);
     $email=check($_POST['email']);
     $password=check($_POST['password']);
     $oldemail=$_POST['oldemail'];
     $phone=$_POST['phone'];
     $phone1=$_POST['phone1'];
     $edulevel=$_POST['edulevel'];
    //   $class=$_POST['class'];
     $err=array();
     $stmt=$con->prepare("SELECT * FROM students WHERE email=? AND email != ?");
     $stmt->execute(array($email,$oldemail));
     $results=$stmt->rowCount();
     if($results!=0) $err['email']="E-mail is used before";
     

if(empty($err)){
     $stmt=$con->prepare("UPDATE  students SET username=?, name=? ,email=?  ,password=? , phone = ?, phone2 = ?, eductionallevel=?  WHERE id=?");     
     $stmt->execute(array($username,$name,$email,$password,$phone,$phone1,$edulevel,$id));
     
     $stmt=$con->prepare("UPDATE  studentssubjects SET classid=? WHERE  studentid=?");     
     $stmt->execute(array($class,$id));
     
     header("Location:allstudents2.php?msg=".urlencode("Done Editing Student"));
     exit();
}}
   

?>



<!doctype html>
<html class="no-js" lang="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>E-Learning System | Editing Student</title>
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
                    <h3> Setting</h3>
                    <ul>
                        <li>
                            <a href="students.php">All Students</a>
                        </li>
                        <li>Edit Student</li>
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
                                        <h3>Edit Student</h3>
                                    </div>
                                  
                                 
                                </div>
                                  
                              <form class="new-added-form" method="post">
                                    <input type="text" hidden value="<?php echo $id;?>" name="id">
                                    <input type="text" hidden value="<?php echo $students['email'];?>" name="oldemail">
                                    <div class="row">
                                        <div class="col-xl-6 col-lg-6 col-12 form-group">
                                            <label>Name *</label>
                                            <input type="text" placeholder="Name" class="form-control" name="name" value="<?php echo $students['name']; ?>" required>
                                        </div>
                                         
                                         <div class="col-xl-6 col-lg-6 col-12 form-group">
                                            <label>Username *</label>
                                            <input type="text" placeholder="Name" class="form-control" name="username" value="<?php echo $students['username']; ?>" required>
                                        </div>
                                        
                                        <div class="col-xl-6 col-lg-6 col-12 form-group">
                                            <label>Email *</label>
                                            <input type="email" placeholder="E-mail" class="form-control" name="email" value="<?php echo $students['email']; ?>" required>
                                        </div>
                                        <div class="col-xl-6 col-lg-6 col-12 form-group">
                                            <label>Password *</label>
                                            <input type="password" placeholder="Password" class="form-control" name="password" value="<?php echo $students['password']; ?>" required>
                                        </div>
    
                                        <div class="col-xl-6 col-lg-6 col-12 form-group">
                                            <label>Phone *</label>
                                            <input type="text" placeholder="Phone Number" class="form-control" name="phone" value="<?php echo $students['phone']; ?>" required minlength="11" maxlength="11">
                                        </div>
                                        
                                        
                                        <div class="col-xl-6 col-lg-6 col-12 form-group">
                                            <label>Parent Phone *</label>
                                            <input type="text" placeholder="Parent Phone" class="form-control" name="phone1" value="<?php echo $students['phone2']; ?>" required minlength="11" maxlength="11">
                                        </div>
                                        
                                        <div class="col-xl-6 col-lg-6 col-12 form-group">
                                            <label>Educational Level *</label>
                                            <select class="select2" name="edulevel" required>
                                              <?php 
                                                $stmt=$con->prepare("SELECT * FROM educationallevels");
                                                $stmt->execute(array());
                                                $edus=$stmt->fetchAll();
                                             
                                                foreach($edus as $edu){
                                                    
                                              ?>  
                                                    <option <?php if($students['eductionallevel']==$edu['id']) echo "selected"; ?> value="<?php echo $edu['id']; ?>"><?php echo $edu['name'];;?></option>
                                              <?php }?>
                                            </select>
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
                </div>
            </div>
                <!-- Account Settings Area End Here -->
          
            </div>
    
        <!-- Page Area End Here -->
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
<?php ob_end_flush();?>