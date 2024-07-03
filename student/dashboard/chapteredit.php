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
$name=$_SESSION['adminname'];
$stmt=$con->prepare("SELECT type FROM admins WHERE name=?");
$stmt->execute(array($name));
$result=$stmt->fetch();
$type=$result['type'];
// if($type!="super admin" && $type!="admin"){
// header("Location:index3.php?msg=You Not Have A Permission");
// }


 if(isset($_GET['id']) && isset($_GET['do'])){
 $id = check($_GET['id']);
 $do = check($_GET['do']);

if($do=='edit') {
$stmt= $con->prepare ("SELECT * FROM branches WHERE id=?");
$stmt->execute(array($id));
$branches=$stmt->fetch();
}     
 }
  if (!isset($_POST['token'])) {
$_SESSION['token'] = bin2hex(random_bytes(32));
$token = $_SESSION['token'];
}

if (!empty($_POST['token'])) {
    if (hash_equals($_SESSION['token'], $_POST['token'])) {
         // Proceed to process the form data
    
if(isset($_POST['edit']))
    {
     $name=check($_POST['name']);
     $unit=check($_POST['unit']);
 if(empty($unit)){
         $unit = NULL;
     }
     $err4=array();
 
   
    
if(empty($err4)){
    $stmt=$con->prepare("UPDATE  branches SET name=? ,units = ?, subjectid=? WHERE id=?");     
    $stmt->execute(array($name , $unit,$_SESSION['subject'],$id));
    header("Location:chapters.php?mssg=".urlencode("Done Editing chapter"));
    exit();
}

}} else {
    error_log("CSRF Failed from file chapteredit.php", 0);
}
}

?>



<!doctype html>
<html class="no-js" lang="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>E-Learning System  | Edit chapter</title>
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
    <!-- Select 2 CSS -->
    <link rel="stylesheet" href="css\select2.min.css">
    <!-- Date Picker CSS -->
    <link rel="stylesheet" href="css\datepicker.min.css">
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
        <?php  include_once 'navbar.php' ?>
        <!-- Header Menu Area End Here -->
        <!-- Page Area Start Here -->
        <div class="dashboard-page-one">
            <!-- Sidebar Area Start Here -->
          <?php include_once 'sidebar.php' ?>
            <!-- Sidebar Area End Here -->
            <div class="dashboard-content-one">
                <!-- Breadcubs Area Start Here -->
                <div class="breadcrumbs-area">
                    <h3> Edit chapter</h3>
                    <ul>
                        <li>
                            <a href="teachers.php">chapters</a>
                        </li>
                        <li>Edit chapter</li>
                    </ul>
                </div>
                <!-- Breadcubs Area End Here -->
                <!-- Account Settings Area Start Here -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="heading-layout1">
                                    <div class="item-title">
                                        <h3>Edit chapter</h3>
                                        <?php if(!empty($err4)){  ?>
                                            <div class="alert alert-danger errors alert-dismissible fade show" role="alert">
                                                <?php echo $err4['name']; ?>
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                    <span aria-hidden="true">×</span>
                                                </button>
                                            </div>
                                        <?php }?>
                                    </div>
                               
                                </div>

                             
                                
                                <form class="new-added-form" method="post">
                                <input type="hidden" name="token" value="<?php echo $token; ?>" />

                                    <div class="row">
                                         
                                        <div class="col-xl-3 col-lg-6 col-12 form-group">
                                              
                                            <label>Name</label>
                                            <input type="text" placeholder="New Name" class="form-control" name="name" value=" <?php echo $branches['name']; ?>" required>
                                           
                                        </div>

                                     
                                           <?php if($_SESSION['subject'] == 10){ ?>
                                        <div class="col-4-xxxl col-xl-4 col-lg-3 col-12 form-group">
                                            <label>Units *</label>
                                            
                                             <select class="form-control"  name="unit" <?php echo $_SESSION['subject'] == 10 ? 'required' : ''; ?>>
                                             <option value="">Select Unit</option>
                                            <?php 
                                                $stmt=$con->prepare("SELECT * FROM branches WHERE units IS  NULL AND chapter IS NULL AND subjectid = ? ");
                                                $stmt->execute(array($_SESSION['subject']));
                                                $data=$stmt->fetchAll();
                                                foreach($data as $item){
                                            ?>
                                                 <option  <?php if($branches['unit'] == $item['id']){?> selected <?php }?>  value="<?php echo $item['id']?>"><?php echo $item['name']?></option>
                                            <?php }?>
                                            </select>  
                                       </div>
                                       <?php }?>
                                       
                                      
                                          
                                         
                                         <div class="col-12 form-group mg-t-8">
                                         <button type="submit" class="btn-fill-lg btn-gradient-yellow btn-hover-bluedark" name= "edit">Edit</button>
                                        </div>
                     </div>
                                </form>
                            </div>
                        </div>
                    </div>
                                
                </div>
                <!-- Account Settings Area End Here -->

            </div>
        </div>
        <!-- Page Area End Here -->
    </div>
     <?php include_once "footer.php";
        ob_end_flush();
     ?>
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
    <!-- Select 2 Js -->
    <script src="js\select2.min.js"></script>
    <!-- Date Picker Js -->
    <script src="js\datepicker.min.js"></script>
    <!-- Custom Js -->
    <script src="js\main.js"></script>

</body>

</html>