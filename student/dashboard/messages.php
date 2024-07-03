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
    $stmt=$con->prepare("SELECT * FROM students ORDER BY email");
    $stmt->execute();
    $students=$stmt->fetchAll();
    $stdno=COUNT($students);


   if(isset($_POST['sendmsg'])){
       $msg=$_POST['msg'];
       $ids=$_POST['stdsids'];
       if($stdno==COUNT($ids)){
           $type=0;
           $stmt=$con->prepare("INSERT INTO notifications (msg,type) VALUES (:msg,:type) ");
           $stmt->execute(array(
               "msg"=> $msg,
               "type"=> $type
           ));
       }else{
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
       }

        header("Location: showmsgs.php?msg=".urlencode("Done Pushing Message"));
        exit();

   }
}
?>
<!doctype html>
<html class="no-js" lang="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>E-Learning System | All Students</title>
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
    <!-- Animate CSS -->
    <link rel="stylesheet" href="css\animate.min.css">
    <!-- Data Table CSS -->
    <link rel="stylesheet" href="css\jquery.dataTables.min.css">
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

    <?php include_once 'navbar.php' ?>
    <!-- Header Menu Area End Here -->
    <!-- Page Area Start Here -->
    <div class="dashboard-page-one">
        <?php  include_once 'sidebar.php'; ?>

        <!-- Sidebar Area End Here -->
        <div class="dashboard-content-one">
            <!-- Breadcubs Area Start Here -->
            <div class="breadcrumbs-area">
                <h3>Students</h3>
                <ul>
                    <li>
                        <a href="index.html">Home</a>
                    </li>
                    <li>All Students</li>
                </ul>
            </div>
            <!-- Breadcubs Area End Here -->
            <!-- Student Table Area Start Here -->
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

                    <div>
                        <table class="table display mydata-table text-nowrap">
                            <thead>
                            <tr>
                                <th>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input checkAll">
                                        <label class="form-check-label">Code</label>
                                    </div>
                                </th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Parent Phone</th>
                                <th>Educational Level </th>


                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($students as $student){

                                $stmt=$con->prepare("SELECT name FROM educationallevels WHERE id = ?");
                                $stmt->execute(array($student['eductionallevel']));
                                $edulevel=$stmt->fetch();

                                $stmt=$con->prepare("SELECT * FROM subjects WHERE id IN (SELECT subjectid FROM studentssubjects WHERE studentid = ?)");
                                $stmt->execute(array($student['id']));
                                $subjects=$stmt->fetchAll();
                                $allsubjects="";
                                foreach($subjects as $subject){
                                    $stmt=$con->prepare("SELECT name FROM educationallevels WHERE id=?");
                                    $stmt->execute(array($subject['edu_id']));
                                    $edulevel=$stmt->fetch();

                                    $stmt=$con->prepare("SELECT name FROM admins WHERE id=?");
                                    $stmt->execute(array($subject['teacher_id']));
                                    $teacher=$stmt->fetch();

                                    $allsubjects .= $subject['name'] ." - ".$edulevel['name']." - ".$teacher['name']."<br>" ;
                                }

                                ?>
                                <tr>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox" value="<?php echo $student['id'];?>" class="form-check-input" name="stdsids[]">
                                            <label class="form-check-label">#<?php echo $student['id'];  ?></label>
                                        </div>
                                    </td>

                                    <td><?php echo $student['name'];  ?></td>
                                    <td><?php echo $student['email'];  ?></td>
                                    <td><?php echo $student['phone'];  ?></td>
                                    <td><?php echo $student['phone1'];  ?></td>
                                    <td><?php echo $edulevel['name']; ?></td>
                                </tr>
                            <?php }   ?>

                            </tbody>
                        </table>
                    </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
    <!-- Page Area End Here -->
    <?php include_once "footer.php";?>
</div>
<!-- jquery-->
<script src="js\jquery-3.3.1.min.js"></script>
<!-- Plugins js -->
<script src="js\plugins.js"></script>
<!-- Popper js -->
<script src="js\popper.min.js"></script>
<!-- Bootstrap js -->
<script src="js\bootstrap.min.js"></script>
<!-- Scroll Up Js -->
<script src="js\jquery.scrollUp.min.js"></script>
<!-- Data Table Js -->
<script src="js\jquery.dataTables.min.js"></script>
<!-- Custom Js -->
<script src="js\main.js"></script>
<script>
    $(document).ready(function() {
    $('.mydata-table').DataTable({
        paging: false,
        searching: true,
        info: false,
        lengthChange: false,
        ordering: false,

        initComplete: function () {
            this.api().columns([6]).every( function () {
                var column = this;
                var select = $('<select><option value=""></option></select>')
                    .appendTo( $(column.header()) )
                    .on( 'change', function () {
                        var val = $.fn.dataTable.util.escapeRegex(
                            $(this).val()
                        );

                        column
                            .search( val ? '^'+val+'$' : '', true, false )
                            .draw();
                    } );

                column.data().unique().sort().each( function ( d, j ) {
                    select.append( '<option value="'+d+'">'+d+'</option>' )
                } );
            } );
        }
    });
    });
</script>
</body>

</html>