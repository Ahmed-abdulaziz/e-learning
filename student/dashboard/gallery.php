<?php    
ob_start();
ini_set('session.cookie_lifetime', 60 * 60 * 24 );
ini_set('session.gc_maxlifetime', 60 * 60 * 24 );
include_once 'connect.php';
ob_start();
 session_start();
 if(!isset($_SESSION['adminname'])){
    header("Location:index.php?msg=".urlencode("Please Login First"));
    exit();
}
$stmt= $con->prepare ("SELECT * FROM gallery");
$stmt->execute();
$inputs=$stmt->fetchAll();

if(isset($_SESSION['adminname']))
{
if(isset($_GET['do'])){
$do=$_GET['do'];
if($do=='delete'){
if($_GET['id']){ $id=$_GET['id']; }
$stmt= $con->prepare ("SELECT * FROM gallery WHERE id=?");
$stmt->execute(array($id));
$inputs=$stmt->fetch();
$image=$inputs['image'];
if (file_exists('img/profile/'.$image)) {
    unlink('img/profile/'.$image);
}
$stmt=$con->prepare("DELETE FROM gallery WHERE id=? ");
$stmt->execute(array($id));
header("Location:gallery.php?msg=".urlencode("Done Deleteing Photo"));
exit();
}}}

 if (!isset($_SESSION['adminname'])) {
           header("Location:index.php?dmsg=".urlencode("please login first"));
exit();
 }
?>

<!doctype html>
<html class="no-js" lang="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>E-Learning System | Gallery</title>
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
                    <div class="row">
                        <div class="col-lg-8">
                            <h3>Admin Dashboard</h3>
                            <ul>
                                <li>
                                    <a href="statistics.php">Statistics</a>
                                </li>
                                <li>Add Photo</li>
                            </ul>
                        </div>
                        <!-- <div class="col-lg-4">
                            <div class="dashboard-summery-one subject">
                                <div class="item-content">
                                    <div class="item-number"><?php echo $subject['name']; echo " / ". $teacher['fullname'];  ?></div>
                                     <div class="item-number"><?php echo $edulevel['name'];  ?></div>
                                </div>
                            </div>
                        </div> -->
                    </div>
                </div>
                <!-- Breadcubs Area End Here -->
                <!-- Teacher Table Area Start Here -->
                <div class="card height-auto">
                    <div class="card-body">
                        <div class="heading-layout1">
                            <div class="item-title">
                                <h3>All Gallery Data</h3>
                            </div>
                        </div>
                    
                        <!-- msg of delete--> 
                        <?php if(isset($_GET['msg'])){?>
                            <div class="alert alert-success  alert-dismissible fade show settingalert">
                                <?php echo $_GET['msg'];?>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div> 
                        <?php } ?>

                        <!--mssg for edit-->
                        <?php if(isset($_GET['mssg'])){?>
                            <div class="alert alert-success  alert-dismissible fade show settingalert">
                                <?php echo $_GET['mssg'];?>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        <?php } ?>

                        <!--msgg for adding-->
                        <?php if(isset($_GET['msgg'])){?>
                        <div class="alert alert-success  alert-dismissible fade show settingalert">
                            <?php echo $_GET['msgg'];?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <?php }?>

    
                                            
                        <form class="mg-b-20">
                            <div class="row gutters-8">
                             
                                <div class="col-1-xxxl col-xl-2 col-lg-3 col-12 form-group">
                                    <a class="fw-btn-fill btn-gradient-yellow" href="galleryadd.php">Add Photo</a>
                                </div>

                            </div>
                        </form>

                       
                        <div class="table-responsive">
                            <table class="table display data-table text-nowrap">
                                <thead>
                                    <tr>
                                       
                                       <th>ID</th>
                                        <th>Image</th>
                                        <th>Link</th>
                                        <th>Control</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                      <?php if(isset($_SESSION['adminname'])){ foreach($inputs as $input){ ?>
                                        
                                        <td><?php echo $input['id']  ?></td>
                                        <td>  <img src="
                                            <?php  echo "img/profile/".$input['image'];
                                                    ?>
                                            "></td>
                                            <td><?php echo $input['link']  ?></td>
                                        <td>
                                            <a class="fw-btn-fill btn-gradient-yellow" href="galleryedit.php?do=edit&id=<?php echo $input["id"]; ?>">Edit </a>
                                            <a class="fw-btn-fill btn-gradient-yellow" href="gallery.php?do=delete&id=<?php echo $input["id"] ?>">Delete</a>
                                        </td>                               
                                    </tr>                                           
     <?php }} ?>
                             
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

</body>

</html>
<?php ob_end_flush();?>