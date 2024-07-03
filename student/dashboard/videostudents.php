<?php 

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
if(isset($_SESSION['subject'])) $subjectid=$_SESSION['subject'];

$name=$_SESSION['adminname'];
$stmt=$con->prepare("SELECT * FROM students WHERE id IN (SELECT studentid FROM studentssubjects WHERE subjectid = ? AND approve = ? AND classid IN (SELECT id from classes WHERE type2 = ?))");
$stmt->execute(array($subjectid,"1","online"));
$admins=$stmt->fetchAll();

}
if(isset($_GET['do'])){

    $do = $_GET['do'];
if(isset($_GET['id'])) $id = $_GET['id'];
if(isset($_GET['m'])) $m = $_GET['m'];

if($do=='approve'){
    

    $stmt=$con->prepare("INSERT INTO video_request (month,studentid) VALUES (:month,:studentid)");
    $stmt->execute(array('month'=>$m,'studentid'=>$id));


    // header("Location:videostudents.php?msg=".urlencode("Done approve Student"));
    // exit();
}elseif($do=='disapprove'){
    $stmt=$con->prepare("DELETE FROM video_request WHERE month=? AND studentid=?");
    $stmt->execute(array($m,$id));
}

}


if(isset($_POST['reopen'])){
    $studentid=$_POST['studentid'];
    $videoid=$_POST['videoid'];
    
    $stmt=$con->prepare("DELETE FROM videocount WHERE videoid=? AND studentid=?");
    $stmt->execute(array($videoid,$studentid));
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
    <title>E-Learning System | Student Approve</title>
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
                                <li>Approved Students</li>
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
                                <h3>All Students Data</h3>
                            </div>
                         
                        </div>
                           <?php if(isset($_GET['msg'])){?>
                                        <div class="alert alert-success  alert-dismissible fade show settingalert">
                                            <?php echo $_GET['msg'];?>
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <?php }?>
                        <form class="mg-b-20">
                            <div class="row gutters-8">
                             
                                <!-- <div class="col-1-xxxl col-xl-2 col-lg-3 col-12 form-group">
                                   <a href="addstudent.php" class="fw-btn-fill btn-gradient-yellow">Add Student</a>
                                </div> -->
                            </div>
                        </form>


                        <div class="table-responsive">
                            <table class="table display data-table text-nowrap" id="myTable">
                                <thead>
                                    <tr>       
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Phone</th>
                                        <th>Status</th>
                                        <th>Controls</th>
                                        <th>Reopen</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <?php if(isset($_SESSION['adminname'])){
                                            
                                                $stmt=$con->prepare("SELECT * FROM videos WHERE month=?");
                                                $stmt->execute(array($_GET['m']));
                                                $videos=$stmt->fetchAll();
                                                
                                            foreach ($admins as $admin){ 
                                        
                                            $stmt=$con->prepare("SELECT * FROM video_request WHERE month = ? AND studentid = ?");
                                            $stmt->execute(array($_GET['m'],$admin['id']));
                                            $check=$stmt->fetch();
                                        
                                        ?>
                                        <td><?php echo "#".$admin['id'];  ?></td>
                                        <td><?php echo $admin['name'];  ?></td>
                                        <td><?php echo $admin['phone'];  ?></td>
                                        <td>
                                            <?php if(empty($check)){?>
                                                <button  class="btn btn-warning btn-lg">Pending</button>
                                            <?php }else{?>
                                                <button  class="btn btn-success btn-lg">approved</button>
                                            <?php }?>
                                        </td>
                                        <td>
                                            <?php if(empty($check)){?>
                                                <a href="videostudents.php?id=<?php echo $admin['id']; ?>&m=<?php echo $_GET['m'];?>&do=approve"  class="fw-btn-fill btn-gradient-yellow">approve</a>
                                            <?php }else{?>
                                                <a href="videostudents.php?id=<?php echo $admin['id']; ?>&m=<?php echo $_GET['m'];?>&do=disapprove"  class="fw-btn-fill btn-gradient-yellow">disapprove</a>
                                            <?php }?>
                                        </td>
                                        
                                        <td>
                                            <form method="post" style="display: inline;">
                                                <input hidden value="<?php echo $admin['id']; ?>" name="studentid">
                                                <select name="videoid" class="form-control reopen">
                                                    <?php 
                                                       foreach($videos as $video){ ?>
                                                           <option value="<?php echo $video['id']; ?>"><?php echo $video['name']; ?></option>
                                                      <?php } ?>
                                                </select>
                                                <button type="submit" name="reopen" class="fw-btn-fill btn-gradient-yellow">Reopen</button>
                                            </form>
                                        </td>
                                    </tr>
                                    <?php }}   ?>
                                </tbody>
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
 <?php include_once "footer.php";?>
     <div class="modal fade" id="imagemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">              
          <div class="modal-body">
          	<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            <img src="" class="imagepreview" style="width: 100%;" >
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
    <script>
        $(function() {
    		$('.pop').on('click', function() {
    			$('.imagepreview').attr('src', $(this).find('img').attr('src'));
    			$('#imagemodal').modal('show');   
    		});		
        });
    </script>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready( function () {
            $('#myTable').dataTable();
        
        });
    </script>

</body>

</html>