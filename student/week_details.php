<?php
include_once 'connect.php';
session_start();

if (!isset($_SESSION['name'])) {
    header("location:index.php?msg=" . urldecode("please login first"));
    exit();
}
function check($var)
{
    return trim(strip_tags(stripcslashes($var)));
}
$stmt = $con->prepare("SELECT id FROM students WHERE email = ?");
$stmt->execute(array($_SESSION['name']));
$student = $stmt->fetch();

// $stmt=$con->prepare("SELECT * FROM studentssubjects WHERE studentid = ?");
// $stmt->execute(array($student['id']));
// $subject=$stmt->fetch();


// $_SESSION['subject']=$subject['subjectid'];
// $_SESSION['class']=$subject['classid'];

//show subject details

date_default_timezone_set('Africa/Cairo');

$cdate = date('Y-m-d');

$stmt = $con->prepare("SELECT name FROM subjects WHERE id = ? ");
$stmt->execute(array($_SESSION['subject']));
$subject = $stmt->fetch();



$stmt = $con->prepare("SELECT teacher_id FROM subjects WHERE id = ? ");
$stmt->execute(array($_SESSION['subject']));
$t = $stmt->fetch();

$stmt = $con->prepare("SELECT fullname FROM admins WHERE id = ? ");
$stmt->execute(array($t['teacher_id']));
$teacher = $stmt->fetch();

$email = $_SESSION['name'];
$stmt = $con->prepare("SELECT eductionallevel FROM students WHERE email = ?");
$stmt->execute(array($email));
$result = $stmt->fetch();
$edulevel = $result['eductionallevel'];

if (isset($_GET['cid'])) {
    $chapter = check($_GET['cid']);
}
$stmt = $con->prepare("SELECT * FROM weeks WHERE subjectid = ? AND chapter = ?");
$stmt->execute(array($_SESSION['subject'], $chapter));
$weeks = $stmt->fetchAll();
// foreach($weeks as $week){
//     $stmt=$con->prepare("SELECT * FROM week_details WHERE week_id = ? ");
// $stmt->execute(array($week['id']));
// $week_details=$stmt->fetch();
// print_r($week_details);
// }




// print_r($weeks);

