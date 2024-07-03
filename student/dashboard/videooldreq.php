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
function check($var){
  return trim(strip_tags(stripcslashes($var)));
}

if(isset($_SESSION['adminname']))
{
$subject=$_SESSION['subject'];
$name=$_SESSION['adminname'];
$stmt=$con->prepare("SELECT * FROM video_old_requests");
$stmt->execute();
$oldvideos=$stmt->fetchAll();
}

 if(isset($_POST['approve'])){
 
    $id=check($_POST['id']);
    $date=check($_POST['date']);
    $videoid=$_POST['videoid'];
    $studentid=$_POST['studentid'];
    
    $stmt=$con->prepare("DELETE FROM videocount WHERE studentid=? AND videoid=?");     
    $stmt->execute(array($studentid,$videoid));

    $stmt=$con->prepare("UPDATE  video_old_requests SET status=?, date=?  WHERE id=?");     
    $stmt->execute(array("1",$date,$id));
    header("Location:videooldreq.php?mssg=".urlencode("Done Approve Video requests"));
    exit();
  }
  
if(isset($_GET['do'])){
$do = $_GET['do'];

    if($do == 'disapprove'){
        if($_GET['id']){
           $id = $_GET['id'];
        }
    $stmt=$con->prepare("UPDATE  video_old_requests SET status=?, date=?  WHERE id=?");     
    $stmt->execute(array("0","0000-00-00",$id));
    header("Location:videooldreq.php?mssg=".urlencode("Done DisApprove Video requests"));
    exit();
  }
}



 if (!isset($_SESSION['adminname'])) {
           header("Location:index.php?dmsg=".urlencode("please login first"));
exit();
 }
$stmt=$con->prepare("SELECT name FROM subjects WHERE id = ? ");   
$stmt->execute(array($_SESSION['subject']));
$subject=$stmt->fetch();

$stmt=$con->prepare("SELECT teacher_id FROM subjects WHERE id = ? ");
$stmt->execute(array($_SESSION['subject']));
$t=$stmt->fetch();

$stmt=$con->prepare("SELECT fullname FROM admins WHERE id = ? ");
$stmt->execute(array($t['teacher_id']));
$teacher=$stmt->fetch();

$stmt=$con->prepare("SELECT edu_id FROM subjects WHERE id = ? ");
$stmt->execute(array($_SESSION['subject']));
$edu=$stmt->fetch();

$stmt=$con->prepare("SELECT name FROM educationallevels WHERE id = ? ");
$stmt->execute(array($edu['edu_id']));
$edulevel=$stmt->fetch();
?>

<!doctype html>
<html class="no-js" lang="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>E-Learning System | Videos</title>
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
                                <li>Videos_Old_Requests</li>
                            </ul>
                        </div>
                        <div class="col-lg-4">
                            <div class="dashboard-summery-one subject">
                                <div class="item-content">
                                    <div class="item-number"><?php echo $subject['name']; echo " / ". $teacher['fullname'];  ?></div>
                                     <div class="item-number"><?php echo $edulevel['name'];  ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Breadcubs Area End Here -->
                <!-- Teacher Table Area Start Here -->
                <div class="card height-auto">
                    <div class="card-body">
                        <div class="heading-layout1">
                            <div class="item-title">
                                <h3>All Videos Data</h3>
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

    

                       
                        <div class="table-responsive">
                            <table class="table display data-table text-nowrap" id="myTable">
                                <thead>
                                    <tr>
                                       
                                       <th>Studend_ID</th>
                                        <th>Student_Name</th>
                                        <th>Video_Name</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>Controls</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                      <?php if(!empty($oldvideos)){ 
                                          foreach($oldvideos as $oldvideo){ 
                                                $stmt=$con->prepare("SELECT name FROM students WHERE id=?");
                                                $stmt->execute(array($oldvideo['studentid']));
                                                $stus=$stmt->fetch();
                                                
                                                $stmt=$con->prepare("SELECT name FROM videos WHERE id=?");
                                                $stmt->execute(array($oldvideo['videoid']));
                                                $videoname=$stmt->fetch();
                                                    ?>
                                                    <tr>
                                        <td><?php echo "#".$oldvideo['studentid'];  ?></td>
                                        <td><?php echo $stus['name'];  ?></td>
                                        <td><?php echo $videoname['name'];  ?></td>
                                        <?php if($oldvideo['status']=="0"){ ?>
                                        <td> <button type="button" class="btn btn-warning btn-lg btn-block">Pending</button>
                                        </td>
                                        <?php }elseif($oldvideo['status']==1){?>
                                         <td><button type="button" class="btn btn-success btn-lg btn-block">Approved</button></td>
                                            <?php } ?>
                                        <td><?php echo $oldvideo['date'];  ?></td>
                                        <td>
                                        <?php if($oldvideo['status']=="0") { ?>
                                        <form method="post" action="videooldreq.php">
                                            
                                            <input type="text"  class="form-control" name="id"  value="<?php echo $oldvideo['id'];?>" hidden>
                                            <input type="text"  class="form-control" name="videoid"  value="<?php echo $oldvideo['videoid'];?>" hidden>
                                            <input type="text"  class="form-control" name="studentid"  value="<?php echo $oldvideo['studentid'];?>" hidden>

                                            <input type="date" placeholder="Enter Date" class="form-control" name="date" required>
                                    
                                            <button type="submit" name="approve" class="fw-btn-fill btn-gradient-yellow">Approve</button>
                                        </form>
                                       <?php }elseif($oldvideo['status']=="1"){ ?>
                                        <a class="fw-btn-fill btn-gradient-yellow" href="videooldreq.php?do=disapprove&id=<?php echo $oldvideo["id"]; ?>">Disapprove </a>
                                          <?php }?>
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
      <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready( function () {
            $('#myTable').dataTable();
        
        });
    </script>

</body>

</html>
<?php ob_end_flush();?>