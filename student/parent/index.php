<?php
include_once '../connect.php';
// server should keep session data for AT LEAST 1 hour
ini_set('session.gc_maxlifetime', 21600);

// each client should remember their session id for EXACTLY 1 hour
session_set_cookie_params(21600);

session_start();
function check($var){
  return trim(strip_tags(stripcslashes($var)));
}
function randomPassword() {
    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 8; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
}

// if(isset($_SESSION['name'])){
//     header("Location:allsubjects2.php");
// }

if(isset($_POST['login'])) 
{
 $email=check($_POST['email']);
 $password=check($_POST['password']);
 $err2=[];
 $stmt=$con->prepare("SELECT * FROM students WHERE parent_username = ? AND parent_password=?");
 $stmt->execute(array($email,$password));
 
if($stmt->rowCount()==0){
    $err2['a']="Invalid Username or password ";
}
else{
$user=$stmt->fetch();
$approve=$user['approve'];

         if($approve==1) {
                $_SESSION['name']=$user['email'];
                $_SESSION['sid']=$user['id'];
                header('location:allsubjects2.php');
                exit();
       
    }
}}
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
    <title>E-Learning System | Login Onxam remon </title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="..\img\favicon.png">
    <!-- Normalize CSS -->
    <link rel="stylesheet" href="..\css\normalize.css">
    <link rel="stylesheet" href="">
    <!-- Main CSS -->
    <link rel="stylesheet" href="..\css\main.css">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="..\css\bootstrap.min.css">
    <!-- Fontawesome CSS -->
    <link rel="stylesheet" href="..\css\all.min.css">
    <!-- Flaticon CSS -->
    <link rel="stylesheet" href="..\fonts\flaticon.css">
    <!-- Animate CSS -->
    <link rel="stylesheet" href="..\css\animate.min.css">
    <!-- Select 2 CSS -->
    <link rel="stylesheet" href="..\css\select2.min.css">
    <!-- Data Table CSS -->
    <link rel="stylesheet" href="..\css\jquery.dataTables.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="..\style.css">
    <link rel="stylesheet" href="..\css/style.css?v=0.1">
    <link rel="stylesheet" href="..\css/media.css">
    <link href="https://fonts.googleapis.com/css?family=Cairo&display=swap" rel="stylesheet">


    <!-- Modernize js -->
    <script src="..\js\modernizr-3.6.0.min.js"></script>
</head>

<body>
    <!-- Preloader Start Here -->
    <div id="preloader"></div>
    <!-- Preloader End Here -->

    <section class="login">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-6">
                <div id="wrapper" class="wrapper bg-ash">
                <!-- Page Area Start Here -->
                <div class="dashboard-page-one">
                    
                    <div class="dashboard-content-one">
                        
                        <!-- Breadcubs Area End Here -->
                        <!-- All Subjects Area Start Here -->
                        <div class="row">
                            <div class="col-12-xxxl col-12">
                                <div class="alphalogo">
                                    <img src="../img/logo.png">
                                </div>
                                <div class="card height-auto">
                                    <div class="card-body">
                                        <div class="heading-layout1">
                                            <div class="item-title">
                                                <h3>Login</h3>
                                            </div>
                                         
                                        </div>
                                        <form class="new-added-form" method="post">
                                        <?php if(isset($_GET['msg'])){?>
                                        <div class="alert alert-success errors alert-dismissible fade show">
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
                                                <div class="col-12-xxxl col-lg-6 col-12 form-group">
                                                    <label> UserName *</label>
                                                    <input type="text" placeholder="please enter your Email Or Phone" class="form-control" name="email" value="<?php if(!empty($err2)) { echo $email; } ?>" required>
                                                </div>
                                              
                                        
                                                <div class="col-12-xxxl col-lg-6 col-12 form-group">
                                                    <label>Password *</label>
                                                    <input type="password" placeholder="please enter your password" class="form-control" name="password" required>
                                                </div>
                                            
                                                <div class="col-12 form-group mg-t-8">
                                                    
                                                    
                                                    <input type="submit" class="btn-fill-lg bg-blue-dark btn-hover-yellow" name="login" value="Login">
                                                     <!--<p>Don’t have an account? <a href="register.php">Sign Up</a></p> -->
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

            <div class="col-lg-6 banner" style="background-image:url('../../assets/images/owner.png');">
                <div class="">
                     <!--<h1>Mr <br> X</h1>-->
                    <!-- <h3> M.Ahmed Saïd </h3>
                    <h3> Les premiers  صاحب سلسلة   </h3>
                    <h3> ونظام تعليم الكترونى منفرد</h3>
                    <h3>للاستفسارات:  01223350857</h3> -->
                    <!-- <h3><a href="register.php"> سجل الآن </a></h3> -->
                </div>
            </div>

        </div>


    </div>
                                            
    <!-- start footer -->
        <?php include_once "footer.php";?>
    <!-- end footer -->

    </section>
    
    <!-- jquery-->
    <script src="..\js\jquery-3.3.1.min.js"></script>
    <!-- Plugins js -->
    <script src="..\js\plugins.js"></script>
    <!-- Popper js -->
    <script src="..\js\popper.min.js"></script>
    <!-- Bootstrap js -->
    <script src="..\js\bootstrap.min.js"></script>
    <!-- Select 2 Js -->
    <script src="..\js\select2.min.js"></script>
    <!-- Scroll Up Js -->
    <script src="..\js\jquery.scrollUp.min.js"></script>
    <!-- Data Table Js -->
    <script src="..\js\jquery.dataTables.min.js"></script>
    <!-- Custom Js -->
    <script src="..\js\main.js"></script>

</body>

</html>