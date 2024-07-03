<?php 
include_once 'connect.php';
session_start();
if(!isset($_SESSION['adminname'])){
    header("Location:index.php?msg=".urlencode("Please Login First"));
    exit();
}
function check($var){
return trim(strip_tags(stripcslashes($var)));
}

$name=$_SESSION['adminname'];
$stmt=$con->prepare("SELECT type FROM admins WHERE name=?");
$stmt->execute(array($name));
$result=$stmt->fetch();
$type=$result['type'];

$subject=$_SESSION['subject'];
$s_id = $subject;

function randomPassword() {
    $alphabet = '0123456789';
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 6; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
}


if(isset($_POST['add']))
    {
        $name=$_POST['name'];
        
        $videos=$_POST['videos'];
        $exams=$_POST['exams'];
        $homeworks=$_POST['homeworks'];
        $files=$_POST['files'];
        $hwmanswer = check($_POST['hwmanswer']);
        $videos_free = $_POST['video_free'];
        $chapter = check($_POST['chapter']);
        $arrange = check($_POST['arrange']);
        $duration = check($_POST['duration']);
        $date=date("Y-m-d h:i:s");


        $free = check($_POST['free']);

        $imageName = $_FILES['image']['name'];
        $imageSize = $_FILES['image']['size'];
        $imageTmp  = $_FILES['image']['tmp_name'];
        $imageType = $_FILES['image']['type'];
        
        $exp = explode('.' , $imageName);
        $imageExtension = strtolower(end($exp));
        $Mimage = rand(0,100000) . '_' .$imageName;
        move_uploaded_file($imageTmp , "uploads/weeks//" . $Mimage);


   if(empty($mothly_exam)){
       $mothly_exam = NULL;
   }
   
   
    

if(empty($err)){
   
        $stmt = $con->prepare("INSERT INTO weeks (name,image,subjectid,homework_model_answer,chapter,free , arrange , duration) VALUES
        (:name,:image,:subjectid,:homework_model_answer,:chapter,:free,:arrange,:duration)");
        $stmt->execute(array(
            'name' => $name,
            'image'=>$Mimage,
            'subjectid' => $_SESSION['subject'],
            'homework_model_answer'=>$hwmanswer,
            'chapter'=>$chapter,
            'free'=>$free,
            'arrange'=>$arrange,
            'duration'=>$duration
        ));
        
         $week_id = $con->lastInsertId();
         
        //  ---------videos--------------------------------------------------------------------------------
             foreach($videos as $video){
            
                    $stmt = $con->prepare("INSERT INTO week_details (week_id,product_id,type) VALUES (:week_id,:product_id,:type)");
                    $stmt->execute(array(
                        'week_id' => $week_id,
                        'product_id' => $video,
                        'type'=>'video'
                        ));
        
           }
           
        //  ---------exams--------------------------------------------------------------------------------
           foreach($exams as $exam){
            
                    $stmt = $con->prepare("INSERT INTO week_details (week_id,product_id,type) VALUES (:week_id,:product_id,:type)");
                    $stmt->execute(array(
                        'week_id' => $week_id,
                        'product_id' => $exam,
                        'type'=>'exam'
                        ));
        
           }
         //  ---------homeworks--------------------------------------------------------------------------------
           foreach($homeworks as $homework){
            
                    $stmt = $con->prepare("INSERT INTO week_details (week_id,product_id,type) VALUES (:week_id,:product_id,:type)");
                    $stmt->execute(array(
                        'week_id' => $week_id,
                        'product_id' => $homework,
                        'type'=>'homework'
                        ));
        
           }
        
         //  ---------files--------------------------------------------------------------------------------
           foreach($files as $file){
            
                    $stmt = $con->prepare("INSERT INTO week_details (week_id,product_id,type) VALUES (:week_id,:product_id,:type)");
                    $stmt->execute(array(
                        'week_id' => $week_id,
                        'product_id' => $file,
                        'type'=>'file'
                        ));
        
           }
        
        // --------Free videos--------------------------------------------------------------------------------------------
        foreach($videos_free as $video){
            
            $stmt = $con->prepare("INSERT INTO week_free_videos (week_id,video_id) VALUES (:week_id,:video_id)");
            $stmt->execute(array(
            'week_id' => $week_id,
            'video_id' => $video
        
        ));
        
        }
        
    
        header("Location:week_sort.php?id=$week_id&msgg=".urlencode("Done Adding Week"));
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
    <title>E-Learning System | Adding Week</title>
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
    <!-- jodit -->
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jodit/3.1.39/jodit.min.css">
<link rel="stylesheet" href="build/jodit.min.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/jodit/3.1.39/jodit.min.js"></script>
<script src="build/jodit.min.js"></script>
    <!-- jodit -->
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
                    <div class="row">
                        <div class="col-lg-8">
                            <h3>Admin Dashboard</h3>
                            <ul>
                                <li>
                                    <a href="statistics.php">Statistics</a>
                                </li>
                                <li>Add Week</li>
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
                                        <h3>Add New Week</h3>
                                    </div>
                                  
                                </div>
                                <form class="new-added-form" method="post" enctype="multipart/form-data">
                           
                                    <div class="row">
                                        <div class="col-xl-6 col-lg-6 col-6 form-group">
                                            <label>Name*</label>
                                            <input type="text"  class="form-control" name="name" required>
                                        </div>
                                        <div class="col-xl-6 col-lg-6 col-6 form-group">
                                            <label>Image*</label>
                                            <input type="file"  class="form-control" name="image" required>
                                        </div>
                                          <div class="col-6-xxxl col-lg-6 col-12 form-group">
                                            <label>Chapter *</label>
                                            <select class="select2" name="chapter" >
                                                <option value="">Select Chapter</option>
                                                <?php
                                                    $stmt=$con->prepare("SELECT id,name FROM week_chapters WHERE subjectid = ? ");
                                                    $stmt->execute(array($_SESSION['subject']));
                                                    $chapters=$stmt->fetchAll();
                                                    foreach($chapters as $chapter){
                                                ?>
                                                <option value="<?php echo $chapter['id']?>"><?php echo $chapter['name']?></option>
                                               
                                                <?php }?>
                                            </select>
                                        </div>
                                  
                                        
                                     <div class="col-6-xxxl col-lg-6 col-12 form-group">
                                            <label>Videos *</label>
                                            <select class="select2" multiple name="videos[]" >
                                                <?php
                                                    $stmt=$con->prepare("SELECT id,name FROM videos WHERE subjectid = ? ");
                                                    $stmt->execute(array($_SESSION['subject']));
                                                    $videos=$stmt->fetchAll();
                                                    foreach($videos as $video){
                                                ?>
                                                <option value="<?php echo $video['id']?>"><?php echo $video['name']?></option>
                                               
                                                <?php }?>
                                            </select>
                                        </div>
                                        
                                        <div class="col-6-xxxl col-lg-6 col-12 form-group">
                                            <label>Homeworks *</label>
                                            <select class="select2" multiple name="homeworks[]" >
                                                <?php
                                                    $stmt=$con->prepare("SELECT id,name FROM homeworks WHERE subjectid = ? ");
                                                    $stmt->execute(array($_SESSION['subject']));
                                                    $homeworks=$stmt->fetchAll();
                                                    foreach($homeworks as $homework){
                                                ?>
                                                <option value="<?php echo $homework['id']?>"><?php echo $homework['name']?></option>
                                               
                                                <?php }?>
                                            </select>
                                        </div>
                                       
                                        <div class="col-6-xxxl col-lg-6 col-12 form-group">
                                            <label>Quizes *</label>
                                            <select class="select2" multiple name="exams[]" >
                                                <?php
                                                    $stmt=$con->prepare("SELECT id,name FROM exams WHERE subjectid =? AND parentexam IS NULL");
                                                    $stmt->execute(array($_SESSION['subject']));
                                                    $exams=$stmt->fetchAll();
                                                    foreach($exams as $exam){
                                                ?>
                                                <option value="<?php echo $exam['id']?>"><?php echo $exam['name']?></option>
                                               
                                                <?php }?>
                                            </select>
                                        </div>

                                        
                                        
                                        <div class="col-6-xxxl col-lg-6 col-12 form-group">
                                            <label>Matrial *</label>
                                            <select class="select2" multiple name="files[]" >
                                                <?php
                                                    $stmt=$con->prepare("SELECT id,name FROM files WHERE subjectid = ? ");
                                                    $stmt->execute(array($_SESSION['subject']));
                                                    $files=$stmt->fetchAll();
                                                    foreach($files as $file){
                                                ?>
                                                <option value="<?php echo $file['id']?>"><?php echo $file['name']?></option>
                                               
                                                <?php }?>
                                            </select>
                                        </div>
                                        

                                        
                                        

                                      
                                       
                                          <div class="col-xl-6 col-lg-6 col-6 form-group">
                                            <label>Duration  *</label>
                                            <input type="number" required name="duration" class="form-control">
                                        </div>
                                         <div class="col-6-xxxl col-lg-6 col-12 form-group">
                                            <label>Lock Sequence  *</label>
                                            <input type="checkbox" name="arrange" style="width:25px;height:20px;" value="1">
                                        </div>
                                         
                                        
                                        
                                        <!--<div class="col-6-xxxl col-lg-6 col-12 form-group">-->
                                        <!--    <label>Lock  *</label>-->
                                        <!--    <input type="checkbox" name="free" style="width:25px;height:20px;" value="1">-->
                                        <!--</div>-->

                                        <!--<div class="col-6-xxxl col-lg-6 col-12 form-group">-->
                                        <!--    <label>Montly Exams *</label>-->
                                        <!--    <select class="select2" name="mothly_exam" >-->
                                        <!--        <option value="">Select Montly Exams</option>-->
                                                <?php
                                                    // $stmt=$con->prepare("SELECT id,name FROM quizes WHERE subjectid = ? ");
                                                    // $stmt->execute(array($_SESSION['subject']));
                                                    // $quizes=$stmt->fetchAll();
                                                    // foreach($quizes as $quize){
                                                ?>
                                                <!--<option value="<?php// echo $quize['id']?>"><?php //echo $quize['name']?></option>-->
                                               
                                                <?php //}?>
                                        <!--    </select>-->
                                        <!--</div>-->


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



    
    

</body>

</html>