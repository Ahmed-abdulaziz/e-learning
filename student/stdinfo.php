<?php
include_once 'connect.php';

// server should keep session data for AT LEAST 1 hour
ini_set('session.gc_maxlifetime', 21600);

// each client should remember their session id for EXACTLY 1 hour
session_set_cookie_params(21600);


if (isset($_SESSION['name'])) {
    header("Location:Chapters.php");
}

if (isset($_POST['submit'])) {
    $parentphone = $_POST['parentphone'];

    $err2 = [];
    $stmt = $con->prepare("SELECT * FROM students WHERE phone2 = ?");
    $stmt->execute(array($parentphone));
    $std = $stmt->fetch();
    if (empty($std)) {
        header("location:parent.php?msg=" . urlencode("رقم الهاتف غير صحيح"));
    }

    $stmt = $con->prepare("SELECT * FROM subjects WHERE edu_id = ?");
    $stmt->execute(array($std['eductionallevel']));
    $subjects = $stmt->fetchAll();
    // print_r($subject);die;

    //  $stmt=$con->prepare("SELECT * FROM weeks WHERE subjectid = ?");
    //  $stmt->execute(array($subject['id']));
    //  $weeks=$stmt->fetch();
    // print_r($weeks);die;

}
?>
<!doctype html>
<html class="no-js" lang="">

