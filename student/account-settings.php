<?php 
include_once 'connect.php';
session_start();
if(!isset($_SESSION['name'])){
    header("Location:index.php?msg=".urlencode("Please Login First"));
    exit();
}
function check($var){
  return trim(strip_tags(stripcslashes($var)));
}

if(isset($_SESSION['name'])){
$oldemail=$_SESSION['name'];
$stmt=$con->prepare("SELECT * FROM students WHERE email= ?  ");
$stmt->execute(array($oldemail));
$input=$stmt->fetch();
$id=$input['id'];
$image=$input['image'];
$oldphone=$input['phone'];

//$image=$input['image'];
}


if(isset($_POST['add']))
    {
     $name=check($_POST['name']);
     $newpassword=check($_POST['password']);
     if(empty($newpassword)) {$password=$_POST['oldpassword'];}
     else {$password = check($_POST['password']); $cpassword=$_POST['cpassword'];}
     $email=check($_POST['email']);
     $phone=check($_POST['phone']);
     $err=array();
   
     
    if(!empty($newpassword)){
    if($password != $cpassword)
    {
        $err['password']="Passwords don't match";
    }
    }
    
     $stmt=$con->prepare("SELECT * FROM students WHERE phone=? AND phone != ?");
     $stmt->execute(array($phone,$oldphone));
     $result=$stmt->rowCount();
     if($result!=0) {$err['phone']="Phone is used before";}
    
     $stmt=$con->prepare("SELECT * FROM students WHERE email=? AND email != ?");
     $stmt->execute(array($email,$oldemail));
     $results=$stmt->rowCount();
     if($results!=0) {$err['email']="E-mail is used before";}

if(empty($err)){
    $_SESSION['name']=$email;
     $stmt=$con->prepare("UPDATE students SET name=?,password=?,email=?,phone=? WHERE id = ?");
     $stmt->execute(array($name ,$password,$email,$phone ,$id));
     header("Location:account-settings.php?msg=".urlencode("Account edited successfuly"));
}
}

?>



<!doctype html>
<html class="no-js" lang="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>E-Learning System | Account Setting</title>
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
    <link rel="stylesheet" href="css\style.css">
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
                            <a href="index3.php">Home</a>
                        </li>
                        <li>Setting</li>
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
                                        <h3>Edit Account</h3>
                                       
                                       <?php if (isset($err) && !empty($err)){?>
                                        <div class="alert alert-danger errors alert-dismissible fade show" role="alert">
                                            <?php foreach ($err as $e){?>
                                                <li><?php echo $e;?></li>
                                            <?php }?>
                                        </div>
                                        <?php }?>
                                        
                                        <?php if(isset($_GET['msg'])){?>
                                        <div class="alert alert-success  alert-dismissible fade show settingalert">
                                            <?php echo $_GET['msg'];?>
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <?php }?>
                                    </div>
                                
                                </div>
                                <?php if(isset($_SESSION['name'])){ ?>
                                
                                <form class="new-added-form" method="post" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="col-xl-9 col-lg-12 col-12">
                                            <div class="row">
                                            <div class="col-xl-12 col-lg-12 col-12 form-group">
                                                <label>Full Name </label>
                                                <input type="text" placeholder="" class="form-control" name="name" value="<?php echo $input['name']; ?>" required>
                                            </div>
                                            </div>
                                            <br>
                                        
                                            <!--<div class="col-xl-3 col-lg-6 col-12 form-group">-->
                                            <!--    <label>E-Mail</label>-->
                                            <!--    <input type="email" placeholder="" class="form-control" name="email" value=" <?php //echo $input['email'] ; ?>" required>-->
                                            <!--</div>-->
                                            <!--<div class="col-xl-3 col-lg-6 col-12 form-group">-->
                                            <!--    <label>Phone </label>-->
                                            <!--    <input type="text" placeholder="" class="form-control" name="phone" value=" <?php //echo $input['phone']; ?>" required>-->
                                            <!--</div> -->
                                            <div class="row">
                                            <div class="col-xl-6 col-lg-6 col-12 form-group">
                                                <label>New Password </label>
                                                <input type="password" placeholder="" class="form-control" name="password">
                                                
                                                <input type="password" placeholder="" class="form-control" name="oldpassword" value="<?php echo $input['password'] ; ?>" hidden>
    
                                            </div>
                                            
                                            <div class="col-xl-6 col-lg-6 col-12 form-group">
                                                <label>Confirm New Password </label>
                                                <input type="password" placeholder="" class="form-control" name="cpassword">
                                            </div>
                                            </div>
                                                <div class="row">
                                            <div class="col-xl-6 col-lg-6 col-12 form-group image">
                                                <label>Email</label>
                                                <input type="text"  class="form-control" name="email" value="<?php echo $input['email']; ?>" required>
                                            </div>
                                            </div>
                                                 <div class="row">
                                            <div class="col-xl-6 col-lg-6 col-12 form-group image">
                                                <label>Phone</label>
                                                <input type="text"  class="form-control" name="phone" value="<?php echo $input['phone']; ?>" minlength="11" maxlength="11" required>
                                            </div>
                                            </div>
                                            
                                         
                                           
                                            
                                            
                                        </div>
                                        
                                      

                                        
                                       
                                        
                                         <div class="col-12 form-group mg-t-8">
                                         <button type="submit" class="btn-fill-lg btn-gradient-yellow btn-hover-bluedark" name= "add">Update</button>
                                        </div>
                                        <?php } ?>
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