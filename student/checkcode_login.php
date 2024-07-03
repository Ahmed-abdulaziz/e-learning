<?php
include_once 'connect.php';
// server should keep session data for AT LEAST 1 hour
ini_set('session.gc_maxlifetime', 21600);
date_default_timezone_set('Africa/Cairo');
// each client should remember their session id for EXACTLY 1 hour
session_set_cookie_params(21600);

session_start();
function check($var){
  return trim(strip_tags(stripcslashes($var)));
}

if(isset($_GET['id'])){
    $student_id = check($_GET['id']);
}

if(isset($_POST['check'])) 
{
 $code=check($_POST['code']);
$studentid=check($_POST['studentid']);
 $stmt=$con->prepare("SELECT * FROM codes WHERE (type = ? OR type = ? OR type = ?) AND studentid IS NULL AND code = ? ");
 $stmt->execute(array('general-Center','general-Online','general',$code));
 $type_code=$stmt->fetch();
 $check =  $stmt->rowCount();
 
 if($check > 0){
     
      
    $stmt=$con->prepare("SELECT * FROM students WHERE id = ?");
    $stmt->execute(array($studentid));
    $user=$stmt->fetch();
    if($stmt->rowCount() > 0){

  if($user['eductionallevel'] ==$type_code['level']){
 
    $stmt=$con->prepare("UPDATE codes SET studentid = ? WHERE code = ? ");
    $stmt->execute(array($studentid,$code));
     
    $cdate = date('Y-m-d');
    $x = strtotime($cdate);
    $end_active = date("Y-m-d",strtotime("+1 month",$x));

     $stmt=$con->prepare("UPDATE students SET approve = ? , end_active = ? WHERE id = ? ");
     $stmt->execute(array(1,$end_active,$studentid));
     
         $_SESSION['name']=$user['email'];
           $_SESSION['sid']=$user['id'];
        if(empty($user['gender'])){
        header('location:update.php?id='.$user['id']);
            
        }
        else{header('location:Chapters.php');}
        
    }else{
        header("Location:checkcode_login.php?id=$studentid&msg=".urlencode("Please Insert Code Of Your Education"));
        exit();
    }
    }
    
    header("Location:index.php?msg=".urlencode("Student Id Is no Found "));
    exit();
    
 }else{
     
        header("Location:checkcode_login.php?id=$studentid&msg=".urlencode("Code Is Incorrect"));
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
    <title>E-Learning System | Check Code</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="img\favicon.png">
    <!-- Normalize CSS -->
    <link rel="stylesheet" href="css\normalize.css">
    <link rel="stylesheet" href="">
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
    <!-- Data Table CSS -->
    <link rel="stylesheet" href="css\jquery.dataTables.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/media.css">
    <link href="https://fonts.googleapis.com/css?family=Cairo&display=swap" rel="stylesheet">


    <!-- Modernize js -->
    <script src="js\modernizr-3.6.0.min.js"></script>
</head>

<body>
    <!-- Preloader Start Here -->
    <div id="preloader"></div>
    <!-- Preloader End Here -->

    <section class="login">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div id="wrapper" class="wrapper bg-ash">
                <!-- Page Area Start Here -->
                <div class="dashboard-page-one">
                    
                    <div class="dashboard-content-one">
                        
                        <!-- Breadcubs Area End Here -->
                        <!-- All Subjects Area Start Here -->
                        <div class="row">
                            <div class="col-12-xxxl col-12">
                               
                                <div class="card height-auto">
                                    <div class="card-body">
                                        <div class="heading-layout1">
                                            <div class="item-title">
                                                <h3>Code</h3>
                                            </div>
                                         
                                        </div>
                                        <form class="new-added-form" method="post">
                                        <?php if(isset($_GET['msg'])){?>
                                        <div class="alert alert-danger errors alert-dismissible fade show">
                                            <?php echo $_GET['msg'];?>
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <?php }?>


                                        <?php if (isset($err2) && !empty($err2)){?>
                                        <div class="alert alert-danger errors alert-dismissible fade show" role="alert">
                                            <?php foreach ($err2 as $ee){?>
                                                <li><?php echo $ee;?></li>
                                            <?php } ?>
                                        </div>
                                        <?php }?>
                                        
                                            <div class="row">
                                                 <input type="text" value="<?php echo $student_id;?>" hidden class="form-control" name="studentid">
                                                <div class="col-12-xxxl col-lg-6 col-12 form-group">
                                                    <label>Your Code *</label>
                                                    <input type="text" placeholder="please enter your Code" class="form-control" name="code"  required>
                                                </div>
                                              
                                        
                                            
                                                <div class="col-12 form-group mg-t-8">
                                                    
                                                    
                                                    <input type="submit" class="btn-fill-lg bg-blue-dark btn-hover-yellow" name="check" value="Check">
                                                    <!-- <p>Don’t have an account? <a href="register.php">Sign Up</a></p> -->
                                                </div>
                                            </div>

                                        </form>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                        <!-- All Subjects Area End Here -->

                    </div>
                </div>
                <!-- Page Area End Here -->
            </div>
            </div>


        </div>


    </div>
                                            
    <!-- start footer -->
        <?php include_once "footer.php";?>
    <!-- end footer -->

    </section>
    
    <!-- jquery-->
    <script src="js\jquery-3.3.1.min.js"></script>
    <!-- Plugins js -->
    <script src="js\plugins.js"></script>
    <!-- Popper js -->
    <script src="js\popper.min.js"></script>
    <!-- Bootstrap js -->
    <script src="js\bootstrap.min.js"></script>
    <!-- Select 2 Js -->
    <script src="js\select2.min.js"></script>
    <!-- Scroll Up Js -->
    <script src="js\jquery.scrollUp.min.js"></script>
    <!-- Data Table Js -->
    <script src="js\jquery.dataTables.min.js"></script>
    <!-- Custom Js -->
    <script src="js\main.js"></script>

</body>

</html>