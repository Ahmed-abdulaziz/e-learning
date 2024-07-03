<?php
include_once 'connect.php';
// server should keep session data for AT LEAST 1 hour
ini_set('session.gc_maxlifetime', 21600);

// each client should remember their session id for EXACTLY 1 hour
session_set_cookie_params(21600);
session_start();

function check($var) {
    return trim(strip_tags(stripcslashes($var)));
}

if (isset($_POST['login'])) {
    $name = check($_POST['name']);
    $password = check($_POST['password']);
    $err2 = array();
    $roles = isset($_POST['roles']) ? $_POST['roles'] : array();

    $stmt = $con->prepare("SELECT * FROM admins WHERE name=? AND password=?");
    $stmt->execute(array($name, $password));
    
    if ($stmt->rowCount() == 0) {
        $err2['a'] = "Invalid username or password";
    } else {
        $user = $stmt->fetch();
        $userType = $user['type'];
        
        // Check if the user type matches any of the selected roles
        if ((in_array('admin', $roles) && ($userType == 'admin' || $userType == 'super admin')) || 
            (in_array('teacher', $roles) && $userType == 'teacher')) {
            $_SESSION['adminname'] = $user['name'];
            $_SESSION['type'] = $user['type'];
            $_SESSION['id'] = $user['id'];
            header('location:index3.php');
        } else {
            $err2['a'] = "Access denied for the selected role";
        }
    }
}
?>
<!doctype html>
<html class="no-js" lang="">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>E-Learning System | login admins</title>
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
    <!-- Data Table CSS -->
    <link rel="stylesheet" href="css\jquery.dataTables.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="css/style.css">
    <!-- Modernize js -->
    <script src="js\modernizr-3.6.0.min.js"></script>
</head>

<body>
    <!-- Preloader Start Here -->
    <div id="preloader"></div>
    <!-- Preloader End Here -->
    <div id="wrapper" class="wrapper bg-ash">
        
        <!-- Page Area Start Here -->
        <div class="dashboard-page-one">
            
            <div class="dashboard-content-one dashlogin">
                
                <!-- Breadcubs Area End Here -->
                <!-- All Subjects Area Start Here -->
                <div class="row">
                    <div class="col-3-xxxl col-12"></div>
                    <div class="col-6-xxxl col-12">
                        <div class="alphalogo">
                            <img src="img/logo.png">
                        </div>
                        <div class="card height-auto">
                            <div class="card-body">
                                <div class="heading-layout1">
                                    <div class="item-title">
                                        <h3>Login to Dashboard</h3>
                                    </div>
                                
                                </div>
                                <form class="new-added-form" method="post">
                            
                                
                                    <div class="row">
                                            <?php if (isset($err2) && !empty($err2)){?>
                                <div class="alert alert-danger errors alert-dismissible fade show" role="alert">
                                    <?php foreach ($err2 as $eee){?>
                                        <li><?php echo $eee;?></li>
                                    <?php } ?>
                                </div>
                                <?php }?>
                                        <div class="col-12-xxxl col-lg-6 col-12 form-group">
                                            <label>Username *</label>
                                            <input type="text" placeholder="please enter your name" class="form-control" name="name" value="<?php if(!empty($err2)) { echo $name; } ?>" required>
                                        </div>
                                   
                                        <div class="col-12-xxxl col-lg-6 col-12 form-group">
                                            <label>Password *</label>
                                            <input type="password" placeholder="please enter your password" class="form-control" name="password" required>
                                        </div>
<div class="col-12-xxxl col-lg-6 col-12 form-group">
    <label>Select your role:</label>
    <div class="form-check">
        <input class="form-check-input" type="checkbox" name="roles[]" value="admin" id="adminCheck">
        <label class="form-check-label" for="adminCheck">
            Admin
        </label>
    </div>
    <div class="form-check">
        <input class="form-check-input" type="checkbox" name="roles[]" value="teacher" id="teacherCheck">
        <label class="form-check-label" for="teacherCheck">
            Doctor
        </label>
    </div>
</div>

                                        <div class="col-12 form-group mg-t-8">
                                             
                                            
                                            <input type="submit" class="btn-fill-lg bg-blue-dark btn-hover-yellow" name="login" value="Login">
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
 <?php include_once "footer.php";?>
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