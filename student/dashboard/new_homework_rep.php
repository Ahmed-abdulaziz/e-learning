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
if(isset($_SESSION['adminname']))
{
if(isset($_GET['id']))
$id=check($_GET['id']);

$stmt=$con->prepare("SELECT * FROM new_student_homework WHERE homeworkid = ? ORDER BY result ");
$stmt->execute(array($id));
$solvedhws=$stmt->fetchAll();


// --------------------------------------------------------------------------------
// Less Than MinPercentage
$stmt=$con->prepare("SELECT minPercentage FROM new_homeworks WHERE id=?");
$stmt->execute(array($id));
$homeworkReport=$stmt->fetch();
$homeworkReportMinPercentage = $homeworkReport['minPercentage'];

// Get Highest Degree
// $stmt=$con->prepare("SELECT MAX(result) AS maxStudent FROM new_student_homework WHERE homeworkid = ?");
// $stmt->execute(array($id));
// $homeworkMaxStudent=$stmt->fetch();
// $homework_MaxStudent_Deg = $homeworkMaxStudent['maxStudent'];

//Get Homework Normal Degree
$stmt=$con->prepare("SELECT q_no AS TotalDegree FROM new_homeworks WHERE id = ?");
$stmt->execute(array($id));
$homeworkNormalDeg=$stmt->fetch();
$homework_Normal_Degree = $homeworkNormalDeg['TotalDegree'];

$homeworkMaxPercentage = 100;
// ($homework_MaxStudent_Deg / $homework_Normal_Degree) * 100;

$stmt=$con->prepare("SELECT * FROM new_student_homework WHERE homeworkid=? AND ( (result / $homework_Normal_Degree) * 100 ) < ?");
$stmt->execute(array($id , $homeworkReportMinPercentage));
$solvedhwsLessMin=$stmt->fetchAll();


// --------------------------------------------------------------------------------
// --------------------------------------------------------------------------------
// Not Solved
$todayDate = date("Y-m-d");
$stmt=$con->prepare("SELECT * FROM new_homeworks WHERE id=? ");
$stmt->execute(array($id));
$homeworkEndDate=$stmt->fetch();
$deadLineDate = $homeworkEndDate['date1'];
$EndDate = $homeworkEndDate['date2'];

if($todayDate > $EndDate){
    $stmt=$con->prepare("SELECT * FROM new_student_homework WHERE homeworkid = ? AND result IS NULL");
    $stmt->execute(array($id));
    $solvedhwsNotSolved=$stmt->fetchAll();
}
// ----------------Less Than MinPercentage Actions-------------------------------------------------
if(isset($_POST['smsLess'])){
    $stdids=$_POST['stdsids'];
    foreach($stdids as $stdid){

        $stmt=$con->prepare("SELECT * FROM students WHERE id = ?");
        $stmt->execute(array($stdid));
        $student=$stmt->fetch();

        $msg = "Your Drgree is Less Than Minimum Percentage";

        $ch = curl_init();
        $url= "https://smsmisr.com/api/webapi/?username=WJ3G3LpJ&password=7rTdmYvGsZ&language=2&sender=".urlencode("Omar-sherbeni")."&mobile=2".$student['phone1']."&message=".urlencode($msg);
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec($ch);
        curl_close ($ch);


    }
    header("Location:rephw.php?msg=".urlencode("Done SMS Student"));
    exit();
}
if(isset($_POST['sleepLess'])){
    $stdids=$_POST['stdsids'];
    foreach($stdids as $stdid){
        
        $stmt=$con->prepare("UPDATE students SET approve = ? WHERE id = ?");
        $stmt->execute(array('-1',$stdid));
    }
    header("Location:rephw.php?msg=".urlencode("Done Sleep Students Accounts"));
    exit();
}
// ----------------Not Solved Actions-----------------------------------------------------------------------------
if(isset($_POST['smsNot'])){
    $stdids=$_POST['stdsids'];

    foreach($stdids as $stdid){

        $stmt=$con->prepare("SELECT * FROM students WHERE id = ?");
        $stmt->execute(array($stdid));
        $student=$stmt->fetch();

        $msg = "Your Drgree is Less Than Minimum Percentage";

        $ch = curl_init();
        $url= "https://smsmisr.com/api/webapi/?username=WJ3G3LpJ&password=7rTdmYvGsZ&language=2&sender=".urlencode("Omar-sherbeni")."&mobile=2".$student['phone1']."&message=".urlencode($msg);
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec($ch);
        curl_close ($ch);

    }
    header("Location:rephw.php?msg=".urlencode("Done SMS Student"));
    exit();
}

if(isset($_POST['sleepNot'])){
    $stdids=$_POST['stdsids'];
    foreach($stdids as $stdid){

        $stmt=$con->prepare("UPDATE students SET approve = ? WHERE id = ?");
        $stmt->execute(array('-1',$stdid));

    }
    header("Location:rephw.php?msg=".urlencode("Done Sleep Students Accounts"));
    exit();
}


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
    <title>E-Learning System | Solved Homeworks </title>
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
                                <li>Solved Homework</li>
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
                <div class="card">
                    <div class="card-body">
                        <div class="heading-layout1">
                            <div class="item-title">
                                <h3>Quiz Report</h3>
                            </div>
                      
                        </div>
<!-- msg of edit-->
                        
                          <?php if(isset($_GET['mssg'])){?>
                                        <div class="alert alert-success  alert-dismissible fade show settingalert">
                                            <?php echo check($_GET['mssg']);?>
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div> <?php } ?>
<!--mssg for delete-->
                              <?php if(isset($_GET['msg'])){?>
                                        <div class="alert alert-success  alert-dismissible fade show settingalert">
                                            <?php echo check($_GET['msg']);?>
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                       <?php } ?>

                         <?php if(isset($_GET['msgg'])){?>
                                        <div class="alert alert-success  alert-dismissible fade show settingalert">
                                            <?php echo check($_GET['msgg']);?>
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                       <?php } ?>
                                     
                          
                        <form class="mg-b-20">
                            <div class="row gutters-8">

                            </div>
                        </form>


                        <style>
                           .table-responsive{
                                height: auto !important;
                            }
                            .nav-pills{
                                border: 2px #000 solid;
                                border-radius: 7px;
                            }
                            .actions{
                                text-align: end;
                            }
                            .nav-pills .nav-item{
                                border: 4px #ff7802 solid !important;
                                border-radius: 6px !important;
                                margin: 2px !important;
                                font-family: cursive !important;
                            }
                            .nav-pills .nav-link{
                                color: #000 !important;
                            }
                            .nav-pills .nav-link.active, .nav-pills .show>.nav-link{
                                background-color: #ff7802 !important;
                                border-radius: 0px !important;
                            }
                        </style>
                        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">All Data</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false">Less MinPercentage</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="pills-contact-tab" data-toggle="pill" href="#pills-contact" role="tab" aria-controls="pills-contact" aria-selected="false">Not Solved</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                                <div class="table-responsive">
                                            <table class="table display data-table text-nowrap" id="myTable">
                                                <thead>
                                                    <tr>
                                                        <th>Studnet ID</th>
                                                        <th>Studnet Name</th>
                                                        <th>Degree</th>
                                                        <!-- <th>Controls</th> -->
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                    
                                                        <?php foreach ($solvedhws as $h){ 
                                                            
                                                            $stmt=$con->prepare("SELECT name FROM students WHERE id = ?");
                                                            $stmt->execute(array($h['studentid']));
                                                            $result=$stmt->fetch();
                                                            $studentname=$result['name'];
                                                            $studentid=$h['studentid'];
                                                        
                                                        
                                                            $stmt=$con->prepare("SELECT result FROM new_student_homework WHERE studentid = ? AND homeworkid = ?");
                                                            $stmt->execute(array($studentid,$id));
                                                            $r=$stmt->fetch();
                                                            $result=$r['result'];

                                                            $stmt=$con->prepare("SELECT q_no FROM new_homeworks WHERE id = ?");
                                                            $stmt->execute(array($id));
                                                            $r=$stmt->fetch();
                                                            $degree=$r['q_no'];
                                                            
                                                            ?>
                                                             <!-- <td>
                                                                <div class="form-check">
                                                                    <input type="checkbox" value="<?php echo $h['studentid'];?>" class="form-check-input" name="stdsids[]">
                                                                    <label class="form-check-label">#<?php echo $h['studentid'];  ?></label>
                                                                </div>
                                                            </td> -->
                                                        <td>#<?php echo $h['studentid'];  ?></td>
                                                        <td><?php echo $studentname;  ?></td>
                                                        <td><?php if($result !=NULL){ echo $result ." / " . $degree;} else{ echo "Not Corrected Yet";} ?></td>
                                                        <!-- <td>
                                                            <a href="correct.php?studentid=<?php echo $h['studentid']; ?>&hwid=<?php echo $h['homeworkid']; ?>" class="fw-btn-fill btn-gradient-yellow">SMS</a>
                                                            <a href="correct.php?studentid=<?php echo $h['studentid']; ?>&hwid=<?php echo $h['homeworkid']; ?>" class="fw-btn-fill btn-gradient-yellow">Sleep Account</a>
                                                        </td> -->
                                                    </tr>
                                                    <?php }   ?>
                                                </tbody>
                                            </table>
                                        
                                        </div>
                            </div>
                            <!-- --------------------------------------------------------------------------------------------------- -->
                            <!-- --------------------------------------------------------------------------------------------------- -->
                            <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                                   
                            <div class="table-responsive">
                                <form method="post">
                                        <div class="actions">
                                            On Selected
                                            <input type="submit" value="SMS" name="smsLess" style="width:100px !important" class="fw-btn-fill btn-gradient-yellow ">
                                            <input type="submit" value="Sleep Account" name="sleepLess" style="width:130px !important" class="fw-btn-fill btn-gradient-yellow ">
                                            <!-- <a href="correct.php?studentid=<?php echo $h['studentid']; ?>&hwid=<?php echo $h['homeworkid']; ?>" class="fw-btn-fill btn-gradient-yellow">SMS </a>
                                            <a href="correct.php?studentid=<?php echo $h['studentid']; ?>&hwid=<?php echo $h['homeworkid']; ?>" class="fw-btn-fill btn-gradient-yellow">Sleep Accounts</a> -->
                                        </div>
                                    <table class="table display data-table text-nowrap" id="myTable1"> 
                                            <thead>
                                                <tr>
                                                    <th>Studnet ID</th>
                                                    <th>Studnet Name</th>
                                                    <th>Degree</th>
                                                    <!-- <th>Controls</th> -->
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                
                                                    <?php foreach ($solvedhwsLessMin as $h){ 
                                                        
                                                        $stmt=$con->prepare("SELECT name FROM students WHERE id = ?");
                                                        $stmt->execute(array($h['studentid']));
                                                        $result=$stmt->fetch();
                                                        $studentname=$result['name'];
                                                        $studentid=$h['studentid'];
                                                    
                                                    
                                                        $stmt=$con->prepare("SELECT result FROM new_student_homework WHERE studentid = ? AND homeworkid = ?");
                                                        $stmt->execute(array($studentid,$id));
                                                        $r=$stmt->fetch();
                                                        $result=$r['result'];

                                                        $stmt=$con->prepare("SELECT q_no FROM new_homeworks WHERE id = ?");
                                                        $stmt->execute(array($id));
                                                        $r=$stmt->fetch();
                                                        $degree=$r['q_no'];
                                                        
                                                        ?>
                                                        <td>
                                                            <div class="form-check">
                                                                <input type="checkbox" value="<?php echo $h['studentid'];?>" class="form-check-input" name="stdsids[]">
                                                                <label class="form-check-label">#<?php echo $h['studentid'];  ?></label>
                                                            </div>
                                                        </td>
                                                    <td><?php echo $studentname;  ?></td>
                                                    <td><?php if($result !=NULL){ echo $result ." / " . $degree;} else{ echo "Not Corrected Yet";} ?></td>
                                                    <!-- <td><a href="correct.php?studentid=<?php echo $h['studentid']; ?>&hwid=<?php echo $h['homeworkid']; ?>" class="fw-btn-fill btn-gradient-yellow">Correct</a></td> -->
                                                </tr>
                                                <?php }   ?>
                                            </tbody>
                                        </table>  
                                      </form>
                                        
                                    </div>
                            </div>
                            <!-- --------------------------------------------------------------------------------------------------- -->
                            <!-- --------------------------------------------------------------------------------------------------- -->
                            <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">
                            <div class="table-responsive">
                                <form method="post">
                                        <div class="actions">
                                            On Selected
                                            <input type="submit" value="SMS" name="smsNot" style="width:100px !important" class="fw-btn-fill btn-gradient-yellow ">
                                            <input type="submit" value="Sleep Account" name="sleepNot" style="width:130px !important" class="fw-btn-fill btn-gradient-yellow ">
                                            <!-- <a href="correct.php?studentid=<?php echo $h['studentid']; ?>&hwid=<?php echo $h['homeworkid']; ?>" class="fw-btn-fill btn-gradient-yellow">SMS </a>
                                            <a href="correct.php?studentid=<?php echo $h['studentid']; ?>&hwid=<?php echo $h['homeworkid']; ?>" class="fw-btn-fill btn-gradient-yellow">Sleep Accounts</a> -->
                                        </div>
                                            <table class="table display data-table text-nowrap" id="myTable2">
                                                <thead>
                                                    <tr>
                                                    <th>Studnet ID</th>
                                                        <th>Studnet Name</th>
                                                        <th>Degree</th>
                                                        <!-- <th>Controls</th> -->
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                    
                                                        <?php

                                                        if(!empty($solvedhwsNotSolved)){
                                                        
                                                        foreach ($solvedhwsNotSolved as $h){ 
                                                            
                                                            $stmt=$con->prepare("SELECT name FROM students WHERE id = ?");
                                                            $stmt->execute(array($h['studentid']));
                                                            $result=$stmt->fetch();
                                                            $studentname=$result['name'];
                                                            $studentid=$h['studentid'];
                                                        
                                                        
                                                            $stmt=$con->prepare("SELECT result FROM new_student_homework WHERE studentid = ? AND homeworkid = ?");
                                                            $stmt->execute(array($studentid,$id));
                                                            $r=$stmt->fetch();
                                                            $result=$r['result'];

                                                            $stmt=$con->prepare("SELECT q_no FROM new_homeworks WHERE id = ?");
                                                            $stmt->execute(array($id));
                                                            $r=$stmt->fetch();
                                                            $degree=$r['q_no'];
                                                            
                                                            ?>
                                                             <td>
                                                                <div class="form-check">
                                                                    <input type="checkbox" value="<?php echo $h['studentid'];?>" class="form-check-input" name="stdsids[]">
                                                                    <label class="form-check-label">#<?php echo $h['studentid'];  ?></label>
                                                                </div>
                                                            </td>
                                                        <td><?php echo $studentname;  ?></td>
                                                        <td><?php if($result !=NULL){ echo $result ." / " . $degree;} else{ echo "Not Corrected Yet";} ?></td>
                                                        <!-- <td><a href="correct.php?studentid=<?php echo $h['studentid']; ?>&hwid=<?php echo $h['homeworkid']; ?>" class="fw-btn-fill btn-gradient-yellow">Correct</a></td> -->
                                                    </tr>
                                                    <?php }  }else{echo "Not End Date" ;} ?>
                                                </tbody>
                                            </table>
                                           </form>
                                        </div>    
                            </div>
                            <!-- --------------------------------------------------------------------------------------------------- -->
                            <!-- --------------------------------------------------------------------------------------------------- -->
                        </div>
                        <!-- --------------------------------------------------------------------------------------------------- -->
                        <!-- --------------------------------------------------------------------------------------------------- -->
                        <!-- --------------------------------------------------------------------------------------------------- -->
                        <!-- --------------------------------------------------------------------------------------------------- -->

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
      $('#myTab a').on('click', function (event) {
        event.preventDefault()
        $(this).tab('show')
        });

 
    </script>

    <script>
        $(document).ready( function () {
            $('#myTable').dataTable({
                dom: 'Bfrtip',
                buttons: [
                  'excel'
                ]
            });
        
        });
        $(document).ready( function () {
            $('#myTable1').dataTable({
                dom: 'Bfrtip',
                buttons: [
                  'excel',
                ],
         
            });
        
        });
        $(document).ready( function () {
            $('#myTable2').dataTable({
                dom: 'Bfrtip',
                buttons: [
                  'excel'
                ]
            });
        
        });
    </script>
        

</body>

</html>