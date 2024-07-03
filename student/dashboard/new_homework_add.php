<?php 
include_once 'connect.php';
session_start();
if(!isset($_SESSION['adminname'])){
    header("Location:index.php?msg=".urlencode("Please Login First"));
    exit();
}
function check($var){
    return htmlentities(trim(strip_tags(stripcslashes($var))));
  }
$name=$_SESSION['adminname'];
$stmt=$con->prepare("SELECT type FROM admins WHERE name=?");
$stmt->execute(array($name));
$result=$stmt->fetch();
$type=$result['type'];
if($type!="super admin" && $type!="admin"){
header("Location:index3.php?msg=You Not Have A Permission");
}

 if (!isset($_POST['token'])) {
$_SESSION['token'] = bin2hex(random_bytes(32));
$token = $_SESSION['token'];
}

if (!empty($_POST['token'])) {
    if (hash_equals($_SESSION['token'], $_POST['token'])) {
         // Proceed to process the form data
    
if(isset($_POST['add']))
    {
     $name=check($_POST['name']);
     $subjectid =$_SESSION['subject'];
     $time=check($_POST['time']); 
     $minimumPercentage = $_POST['min'];
     $homeworkNo = $_POST['no'];

     
    $errr=array();
        if(!$name || !filter_var($name,FILTER_SANITIZE_STRING) || strlen($name)>50){
            $errr['name']="Invaild Name";
    }


if(empty($errr)){

   
$stmt=$con->prepare("INSERT INTO new_homeworks (name , timer , q_no , minPercentage , subjectid) 
VALUES(:name ,:timer ,:q_no ,:minPercentage ,:subjectid)");
$stmt->execute(array(
    'name'=>$name,
    'timer'=>$time,
    'q_no'=>$homeworkNo,
    'minPercentage'=>$minimumPercentage,
    'subjectid'=>$subjectid
));
$last_id = $con->lastInsertId();

header("Location:new_homework_add_step2.php?qID=".$last_id."&no=".$homeworkNo);
exit();
}

}} else {
    error_log("CSRF Failed from file quizesadd.php", 0);
}
}


?>
<!doctype html>
<html class="no-js" lang="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>E-Learning System | Add Homeworks</title>
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
                    <h3>Add Homeworks</h3>
                    <ul>
                        <li>
                            <a href="index2.php">Home</a>
                        </li>
                        <li>Add Homeworks</li>
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
                                        <h3>Add New Homework</h3>
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
                                    <input type="hidden" name="token" value="<?php echo $token; ?>" />

                                    <div class="row">
                                        <div id="x"></div>
                                        <div class="col-xl-4 col-lg-6 col-12 form-group">
                                              
                                              <label>Name</label>
                                              <input type="text" placeholder="" class="form-control" name="name" required>
                                             
                                           </div>
                                          
                                            <div class="col-xl-4 col-lg-6 col-12 form-group">
                                                <label>Timer</label>
                                                <input type="number" class="form-control" name="time" required autocomplete="off">
                                            </div>
                                            
                                          <div class="col-xl-4 col-lg-6 col-12 form-group">
                                                <label>Number of Homework Questions</label>
                                                <input type="number" class="form-control" name="no" required autocomplete="off">
                                            </div>
                                            <div class="col-xl-4 col-lg-6 col-12 form-group">
                                                <label>Minimum Percentage %</label>
                                                <input type="text" class="form-control" name="min" required autocomplete="off">
                                            </div>
                                      
                                      
                                        </div>
                                    
                                    
                                         
                                         <div class="col-12 form-group mg-t-8">
                                         <button type="submit" class="btn-fill-lg btn-gradient-yellow btn-hover-bluedark" name="add">Next</button>
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

        function fetch_select(val)
                {
                    $.ajax({
                        type: 'post',
                        url: 'select.php',
                        data: {
                        get_option:val
                        },
                        success: function (response) {
                        document.getElementById("new_select").innerHTML=response; 
                        }
                    });
                }

    </script>

</body>

</html>