<?php 
include_once 'connect.php';
//set cookie lifetime for 100 days (60sec * 60mins * 24hours * 100days)
ini_set('session.cookie_lifetime',0);
ini_set('session.gc_maxlifetime', 60 * 60 * 24 * 100);
ini_set('session.gc_probability',0);
date_default_timezone_set('Africa/Cairo');
//then start the session
session_start();
if(!isset($_SESSION['name'])){
    header("Location:index.php?msg=".urlencode("Please Login First"));
    exit();
}
if(isset($_SESSION['name']))
{
$name=$_SESSION['name'];
$stmt=$con->prepare("SELECT id FROM students WHERE email = ?");
$stmt->execute(array($name));
$results=$stmt->fetch();
$studentid=$results['id'];

$stmt=$con->prepare("SELECT * FROM new_homeworks WHERE subjectid = ?");
$stmt->execute(array($_SESSION['subject']));
$new_homeworks=$stmt->fetchAll();

$stmt=$con->prepare("SELECT name FROM subjects WHERE id = ? ");   
$stmt->execute(array($_SESSION['subject']));
$subject=$stmt->fetch();

$stmt=$con->prepare("SELECT teacher_id FROM subjects WHERE id = ? ");
$stmt->execute(array($_SESSION['subject']));
$t=$stmt->fetch();

$stmt=$con->prepare("SELECT fullname FROM admins WHERE id = ? ");
$stmt->execute(array($t['teacher_id']));
$teacher=$stmt->fetch();
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
    <title>E-Learning System  | Current Homeworks</title>
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
    <!-- Date Picker CSS -->
    <link rel="stylesheet" href="css\datepicker.min.css">
    <!-- Select 2 CSS -->
    <link rel="stylesheet" href="css\select2.min.css">
    <!-- Data Table CSS -->
    <link rel="stylesheet" href="css\jquery.dataTables.min.css">
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
<?php include_once 'navbar.php' ?>
        <!-- Header Menu Area End Here -->
        <!-- Page Area Start Here -->
        <div class="dashboard-page-one">
            <!-- Sidebar Area Start Here -->
      <?php include_once'sidebar.php' ?>
            <!-- Sidebar Area End Here -->
            <div class="dashboard-content-one">
                <!-- Breadcubs Area Start Here -->
                <div class="breadcrumbs-area">
                    <div class="row">
                        <div class="col-lg-8">
                            <h3>Student Dashboard</h3>
                            <ul>
                                <li>
                                    <a href="homeworks-current.php">Homeworks</a>
                                </li>
                                <li>Student</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- Breadcubs Area End Here -->
                <!-- Student Table Area Start Here -->
                <div class="card height-auto">
                    <div class="card-body">
                        <div class="heading-layout1">
                            <div class="item-title">
                                <h3>Current Homeworks</h3>
                            </div>
                        
                        </div>
                         <?php if(isset($_GET['err'])){?>
                            <div class="alert alert-danger errors alert-dismissible fade show" role="alert">
                                <?php echo $_GET['err'];?>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <?php }?>
                   
                        <div class="table-responsive">
                            <table class="table display data-table text-nowrap">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Solve</th>                               
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                    
                                        <?php if(!empty($new_homeworks)){ foreach ($new_homeworks as $homework){ 
                                            
                                            $homeworkid=$homework['id'];
                                            $stmt=$con->prepare("SELECT * FROM new_student_homework WHERE homeworkid = ? AND studentid = ?");
                                            $stmt->execute(array($homeworkid,$studentid));
                                            $stuHw=$stmt->fetch();

                                            $stuDeg = false;
                                            if(!empty($stuHw['result'])){ $stuDeg = true;}
                                            
                                            $cdate = date('Y-m-d');
                                            $exp = false;
                                            if($cdate > $homework['date2']){ $exp = true;}
                                            
                                
                                        ?>
                                        <tr>
                                        <td><?php echo $homework['name'] ?></td>
                                        <td><?php if($stuDeg == true) {echo $stuHw['result']." / ".$homework['q_no']; }else{echo "No Degree";} ?></td>
                                        
                                        <?php if($stuDeg != true && $exp == false) { ?>
                                        <td><a href="new_homework_solve.php?qID=<?php echo $homework['id'];?>">Start solving</a></td>
                                        <?php }elseif($exp == true && $stuDeg == false) { ?>
                                        <td>Expired</td>
                                        <?php }elseif($exp == true && $stuDeg == true) { ?>
                                        <td><a href="new_homework_modelanswer.php?qID=<?php echo $homework['id'];?>">Model Answer</a></td>
                                        <?php }elseif($stuDeg == true && $exp == false) { ?>
                                        <td>NO Model Answer yet</td>
                                    </tr>
                                    <?php } }} ?>
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                 
                </div>
                <!-- Student Table Area End Here -->
               
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
    <!-- Date Picker Js -->
    <script src="js\datepicker.min.js"></script>
    <!-- Data Table Js -->
    <script src="js\jquery.dataTables.min.js"></script>
    <!-- Custom Js -->
    <script src="js\main.js"></script>

</body>

</html>