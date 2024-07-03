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
$stmt=$con->prepare("SELECT COUNT(id) FROM codes WHERE studentid IS NOT NULL");
$stmt->execute();
$totalcodes=$stmt->fetch();
}
    function randomPassword() {
    $alphabet = '123456789';
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 8; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
}

function randomLetter(){
$seed = str_split('ABCDEFGHIJKLMNOPQRSTUVWXYZ'); // and any other characters
    shuffle($seed); // probably optional since array_is randomized; this may be redundant
    $rand = '';
    foreach (array_rand($seed, 2) as $k) $rand .= $seed[$k];
 return $rand;
}


// $date=date("Y-m-d h:i");

if(isset($_POST['generate'])){
    
    $name=$_SESSION['adminname'];
    $stmt=$con->prepare("SELECT type FROM admins WHERE name=?");
    $stmt->execute(array($name));
    $result=$stmt->fetch();
    $type=$result['type'];
    $codetype=$_POST['codetype'];

    if($type!="super admin"){
        header("Location:generalcodes.php?msg=You Not Have A Permission");
        }else{
            
                $code=$_POST['code'];
                $price=$_POST['price'];
                $date= date("Y-m-d h:i:s");
         
                 
                    
            for( $x=0 ; $x<$code ; $x++){
                   start:
                     $value=randomPassword().randomLetter();
                     
             
                    $stmt=$con->prepare("SELECT * FROM codes WHERE code=?");
                    $stmt->execute(array($value));
                    $check=$stmt->rowCount();
                    if($check > 0){
                         goto start;
                    }
            
                    $stmt=$con->prepare("INSERT INTO codes (code,price,created_at,type) VALUES(:code,:price,:created_at,:type)");
                    $stmt->execute(array('code'=>$codetype.$value ,'price'=>$price ,'created_at'=>$date,'type'=>$codetype));
        
            }
                // -----
                
                    $stmt=$con->prepare("SELECT * FROM codes ORDER BY id DESC LIMIT $code");
                    $stmt->execute();
                    $codes=$stmt->fetchAll();
         
        }
   
  
}

if(isset($_POST['search'])){
    
        $code=$_POST['code'];
        $price=$_POST['price'];
        $created_at=$_POST['created_at'];
        $studentid=$_POST['student_id'];
        $type=$_POST['type'];
        
        $arr=array();
        array_push($arr,"studentid IS NOT NULL");

            if(!empty($code)){
                 array_push($arr,"code ='$code'");
            }
            if(!empty($price)){
               
                  array_push($arr,"price = ". $price);
            }
             if(!empty($created_at)){
                 
                array_push($arr,"created_at LIKE '%$created_at%'");
            }
              if(!empty($studentid)){

                  array_push($arr,"studentid = ". $studentid);
            }
            if(!empty($type)){

                  array_push($arr,"type = '$type'");
            }
           
             if(!empty($arr)){
                 $option = implode(" AND ",$arr);
                        $query="SELECT  *  FROM codes WHERE $option ";

                }else{
                        $query="SELECT  *  FROM codes";
                }
                                //  echo $query;die;

                    $stmt=$con->prepare("$query");
                    $stmt->execute();
                    $codes=$stmt->fetchAll();
                 
}

if(isset($_GET['clear'])){
    $id=$_GET['id'];
    
     $stmt=$con->prepare("UPDATE  codes SET studentid=?   WHERE id=?");     
     $stmt->execute(array(NULL,$id));
     header("Location:generalcodes.php?msgg=".urlencode("Done Clear student from code"));
 exit();
}elseif(isset($_GET['delete'])){
    $id=$_GET['id'];
    
        $stmt=$con->prepare("DELETE FROM codes WHERE id=? ");
        $stmt->execute(array($id));
        
        header("Location:generalcodes.php?msgg=".urlencode("Done Delete code"));
        exit();
}
?>

