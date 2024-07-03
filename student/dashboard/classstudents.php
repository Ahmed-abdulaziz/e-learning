<?php    
ob_start();
include_once 'connect.php';
ob_start();
session_start();
if(!isset($_SESSION['adminname'])){
    header("Location:index.php?msg=".urlencode("Please Login First"));
    exit();
}

if(isset($_GET['classid'])){
$classid=$_GET['classid'];
$stmt=$con->prepare("SELECT * FROM studentssubjects WHERE classid=? ");
$stmt->execute(array($classid));
$students=$stmt->fetchAll(); 
}
//control students and show class students
if(isset($_GET['do'])){
$do = $_GET['do'];
$id = $_GET['id'];
if($do=='approve'){
$stmt=$con->prepare("UPDATE studentssubjects SET approve=? WHERE studentid = ?");
$stmt->execute(array("1",$id));

$stmt=$con->prepare("UPDATE  students SET approve=? WHERE id = ?");
$stmt->execute(array("1",$id));
header("Location:classstudents.php?classid=$classid&msg=".urlencode("Done Approve Student"));
exit();
}elseif($do=='disapprove'){
        $stmt=$con->prepare("UPDATE studentssubjects SET approve=? WHERE studentid = ?");
        $stmt->execute(array("2",$id));

        $stmt=$con->prepare("UPDATE  students SET approve=? WHERE id = ?");
        $stmt->execute(array("2",$id));
        header("Location:classstudents.php?classid==$classid&msg=".urlencode("Done Disapprove Student"));
        exit();
}elseif($do=='delete'){
    $stmt=$con->prepare("SELECT * FROM students WHERE id = ?");
    $stmt->execute(array($id));
    $student=$stmt->fetch();

    //delete student photo from uploaded images if its not empty
    if(!empty($student['image']))
    unlink("../img/profile/".$student['image']);

    //delete student data
    $stmt=$con->prepare("DELETE FROM students WHERE id = ?");
    $stmt->execute(array($id));

    //delete student subjects
    $stmt=$con->prepare("DELETE FROM studentssubjects WHERE studentid = ?");
    $stmt->execute(array($id));

    header("Location:classstudents.php?classid=$classid&?msg=".urldecode("Student deleted successfully"));
    exit();
}
}



//send messages to students
if(isset($_POST['sendmsg'])){
   $msg=$_POST['msg'];
   $ids=$_POST['stdsids'];

   $type=1;
   $stmt=$con->prepare("INSERT INTO notifications (msg,type) VALUES (:msg,:type) ");
   $stmt->execute(array(
       "msg"=> $msg,
       "type"=> $type
   ));
   $last_id = $con->lastInsertId();
   foreach ($ids as $id){
       $stmt=$con->prepare("INSERT INTO std_noti (studentid,msg_id) VALUES (:stdid,:msgid) ");
       $stmt->execute(array(
           "stdid"=> $id,
           "msgid"=>  $last_id
       ));
   }


    header("Location: showmsgs.php?msg=".urlencode("Done Pushing Message"));
    exit();

}

?>

<!doctype html>
<html class="no-js" lang="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>E-Learning System | Students </title>
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
            <!-- Sidebar Area Start Here -->


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
                                <li>All Students</li>
                            </ul>
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
                            
                         <form class="mg-b-20" method="post">
                            <div class="row gutters-8">
                                <div class="col-3-xxxl col-xl-3 col-lg-3 col-12 form-group">
                                    <input type="text" placeholder="Type Message Here ..." class="form-control" name="msg">
                                </div>
    
                                <div class="col-1-xxxl col-xl-2 col-lg-3 col-12 form-group">
                                    <button type="submit" name="sendmsg" class="fw-btn-fill btn-gradient-yellow">SEND</button>
                                </div>
                            </div>

                        
                              <?php if(isset($_GET['msg'])){?>
                                        <div class="alert alert-success  alert-dismissible fade show settingalert">
                                            <?php echo $_GET['msg'];?>
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                       <?php } ?>
                                    <!--msgg for Disaprov-->
                                    <?php if(isset($_GET['msgg'])){?>
                                        <div class="alert alert-success  alert-dismissible fade show settingalert">
                                            <?php echo $_GET['msgg'];?>
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                       <?php } ?>
                      

                        <div class="table-responsive">
                            <table class="table display data-table text-nowrap">
                                <thead>
                                    <tr>
                                    <th>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input checkAll">
                                            <label class="form-check-label">Code</label>
                                        </div>
                                    </th>
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <th>Status</th>
                                    <th>Controls</th>
                                    </tr>
                                </thead>
                                <tbody>
                                          <?php foreach ($students as $student){
                    
                                            $stmt=$con->prepare("SELECT * FROM students WHERE id=? ");
                                            $stmt->execute(array($student['studentid']));
                                            $studs=$stmt->fetch(); 
                                            
                                            $stmt=$con->prepare("SELECT * FROM studentssubjects WHERE classid=? AND studentid=? ");
                                            $stmt->execute(array($student['classid'],$student['studentid']));
                                            $status=$stmt->fetch(); 
                                        ?>
                                    <tr>
                                      
                                        <td>
                                            <div class="form-check">
                                                <input type="checkbox" value="<?php echo $studs['id'];?>" class="form-check-input" name="stdsids[]">
                                                <label class="form-check-label">#<?php echo $studs['id'];  ?></label>
                                            </div>
                                        </td>
                                        <td><?php echo $studs['name'];  ?></td>
                                        <td><?php echo $studs['phone'];  ?></td>
                                      
            
                                    <?php if($status['approve']==0){?>
                                        <td><button type="button" class="btn btn-warning btn-lg btn-block">معلق</button></td>
                                    <?php }elseif($status['approve']==1){?>
                                        <td><button type="button" class="btn btn-success btn-lg btn-block">مقبول</button></td>
                                    <?php }elseif($status['approve']==2){?>
                                        <td><button type="button" class="btn btn-danger btn-lg btn-block">مرفوض</button></td>
                                    <?php } elseif($status['approve']==3){?>
                                        <td><button type="button" class="btn btn-primary btn-lg btn-block">تم الحجز</button></td>
                                    <?php } ?>
                                    
                                    <td>
                                         <a href="classstudents.php?do=approve&id=<?php echo $studs["id"] ?>&classid=<?php echo $_GET['classid'] ?>"  class="fw-btn-fill btn-gradient-yellow">Approve</a>

                                         <a href="classstudents.php?do=disapprove&id=<?php echo $studs["id"] ?>&classid=<?php echo $_GET['classid'] ?>"  class="fw-btn-fill btn-gradient-yellow">Disapprove </a>
                                            
                                         <a href="studentedit.php?do=ediit&id=<?php echo $studs["id"] ?>&classid=<?php echo $_GET['classid'] ?>" target="_blank"  class="fw-btn-fill btn-gradient-yellow">Edit</a>

                                         <a href="classstudents.php?do=delete&id=<?php echo $studs["id"] ?>&classid=<?php echo $_GET['classid'] ?>"  class="fw-btn-fill btn-gradient-yellow confirmation">Delete</a>
                                    </td>
                                    
                                    </tr>
                                    <?php }   ?>
                                </tbody>
                            </table>
                        </div>
                        </form>
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
    <script>
        $(function() {
    		$('.pop').on('click', function() {
    			$('.imagepreview').attr('src', $(this).find('img').attr('src'));
    			$('#imagemodal').modal('show');   
    		});		
        });
    </script>

</body>

</html>
<?php ob_end_flush();?>