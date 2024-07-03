<?php    
ob_start();
ini_set('session.cookie_lifetime', 60 * 60 * 24 );
ini_set('session.gc_maxlifetime', 60 * 60 * 24 );
session_start();
include_once 'connect.php';
if(!isset($_SESSION['adminname'])){
    header("Location:index.php?msg=".urlencode("Please Login First"));
    exit();
}
function check($var){
  return trim(strip_tags(stripcslashes($var)));
}
if(isset($_GET['id']) && isset($_GET['do'])){
 $id = $_GET['id'];
 $do = $_GET['do'];
$s_id=$_SESSION['subject'];
if($do=='edit') {
$stmt= $con->prepare("SELECT * FROM website_teacher WHERE id=?");
$stmt->execute(array($id));
$subjects=$stmt->fetch();
$image=$subjects['img'];
}}

if(isset($_POST['edit']))
    {
     $title=$_POST['title'];
    $name=check($_POST['name']);
    $subject=check($_POST['subject']);
     $error=array();
 
if(empty($error)){
    
    if(!empty($_FILES['image']['name'])){

        if (file_exists('../../assets/images/'.$image)) {
            unlink('../../assets/images/'.$image);
        }
        
        $imageName = $_FILES['image']['name']; 
        $imageSize = $_FILES['image']['size'];
        $imageTmp  = $_FILES['image']['tmp_name'];
        $imageType = $_FILES['image']['type'];
        $exp = explode('.' , $imageName);
        $imageExtension = strtolower(end($exp));
        $image = rand(0,100000) . '_' .$imageName;
        move_uploaded_file($imageTmp , "../../assets/images//" . $image);
     $stmt=$con->prepare("UPDATE  website_teacher SET title=?,name=?, img=? WHERE id=?");     
     $stmt->execute(array($title,$name,$image,$id));
    }else{
        $stmt=$con->prepare("UPDATE  website_teacher SET title=?,name=? WHERE id=?");     
        $stmt->execute(array($title,$name,$id));
    }
    header("Location:website_showteacher.php?mssg=".urlencode("Done Editing Teacher"));
      exit();
}
}
$stmt=$con->prepare("SELECT name FROM subjects WHERE id = ? ");   
$stmt->execute(array($_SESSION['subject']));
$subject=$stmt->fetch();

$stmt=$con->prepare("SELECT teacher_id FROM subjects WHERE id = ? ");
$stmt->execute(array($_SESSION['subject']));
$t=$stmt->fetch();

$stmt=$con->prepare("SELECT fullname FROM admins WHERE id = ? ");
$stmt->execute(array($t['teacher_id']));
$teacher=$stmt->fetch();

$stmt=$con->prepare("SELECT edu_id FROM subjects WHERE id = ? ");
$stmt->execute(array($_SESSION['subject']));
$edu=$stmt->fetch();

$stmt=$con->prepare("SELECT name FROM educationallevels WHERE id = ? ");
$stmt->execute(array($edu['edu_id']));
$edulevel=$stmt->fetch();
?>
<!doctype html>
<html class="no-js" lang="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>E-Learning System | Editing Teacher</title>
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
            <!-- jodit -->
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jodit/3.1.39/jodit.min.css">
<link rel="stylesheet" href="build/jodit.min.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/jodit/3.1.39/jodit.min.js"></script>
<script src="build/jodit.min.js"></script>
    <!-- jodit -->
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
                    <div class="row">
                        <div class="col-lg-8">
                            <h3>Admin Dashboard</h3>
                            <ul>
                                <li>
                                    <a href="statistics.php">Statistics</a>
                                </li>
                                <li>Editing Teacher</li>
                            </ul>
                        </div>
                       
                    </div>
                </div>
                <!-- Breadcubs Area End Here -->
                <!-- Account Settings Area Start Here -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="heading-layout1">
                                    <div class="item-title">
                                        <h3>Editing Teacher</h3>
                                    </div>
                                  
                                </div>
                              

                              <form class="new-added-form" method="post" enctype="multipart/form-data">
                                    <?php if(!empty($subjects['image'])){?>
                                    <div class="row">
                                        <div class="col-xl-8 col-lg-6 col-12 form-group">
                                    <?php }?>
                                    <div class="row">
                                        <div class="col-xl-6 col-lg-6 col-12 form-group">
                                            <label>Title *</label>
                                            <textarea  name="title" class="form-control" required style=""><?php echo $subjects['title']; ?></textarea>

                                        </div>
                                  
                                        
                                        <div class="col-xl-6 col-lg-6 col-12 form-group image">
                                            <label>Change Image</label>
                                            <input type="file"  class="form-control" name="image">
                                        </div>
                                        
                                     <div class="col-xl-6 col-lg-6 col-12 form-group">
                                            <label>Name *</label>
                                            <input type="text" placeholder="" class="form-control" name="name" value="<?php echo $subjects['name']; ?>" required>
                                        </div>
                                    
                                            
                                         <div class="col-12 form-group mg-t-8">
                                         <button type="submit" class="btn-fill-lg btn-gradient-yellow btn-hover-bluedark" name= "edit">Edit</button>
                                        </div>
                                        
                                    </div>
                                    
                                </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>  
                <!-- Account Settings Area End Here -->
              
            </div>
    
        <!-- Page Area End Here -->
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

</script>
<link rel="stylesheet" href="/node_modules/guppy-js/style/guppy-default.min.css">
    <script type="text/javascript" src="/node_modules/guppy-js/guppy.min.js"></script>
    <script type="text/javascript">
        window.onload = function(){
            var editor = new Jodit("#editor", {
  "toolbarSticky": false,
  "showXPathInStatusbar": false,
  "disablePlugins": "about",
  "buttons": "|,bold,strikethrough,underline,italic,|,|,ul,ol,|,outdent,indent,|,font,fontsize,brush,,table,|,align,undo,redo,\n,|"
});
        }


    </script>
  <script>
  
  
   function fetch_chapter(units){
         
          var s_id = $( "#s_id" ).val();

            $.ajax({
                        type: 'post',
                        url: 'select.php',
                        data: {
                        get_chapters:units,
                        s_id:s_id,
                        },
                        success: function (lesson) {
                        document.getElementById("chapter").innerHTML=lesson; 
                        
                        
                        }
                    });
        }
        
          function fetch_lesson(lesson){
            var s_id = $( "#s_id" ).val();

            $.ajax({
                        type: 'post',
                        url: 'select.php',
                        data: {
                        get_lesson:lesson,
                        s_id:s_id
                        },
                        success: function (lesson) {
                        document.getElementById("lesson").innerHTML=lesson; 
                        
                        
                        }
                    });
        }
    </script>
</body>

</html>
<?php ob_end_flush();?>