<!doctype html>
<html class="no-js" lang="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>E-Learning System | General Codes</title>
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
                        <li>General Codes</li>
                    </ul>
                </div>
                <!-- Breadcubs Area End Here -->
                <!-- Teacher Table Area Start Here -->
                <div class="card height-auto">
                    <div class="card-body">
                        <div class="heading-layout1">
                            <div class="item-title">
                                <h3>Total Used Codes (<?php if(isset($codes)) echo COUNT ($codes); else echo $totalcodes['COUNT(id)']; ?>)</h3>
                                
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
                                     <form method="post" class="mg-b-20 border p-3">
                                         <h3>Search</h3>
                                        <div class="row">
                                                      <div class="col-xl-2 col-lg-2 col-6 form-group">
                                                    <label>Code</label>
                                                    <input type="text" placeholder="Code" class="form-control" name="code">
                                                        
                                                </div>
                                                
                                                <div class="col-xl-2 col-lg-2 col-6 form-group">
                                                      <label>Price </label>
                                                      <select class="form-control" name="price">
                                                            <option value=''>Select Price</option>
                                                            <?php
                                                                $stmt=$con->prepare("SELECT DISTINCT price FROM codes");
                                                                $stmt->execute();
                                                                $prices=$stmt->fetchAll();
                                                                foreach($prices as $price){
                                                                ?>
                                                                     <option value="<?=$price['price']?>"><?=$price['price']?></option>
                                                                 <?php }?>
                                                      </select>
                                                   
                                                </div>
                                                
                                                <div class="col-xl-2 col-lg-2 col-6 form-group">
                                                      <label>Category </label>
                                                      <select class="form-control" name="type">
                                                            <option value=''>Select Category</option>
                                                            <?php
                                                                $stmt=$con->prepare("SELECT DISTINCT type FROM codes");
                                                                $stmt->execute();
                                                                $cats=$stmt->fetchAll();
                                                                foreach($cats as $cat){
                                                                ?>
                                                                     <option value="<?=$cat['type']?>"><?=$cat['type']?></option>
                                                                 <?php }?>
                                                      </select>
                                                   
                                                </div>
                                                
                                                 <div class="col-xl-2 col-lg-2 col-6 form-group">
                                                      <label>Created At </label>
                                                      <select class="form-control" name="created_at">
                                                            <option value=''>Select Created At</option>
                                                            <?php
                                                                $stmt=$con->prepare("SELECT DISTINCT created_at FROM codes");
                                                                $stmt->execute();
                                                                $created=$stmt->fetchAll();
                                                                foreach($created as $create){
                                                                ?>
                                                                     <option value="<?=$create['created_at']?>"><?=$create['created_at']?></option>
                                                                 <?php }?>
                                                      </select>
                                                   
                                                </div>
                                                
                                                 <div class="col-xl-2 col-lg-2 col-6 form-group">
                                                    <label>Student Id </label>
                                                    <input type="number"  placeholder="Student Id" class="form-control" name="student_id">
                                                            
                                                </div>

                                        <div class="col-xl-2 col-lg-2 col-6 form-group my-5">
                                            <button type="submit" class="btn-fill-lg bg-blue-dark btn-hover-yellow " name="search">Search</button>
                                        </div>
                                        
                                        </div>
                                    </form>

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
                                        <th>Code</th>
                                         <th>Price</th>
                                        <th>Student Name</th>
                                         <th>created_at</th>
                                        <th>Status</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                      <?php if(isset($codes)){ if(isset($_SESSION['adminname'])){ foreach($codes as $code){
                                      
                                      if($code['studentid'] != NULL){

                                        $stmt=$con->prepare("SELECT * FROM students WHERE id=? ");
                                        $stmt->execute(array($code['studentid']));
                                        $students=$stmt->fetch();

                                      }
                                      
                                      
                                      ?>
                                        
                                        <td><?php echo $code['code'];  ?></td>
                                          <td><?php echo $code['price'];  ?></td>
                                        <td><?php  if(!empty($code['studentid'])){echo $students['name']; } ?></td>
                                        <td><?php echo $code['created_at'];  ?></td>
                                        
                                    <td>
                                          <?php if($code['studentid'] != NULL){?>
                                           
                                        <button type="button" class="btn btn-success btn-lg btn-block mb-2">Used</button>
                                        <a href="?clear&id=<?php echo $code['id'];  ?>"><button type="button" class="btn btn-success btn-lg btn-block">Clear student</button></a>
                                
                                        <?php }else{?>
                                            <button type="button" class="btn btn-warning btn-lg btn-block">Pending</button>
                             <?php } ?>
                                              <a href="?delete&id=<?php echo $code['id'];  ?>"><button type="button" class="btn btn-danger btn-lg btn-block">Delete</button></a>
                                        </td>
                                    </tr>            
                                
     <?php }} }?>
                             
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
                this.api().columns([3]).every( function () {
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

</body>

</html>