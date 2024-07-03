<?php 

include_once 'connect.php';
ob_start();
 session_start();
 if(!isset($_SESSION['adminname'])){
    header("Location:index.php?msg=".urlencode("Please Login First"));
    exit();
}
function check($var){
    return htmlentities(trim(strip_tags(stripcslashes($var))));
  }
$name=$_SESSION['adminname'];
$stmt=$con->prepare("SELECT type FROM admins WHERE name=?");
$stmt->execute(array($name));
$result=$stmt->fetch();
$type=$result['type'];
if($type!="super admin" && $type!="admin"){
header("Location:index3.php?msg=You Not Have A Permission");
}

if(isset($_SESSION['adminname']))
{$name=$_SESSION['adminname'];

    if(isset($_GET['subjectid'])){

        $subjectid =check($_GET['subjectid']);
        $quizid= check($_GET['quizid']);
        $stmt=$con->prepare("SELECT * FROM studentssubjects WHERE subjectid = $subjectid AND studentid IN (SELECT id FROM students WHERE approve = ?)  ");
        $stmt->execute(array("1"));
        $students=$stmt->fetchAll();

    }else{
        header("Location:quizes.php");
    }

if (!isset($_POST['token'])) {
$_SESSION['token'] = bin2hex(random_bytes(32));
$token = $_SESSION['token'];
}

if (!empty($_POST['token'])) {
    if (hash_equals($_SESSION['token'], $_POST['token'])) {
     // Proceed to process the form data
    
if(isset($_POST['save'])){

    $studentid = check($_POST['studentid']);
    $degree = check($_POST['degree']);
    $quizid = check($_GET['quizid']);

    $stmt=$con->prepare("INSERT INTO quizstudent (studentid,quizid,degree) VALUES(:studentid ,:quizid,:degree)");
    $stmt->execute(array('studentid'=>$studentid,'quizid'=>$quizid,'degree'=>$degree));



    $_SESSION['token'] = bin2hex(random_bytes(32));
    $token = $_SESSION['token'];
   
}


}} else {
    error_log("CSRF Failed from file quizshow.php", 0);
}
}



?>

<!doctype html>
<html class="no-js" lang="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>E-Learning System  | Monthly exams</title>
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
</head>

<body>
    <!-- Preloader Start Here -->
  
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
                    <h3>Monthly exams</h3>
                    <ul>
                        <li>
                            <a href="index3.php">Monthly exams</a>
                        </li>
                        <li>All Monthly exams</li>
                    </ul>
                </div>
                <!-- Breadcubs Area End Here -->
                <!-- Teacher Table Area Start Here -->
                <div class="card height-auto">
                    <div class="card-body">
                        <div class="heading-layout1">
                            <div class="item-title">
                                <h3>All Monthly exams Data</h3>
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

    
                                            
                        <form class="mg-b-20">
                            <div class="row gutters-8">
                             
                            

                            </div>
                        </form>

                       
                        <div class="table-responsive">
                            <table class="table display data-table text-nowrap" id="myTable">
                                <thead>
                                    <tr>
                                
                                        <th>Student ID</th>
                                        <th>Student name</th>
                                        <th>Degree</th>
                                        <th>Control</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                      <?php foreach($students as $student){ 
                                          $studentid = $student['studentid'];
                                          $subjectid = $student['subjectid'];
                                          $stmt=$con->prepare("SELECT * FROM quizstudent WHERE studentid = $studentid AND quizid = $quizid");
                                          $stmt->execute();
                                          $quizdegree=$stmt->fetch();

                                          $stmt=$con->prepare("SELECT name FROM students WHERE id = $studentid");
                                          $stmt->execute();
                                          $staudentname=$stmt->fetch();
                                          $stmt=$con->prepare("SELECT name FROM subjects WHERE id = $subjectid");
                                          $stmt->execute();
                                          $subject=$stmt->fetch();
                                          ?>
                                        
                                        <td><?php echo $student['studentid'];  ?></td>   
                                        <td><?php echo $staudentname['name'];?></td>
                                        <td>
                                            <?php
                                            
                                            $stmt=$con->prepare("SELECT final_degree FROM quizes WHERE id=?");
                                            $stmt->execute(array($quizid));
                                            $quiz=$stmt->fetch();
                                            
                                            if($quizdegree['degree'] === NULL){ ?>
                                                <button disabled class="btn-fill-lg btn btn-danger">Not corrected yet</button>
                                            <?php } else{
                                              

                                                echo $quizdegree['degree']." / ".$quiz['final_degree']; 
                                            }?> 
                                        </td>
                                        <td>
                                            <form method="post">
                                                <input type="hidden" name="token" value="<?php echo $token; ?>" />
                                                <input type="hidden" name="quizid" class="quizid" value="<?php echo check($_GET['quizid']); ?>" />
                                                <input type="number" name="degree" value="<?php echo $quizdegree['degree'];  ?>" class="form-control degree" >
                                                <input type="number" hidden name="finaldegree" value="<?php echo $quiz['final_degree'];  ?>" class="form-control finaldegree" >
                                                <input hidden type="number" name="studentid" value="<?php echo $student['studentid'];?>" class="form-control studentid" >
                                                <button name="save" class="fw-btn-fill btn-gradient-yellow savehw">Save </button>
                                            </form>
                                        </td>
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
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.print.min.js"></script>
    <script>
        $(document).ready( function () {
            $('#myTable').dataTable({
                dom: 'Bfrtip',
                buttons: [
                  'excel'
                ]
            });
        
        });

        
        $('.savehw').click(function(){
            var form = $(this).closest('form');
            var degree = form.find(".degree").val();
            var studentid = form.find(".studentid").val();
            var quizid = form.find(".quizid").val();
            var finaldegree = form.find(".finaldegree").val();
            
           $.ajax({
              type: "POST",
              url: "ajax/savehw.php",
              data: {
                  auth: "savehw",
                  degree: degree,
                  studentid: studentid,
                  quizid: quizid,
                  finaldegree:finaldegree
              },success:function(){
                  form.closest('td').prev('td').html(degree + " / "+ finaldegree);
              } 
           }); 
           return false;
        });
    </script>

</body>

</html>