<?php
ob_start();
ini_set('session.cookie_lifetime', 60 * 60 * 24);
ini_set('session.gc_maxlifetime', 60 * 60 * 24);
session_start();
include_once 'connect.php';
if (!isset($_SESSION['adminname'])) {
    header("Location:index.php?msg=" . urlencode("Please Login First"));
    exit();
}

if (!isset($_SESSION['subject'])) {
    header("Location:index3.php?msg=" . urlencode("Please Select Teacher"));
    exit();
}
function check($var)
{
    return trim(strip_tags(stripcslashes($var)));
}
// echo $_SESSION['subject'].'dd';
if (isset($_GET['id'])) {
    $id = check($_GET['id']);
} else {
    header("Location: index3.php");
}
$stmt = $con->prepare("SELECT * FROM students WHERE id=?");
$stmt->execute(array($id));
$student = $stmt->fetch();



$stmt = $con->prepare("SELECT * FROM subjects WHERE edu_id = ? AND teacher_id=? ");
$stmt->execute(array($student['eductionallevel'],$_SESSION['id']));
$subject = $stmt->fetch();
// echo $subject['id'];

$stmt = $con->prepare("SELECT * FROM weeks WHERE subjectid = ?");
$stmt->execute(array($_SESSION['subject']));
$weeks = $stmt->fetchAll();


// print_r($weeks);die;


















// $stmt=$con->prepare("SELECT * FROM studentssubjects WHERE studentid=?");
// $stmt->execute(array($id));
// $stdclass=$stmt->fetch();

// $stmt=$con->prepare("SELECT * FROM exams WHERE classid = ?");
// $stmt->execute(array($stdclass['classid']));
// $exams=$stmt->fetchAll();
// $checkexam = $stmt->rowCount();

// $stmt=$con->prepare("SELECT * FROM homeworks  WHERE classid = ?");
// $stmt->execute(array($stdclass['classid']));
// $quizes=$stmt->fetchAll();
// $checkquiz = $stmt->rowCount();

// $stmt=$con->prepare("SELECT * FROM quizes WHERE subjectid = ?");
// $stmt->execute(array($stdclass['classid']));
// $homeworks=$stmt->fetchAll();
// $checkhome = $stmt->rowCount();

// $cdate=date("Y-m-d"); 


// $stmt=$con->prepare("SELECT * FROM new_homeworks WHERE subjectid = ?");
// $stmt->execute(array($stdclass['classid']));
// $assignments=$stmt->fetchAll();
// $checkassignment = $stmt->rowCount();

// //----- Rating
// $stmt=$con->prepare("SELECT MAX(points) FROM students WHERE eductionallevel = ?");
// $stmt->execute(array($student['eductionallevel']));
// $maxstudent=$stmt->fetch();
// if($maxstudent['MAX(points)']==0){
//     $rating=0;
// }else{
//     $rating=$student['points']/$maxstudent['MAX(points)']*100 ;
//     $rating=number_format((float)$rating, 2, '.', '');
// }


//-----------------------------------------------------------------------------------------------------------------------------------




?>



<!doctype html>
<html class="no-js" lang="">

<head>
        <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>E-Learning System | Profile </title>
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
    <!-- Full Calender CSS -->
    <link rel="stylesheet" href="css\fullcalendar.min.css">
    <!-- Animate CSS -->
    <link rel="stylesheet" href="css\animate.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">

    <!-- Modernize js -->
    <script src="js\modernizr-3.6.0.min.js"></script>
</head>

