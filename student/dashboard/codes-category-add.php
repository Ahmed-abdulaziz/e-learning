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






if(isset($_POST['add'])){
    $cat_name=$_POST['name'];
 
     
        $stmt=$con->prepare("SELECT * FROM codes_category WHERE name=?");
        $stmt->execute(array($cat_name));
        $check=$stmt->rowCount();
        if($check > 0){
             header("Location:codes-category-add.php?msg=".urlencode("Name Used Before"));
             exit();
        }
                    
     $stmt=$con->prepare("INSERT INTO codes_category (name) VALUES(:name)");
     $stmt->execute(array('name'=>$cat_name ));
    
         header("Location:codes-category.php?msgg=".urlencode("Done Add  Category"));
         exit();
 
}
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
                        <form method="post" class="mg-b-20 d-flex flex-wrap">
  
                           <div class="col-xl-2 col-lg-2 col-6 form-group">
                                 <label>Category Name</label>
                                <input type="text" placeholder="Category Name" class="form-control number-of-codes" name="name" >
                            </div>
                            
  
                      
                            
                            <div class="col-xl-6 col-lg-6 col-12 form-group ">
                                <button type="submit" class="btn-fill-lg bg-blue-dark btn-hover-yellow generate mt-5" name="add">Add</button>
                            </div>
                         
                        </form>
                      
                        
                      
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
                if(!confirm("Are You Sure You Want To Delete ?!")){e.preventDefault();}
            });
            
            $(".deleteall").click(function(e){
                if(!confirm("Are You Sure You Want To Delete All  ?!")){e.preventDefault();}
            });
            
    </script>
    
    
</body>

</html>