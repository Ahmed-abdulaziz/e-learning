<?php
ob_start();
include_once 'connect.php';
ob_start();
session_start();
if (!isset($_SESSION['adminname'])) {
    header("Location:index.php?msg=" . urlencode("Please Login First"));
    exit();
}
if (isset($_SESSION['adminname'])) {
    $name = $_SESSION['adminname'];
    $stmt = $con->prepare("SELECT type FROM admins WHERE name=?");
    $stmt->execute(array($name));
    $result = $stmt->fetch();
    $admintype = $result['type'];

    $name = $_SESSION['adminname'];

    $level = $_GET['level'];
    $type_edu = $_GET['type_edu'];

    $i = 0;
    if (!empty($type_edu) && !empty($level)) {
        $stmt = $con->prepare("SELECT * FROM students WHERE eductionallevel = ? AND eductionallevel IN 
        (SELECT id FROM educationallevels where lang = ?)  ORDER BY email");
        $stmt->execute(array($level, $type_edu));
        $students = $stmt->fetchAll();
    } elseif (!empty($type_edu) && empty($level)) {

        $stmt = $con->prepare("SELECT * FROM students WHERE eductionallevel IN (SELECT id FROM educationallevels where lang = ?) ORDER BY email ");
        $stmt->execute(array($type_edu));
        $students = $stmt->fetchAll();
    } elseif (empty($type_edu) && !empty($level)) {
        $stmt = $con->prepare("SELECT * FROM students WHERE eductionallevel = ?  ORDER BY email ");
        $stmt->execute(array($level));
        $students = $stmt->fetchAll();
    } else {
        $stmt = $con->prepare("SELECT * FROM students   ORDER BY email LIMIT 100");
        $stmt->execute();
        $students = $stmt->fetchAll();
    }








    if (isset($_GET['do'])) {
        $do = $_GET['do'];
        if ($do == 'approve') {
            $id = $_GET['id'];
            $level = $_GET['level'];
            $stmt = $con->prepare("UPDATE studentssubjects SET approve=? WHERE studentid = ?");
            $stmt->execute(array("1", $id));

            $stmt = $con->prepare("UPDATE  students SET approve=? WHERE id = ?");
            $stmt->execute(array("1", $id));
            header("Location:allstudents.php?level=$level&msg=" . urlencode("Done Approve Student"));
            exit();
        } elseif ($do == 'disapprove') {
            $id = $_GET['id'];
            $level = $_GET['level'];
            $stmt = $con->prepare("UPDATE studentssubjects SET approve=? WHERE studentid = ?");
            $stmt->execute(array("2", $id));

            $stmt = $con->prepare("UPDATE  students SET approve=? WHERE id = ?");
            $stmt->execute(array("2", $id));
            header("Location:allstudents.php?level=$level&msg=" . urlencode("Done Disapprove Student"));
            exit();
        } elseif ($do == 'approveall') {
            $id = $_GET['id'];
            $level = $_GET['level'];
            $stmt = $con->prepare("UPDATE studentssubjects SET approve=?");
            $stmt->execute(array("1"));

            $stmt = $con->prepare("UPDATE  students SET approve=?");
            $stmt->execute(array("1"));
            header("Location:allstudents.php?level=$level&msg=" . urlencode("Done Approve All Students"));
            exit();
        } elseif ($do == 'disapproveall') {
            $id = $_GET['id'];
            $level = $_GET['level'];
            $stmt = $con->prepare("UPDATE studentssubjects SET approve=?");
            $stmt->execute(array("2"));

            $stmt = $con->prepare("UPDATE  students SET approve=?");
            $stmt->execute(array("2"));
            header("Location:allstudents.php?level=$level&msg=" . urlencode("Done Disapprove All Students"));
            exit();
        } elseif ($do == 'delete') {
            $id = $_GET['id'];
            $level = $_GET['level'];
            $stmt = $con->prepare("SELECT * FROM students WHERE id = ?");
            $stmt->execute(array($id));
            $student = $stmt->fetch();

            //delete student photo from uploaded images
            unlink("../img/profile/" . $student['image']);

            //delete student data
            $stmt = $con->prepare("DELETE FROM students WHERE id = ?");
            $stmt->execute(array($id));

            //delete student subjects
            $stmt = $con->prepare("DELETE FROM studentssubjects WHERE studentid = ?");
            $stmt->execute(array($id));

            header("Location: allstudents.php?level=$level&msg=" . urldecode("Student deleted successfully"));
            exit();
        }
    }

    if (isset($_POST['charge'])) {
        $balance = $_POST['balance'];
        $studentid = $_POST['studentid'];
        $type = $_POST['type'];
        $level = $_POST['level'];
        if ($type == 'add') {
            $stmt = $con->prepare("INSERT INTO student_wallet (studentid,money,date) VALUES (:studentid,:money,:date)");
            $stmt->execute(array(
                'studentid' => $studentid,
                'money' => $balance,
                'date' => date('Y-m-d')
            ));

            header("Location: allstudents.php?level=$level&msg=" . urldecode("Student Charge Wallet successfully"));
            exit();
        } else {
            $stmt = $con->prepare("INSERT INTO student_wallet (studentid,money,date) VALUES (:studentid,:money,:date)");
            $stmt->execute(array(
                'studentid' => $studentid,
                'money' => $balance * -1,
                'date' => date('Y-m-d')
            ));

            header("Location: allstudents.php?level=$level&msg=" . urldecode("Student Charge Wallet successfully"));
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


            <?php include_once 'sidebar.php'; ?>
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
                        <?php if (isset($_GET['msg'])) { ?>
                            <div class="alert alert-success  alert-dismissible fade show settingalert">
                                <?php echo $_GET['msg']; ?>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        <?php } ?>

                        <!-- <a href="addstudent.php"  class="fw-btn-fill btn-gradient-yellow">Add Student</a> -->
                        <!--<a href="allstudents.php?do=approveall"  class="fw-btn-fill btn-gradient-yellow">Approve All</a>-->
                        <!--<a href="allstudents.php?do=disapproveall"  class="fw-btn-fill btn-gradient-yellow">Disapprove All</a>-->


                        <div class="table-responsive">
                            <table class="table display data-table text-nowrap" id="myTabledata">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Username</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Password</th>
                                        <th>Parent Phone</th>
                                        <th>Educational Level</th>
                                        <th>Governorate</th>
                                        <th>School</th>
                                        <th>Area</th>
                                        <?php if ($admintype == "super admin") { ?>
                                            <th>Wallet</th>
                                        <?php } ?>
                                        <th>Status</th>

                                        <th>Controls</th>

                                    </tr>
                                </thead>
                                <tbody>


                                    <?php foreach ($students as $student) {

                                        $stmt = $con->prepare("SELECT name FROM educationallevels WHERE id = ?");
                                        $stmt->execute(array($student['eductionallevel']));
                                        $edulevel = $stmt->fetch();

                                        // $stmt=$con->prepare("SELECT classid FROM studentssubjects WHERE studentid = ?");
                                        // $stmt->execute(array($student['id']));
                                        // $class=$stmt->fetch();

                                        // $stmt=$con->prepare("SELECT * FROM classes WHERE id = ?");
                                        // $stmt->execute(array($class['classid']));
                                        // $class=$stmt->fetch();

                                    ?>
                                        <tr>
                                            <td><?php echo $student['id'];  ?></td>
                                            <td><?php echo $student['username'];  ?></td>
                                            <td><?php echo $student['name'];  ?></td>
                                            <td><?php echo $student['email'];  ?></td>
                                            <td><?php echo $student['phone'];  ?></td>
                                            <td><?php echo $student['password'];  ?></td>
                                            <td><?php echo $student['phone2'];  ?></td>
                                            <td><?php echo $edulevel['name']; ?></td>
                                            <td><?php echo $student['governorate']; ?></td>
                                            <td><?php echo $student['school']; ?></td>
                                            <td><?php echo $student['area']; ?></td>
                                            <?php if ($admintype == "super admin") { ?>
                                                <td>
                                                    <?php
                                                    $stmt = $con->prepare("SELECT sum(money) as totalwallet FROM student_wallet WHERE studentid = ?");
                                                    $stmt->execute(array($student['id']));
                                                    $total = $stmt->fetch();

                                                    echo $total['totalwallet'] != NULL ? $total['totalwallet'] . ' EGP' : 0 . ' EGP';
                                                    ?>
                                                </td>
                                            <?php } ?>

                                            <?php if ($student['approve'] == 0) { ?>
                                                <td><button type="button" class="btn btn-warning btn-lg btn-block">Pending</button></td>
                                            <?php } elseif ($student['approve'] == 1) { ?>
                                                <td><button type="button" class="btn btn-success btn-lg btn-block">Approved</button></td>
                                            <?php } elseif ($student['approve'] == 2) { ?>
                                                <td><button type="button" class="btn btn-danger btn-lg btn-block">Disapproved</button></td>
                                            <?php } ?>


                                            <td style="display: flex;">
                                                <!--<a href="profile.php?id=<?php //echo $student["id"] 
                                                                            ?>"  class="fw-btn-fill btn-gradient-yellow">Profile</a>-->
                                                <?php if ($admintype == "super admin") { ?>
                                                    <form method="post" style="display: flex;">
                                                        <input type="hidden" value="<?= $student['id']; ?>" name="studentid" />
                                                        <input type="hidden" value="<?= $level; ?>" name="level" />

                                                        <input type="hidden" value="add" name="type" />
                                                        <input type="number" class="form-control mr-1 h-100" placeholder="Enter Balance" step="any" min="0" required name="balance" />
                                                        <button type="submit" class="fw-btn-fill btn-gradient-yellow mr-1" style="width: 207px;" name="charge">Charge Wallet</button>
                                                    </form>

                                                    <form method="post" style="display: flex;">
                                                        <input type="hidden" value="<?= $student['id']; ?>" name="studentid" />
                                                        <input type="hidden" value="<?= $level; ?>" name="level" />
                                                        <input type="hidden" value="Withdraw" name="type" />
                                                        <input type="number" class="form-control mr-1 h-100" placeholder="Enter Balance" step="any" min="0" required name="balance" />
                                                        <button type="submit" class="fw-btn-fill btn-gradient-yellow mr-1" style="width: 250px;" name="charge">Withdraw Wallet</button>
                                                    </form>
                                                <?php } ?>
                                                <?php if ($admintype == "super admin") { ?>
                                                    <a href="allstudents.php?do=approve&id=<?php echo $student["id"] ?>&level=<?= $level ?>" class="fw-btn-fill btn-gradient-yellow mr-1">Approve</a>

                                                    <a href="allstudents.php?do=disapprove&id=<?php echo $student["id"] ?>&level=<?= $level ?>" class="fw-btn-fill btn-gradient-yellow mr-1">Disapprove </a>
                                                    <a href="stdprofile.php?id=<?php echo $student["id"] ?>" target="_blank" class="fw-btn-fill btn-gradient-yellow mr-1">Profile</a>

                                                    <a href="studentedit.php?do=ediit&id=<?php echo $student["id"] ?>" target="_blank" class="fw-btn-fill btn-gradient-yellow mr-1">Edit</a>

                                                    <a href="allstudents.php?do=delete&id=<?php echo $student["id"] ?>&level=<?= $level ?>" class="fw-btn-fill btn-gradient-yellow confirmation mr-1">Delete</a>
                                                <?php } ?>
                                                <a href="stdprofile.php?id=<?php echo $student["id"] ?>" target="_blank" class="fw-btn-fill btn-gradient-yellow mr-1">Profile</a>

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
    <?php include_once "footer.php"; ?>

    <div class="modal fade" id="imagemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <img src="" class="imagepreview" style="width: 100%;">
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
    <script src="js\select2.min.js"></script>
    <!-- Chart Js -->
    <script src="js\Chart.min.js"></script>
    <!-- Custom Js -->
    <script src="js\main.js"></script>
    <script src="js\jquery-3.3.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.html5.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#myTabledata').DataTable({
                dom: 'Blfrtip',
                buttons: [
                    'excel'
                ],
                "lengthMenu": [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "All"]
                ]

            });

        });
    </script>

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
        var confirmIt = function(e) {
            if (!confirm('هل أنت متاكد من إزالة هذا المستخدم؟')) e.preventDefault();
        };
        for (var i = 0, l = elems.length; i < l; i++) {
            elems[i].addEventListener('click', confirmIt, false);
        }
    </script>


</body>

</html>
<?php ob_end_flush(); ?>