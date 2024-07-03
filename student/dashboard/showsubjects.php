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

    $name=$_SESSION['adminname'];
    $subjectid=$_SESSION['subject'];

    $studentid=$_GET['id'];
    $stmt=$con->prepare("SELECT * FROM students WHERE id = ?");
    $stmt->execute(array($studentid));
    $student=$stmt->fetch();

    $stmt=$con->prepare("SELECT name FROM educationallevels WHERE id = ?");
    $stmt->execute(array($student['eductionallevel']));
    $edulevel=$stmt->fetch();

    $stmt=$con->prepare("SELECT * FROM studentssubjects WHERE studentid = ?");
    $stmt->execute(array($student['id']));
    $subjectsids=$stmt->fetchAll();


    if(isset($_GET['do'])){
        $do = $_GET['do'];
        if($do=='approve'){
            $id=$_GET['id'];
            $subjectid=$_GET['subjectid'];

            $stmt=$con->prepare("UPDATE studentssubjects SET approve=? WHERE studentid = ? AND subjectid = ?");
            $stmt->execute(array("1",$id,$subjectid));

            $stmt=$con->prepare("UPDATE  students SET approve=? WHERE id = ?");
            $stmt->execute(array("1",$id));

            header("Location:showsubjects.php?id=$id&msg=".urlencode("Done Approve Subject"));
            exit();
        }elseif($do=='disapprove'){
            $id=$_GET['id'];
            $subjectid=$_GET['subjectid'];

            $stmt=$con->prepare("UPDATE studentssubjects SET approve=? WHERE studentid = ? AND subjectid = ?");
            $stmt->execute(array("2",$id,$subjectid));

            $stmt=$con->prepare("SELECT * FROM studentssubjects WHERE studentid = ? AND approve = ?");
            $stmt->execute(array($id,"1"));
            $subjectsids=$stmt->fetchAll();

            if(empty($subjectsids)){
                $stmt=$con->prepare("UPDATE  students SET approve=? WHERE id = ?");
                $stmt->execute(array("2",$id));
            }

            header("Location:showsubjects.php?id=$id&msg=".urlencode("Done Disapprove Subject"));
            exit();
        }elseif($do=='approveall'){
            $id=$_GET['id'];

            $stmt=$con->prepare("UPDATE studentssubjects SET approve=? WHERE studentid = ? ");
            $stmt->execute(array("1",$id));

            $stmt=$con->prepare("UPDATE  students SET approve=? WHERE id = ?");
            $stmt->execute(array("1",$id));

            header("Location:showsubjects.php?id=$id&msg=".urlencode("Done Approve All Subjects"));
            exit();
        }elseif($do=='disapproveall'){
            $id=$_GET['id'];
            $subjectid=$_GET['subjectid'];

            $stmt=$con->prepare("UPDATE studentssubjects SET approve=? WHERE studentid = ?");
            $stmt->execute(array("2",$id));

            $stmt=$con->prepare("UPDATE  students SET approve=? WHERE id = ?");
            $stmt->execute(array("2",$id));

            header("Location:showsubjects.php?id=$id&msg=".urlencode("Done Disapprove Subject"));
            exit();
        }
//        elseif($do=='delete'){
//            $id=$_GET['id'];
//            $subjectid=$_GET['subjectid'];
//
//            //delete student subjects
//            $stmt=$con->prepare("DELETE FROM studentssubjects WHERE studentid = ? AND subjectid = ?");
//            $stmt->execute(array($id,$subjectid));
//
//            header("Location: showsubjects.php?id=$id&msg=".urldecode("Subject deleted successfully"));
//            exit();
//        }
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
                    <h3>Students</h3>
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
                                <h3><?php echo $student['code']." - ".$student['name']." - ".$student['email']." - ".$student['phone']." - ".$student['phone1']." - ".$edulevel['name'];?></h3>
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

                        <a href="showsubjects.php?do=approveall&id=<?php echo $student["id"]; ?>"  class="fw-btn-fill btn-gradient-yellow">Approve All</a>
                        <a href="showsubjects.php?do=disapproveall&id=<?php echo $student["id"]; ?>"  class="fw-btn-fill btn-gradient-yellow">Disapprove All</a>

                        <div class="table-responsive">
                            <table class="table display data-table text-nowrap">
                                <thead>
                                <tr>
                                    <th>Subject</th>
                                    <th>Teacher</th>
                                    <th>Educational Level</th>
                                    <th>Status</th>
                                    <th>Controls</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>

                                    <?php foreach ($subjectsids as $subjectid){

                                    $stmt=$con->prepare("SELECT * FROM subjects WHERE id=?");
                                    $stmt->execute(array($subjectid['subjectid']));
                                    $subject=$stmt->fetch();

                                    $stmt=$con->prepare("SELECT name FROM educationallevels WHERE id=?");
                                    $stmt->execute(array($subject['edu_id']));
                                    $edulevel=$stmt->fetch();

                                    $stmt=$con->prepare("SELECT name FROM admins WHERE id=?");
                                    $stmt->execute(array($subject['teacher_id']));
                                    $teacher=$stmt->fetch();

                                    ?>
                                    <td><?php echo $subject['name']  ?></td>
                                    <td><?php echo $teacher['name']  ?></td>
                                    <td><?php echo $edulevel['name']  ?></td>


                                    <?php if($subjectid['approve']==0){?>
                                        <td><button type="button" class="btn btn-warning btn-lg btn-block">Pending</button></td>
                                    <?php }elseif($subjectid['approve']==1){?>
                                        <td><button type="button" class="btn btn-success btn-lg btn-block">Approved</button></td>
                                    <?php }elseif($subjectid['approve']==2){?>
                                        <td><button type="button" class="btn btn-danger btn-lg btn-block">Disapproved</button></td>
                                    <?php } ?>


                                    <td>

                                        <a href="showsubjects.php?do=approve&id=<?php echo $student["id"]; ?>&subjectid=<?php echo $subject["id"]; ?>"  class="fw-btn-fill btn-gradient-yellow">Approve</a>

                                        <a href="showsubjects.php?do=disapprove&id=<?php echo $student["id"]; ?>&subjectid=<?php echo $subject["id"]; ?>"  class="fw-btn-fill btn-gradient-yellow">Disapprove </a>

                                        <!-- <a href="showsubjects.php?do=delete&id=--><?php //echo $student["id"]; ?><!--&subjectid=--><?php //echo $subject["id"]; ?><!--"  class="fw-btn-fill btn-gradient-yellow confirmation">Delete</a>-->

                                    </td>
                                </tr>
                                <?php }   ?>
                                </tbody>
                            </table>

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
    <!-- Custom Js -->
    <script src="js\main.js"></script>


    </body>

    </html>
<?php ob_end_flush();?>