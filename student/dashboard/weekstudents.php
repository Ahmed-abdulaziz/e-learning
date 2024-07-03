<?php 

include_once 'connect.php';
ob_start();
session_start();
date_default_timezone_set("Africa/Cairo");
 if(!isset($_SESSION['adminname'])){
    header("Location:index.php?msg=".urlencode("Please Login First"));
    exit();
}
function check($var){
    return htmlentities(trim(strip_tags(stripcslashes($var))));
  }
$name=$_SESSION['adminname'];

if(isset($_GET['id'])){
    $id = $_GET['id'];

    $stmt=$con->prepare("SELECT * FROM student_wallet WHERE product_id=?");
    $stmt->execute(array($id));
    $weeks=$stmt->fetchAll();
}

if(isset($_GET['do'])){
 $do = $_GET['do'];
 
if($do=='reset'){
    $stdid=$_GET['stdid'];
    $weekid=$_GET['weekid'];
    
    $stmt=$con->prepare("UPDATE student_wallet SET date=? WHERE studentid = ? AND product_id = ?");
    $stmt->execute(array(date('Y-m-d'),$stdid,$weekid));

    $stmt=$con->prepare("DELETE FROM videocount  WHERE studentid = ? AND videoid IN (SELECT product_id FROM week_details WHERE week_id = ? AND type = ?)");
    $stmt->execute(array($stdid,$weekid,"video"));
    
    $stmt=$con->prepare("UPDATE students SET resets=resets+1 WHERE id = ? ");
    $stmt->execute(array($stdid));
    
    header("Location:weekstudents.php?id=$weekid");
    exit();
}
 
 
}



?>

<!doctype html>
<html class="no-js" lang="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>E-Learning System | Weeks Students</title>
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
    <!-- Modernize js -->
    <script src="js\modernizr-3.6.0.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@600&display=swap" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">

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
 

            <?php  include_once 'sidebar.php'; ?>
            <!-- Sidebar Area End Here -->
            <div class="dashboard-content-one">
                <!-- Breadcubs Area Start Here -->

        
                <div class="breadcrumbs-area">
                    <h3>Weeks</h3>
                    <ul>
                        <li>
                            <a href="index3.php">home</a>
                        </li>
                        <li>Weeks Students</li>
                    </ul>
                </div>
                <!-- Breadcubs Area End Here -->
                <!-- Teacher Table Area Start Here -->
                <div class="card height-auto">
                    <div class="card-body">
                        <div class="heading-layout1">
                            <div class="item-title">
                                <h3>All Weeks Students Data</h3>
                            </div>
                        </div>
                    
                        <!-- msg of delete--> 
                        <?php if(isset($_GET['msg'])){?>
                            <div class="alert alert-success  alert-dismissible fade show settingalert">
                                <?php echo check($_GET['msg']);?>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div> 
                        <?php } ?>

                        <!--mssg for edit-->
                        <?php if(isset($_GET['mssg'])){?>
                            <div class="alert alert-success  alert-dismissible fade show settingalert">
                                <?php echo check($_GET['mssg']);?>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        <?php } ?>

                        <!--msgg for adding-->
                        <?php if(isset($_GET['msgg'])){?>
                        <div class="alert alert-success  alert-dismissible fade show settingalert">
                            <?php echo check($_GET['msgg']);?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <?php }?>

    
               

                       
                        <div class="table-responsive">
                            <table class="table display data-table text-nowrap" id="myTabledata">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Phone</th>
                                        <th>Parent Phone</th>
                                        <th>No. of resets</th>
                                        <th>Controls</th>
                                      
                                    </tr>
                                </thead>
                                <tbody>

                                      <?php foreach($weeks as $week){
                                      
                                        $stmt=$con->prepare("SELECT * FROM students WHERE id=?");
                                        $stmt->execute(array($week['studentid']));
                                     
                                        $weekdata=$stmt->fetch();
                                      
                                      
                                      ?>
                                    <tr>
                                        <td><?php echo $weekdata['id'];  ?></td>
                                        <td><?php echo $weekdata['name'];  ?></td>
                                        <td><?php echo $weekdata['phone'];  ?></td>
                                        <td><?php echo $weekdata['phone2'];  ?></td>
                                        <td><?php echo $weekdata['resets'];  ?></td>
                                        <td><a href="weekstudents.php?do=reset&stdid=<?php echo $weekdata["id"]; ?>&weekid=<?php echo $week["product_id"] ?>"  class="fw-btn-fill btn-gradient-yellow mr-1">Reset</a></td>
                                    </tr>            
                                
                                     <?php } ?>
                             
                                </tbody>
                            </table>
                           
                        </div>
                    </div>
                </div>
                
                <!-- Breadcubs Area End Here -->
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
    <script>
        $(document).ready( function () {
            $('#myTabledata').DataTable({
            dom: 'Blfrtip',
                buttons: [
                  'excel'
                ],
            "lengthMenu": [ [10, 25, 50, 100,-1], [10, 25, 50, 100,"All"] ]
                
            });
        
        });
    </script>
    <script>
         var elems1 = document.getElementsByClassName('confirmation');
        var confirmIt = function (e) {
            if (!confirm('Are You Sure to Delete')) e.preventDefault();
        };
        for (var i = 0, l = elems1.length; i < l; i++) {
            elems1[i].addEventListener('click', confirmIt, false);
        }
    </script>


</body>

</html>