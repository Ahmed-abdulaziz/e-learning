<?php 
include_once 'connect.php';
ob_start();
 session_start();
 date_default_timezone_set("Africa/Cairo");

 if(!isset($_SESSION['adminname'])){
    header("Location:index.php?msg=".urlencode("Please Login First"));
    exit();
}
$name=$_SESSION['adminname'];
$stmt=$con->prepare("SELECT type FROM admins WHERE name=?");
$stmt->execute(array($name));
$result=$stmt->fetch();
$type=$result['type'];
if($type!="admin" && $type != 'super admin'){
header("Location:index3.php?dmsg=");
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
$stmt=$con->prepare("SELECT DISTINCT groupname FROM codes WHERE groupname IS NOT NULL ");
$stmt->execute();
$codes=$stmt->fetchAll();


}
    function randomPassword() {
    $alphabet = 'abcdefghjkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ123456789';
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 8; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
}



// if(isset($_POST['edit'])){
//     $name = $_POST['name'];
//     $date = $_POST['ex-date'];
    
//     $stmt=$con->prepare("UPDATE  codes SET expiration_date=?   WHERE groupname=?");
//     $stmt->execute(array($date,$name));
    
//     header("Location:groups.php?mssg=".urlencode("Done Editing Expiration Date"));

    
// }


?>

<!doctype html>
<html class="no-js" lang="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>E-Learning System | Groups</title>
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
                        <li>Code Groups</li>
                    </ul>
                </div>
                <!-- Breadcubs Area End Here -->
                <!-- Teacher Table Area Start Here -->
                <div class="card height-auto">
                    <div class="card-body">
                        <div class="heading-layout1">
                            <div class="item-title">
                                <h3>Code Groups</h3>
                                
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
                       

                        <!--msgg for adding-->
                        <?php if(isset($_GET['msgg'])){?>
                        <div class="alert alert-success  alert-dismissible fade show settingalert">
                            <?php echo $_GET['msgg'];?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <?php }?>
                       <?php if(isset($teach)){?>
                       <div class="text-center h3 border border-danger p-3">
                           
                              <?php echo $teach['name']." - ".$edu_level['name']; ?> 
                          
                       </div>
                       <?php  }
                           ?>
                        <div class="table-responsive">
                            <table class="table display data-table text-nowrap" id="myTable">
                                <thead>
                                    <tr>
                                        <th>Group Name</th>
                                        <th>Total</th>
                                        <th>Used</th>
                                         <!--<th>Expiration Date</th>-->
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                      <?php if(isset($_SESSION['adminname'])){ foreach($codes as $code){
                                      
                                        $stmt=$con->prepare("SELECT * FROM codes WHERE groupname = ? ");
                                        $stmt->execute(array($code['groupname']));
                                        $group=$stmt->fetch();
                                        $count= $stmt->rowCount();
                                        
                                        $stmt=$con->prepare("SELECT COUNT(id) FROM codes WHERE groupname = ? AND  studentid  IS NOT NULL ");
                                        $stmt->execute(array($code['groupname']));
                                        $used=$stmt->fetch();

                                      ?>
                                        <td><?php echo $code['groupname'];  ?></td>
                                        <td><a href="used_codes.php?name=<?php echo $code['groupname']."&do=show";?>" target="_blank"><?php echo $count;  ?></a></td>
                                        <td><a href="used_codes.php?name=<?php echo $code['groupname']."&do=used";?>" target="_blank"> <?php echo $used['COUNT(id)'];  ?> </a></td>
                                        <!--<td > -->
                                        <!--<form class="d-flex" method="post">-->
                                        <!--         <input type="text" hidden value="<?php// echo $code['groupname'];  ?>" class="form-control" name="name" />-->
                                        <!--         <input type="date" value="<?php //echo $group['expiration_date'];  ?>" class="form-control btn-lg" name="ex-date" />-->
                                        <!--         <button type="submit" class="btn-fill-lg btn-gradient-yellow btn-hover-bluedark ml-1" name= "edit">Edit</button>-->
                                        <!--</form>-->
                                            
                                        <!--</td>-->
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
                this.api().columns([0]).every( function () {
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
                dom: 'Bfrtip',
                buttons: [
                  'excel'
                ]
           
                
            });
        
        });
    </script>
    <script>
        
        $(".generate").click(function(){
               $(".number-of-codes").attr("required", "true");
               $(".ex_date").attr("required", "true");
        })
      
    </script>

</body>

</html>