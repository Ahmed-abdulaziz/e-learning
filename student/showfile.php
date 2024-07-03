<?php
ob_start();
ini_set('session.cookie_lifetime', 60 * 60 * 24 );
ini_set('session.gc_maxlifetime', 60 * 60 * 24 );
session_start();
include_once 'connect.php';

if(!isset($_SESSION['name'])){
    header("location:index.php?msg=".urldecode("please login first"));
    exit();
}

if(isset($_GET['id'])){
    $id=$_GET['id'];
    $stmt=$con->prepare("SELECT * FROM files WHERE id=?");
    $stmt->execute(array($id));
    $file=$stmt->fetch();
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
        <title>E-Learning System | Files </title>
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
        <!-- Modernize js -->
        <script src="js\modernizr-3.6.0.min.js"></script>
    </head>

    <body>
    <!-- Preloader Start Here -->
    <div id="preloader"></div>
    <!-- Preloader End Here -->
    <div id="wrapper" class="wrapper bg-ash">
        <!-- Header Menu Area Start Here -->
        <?php include_once "navbar.php";?>
        <!-- Header Menu Area End Here -->

        <!-- Page Area Start Here -->
        <div class="dashboard-page-one">
            <!-- Sidebar Area Start Here -->
            <?php include_once "sidebar.php";?>
            <!-- Sidebar Area End Here -->
            <div class="dashboard-content-one">
                <!-- Breadcubs Area Start Here -->
                <div class="breadcrumbs-area">
                    <h3>Material</h3>
                    <ul>
                        <li>
                            <a href="files.php">Material</a>
                        </li>
                        <li>Student</li>
                    </ul>
                </div>
                <!-- Breadcubs Area End Here -->
                <div class="row">
                    <!-- Student Info Area Start Here -->
                    <!-- Student Info Area End Here -->
                    <div class="col-12-xxxl col-12" id="element-to-hide">
                        <div class="row">
                            <div class="col-lg-12">
                            <!-- Summery Area Start Here -->
                                    <?php if(!empty($file['file'])){ ?>
                                        <h2><?php echo $file['name'];?></h2>
                                        <embed style="position: relative" id="myFrame"  src="<?php echo "https://docs.google.com/gview?url=https://Mr. Ibrahim Gad.com/student/dashboard/uploads/files/".$file['file']."&embedded=true&page=hsn#toolbar=0"; ?>" width="100%" height="600px" oncontextmenu="return false" >
                                        <img src="img/icon.png" style="position: absolute; right: 9px; top: 33px;">
                                    <?php } ?>
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
    <script type="text/javascript">
        $(document).keydown(function(e){
            if(e.which === 123){
                return false;
            }
        });
        $(document).bind("contextmenu",function(e) {
            e.preventDefault();
        });



    </script>


    </body>

    </html>
<?php ob_end_flush();?>