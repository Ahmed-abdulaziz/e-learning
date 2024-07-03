<?php 
include_once 'connect.php';
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

$subject=$_SESSION['subject'];

if(isset($_POST['submit'])){
   $lessonid=check($_POST['lesson']);
   $chapterid=check($_POST['chapter']);
   $branchid=check($_POST['branch']);


}elseif(isset($_GET['lesson'])){
        $lessonid=check($_GET['lesson']);
        $chap=check($_GET['chapter']);
        
        $stmt=$con->prepare("SELECT name , branch FROM branches WHERE id = ? ");
        $stmt->execute(array($chap));
        $chapter=$stmt->fetch();
        $chapterid = $chapter['name'];
        $branchid=$chapter['branch'];
        
}else{
    $lessonid = null;

}



    if (!isset($_POST['token'])) {
        $_SESSION['token'] = bin2hex(random_bytes(32));
        $token = $_SESSION['token'];
    }

// if (!empty($_POST['token'])) {
//     if (hash_equals($_SESSION['token'], $_POST['token'])) {
         // Proceed to process the form data
if(isset($_POST['add']))
    {
     $question=check($_POST['question']);
     $choice1=check($_POST['choice1']);
     $choice2=check($_POST['choice2']);
     $choice3=check($_POST['choice3']);
     $choice4=check($_POST['choice4']);
     $answer=check($_POST['answer']);    
     $subjects=$_POST['subjects'];
     $status=check($_POST['status']);
     $lessonid=check($_POST['lessonid']);
     $chapterid=check($_POST['chapterid']);
     $branchid=check($_POST['branchid']);

     
$err=array();
if(empty($err)){
    if(!empty($_FILES['image']['name'])){
        $imageName = $_FILES['image']['name']; 
        $imageSize = $_FILES['image']['size'];
        $imageTmp  = $_FILES['image']['tmp_name'];
        $imageType = $_FILES['image']['type'];
        $allowedExts = array("jpeg", "jpg", "png");
        $exp = explode('.' , $imageName);
        $imageExtension = strtolower(end($exp));
        if(in_array($imageExtension,$allowedExts) && ( ($imageType == "image/jpeg") ||  ($imageType == "image/pjpeg") || ($imageType == "image/png") || ($imageType == "image/gif") && ($imageType == "image/bmp") || ($imageType == "image/x-icon") )  ){
            $image = rand(0,100000) . '_' .$imageName;
            move_uploaded_file($imageTmp , "img/questions//" . $image);
        }
        $stmt=$con->prepare("INSERT INTO questions (question,choice1,choice2,choice3,choice4,answer,subjectid,image,status,lessons,chapter,branch)
             VALUES(:question,:choice1,:choice2,:choice3,:choice4,:answer,:subjectid,:image,:status,:lessons,:chapter,:branch)");
        $stmt->execute(array('question'=>$question ,'choice1'=>$choice1,'choice2'=>$choice2,'choice3'=>$choice3,'choice4'=>$choice4,'answer'=>$answer,'subjectid'=>$subject,'image'=>$image,'status'=>$status , 'lessons'=>$lessonid,'chapter'=>$chapterid,'branch'=>$branchid));
    }else{
        $stmt = $con->prepare("INSERT INTO questions (question,choice1,choice2,choice3,choice4,answer,subjectid,status,lessons,chapter,branch) 
            VALUES (:question,:choice1,:choice2,:choice3,:choice4,:answer,:subjectid,:status,:lessons,:chapter,:branch)");
        $stmt->execute(array('question' => $question, 'choice1' => $choice1, 'choice2' => $choice2, 'choice3' => $choice3, 'choice4' => $choice4, 'answer' => $answer, 'subjectid' => $subject,'status'=>$status , 'lessons'=>$lessonid,'chapter'=>$chapterid,'branch'=>$branchid));
    
    }
    
$stmt=$con->prepare("SELECT id FROM branches WHERE name = ? ");
$stmt->execute(array($chapterid));
$chapter=$stmt->fetch();



     header("Location:addmultichoice.php?lesson=".$lessonid."&chapter=".$chapter['id']."&branch=".$branchid."&msgg=".urlencode("Done Adding Question"));  // t -> token
     exit();
}
}
// } else {
//     error_log("CSRF Failed from file addmultichoice.php", 0);
// }
// }
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
    <title>E-Learning System | Adding Question</title>
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
    <script src="integration/WIRISplugins.js"></script>
    <script src="node_modules/@wiris/mathtype-generic/wirisplugin-generic.js"></script>

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
                    <div class="row">
                        <div class="col-lg-8">
                            <h3>Admin Dashboard</h3>
                            <ul>
                                <li>
                                    <a href="statistics.php">Statistics</a>
                                </li>
                                <li>Add Multichoice</li>
                            </ul>
                        </div>
                        <div class="col-lg-4">
                            <div class="dashboard-summery-one subject">
                                <div class="item-content">
                                    <div class="item-number"><?php echo $subject['name']; echo " / ". $teacher['fullname'];  ?></div>
                                     <div class="item-number"><?php echo $edulevel['name'];  ?></div>
                                </div>
                            </div>
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
                                        <h3>Add New Question</h3>
                                    </div>
                                </div>
                                
                                
                                <?php if(isset($_GET['msgg'])){?>
                                    <div class="alert alert-success  alert-dismissible fade show settingalert">
                                        <?php echo check($_GET['msgg']);?>
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div> 
                                <?php } ?>
                                
                                <form class="new-added-form" id="new_document_attachment"   method="post" enctype="multipart/form-data">
                                    <input type="hidden" name="token" value="<?php echo $token; ?>" />
                                    <input type="hidden" name="lessonid" value="<?php echo $lessonid; ?>" />
                                    <input type="hidden" name="chapterid" value="<?php echo $chapterid; ?>" />
                                    <input type="hidden" name="branchid" value="<?php if (isset($_GET['branch'])){ echo $_GET['branch']; }else{ echo $branchid; }?>" />


                                    <div class="row">
                                        <div class="col-xl-4 col-lg-6 col-12 form-group">
                                            
                                              <div class="col-lg-12 mb-3 p-3" style="border: 2px solid;">
                                            <div id="toolbar1"></div>
                                            <hr>
                                            <div style="min-height:100px;background-color:#fff" class="p-3" id="htmlEditor1"  contenteditable="true">Type here...</div>
                                            <input value="" name="editor" id="editor1" hidden>
                                        </div>
                                        
                                        
                                        </div>
                                            <div class="col-4-xxxl col-lg-6 col-12 form-group">
                                            <label>Level *</label>
                                            <select class="select2" name="status" required>
                                                <!-- <option >Please Select</option> -->
                                                <option>Easy</option>
                                                <option>medium</option>
                                                <option>Difficult</option>
                                            </select>
                                        </div>
                                        <div class="col-xl-4 col-lg-6 col-12 form-group image">
                                            <label>Image</label>
                                            <input type="file"  class="form-control" name="image">
                                        </div>
                                        
                                        <div class="col-xl-3 col-lg-6 col-12 form-group">
                                            <label>Choice1 *</label>
                                            <input type="text" placeholder="" class="form-control" name="choice1" required>
                                        </div>
                                     <div class="col-xl-3 col-lg-6 col-12 form-group">
                                            <label>Choice2 *</label>
                                            <input type="text" placeholder="" class="form-control" name="choice2" required>
                                        </div>
                                           <div class="col-xl-3 col-lg-6 col-12 form-group">
                                            <label>Choice3 *</label>
                                            <input type="text" placeholder="" class="form-control" name="choice3" value="">
                                        </div>
                                           <div class="col-xl-3 col-lg-6 col-12 form-group">
                                            <label>Choice4 *</label>
                                            <input type="text" placeholder="" class="form-control" name="choice4" value="">
                                        </div>
                                         <div class="col-12-xxxl col-lg-6 col-12 form-group">
                                            <label>Answer *</label>
                                            <select class="select2" name="answer" required>
                                                <option value="1">Choice1</option>
                                                <option value="2">Choice2</option>
                                                <option value="3">Choice3</option>
                                                <option value="4">Choice4</option>
                                            </select>
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
        function mathType(id){
            var genericIntegrationProperties = {};
            genericIntegrationProperties.target = document.getElementById('htmlEditor'+id);
            genericIntegrationProperties.toolbar = document.getElementById('toolbar'+id);
          
            // We just added this line:
            genericIntegrationProperties.configurationService = 'integration/configurationjs.php';
          
            // GenericIntegration instance.
            var genericIntegrationInstance = new WirisPlugin.GenericIntegration(genericIntegrationProperties);
            genericIntegrationInstance.init();
            genericIntegrationInstance.listeners.fire('onTargetReady', {});
            
            $('body').on('DOMSubtreeModified', '#htmlEditor'+id, function(){
                var x = document.getElementById("htmlEditor"+id).innerHTML;
                document.getElementById("editor"+id).value = x;
            });
        }
    
        mathType('1');
     

    </script>
    
</body>

</html>