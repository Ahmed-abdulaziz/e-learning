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
if($type!="super admin"){
header("Location:index3.php?msg=You Not Have A Permission");
}





if(isset($_SESSION['adminname']))
{
$name=$_SESSION['adminname'];

$stmt=$con->prepare("SELECT * FROM codes_category  ");
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





// if(isset($_POST['generate'])){
//     $date=date("Y-m-d h:i:s");
//     $code=$_POST['code'];
//     $groupname=$_POST['groupname'];
 
    
//     for( $x=0 ; $x<$code ; $x++){
        
//      start:
//      $value=randomPassword();
     
//         $stmt=$con->prepare("SELECT * FROM codes WHERE code=?");
//         $stmt->execute(array($value));
//         $check=$stmt->rowCount();
//         if($check > 0){
//          goto start;
//         }
                    
//      $stmt=$con->prepare("INSERT INTO codes (code,created_at,groupname,type) VALUES(:code,:created_at,:groupname,:type)");
//      $stmt->execute(array('code'=>$value ,'created_at'=>$date,'groupname'=>$groupname,'type'=>1));
//     }
//          header("Location:generatecode.php?msgg=".urlencode("Done Generate Code"));
//          exit();
 
// }


if(isset($_GET['delete'])){
    $id=$_GET['id'];
    
    $stmt=$con->prepare("DELETE FROM codes_category WHERE id=? ");
    $stmt->execute(array($id));
     header("Location:codes-category.php?msgg=".urlencode("Done Delete code Category"));
    exit();
}
if(isset($_POST['deleteall'])){
    
    $stmt=$con->prepare("DELETE FROM codes_category ");
    $stmt->execute();
    header("Location:codes-category.php?msgg=".urlencode("Done Delete All codes Category"));
    exit();
}
?>

<!doctype html>
<html class="no-js" lang="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>E-Learning System  | Codes Category</title>
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
                        <li>Codes Category</li>
                    </ul>
                </div>
                <!-- Breadcubs Area End Here -->
                <!-- Teacher Table Area Start Here -->
                <div class="card height-auto">
                    <div class="card-body">
                        <div class="heading-layout1">
                            <div class="item-title">
                                <h3>Codes Category</h3>
                                
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
                      
                           <a href="codes-category-add.php"><button class="btn-fill-lg bg-blue-dark btn-hover-yellow btn-lg my-5">Add</button></a>
                        <div class="table-responsive">
                            <table class="table display data-table text-nowrap" id="myTable">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                         <th>Controls</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                      <?php if(isset($_SESSION['adminname'])){ foreach($codes as $code){
                                      
                                      ?>
                                        <td><?php echo $code['id'];  ?></td>
                                        <td><?php echo $code['name'];  ?></td>
                                      

                                         
                                             <td>
                                             <a href="codes-category-edit.php?delete&id=<?php echo $code['id'];  ?>"><button type="button" class="btn btn-info btn-lg   mt-1">Edit</button></a>
                                             <a href="?delete&id=<?php echo $code['id'];  ?>"><button type="button" class="btn btn-danger btn-lg delete mt-1">Delete</button></a>

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
               
            dom: 'Blfrtip',
                buttons: [
                  'excel'
                ],

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
                if(!confirm("Are You Sure You Want To Delete ?!")){e.preventDefault();}
            });
            
            $(".deleteall").click(function(e){
                if(!confirm("Are You Sure You Want To Delete All  ?!")){e.preventDefault();}
            });
            
    </script>
    
    
</body>

</html>