<?php    
ob_start();
include_once 'connect.php';
ob_start();
session_start();
date_default_timezone_set('Africa/Cairo');
if(!isset($_SESSION['adminname'])){
    header("Location:index.php?msg=".urlencode("Please Login First"));
    exit();
}
if(isset($_GET['id'])){
 $id = $_GET['id'];

 $stmt=$con->prepare("SELECT * FROM studentssubjects WHERE classid=? ");
    $stmt->execute(array($id));
    $students=$stmt->fetchAll();
}

if(isset($_GET['do'])){
 $doo = $_GET['do'];
if($doo=='done'){

 $stuid = $_GET['stuid'];
 $classid = $_GET['classid'];
 
    $stmt=$con->prepare("SELECT * FROM students WHERE id =? ");
    $stmt->execute(array($stuid));
    $student=$stmt->fetch();
    $date=date("Y-m-d");
    $time=date("h:i:sa");

    $stmt=$con->prepare("INSERT INTO attendance (studentid,classid,date)
         VALUES(:studentid,:classid,:date)");
    $stmt->execute(array('studentid'=>$stuid,'classid'=>$classid,'date'=>$date));
    $msg =  "أكاديمية" ." E-Learning System %0a ". "   برجاء العلم ان الطالب ".$student['name']."حضر حصة يوم ".$date." في الساعة ".$time;
     
    $ch = curl_init();
    $url= "https://smssmartegypt.com/sms/api/?username=msuahmed777@gmail.com&password=Msu12345&sendername=MrAhmedSaid&mobiles=2".$student['phone1']."&message=".urlencode($msg);
    curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $server_output = curl_exec($ch);
    curl_close ($ch);
     
    header("Location:attendancestudent.php?id=$classid&msgg=".urlencode("Student Attend Successfully"));
    exit();
}}

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
                                        <div class="alert alert-danger  alert-dismissible fade show settingalert">
                                            <?php echo $_GET['msgg'];?>
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                       <?php } ?>
                      
                                       <form class="mg-b-20" method="post" action="student_profile.php">
                                            <div class="row gutters-8">
                                            
                                                <div class="col-5-xxxl col-xl-2 col-lg-3 col-12 form-group d-flex">
                                                    
                                                <input type="number" name="studentid" class="form-control mx-2" placeholder="Insert Id Of Student" />
                                                <button class="fw-btn-fill btn-gradient-yellow" name="show" >Show</button>
                                                </div>

                                            </div>
                                        </form>
                        <div class="table-responsive">
                            <table class="table display data-table text-nowrap" id="myTable">
                                <thead>
                                    <tr>
                                   <th>ID</th>
                                   <th>Name</th>
                                   <th>Status</th>
                                   <th>Controls</th>
                                    </tr>
                                </thead>
                                <tbody>
                                          <?php foreach ($students as $student){

                                            $stmt=$con->prepare("SELECT * FROM students WHERE id=? ");
                                            $stmt->execute(array($student['studentid']));
                                            $studs=$stmt->fetch(); 
                                        ?>
                                    <tr>
                                      
                                  
                                        <td><?php echo $studs['id'];  ?></td>
                                        <td><?php echo $studs['name'];  ?></td>
                                        <td>
                                            <?php 
                                                 $today=date("Y-m-d");;
                                                 $stmt=$con->prepare("SELECT * FROM attendance WHERE studentid=? AND classid=? AND date = ?");
                                                 $stmt->execute(array($studs['id'],$id,$today));
                                                 $stds=$stmt->fetch();
                                                 if(!empty($stds)){
                                                     
                                                 
                                            ?>
                                            <button type="button" class="btn btn-success btn-lg btn-block">Attended</button>
                                            <?php }else{?>
                                            <button type="button" class="btn btn-warning btn-lg btn-block">Pending</button>
                                            <?php }?>
                                        </td>
                                        <td>
                                         
                                  
                                            <a href="attendancestudent.php?do=done&stuid=<?php echo $studs["id"]; ?>&classid=<?php echo $id; ?>"  class="fw-btn-fill btn-gradient-yellow">Attend</a>
                                        </td>
                                    </tr>
                                    <?php }   ?>
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