<head>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-145403987-2"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'UA-145403987-2');
    </script>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>E-Learning System</title>
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
    <link rel="stylesheet" href="css/style.css?v=0.1">
    <link rel="stylesheet" href="css/media.css">
    <link href="https://fonts.googleapis.com/css?family=Cairo&display=swap" rel="stylesheet">
    <style>
        body {
            background: white;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            text-align: right;
            padding: 8px;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
    </style>


    <!-- Modernize js -->
    <script src="js\modernizr-3.6.0.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body style="text-align:right;font-family:cairo">
    <!-- Preloader Start Here -->
    <div id="preloader"></div>
    <!-- Preloader End Here -->
    <div id="wrapper" class="wrapper bg-ash">
        <!-- Header Menu Area Start Here -->
        <?php include_once "navbar.php"; ?>
        <!-- Header Menu Area End Here -->

        <!-- Page Area Start Here -->
        <div class="dashboard-page-one" style="direction:rtl">
            <!-- Sidebar Area Start Here -->
            <?php //include_once "sidebar.php";
            ?>
            <!-- Sidebar Area End Here -->
            <div class="dashboard-content-one">
                <!-- Breadcubs Area Start Here -->
                <div class="breadcrumbs-area">
                    <h3>صفحة الوالد</h3>
                    <ul>
                        <li>
                            <a href="#">الرئيسية</a>
                        </li>
                        <li>الوالد</li>
                    </ul>
                </div>
                <!-- Breadcubs Area End Here -->
                <div class="row">
                    <div class="heading-layout1">
                        <div class="item-title">

                            <?php if (!empty($_GET['msg'])) {  ?>
                                <div class="alert alert-danger errors alert-dismissible fade show adminerror" role="alert">
                                    <?php echo $_GET['msg']; ?>
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>
                            <?php } ?>
                        </div>

                    </div>

                    <div class="col-12-xxxl col-12">
                        <div class="row">
                            <div class="col-lg-12">
                                <div id="wrapper" class="wrapper bg-ash">
                                    <!-- Page Area Start Here -->
                                    <div class="dashboard-page-one">

                                        <div class="dashboard-content-one" style="background: white;">

                                            <!-- Breadcubs Area End Here -->
                                            <!-- All Subjects Area Start Here -->
                                            <div class="row">
                                                <div class="col-3-xxxl col-3"></div>

                                                <div class="col-6-xxxl col-12">
                                                    <h3 class="mb-1 mt-3" style="text-align: right;"><span class="font-weight-bold">الاسم:</span>
                                                        <?php echo $std['name']; ?></h3>
                                                    <div style="overflow-x: auto;">
                                                        <h3 class="mt-5" style="    text-align: right;">حضور</h3>
                                                        <table>
                                                            <tr>
                                                                <th>اسم الأسبوع</th>
                                                                <th>حضور</th>
                                                                <!--<th>الواجب</th>-->
                                                                <!--<th>المادة</th>-->
                                                            </tr>
                                                            <?php

                                                            foreach ($subjects as $subject) {
                                                                 
                                                                $stmt = $con->prepare("SELECT * FROM weeks WHERE subjectid = ?");
                                                                $stmt->execute(array($subject['id']));
                                                                $weeks = $stmt->fetchAll();
                                                                foreach ($weeks as $week) {
                                                                   
                                                                    $stmt = $con->prepare("SELECT *  FROM  student_wallet WHERE studentid=? AND type=? AND product_id=?");
                                                                    $stmt->execute(array($std['id'], 'week', $week['id']));
                                                                    $att2 = $stmt->fetchAll();

                                                                    //  $stmt=$con->prepare("SELECT * FROM codes WHERE weekid = ? AND studentid = ?");
                                                                    //  $stmt->execute(array($week['id'],$std['id']));
                                                                    //  $att2=$stmt->fetch(); 
                                                                    //  print_r($att2);

                                                            ?>
                                                                    <tr>
                                                                        <td><?php echo $week['name']; ?></td>
                                                                        <td><?php if (!empty($att2)) echo "حضر عبر الإنترنت";
                                                                            else echo "لم يتم الحضور "; ?>
                                                                        </td>
                                                                        <!--<td><?php //if($att['hw']==NULL) echo "-"; elseif($att['hw']=="1")echo "Solved"; else echo "لم يتم الحل"; 
                                                                                ?></td>-->
                                                                                <!--<td><?php //echo $subject['name']; ?></td>-->

                                                                    </tr>

                                                            <?php
                                                                }
                                                            } ?>
                                                        </table>


                                                        <h3 class="mt-5" style="text-align: right;">الإختبارات</h3>
                                                        <table>
                                                            <tr>
                                                                <th>اسم الاختبار</th>
                                                                <th>درجة</th>
                                                                <!--<th>المادة</th>-->
                                                            </tr>
                                                            <?php
                                                          
                                                            foreach ($subjects as $subject) {
                                                                $stmt = $con->prepare("SELECT * FROM weeks WHERE subjectid = ?");
                                                                $stmt->execute(array($subject['id']));
                                                                $weeks = $stmt->fetchAll();
                                                                foreach ($weeks as $week) {
                                                                    $stmt = $con->prepare("SELECT * FROM week_details WHERE week_id = ? AND type=?");
                                                                    $stmt->execute(array($week['id'], 'exam'));
                                                                    $details = $stmt->fetchAll();

                                                                    foreach ($details as $detail) {
                                                                        $stmt = $con->prepare("SELECT * FROM exams where id=?");
                                                                        $stmt->execute(array($detail['product_id']));

                                                                        $quize = $stmt->fetch();

                                                                        if ($stmt->rowCount() > 0) {
                                                                            $stmt = $con->prepare("SELECT * FROM studentexam WHERE examid = ? AND studentid = ?");
                                                                            $stmt->execute(array($quize['id'], $std['id']));
                                                                            $quizstd = $stmt->fetch();
                                                                            $degree = $quizstd['result'];

                                                            ?>
                                                                            <tr>
                                                                                <td><?php echo $quize['name']; ?></td>
                                                                                <td><?php if (empty($quizstd)) echo "Not Solved";
                                                                                    else echo $degree . "/" . $quize['degree']; ?>
                                                                                </td>
                                                                                <!--<td><?//= $subject['name'];?></td>-->
                                                                            </tr>

                                                            <?php
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                            ?>
                                                        </table>

                                                        <!--<h3 class="mt-5">Exams</h3>-->
                                                        <!--<table>-->
                                                        <!--<tr>-->
                                                        <!--  <th>Exam Name</th>-->
                                                        <!--  <th>Degree</th>-->
                                                        <!--</tr>-->

                                                        <!--<tr>-->
                                                        <!--  <td><?php echo $exam['name']; ?></td>-->
                                                        <!--  <td><?php if (empty($examstd)) echo "Not Solved";
                                                                    else echo $examstd['degree'] . "/" . $exam['degree']; ?></td>-->
                                                        <!--</tr>-->


                                                        <!--</table>-->
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
                </div>
            </div>
        </div>
        <!-- Page Area End Here -->
    </div>
    <?php include_once "footer.php"; ?>
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
    <!-- Data Table Js -->
    <script src="js\jquery.dataTables.min.js"></script>
    <!-- Custom Js -->
    <script src="js\main.js"></script>

</body>

</html>