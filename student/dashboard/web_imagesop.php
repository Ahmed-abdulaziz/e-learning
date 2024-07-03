<?php 
include_once 'connect.php';
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
exit();
}

if(isset($_POST['add']))
    {

        $imageName = $_FILES['image']['name'];
        $imageSize = $_FILES['image']['size'];
        $imageTmp  = $_FILES['image']['tmp_name'];
        $imageType = $_FILES['image']['type'];
    
        $exp = explode('.' , $imageName);
        $imageExtension = strtolower(end($exp));
        $Mimage = rand(0,100000) . '_' .$imageName;
        move_uploaded_file($imageTmp , "../../img/course//" . $Mimage);
        
    


     $stmt=$con->prepare("INSERT INTO images (image)
         VALUES(:name)");
     $stmt->execute(array('name'=>$Mimage));
     header("Location:website_photos.php?msgg=".urlencode("Done Adding Images"));
      exit();


}


if(isset($_GET['do']) && $_GET['do'] == 'delete'){
     $IID = '' ;
    if($_GET['id']){
       $IID = $_GET['id'];
    }
    
      $stmt = $con->prepare("DELETE  FROM images WHERE id = :zid");
      $stmt->bindParam(":zid", $IID);
      $stmt->execute();
    
      header("Location:website_photos.php?msg=".urlencode("Done Deleteing image"));
      exit();
}

?>
<!doctype html>
<html class="no-js" lang="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>E-Learning System | Add Teacher</title>
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
<?php include_once 'navbar.php'; ?>

        <!-- Header Menu Area End Here -->
        <!-- Page Area Start Here -->
        <div class="dashboard-page-one">

            <!-- Sidebar Area Start Here -->
            <?php include_once 'sidebar.php'; ?>

            <!-- Sidebar Area End Here -->
            <div class="dashboard-content-one">
                <!-- Breadcubs Area Start Here -->
                <div class="breadcrumbs-area">
                    <h3>Add Image</h3>
                    <ul>
                        <li>
                            <a href="index2.php">Home</a>
                        </li>
                        <li>Add Image</li>
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
                                        <h3>Add New Photo</h3>
                                        <?php if(!empty($errr)){  ?>
                                             <div class="alert alert-danger errors alert-dismissible fade show adminerror" role="alert">
                                                    <?php echo $errr['namee']; ?>
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                        <span aria-hidden="true">×</span>
                                                    </button>
                                             </div>
                                        <?php }?>
                                    </div>
                              
                                </div>  
                                <form class="new-added-form" method="post"  enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="col-xl-3 col-lg-6 col-12 form-group">
                                            <label>Photo *</label>
                                            <input type="file" class="form-control" name="image"  value="<?php if(!empty($errr)){echo $namee;} ?>" required>
                                        </div>

                                        <div class="col-12 form-group mg-t-8">
                                            <button type="submit" class="btn-fill-lg bg-blue-dark btn-hover-yellow" name="add">Add</button>
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
 <?php include_once "footer.php";?>
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

    <script>
        $(document).ready(function () {
        setTimeout(function () {
            $('.alert').fadeOut();
        }, 4000);
        });
    </script>

</body>

</html>