if (isset($_GET['do']) && $_GET['do'] == 'buy-week') {
    $wid = check($_GET['wid']);

    $stmt = $con->prepare("SELECT * FROM weeks WHERE id = ?");
    $stmt->execute(array($wid));
    $wek = $stmt->fetch();

    $stmt = $con->prepare("SELECT sum(money) as totalwallet FROM student_wallet WHERE studentid = ?");
    $stmt->execute(array($student['id']));
    $total = $stmt->fetch();

    if ($wek['price'] > $total['totalwallet']) {

        header("Location: " . $_SERVER['HTTP_REFERER'] . "&msg=" . urldecode("Insufficient Balance. Please charge the wallet first"));
        exit();
    } else {
        $stmt = $con->prepare("INSERT INTO student_wallet (studentid,money,date ,product_id , type) VALUES (:studentid,:money,:date , :product_id , :type)");
        $stmt->execute(array(
            'studentid' => $student['id'],
            'money' => $wek['price'] * -1,
            'date' => date('Y-m-d'),
            'product_id' => $wid,
            'type' => "Week"
        ));


        $stmt = $con->prepare("SELECT * FROM week_details WHERE   week_id = ? AND type = ? ");
        $stmt->execute(array($wid, 'video'));
        $week_details = $stmt->fetch();

        $stmt = $con->prepare("DELETE FROM videocount WHERE videoid = ? AND studentid = ?");
        $stmt->execute(array($week_details['product_id'], $student['id']));


        header("location:myweek.php?wid=$wid&msg=" . urldecode("Successfully purchased this week!"));
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

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'UA-145403987-2');
    </script>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>E-Learning System | Weeks On</title>
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
    <!-- Data Table CSS -->
    <link rel="stylesheet" href="css\jquery.dataTables.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="css/style.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;600;900&display=swap" rel="stylesheet">

    <style>
        .confirm {
            color: red;
        }

        .confirm2 {
            color: blue;
        }
    </style>

    <!-- Modernize js -->
    <script src="js\modernizr-3.6.0.min.js"></script>
</head>

<body style="font-family: 'Cairo', sans-serif;">
    <!-- Preloader Start Here -->
    <div id="preloader"></div>
    <!-- Preloader End Here -->
    <div id="wrapper" class="wrapper bg-ash">
        <!-- Header Menu Area Start Here -->
        <?php include_once "navbar.php"; ?>
        <!-- Header Menu Area End Here -->

        <!-- Page Area Start Here -->
        <div class="dashboard-page-one">
            <!-- Sidebar Area Start Here -->
            <?php include_once "sidebar.php"; ?>
            <!-- Sidebar Area End Here -->
            <div class="dashboard-content-one">
                <!-- Breadcubs Area Start Here -->
                <div class="breadcrumbs-area">
                    <div class="row">
                        <div class="col-lg-8">
                            <h3>Student Dashboard</h3>
                            <ul>
                                <li>
                                    <a href="statistics.php">Statistics</a>
                                </li>
                                <li>Weeks</li>
                            </ul>
                        </div>
                        <div class="col-lg-4">
                            <div class="dashboard-summery-one subject">
                                <div class="item-content">
                                    <div class="item-number"><?php echo $subject['name'];
                                                                echo " / " . $teacher['fullname']  ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php if (isset($_GET['msg'])) { ?>
                    <div class="alert alert-success errors alert-dismissible fade show" role="alert">
                        <?php echo check($_GET['msg']); ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php } ?>
                <!-- Breadcubs Area End Here -->
                <div class="row">
                    <!-- Student Info Area Start Here -->
                    <!-- Student Info Area End Here -->
                    <div class="col-12-xxxl col-12">


                        <div class="row" style="direction:ltr;">



                            <!-- Summery Area Start Here -->
                            <?php $x = 1;
                            foreach ($weeks as $week) {

                                $stmt = $con->prepare("SELECT * FROM weeks WHERE id = ?  AND  ( id  IN (SELECT product_id FROM student_wallet WHERE studentid = ? AND type = ? ) )");
                                $stmt->execute(array($week['id'], $student['id'], 'week'));
                                $myweek = $stmt->fetch();
                                $check = $stmt->rowCount();

                                $stmt = $con->prepare("SELECT * FROM student_wallet WHERE product_id = ? AND  studentid = ? AND type = ? ORDER BY date DESC ");
                                $stmt->execute(array($week['id'], $student['id'], 'week'));
                                $duration = $stmt->fetch();

                                $end_date = date('Y-m-d', strtotime($duration['date'] . "+" . $week['duration'] . "days"));
                                // $cdate='2023-08-26';
                                if ($end_date < $cdate) {
                                    $expired_date = 1;
                                } else {
                                    $expired_date = 0;
                                }

                                if (($check > 0 && $expired_date == 0) || $week['free'] == 1) {
                                    $icon = '<i class="fas fa-lock-open text-success"></i>';
                                    $url = "myweek.php";
                                    $buy = "1";
                                } else {
                                    $icon = '<i class="fas fa-lock text-danger"></i>';
                                    $url = "weeks.php?do=buy-week&wid=" . $week['id'];
                                    $buy = "0";
                                }

                            ?>
                                <div class="col-lg-4">

                                    <div class="dashboard-summery-one">
                                        <div class="row">
                                            <a href="javascript:void();" class="w-100 <?php echo $buy == 0 ? 'conf' : ''; ?>" data-id=<?php echo $week['id'] ?>>

                                                <div class="item-content text-center d-flex">
                                                    <span style="position: absolute;right: 30px;top: 0;"><?php echo $icon; ?></span>

                                                    <p class="item-title text-dark font-weight-bold w-100 mt-3 d-flex justify-content-between">
                                                        <span> <?php echo $week['name']; ?></span>
                                                        <span class="btn btn-light h2" style="font-size:15px !important"><?= $week['price'] ?> EGP</span>
                                                    </p>
                                                    <hr>
                                                </div>
                                                <hr>

                                                <?php if (!empty($week['image'])) { ?>
                                                    <img style="height: 250px;width: 100%;object-fit: cover;" src="dashboard/uploads/weeks/<?php echo $week['image']; ?>" />
                                                <?php } else { ?>
                                                    <img style="height: 250px;width: 100%;object-fit: contain;" src="img/logo.png" />
                                                <?php } ?>
                                                <hr>
                                            </a>
                                            <hr>
                                            <div class="accordion  w-100" id="accordionExample">
                                                <div class="card">
                                                    <?php




                                                    ?>
                                                    <?php

                                                    if (($check > 0 && $expired_date == 0)) { ?>
                                                        <a href="<?php echo $url; ?>?wid=<?php echo $week['id'] ?>" class="text-white font-weight-bold btn btn-dark mt-5 btn-lg h3">Open</a>

                                                    <?php } else { ?>

                                                        <?php echo $week['id'];
                                                        echo $student['id']; ?>


                                                        <a href="javascript:void();" class="text-white font-weight-bold 
                                                      btn btn-dark mt-5 btn-lg h3 conf " data-id="<?php echo $week['id'] ?>" id="a">Buy now </a>
                                                    <?php
                                                    }
                                                    ?>



                                                </div>

                                            </div>
                                        </div>
                                    </div>






                                </div>

                            <?php $x++;
                            } ?>

                            <!-- Exam Result Area End Here -->
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
    <script>
        $(document).ready(function() {

            $(".conf").on("click", function(e) {
                var id = $(this).data('id');


                $.ajax({
                    url: 'ajax/checkproduct.php?std=<?php echo $student['id']; ?>',
                    data: {
                        id: id
                       

                    },
                    method: 'post',
                    success: function(data) {
                        if (data == "confirm") {
                            e.preventDefault();
                            if (confirm("Are you sure you want to Buy This Week ?")) {
                                //   $(".conf").attr("href", "weeks.php?do=buy-week&wid="+id);
                                console.log("aya");
                                window.location.href = "weeks.php?do=buy-week&wid=" + id;
                            };


                        } else {
                            e.preventDefault();
                            if (confirm("This week will bought for second time, are you sure ??")) {
                                window.location.href = "weeks.php?do=buy-week&wid=" + id;
                            }
                      }
                    }
                })
            });
        })
    </script>
</body>

</html>