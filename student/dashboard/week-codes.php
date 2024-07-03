<?php 
include_once 'connect.php';
date_default_timezone_set("Africa/Cairo");
ob_start();
 session_start();
 if(!isset($_SESSION['adminname'])){
    header("Location:index.php?msg=".urlencode("Please Login First"));
    exit();
}
$name=$_SESSION['adminname'];
$stmt=$con->prepare("SELECT type FROM admins WHERE name=?");
$stmt->execute(array($name));
$result=$stmt->fetch();
$type=$result['type'];
if($type!="super admin" && $type!="admin"){
header("Location:index3.php?msg=You Not Have A Permission");
}
$stmt=$con->prepare("SELECT * FROM admins WHERE type=?");
$stmt->execute(array('teacher'));
$teachers=$stmt->fetchAll();

$stmt=$con->prepare("SELECT * FROM educationallevels ");
$stmt->execute();
$levels=$stmt->fetchAll();




if(isset($_SESSION['adminname']))
{
$name=$_SESSION['adminname'];

if(isset($_GET['wid'])){
    $wid = $_GET['wid'];
}
$stmt=$con->prepare("SELECT * FROM codes WHERE weekid = ? ");
$stmt->execute(array($wid));
$codes=$stmt->fetchAll();


}
    
if(isset($_GET['clear'])){
    $id=$_GET['id'];
    $wid=$_GET['wid'];
     $stmt=$con->prepare("UPDATE  codes SET studentid=?   WHERE id=?");     
     $stmt->execute(array(NULL,$id));
     header("Location:week-codes.php?wid=$wid&msgg=".urlencode("Done Clear student from code"));
    exit();
}
if(isset($_GET['delete'])){
    $id=$_GET['id'];
     $wid=$_GET['wid'];
    $stmt=$con->prepare("DELETE FROM codes WHERE id=? ");
    $stmt->execute(array($id));
     header("Location:week-codes.php?wid=$wid&msgg=".urlencode("Done Delete code"));
    exit();
}
if(isset($_POST['deleteall'])){
    $wid=$_POST['wid'];
    $stmt=$con->prepare("DELETE FROM codes  WHERE weekid = ?");
    $stmt->execute(array($wid));
    header("Location:week-codes.php?wid=$wid&msgg=".urlencode("Done Delete All codes"));
    exit();
}
?>

<!doctype html>
<html class="no-js" lang="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>E-Learning System   | Codes</title>
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
                    <h3>Codes</h3>
                    <ul>
                        <li>
                            <a href="index3.php">Home</a>
                        </li>
                        <li>Codes</li>
                    </ul>
                </div>
                <!-- Breadcubs Area End Here -->
                <!-- Teacher Table Area Start Here -->
                <div class="card height-auto">
                    <div class="card-body">
                        <div class="heading-layout1">
                            <div class="item-title">
                                <h3>Codes</h3>
                                
                                 <!--msgg for delete-->
                                            <?php if(isset($_GET['msg'])){?>
                                            <div class="alert alert-danger  alert-dismissible fade show settingalert">
                                                <?php echo $_GET['msg'];?>
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <?php }?>
                            </div>
                        </div>
                        <div class="row">
                            <form method="post">
                                <input hidden name="wid" value="<?php echo $wid?>" />
                                <button type="submit" class="btn-fill-lg bg-blue-dark btn-hover-yellow generate my-3 deleteall" name="deleteall">Delete All Codes</button>
                            </form>
                        </div>
                        <!--msgg for adding-->
                        <?php if(isset($_GET['msgg'])){?>
                        <div class="alert alert-success  alert-dismissible fade show settingalert">
                            <?php echo $_GET['msgg'];?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <?php }?>
                      
                        <div class="table-responsive">
                            <table class="table display data-table text-nowrap" id="myTable">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Code</th>
                                        <th>Student Name</th>
                                        <th>Created At</th>
                                        <th>Status</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                      <?php if(isset($_SESSION['adminname'])){ foreach($codes as $code){
                                      
                                        $stmt=$con->prepare("SELECT * FROM students WHERE id=? ");
                                        $stmt->execute(array($code['studentid']));
                                        $students=$stmt->fetch();

                                        
                                      ?>
                                        <td><?php echo $code['id'];  ?></td>
                                        <td><?php echo $code['code'];  ?></td>
                                        <td><?php echo $students['name'];  ?></td>
                                        <td><?php echo $code['created_at'];  ?></td>

                                         
                                             <td>
                                                  <?php if($code['studentid']!=NULL){?>
                                                  
                                                 <button type="button" class="btn btn-success btn-lg btn-block mb-2">Used</button>
                                                  <a href="?clear&id=<?php echo $code['id'];  ?>&wid=<?=$wid?>"><button type="button" class="btn btn-success btn-lg btn-block">Clear student</button></a>
                                                 <?php }else{?>
                                                  <button type="button" class="btn btn-warning btn-lg btn-block">Pending</button>
                                                <!--<a href="add-student-to-code.php?id=<?php //echo $code['id'];  ?>"><button type="button" class="btn btn-success btn-lg btn-block">Add student</button></a>-->

                                                    <?php } ?>
                                             <a href="?delete&id=<?php echo $code['id']; ?>&wid=<?=$wid?>"><button type="button" class="btn btn-danger btn-lg btn-block delete mt-1">Delete</button></a>

                                             </td>

                                       
                           
                                    </tr>            
                                
     <?php }}?>
                             
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
     <script src="js\jquery-3.3.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.html5.min.js"></script>
       <script>
         $(document).ready( function () {
            $('#myTable').dataTable({
                initComplete: function () {
                this.api().columns([2,3,4]).every( function () {
                    var column = this;
                    var select = $('<select><option value=""></option></select>')
                        .appendTo( $(column.header()).empty() )
                        .on( 'change', function () {
                            var val = $.fn.dataTable.util.escapeRegex(
                                $(this).val()
                            );
     
                            column
                                .search( val ? '^'+val+'$' : '', true, false )
                                .draw();
                        } );
     
                  column.data().unique().sort().each( function ( d, j ) {
                        if(column.index() == 5){ d = $(d).text(); }
                        select.append( '<option value="'+d+'">'+d+'</option>' )
                    } );
                } );
            },
            dom: 'Blfrtip',
                buttons: [
                  'excel'
                ],
            "lengthMenu": [ [-1,10, 25, 50, 100 ], ["All",10, 25, 50, 100] ]
                
            });
        
        });
    </script>
    <script>
        
        $(".generate").click(function(){
               $(".number-of-codes").attr("required", "true");
               $(".ex_date").attr("required", "true");
        })
      
    </script>
 <script>
         
             $(".delete").click(function(e){
                if(!confirm("Are You Sure You Want To Delete This Code ?!")){e.preventDefault();}
            });
            
            $(".deleteall").click(function(e){
                if(!confirm("Are You Sure You Want To Delete All Codes ?!")){e.preventDefault();}
            });
            
    </script>
    
    
</body>

</html>