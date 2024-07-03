<?php
$starttime = microtime();
ob_start();
include_once 'connect.php';
ob_start();
session_start();
if (!isset($_SESSION['adminname'])) {
    header("Location:index.php?msg=" . urlencode("Please Login First"));
    exit();
}
if (isset($_SESSION['adminname'])) {

    $name=$_SESSION['adminname'];
    $stmt=$con->prepare("SELECT type FROM admins WHERE name=?");
    $stmt->execute(array($name));
    $result=$stmt->fetch();
    $type=$result['type'];
    if($type!="super admin"){
        header("Location:index3.php?msg=You Not Have A Permission");
    }
}
if(isset($_GET['clear'])){
    $id=$_GET['id'];
    
     $stmt=$con->prepare("UPDATE  codes SET studentid=?   WHERE id=?");     
     $stmt->execute(array(NULL,$id));
     header("Location:generalcodes.php?msgg=".urlencode("Done Clear student from code"));
 exit();
}elseif(isset($_GET['delete'])){
    $id=$_GET['id'];
    
        $stmt=$con->prepare("DELETE FROM codes WHERE id=? ");
        $stmt->execute(array($id));
        
        header("Location:generalcodes.php?msgg=".urlencode("Done Delete code"));
        exit();
}

?>

<!doctype html>
<html class="no-js" lang="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>E-Learning System| Students </title>
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
    <div id="wrapper" class="wrapper bg-ash">
        <!-- Header Menu Area Start Here -->
        <?php include_once 'navbar.php' ?>

        <!-- Header Menu Area End Here -->
        <!-- Page Area Start Here -->
        <div class="dashboard-page-one">
            <!-- Sidebar Area Start Here -->


            <?php include_once 'sidebar.php'; ?>
            <!-- Sidebar Area End Here -->
            <div class="dashboard-content-one">
                <!-- Breadcubs Area Start Here -->


                <div class="breadcrumbs-area">
                    <h3>Student</h3>
                    <ul>
                        <li>
                            <a href="index3.php">Home</a>
                        </li>
                        <li>All Students</li>
                    </ul>
                </div>
                <!-- Breadcubs Area End Here -->
                <!-- Teacher Table Area Start Here -->
                <div class="card height-auto">
                    <div class="card-body">
                        <div class="heading-layout1">
                            <div class="item-title">
                                <h3>All Students Data</h3>
                            </div>


                        </div>
                        <!--msg for done-->
                        <?php if (isset($_GET['msg'])) { ?>
                            <div class="alert alert-success  alert-dismissible fade show settingalert">
                                <?php echo $_GET['msg']; ?>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        <?php } ?>

                        <!-- <a href="addstudent.php"  class="fw-btn-fill btn-gradient-yellow">Add Student</a> -->
                        <!--<a href="allstudents.php?do=approveall"  class="fw-btn-fill btn-gradient-yellow">Approve All</a>-->
                        <!--<a href="allstudents.php?do=disapproveall"  class="fw-btn-fill btn-gradient-yellow">Disapprove All</a>-->


                        <div class="table-responsive">
                             <!--<a href="excel.php" title="click to export" >excel</a> -->
                            <table class="table display data-table text-nowrap" id="myTabledata">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Codes</th>
                                        <th>Codes Category</th>
                                        <th>Student Name</th>
                                        <th>Student Phone</th>
                                        <th>Price</th>
                                        <th>Status</th>
                                       
                                    </tr>
                                </thead>

                            </table>


                        </div>
                    </div>
                </div>

                <!-- Breadcubs Area End Here -->
                <!-- Dashboard summery Start Here -->

                <!-- Social Media End Here -->
                <!-- Footer Area Start Here -->

                <!-- Footer Area End Here -->
            </div>
        </div>
        <!-- Page Area End Here -->
    </div>
    <?php include_once "footer.php"; ?>

    <div class="modal fade" id="imagemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <img src="" class="imagepreview" style="width: 100%;">
                </div>
            </div>
        </div>
    </div>
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
        $(document).ready(function() {
            $('#myTabledata').DataTable({
                'processing': true,
                'serverSide': true,
                'ajax': {
                    url: 'ajax/fetch_codes_data.php',
                    type: 'post'
                },
               
                "lengthMenu": [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100,200]
                ]
            });



        });
        
    </script>


</body>

</html>
<?php
ob_end_flush(); ?>