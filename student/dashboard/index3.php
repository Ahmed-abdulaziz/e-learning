<?php 
include_once 'connect.php';
session_start();

if(!isset($_SESSION['adminname'])){
header("location:index.php?msg=".urldecode("please login first"));
exit();
}
if($_SESSION['type']=="admin" || $_SESSION['type']=="super admin"||$_SESSION['type']=="assistant"){
    $stmt=$con->prepare("SELECT * FROM subjects");
    $stmt->execute();
    $subjects=$stmt->fetchAll();
}elseif($_SESSION['type']=="teacher"){
    $stmt=$con->prepare("SELECT * FROM subjects WHERE teacher_id = ?");
    $stmt->execute(array($_SESSION['id']));
    $subjects=$stmt->fetchAll();
}


//     $ar=[];
//     $stmt=$con->prepare("SELECT  DISTINCT code  FROM students ");
//     $stmt->execute();
//     $students=$stmt->fetchAll();
    
//     foreach($students as $student){
//         $count = 0;
//         $c= $student['code'];
//             $stmt=$con->prepare("SELECT * FROM students WHERE code = ? ");
//             $stmt->execute(array($c));
//             $codes=$stmt->fetchAll();
//             foreach($codes as $code){
//                 $count++;
//             }
//             $ar[$c] = $count;
    
//     }
    
//      foreach($ar as $key => $value){
      
        
        
//               echo $key ." -> ".$value."<br>";
        
    
//     }
    
//   die;



// Update Codes----------------------------------
    
//     $ar=[];
//     $std = [];

//     $stmt=$con->prepare("SELECT   code  FROM students ");
//     $stmt->execute();
//     $students=$stmt->fetchAll();
    
//     foreach($students as $student){
//         $count = 0;
//         $c= $student['code'];
//             $stmt=$con->prepare("SELECT * FROM students WHERE code = ? ");
//             $stmt->execute(array($c));
//             $codes=$stmt->fetchAll();
//             $x=1;
//             foreach($codes as $code){
//                 $count++;
//                 if($x > 1){
                    
//                     // Declare an associative array
//                     $arr = array( "a"=>"1", "b"=>"2", "c"=>"3", "d"=>"4" );
                    
//                     // Use array_rand function to returns random key
//                     $key = array_rand($arr);
                    
//                     $new = $code['code']+$arr[$key];
//                     $stmt=$con->prepare("UPDATE  students SET code =?  WHERE id=?");
//                     $stmt->execute(array($new,$code['id']));
//                     $s = $code['id']." - ". $code['name'];
//                     $std[$s] = $new;
//                 }
//                 $x++;
//             }
//             $ar[$c] = $count;
    
//     }
    
//      foreach($std as $key => $value){
      
        
//         if($value > 1){
//               echo $key ." -> ".$value."<br>";
//         }
    
//     }
    
//   die;


?>
<!doctype html>
<html class="no-js" lang="">

  <?php include('header/head.php'); ?>

<body>
    <!-- Preloader Start Here -->
    <div id="preloader"></div>
    <!-- Preloader End Here -->
    <div id="wrapper" class="wrapper bg-ash">
        <!-- Header Menu Area Start Here -->
        <?php include_once "navbar.php";?>
        <!-- Header Menu Area End Here -->

        <!-- Page Area Start Here -->
        <div class="dashboard-page-one">
            <!-- Sidebar Area Start Here -->
            <?php include_once "sidebar.php";?>
            <!-- Sidebar Area End Here -->
            <div class="dashboard-content-one">
                <!-- Breadcubs Area Start Here -->
                <div class="breadcrumbs-area">
                    <h3>Admin Dashboard</h3>
                    <ul>
                        <li>
                            <a href="index3.php">Home</a>
                        </li>
                        <li>Admin</li>
                    </ul>
                </div>
                <!-- Breadcubs Area End Here -->
                <div class="row">
                     <div class="heading-layout1">
                                    <div class="item-title">
                                       
                                        <?php if(!empty($_GET['msg'])){  ?>
                                             <div class="alert alert-danger errors alert-dismissible fade show adminerror" role="alert">
                                                    <?php echo $_GET['msg']; ?>
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                        <span aria-hidden="true">×</span>
                                                    </button>
                                             </div>
                                        <?php }?>
                                    </div>
                              
                                </div>
                                
                    <div class="col-12-xxxl col-12">
                        <div class="row">
                            <!-- Summery Area Start Here -->
                            <?php foreach($subjects as $subject){
                            
                                $stmt=$con->prepare("SELECT name FROM educationallevels WHERE id=?");
                                $stmt->execute(array($subject['edu_id']));
                                $edulevel=$stmt->fetch();

                                $stmt=$con->prepare("SELECT fullname FROM admins WHERE id=?");
                                $stmt->execute(array($subject['teacher_id']));
                                $teacher=$stmt->fetch();
                                
                                $stmt=$con->prepare("SELECT COUNT(id) FROM weeks WHERE subjectid = ?");
                                $stmt->execute(array($subject['id']));
                                $result=$stmt->fetch();
                                $weeks=$result['COUNT(id)'];
                                
                                $stmt=$con->prepare("SELECT COUNT(id) FROM codes WHERE studentid IS NOT NULL AND weekid IN (SELECT id FROM weeks WHERE subjectid = ?)");
                                $stmt->execute(array($subject['id']));
                                $result=$stmt->fetch();
                                $usedcodes=$result['COUNT(id)'];
                            
                            ?>
                            <div class="col-lg-3">
                                <a href="statistics.php?do=session&sid=<?php echo $subject['id']; ?>">
                                <div class="dashboard-summery-one subject">
                                    <div class="item-content">
                                        <div class="item-number"><?php echo $subject['name'];?></div>
                                        <div class="item-number"><span><?php echo $teacher['fullname']; ?></span></div>
                                        <div class="item-title"><span><?php echo $edulevel['name'];?></span></div>
                                        <div class="item-title"><span><?php echo $weeks." Weeks / ".$usedcodes." Used code";?></span></div>
                                    </div>
                                </div>
                                </a>
                            </div>
                            <?php }?>

                        </div>
                    </div>
                </div>
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
    <!-- Data Table Js -->
    <script src="js\jquery.dataTables.min.js"></script>
    <!-- Custom Js -->
    <script src="js\main.js"></script>

</body>

</html>