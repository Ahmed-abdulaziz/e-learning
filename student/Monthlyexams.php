<?php 
include_once 'connect.php';
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
$stmt=$con->prepare("SELECT * FROM homeworks WHERE classid = ?");
$stmt->execute(array($_SESSION['class']));
$homeworks=$stmt->fetchAll();

$stmt=$con->prepare("SELECT name,id FROM subjects WHERE id = ? ");   
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
    <title>E-Learning System | Current Monthly exams</title>
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
                                    <a href="homeworks-current.php">Monthly exams</a>
                                </li>
                                <li>Student</li>
                            </ul>
                        </div>
                        <div class="col-lg-4">
                            <div class="dashboard-summery-one subject">
                                <div class="item-content">
                                    <div class="item-number"><?php echo $subject['name']; echo " / ". $teacher['fullname']  ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Breadcubs Area End Here -->
                <!-- Student Table Area Start Here -->
                <div class="card height-auto">
                    <div class="card-body">
                        <div class="heading-layout1">
                            <div class="item-title">
                                <h3>Monthly exams</h3>
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
                                        <th>exam Name</th>
                                        <th>Degree</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php

                                        $subjectid = $subject['id'];
                                        $stmt=$con->prepare("SELECT name,subjectid , id FROM quizes WHERE subjectid = ? ");
                                        $stmt->execute(array($subjectid));
                                        $subjectname=$stmt->fetchAll();

                                            foreach ($subjectname as $subjectname){ 
                                                $quizid =  $subjectname['id'];
                                                $stmt=$con->prepare("SELECT degree FROM quizstudent WHERE studentid = ? And quizid=?");
                                                $stmt->execute(array($studentid, $quizid ));
                                                $degree=$stmt->fetch();

                                        ?>
                                    
                                    <tr>
                                        <td><?php echo $subjectname['name'] ?></td>
                                        <td><?php if($degree['degree'] === NULL){ ?>
                                                <button disabled class="btn-fill-lg btn btn-danger">Not corrected yet</button>
                                            <?php }else{

                                                $stmt=$con->prepare("SELECT final_degree FROM quizes WHERE id=?");
                                                $stmt->execute(array($quizid));
                                                $quiz=$stmt->fetch();

                                                echo $degree['degree']." / ".$quiz['final_degree'];; 
                                            }?> 
                                        </td>
                                    
                                    </tr>

                                    <?php } ?>
                                    
                                   
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