<body>
    <!-- Preloader Start Here -->
    <div id="preloader"></div>
    <!-- Preloader End Here -->
    <div id="wrapper" class="wrapper bg-ash replace">
        <!-- Header Menu Area Start Here -->
        <?php include_once "navbar.php";?>

        <!-- Header Menu Area End Here -->
        <!-- Page Area Start Here -->
        <div class="dashboard-page-one">
            <!-- Sidebar Area Start Here -->


            <?php include_once 'sidebar.php'; ?>
            <!-- Sidebar Area End Here -->
            <div class="dashboard-content-one">
                <!-- Breadcubs Area Start Here -->


                <div class="breadcrumbs-area">
                    <h3>Profile</h3>
                    <ul>
                        <li>
                            <a href="index3.html">Home</a>
                        </li>
                        <li>All Student</li>
                        <li>Profile</li>
                    </ul>
                </div>
                <div class="card ui-tab-card">
                    <div class="card-body">
                        <div class="heading-layout1 mg-b-25">
                            <div class="item-title">
                                <?php

                                ?>
                                <strong>ID : </strong> <?php echo $student['id'] ?> <br>
                                <strong>Name : </strong> <?php echo $student['name'] ?><br>
                                <strong>Code : </strong> <?php echo $student['code'] ?><br>

                                <strong>Student phone : </strong> <?php echo $student['phone'] ?><br>
                                <strong>Father phone : </strong> <?php echo $student['phone2'] ?><br>
                            </div>


                        </div>
                        <!-- --------------------------------------------------- -->
                        <div class="basic-tab">
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#tab1" role="tab" aria-selected="true">Weeks</a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#tabQuizes" role="tab" aria-selected="false">Quizes</a>
                                </li>



                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane fade show active" id="tab1" role="tabpanel">
                                    <table class="table" id="table1">
                                        <thead>
                                            <tr>
                                                <th scope="col">Week</th>
                                                <th scope="col">Price</th>
                                                <!--<th scope="col">Buying Method</th>-->

                                                <!--<th scope="col">Date</th>-->
                                                <!--<th scope="col">End Date</th>-->
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $stmt = $con->prepare("SELECT COUNT(product_id) as total FROM  student_wallet WHERE studentid=? AND type=?");
                                            $stmt->execute(array($id, 'week'));
                                            $total = $stmt->fetch();
                                            
                                            // print_r($total);
                                            
                                              $stmt = $con->prepare("SELECT distinct product_id FROM  student_wallet WHERE studentid=? AND type=?");
                                            $stmt->execute(array($id, 'week'));
                                            $stdweeks = $stmt->fetchAll();
                                            
                                            // print_r($weeks);die;
                                            foreach ($stdweeks as $week) {
                                                $stmt = $con->prepare("SELECT * FROM weeks  WHERE id=? AND subjectid=?");
                                                $stmt->execute(array($week['product_id'],$_SESSION['subject']));
                                                $week_name = $stmt->fetch();
                                                if (!empty($week_name)) {
                                            ?>
                                                    <tr>

                                                        <td>

                                                            <?php echo $week_name['name']; ?>
                                                        </td>

                                                        <td>

                                                            <?php echo $week_name['price']; ?>
                                                        </td>
                                                        <!--<td>-->

                                                        <?php //echo $week['method']; 
                                                        ?>
                                                        <!--</td>-->

                                                        <!--<td>-->

                                                        <?php //echo $week['date']; 
                                                        ?>
                                                        <!--</td>-->

                                                    </tr>
                                            <?php }
                                            } ?>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="tab-pane fade" id="tabQuizes" role="tabpanel">
                                    <table class="table" id="table2">
                                        <thead>

                                            <?php


                                            // $stmt = $con->prepare("select * from quizstudent where studentid=?");
                                            // $stmt->execute(array($student['id']));
                                            // $quizes = $stmt->fetchAll();
                                            // $checkquiz = $stmt->rowCount();
                                            // if ($checkquiz > 0) {

                                            ?>
                                            <tr>
                                                <th scope="col">Name</th>
                                                <th scope="col">Degree</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            foreach ($weeks as $week) {
                                                $stmt = $con->prepare("SELECT * FROM week_details WHERE week_id = ? AND type=?");
                                                $stmt->execute(array($week['id'], 'exam'));
                                                $details = $stmt->fetchAll();
                                                // print_r($details);
                                                $checkquiz = $stmt->rowCount();

                                                foreach ($details as $detail) {
                                                    $stmt = $con->prepare("SELECT * FROM exams where id=?");
                                                    $stmt->execute(array($detail['product_id']));

                                                    $quize = $stmt->fetch();
                                                  
                                                    if($stmt->rowCount()>0){
                                                    $stmt = $con->prepare("SELECT * FROM studentexam WHERE examid = ? AND studentid = ?");
                                                    $stmt->execute(array($quize['id'], $student['id']));
                                                    $quizstd = $stmt->fetch();
                                                    
                                                    $degree = $quizstd['result']; ?>
                                                    <tr>
                                                        <td><?php echo $quize['name']; ?></td>
                                                        <td><?php if (empty($quizstd)) echo "Not Solved";
                                                            else echo $degree . "/" . $quize['degree']; ?></td>
                                                    </tr>
                                            <?php }}
                                            } ?>
                                        </tbody>


                                    </table>
                                </div>


                            </div>
                        </div>
                        <!-- --------------------------------------------------- -->

                    </div>
                </div>

            </div>
        </div>
        <!-- Page Area End Here -->
    </div>
    <?php include_once "footer/footer.php"; ?>

    <!-- jquery-->
    <script src="js\jquery-3.3.1.min.js"></script>
    <!-- Plugins js -->
    <script src="js\plugins.js"></script>
    <!-- Popper js -->
    <script src="js\popper.min.js"></script>
    <!-- Bootstrap js -->
    <script src="js\bootstrap.min.js"></script>
    <!-- Counterup Js -->
    <script src="js\jquery.counterup.min.js"></script>
    <!-- Moment Js -->
    <script src="js\moment.min.js"></script>
    <!-- Waypoints Js -->
    <script src="js\jquery.waypoints.min.js"></script>
    <!-- Scroll Up Js -->
    <script src="js\jquery.scrollUp.min.js"></script>
    <!-- Full Calender Js -->
    <script src="js\fullcalendar.min.js"></script>
    <!-- Chart Js -->
    <script src="js\Chart.min.js"></script>
    <!-- Custom Js -->
    <script src="js\main.js"></script>
    <script src="js\jquery-3.3.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.html5.min.js"></script>
    <script type="text/javascript">

    </script>
    <script>
        $('#table1').DataTable({});
        $('#table2').DataTable({});
    </script>

</body>

</html>