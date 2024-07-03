<?php    
ob_start();
include_once 'connect.php';
ob_start();
session_start();
if(!isset($_SESSION['adminname'])){
    header("Location:index.php?msg=".urlencode("Please Login First"));
    exit();
}
if(isset($_SESSION['adminname']))
{

// $name=$_SESSION['adminname'];
// $stmt=$con->prepare("SELECT * FROM students ORDER BY email");
// $stmt->execute();
// $students=$stmt->fetchAll();


$id=$_GET['id'];

$stmt=$con->prepare("SELECT min_degree FROM exams WHERE id=?");
$stmt->execute(array($id));
$examm=$stmt->fetch();

$stmt=$con->prepare("SELECT * FROM studentexam WHERE result <? AND examid=?");
$stmt->execute(array($examm['min_degree'],$id));
$exams=$stmt->fetchAll();

if(isset($_GET['do'])){
 $do = $_GET['do'];
if($do=='pass'){
$id=$_GET['id'];
$stuid=$_GET['stuid'];

$stmt=$con->prepare("UPDATE studentexam SET pass=? WHERE studentid = ? AND examid =?");
$stmt->execute(array("1",$stuid,$id));
header("Location:stuexamfail.php?msg=".urlencode("Done Pass Student"));
exit();
}elseif($do=='send-msgs'){


$id=$_GET['id'];

$stmt=$con->prepare("SELECT min_degree , name FROM exams WHERE id=?");
$stmt->execute(array($id));
$examm=$stmt->fetch();
$exname = $examm['name'];
$stmt=$con->prepare("SELECT * FROM studentexam WHERE result <? AND examid=? AND pass = 0");
$stmt->execute(array($examm['min_degree'],$id));
$exams=$stmt->fetchAll();

foreach ($exams as $exam){ 
                                        
        $stmt=$con->prepare("SELECT * FROM students WHERE id=?");
        $stmt->execute(array($exam['studentid']));
        $student=$stmt->fetch();
        $studentname = $student['name'];
        
        
        $stmt=$con->prepare("SELECT * FROM studentexam WHERE examid=? AND studentid=?");
        $stmt->execute(array($id,$student['id']));
        $min=$stmt->fetch();
        $degree = $min['result'];
                                        
        $msg = "مرحبا - لقد رسب $studentname فى امتحان $exname وحصل على درجه $degree  ";
        
        $ch = curl_init();
        $url= "https://smssmartegypt.com/sms/api/?username=easyphysics&password=easyphysics@1166&sendername=Academy&mobiles=2".$student['phone2']."&message=".urlencode($msg);
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec($ch);
        curl_close ($ch);
    
}

    header("Location:stuexamfail.php?msg=".urlencode("Done Send Messges To parent Students"));
    exit();
}



}
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


            <?php  include_once 'sidebar.php'; ?>
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
                              <?php if(isset($_GET['msg'])){?>
                                <div class="alert alert-success  alert-dismissible fade show settingalert">
                                    <?php echo $_GET['msg'];?>
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                               <?php } ?>
                                     
                        <!-- <a href="addstudent.php"  class="fw-btn-fill btn-gradient-yellow">Add Student</a> -->
                        <!--<a href="allstudents.php?do=approveall"  class="fw-btn-fill btn-gradient-yellow">Approve All</a>-->
                        <!--<a href="allstudents.php?do=disapproveall"  class="fw-btn-fill btn-gradient-yellow">Disapprove All</a>-->
                        <!--<a href="?do=send-msgs&id=<?php// echo $id; ?>"  class="fw-btn-fill btn-gradient-yellow">Send Messages To Parent</a>-->

                        <div class="table-responsive">
                            <table class="table display data-table text-nowrap" id="myTable">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                  
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Controls</th>
                         
                                    
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                      
                                        <?php foreach ($exams as $exam){ 
                                        
                                        $stmt=$con->prepare("SELECT * FROM students WHERE id=?");
                                        $stmt->execute(array($exam['studentid']));
                                        $student=$stmt->fetch();
                                        
                                        $stmt=$con->prepare("SELECT pass FROM studentexam WHERE examid=? AND studentid=?");
                                        $stmt->execute(array($id,$student['id']));
                                        $passstu=$stmt->fetch();
                                        ?>
                                        <tr>
                                        <td><?php echo $student['id'];  ?></td>
                                      
                                        <td><?php echo $student['name'];  ?></td>
                                        <td><?php echo $student['email'];  ?></td>
                                  
                                        <td><?php echo $student['phone2'];  ?></td>
                                     
                                     <td>
                                            <?php if($passstu['pass']==0){ ?>
                                    <a href="stuexamfail.php?do=pass&id=<?php echo $id; ?>&stuid=<?php echo $student['id']; ?>"  class="fw-btn-fill btn-gradient-yellow">Pass</a>
                                       <?php }  ?>
                                     </td>
                                       </tr>
                                        <?php }  ?>

                                  
                             
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

    <script type="text/javascript">
        var elems = document.getElementsByClassName('confirmation');
        var confirmIt = function (e) {
            if (!confirm('هل أنت متاكد من إزالة هذا المستخدم؟')) e.preventDefault();
        };
        for (var i = 0, l = elems.length; i < l; i++) {
            elems[i].addEventListener('click', confirmIt, false);
        }
    </script>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script>
  
    </script>

</body>

</html>
<?php ob_end_flush();?>