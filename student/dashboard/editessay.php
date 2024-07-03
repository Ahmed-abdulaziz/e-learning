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
$stmt= $con->prepare("SELECT * FROM essay WHERE id=?");
$stmt->execute(array($id));
$students=$stmt->fetch();
$image=$students['image'];
}}

if(isset($_POST['edit']))
    {
     $question=$_POST['question'];
    $answer=check($_POST['answer']);
    $degree=check($_POST['degree']);
    $status=check($_POST['status']);
    $lessonid=check($_POST['lesson']);
    
  if(empty($lessonid)){
      $lessonid = $students['lesson'];
  }
     $error=array();
 
if(empty($error)){
    
    if(!empty($_FILES['image']['name'])){

        if (file_exists('img/questions/'.$image)) {
            unlink('img/profile/'.$image);
        }
        
        $imageName = $_FILES['image']['name']; 
        $imageSize = $_FILES['image']['size'];
        $imageTmp  = $_FILES['image']['tmp_name'];
        $imageType = $_FILES['image']['type'];
        $exp = explode('.' , $imageName);
        $imageExtension = strtolower(end($exp));
        $image = rand(0,100000) . '_' .$imageName;
        move_uploaded_file($imageTmp , "img/questions//" . $image);
     $stmt=$con->prepare("UPDATE  essay SET question=?,modelanswer=?,degree=?,image=?,status=?,lesson=? WHERE id=?");     
     $stmt->execute(array($question,$answer,$degree,$image,$status,$lessonid,$id));
    }else{
        $stmt=$con->prepare("UPDATE  essay SET question=?,modelanswer=?,degree=?,status=?,lesson = ? WHERE id=?");     
        $stmt->execute(array($question,$answer,$degree,$status,$lessonid,$id));
    }
    header("Location:essay.php?mssg=".urlencode("Done Editing Question"));
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
    <title>E-Learning System | Editing Question</title>
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
                                <li>Edit Question</li>
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
                                        <h3>Edit Question</h3>
                                    </div>
                                  
                                </div>
                              

                              <form class="new-added-form" method="post" enctype="multipart/form-data">
                                    <?php if(!empty($students['image'])){?>
                                    <div class="row">
                                        <div class="col-xl-8 col-lg-6 col-12 form-group">
                                    <?php }?>
                                    <div class="row">
                                        <div class="col-xl-12 col-lg-12 col-12 form-group">
                                            <label>Question *</label>
                                            <textarea  name="question" class="form-control" required style="direction: rtl;"><?php echo $students['question']; ?></textarea>

                                        </div>
                                             <div class="col-6-xxxl col-lg-6 col-12 form-group">
                                            <label>Level *</label>
                                            <select class="select2" name="status"  required>
                                                <!-- <option >Please Select</option> -->
                                                <option>Easy</option>
                                                <option>medium</option>
                                                <option>Difficult</option>
                                            </select>
                                        </div>
                                        
                                        <div class="col-xl-6 col-lg-6 col-12 form-group image">
                                            <label>Change Image</label>
                                            <input type="file"  class="form-control" name="image">
                                        </div>
                                        
                                     <div class="col-xl-6 col-lg-6 col-12 form-group">
                                            <label>Answer *</label>
                                            <input type="text" placeholder="" class="form-control" name="answer" value="<?php echo $students['modelanswer']; ?>" required>
                                        </div>
                                           <div class="col-xl-6 col-lg-6 col-12 form-group">
                                            <label>Degree *</label>
                                            <input type="number" min="0" placeholder="" class="form-control" name="degree" value="<?php echo $students['degree']; ?>" required>
                                        </div>
                                            <?php if($s_id != 10){ ?>   
                                         <div class="col-xl-6 col-lg-6 col-12 form-group">
                                                <label>Chapter *</label>
                                                    <?php 
                                                    $stmt=$con->prepare("SELECT chapter , name FROM branches WHERE id =? ");
                                                    $stmt->execute(array($students['lesson']));
                                                    $chap=$stmt->fetch();
                                                    
                                                    $stmt=$con->prepare("SELECT  name FROM branches WHERE id =? ");
                                                    $stmt->execute(array($chap['chapter']));
                                                    $c=$stmt->fetch();
                                                    
                                                        
                                                    ?>
                                                <input type="text" placeholder="" class="form-control mb-1" disabled value="<?php echo $c['name']; ?>">
                                                
                                                 <select class="form-control"  name="chapter" onchange="fetch_lesson(this.value);">
                                                     <option value="">Select Chapter</option>
                                                    <?php 
                                                        $stmt=$con->prepare("SELECT name,id FROM branches WHERE chapter IS NULL AND subjectid=? ");
                                                        $stmt->execute(array($s_id));
                                                        $chapters=$stmt->fetchAll();
                                                        foreach($chapters as $chapter){
                                                    ?>
                                                             <option value="<?php echo $chapter['id']?>"><?php echo $chapter['name']?></option>
                                                    <?php }?>
                                                </select>  
                                     </div>
                                <div class="col-xl-6 col-lg-6 col-12 form-group">
                                        <label>Lessons *</label>
                                             <input type="text" placeholder="" class="form-control mb-1" disabled value="<?php echo $chap['name']; ?>">
                                        
                                        <select class="form-control"   id="lesson" name="lesson" >  
                                                <option value="">Select Lesson</option>
                                        </select>              
                                </div>
                                <?php }else{?>
                                          <div class="col-4-xxxl col-xl-4 col-lg-4 col-12 form-group">
                                                <label>Units *</label>
                                                 <?php 
                                                    $stmt=$con->prepare("SELECT * FROM branches WHERE id =? ");
                                                    $stmt->execute(array($students['lesson']));
                                                    $chap=$stmt->fetch();
                                                    
                                                    $stmt=$con->prepare("SELECT  * FROM branches WHERE id =? ");
                                                    $stmt->execute(array($chap['chapter']));
                                                    $c=$stmt->fetch();
                                                    
                                                    $stmt=$con->prepare("SELECT  name FROM branches WHERE id =? ");
                                                    $stmt->execute(array($c['units']));
                                                    $u=$stmt->fetch();

                                                    ?>
                                                <input type="text" placeholder="" class="form-control mb-1" disabled value="<?php echo $u['name']; ?>">
                                                
                                                 <select class="form-control"  name="unit" onchange="fetch_chapter(this.value);">
                                                 <option value="">Select Unit</option>
                                                <?php 
                                                    $stmt=$con->prepare("SELECT * FROM branches WHERE units IS  NULL AND chapter IS NULL AND subjectid = ?");
                                                    $stmt->execute(array(10));
                                                    $units=$stmt->fetchAll();
                                                    foreach($units as $unit){
                                                ?>
                                                         <option value="<?php echo $unit['id']?>"><?php echo $unit['name']?></option>
                                                <?php }?>
                                                </select>  
                                   </div>
                                    <div class="col-4-xxxl col-xl-4 col-lg-4 col-12 form-group">
                                                    <label>Chapters *</label>
                                                     <input type="text" placeholder="" class="form-control mb-1" disabled value="<?php echo $c['name']; ?>">
                                                        <select class="form-control"   id="chapter" onchange="fetch_lesson(this.value);" name="chapter" >  
                                                            <option value="">Select Chapter</option>
                                                        </select>              
                                    </div>
                                    <div class="col-4-xxxl col-xl-4 col-lg-4 col-12 form-group">
                                                    <label>Lessons *</label>
                                                     <input type="text" placeholder="" class="form-control mb-1" disabled value="<?php echo $chap['name']; ?>">
                                                        <select class="form-control"   id="lesson" name="lesson" >  
                                                            <option value="">Select Lesson</option>
                                                        </select>              
                                    </div>
                                <?php }?>
                                     <input type="text" value="<?php echo $s_id;?>" id="s_id" hidden>

                                        <div class="col-12 form-group mg-t-8">
                                           
                                            <button type="submit" class="btn-fill-lg bg-blue-dark btn-hover-yellow" name="edit">Edit</button>
                                        </div>
                                         
                                        
                                        <?php if(!empty($students['image'])){?>
                                        </div>
                                        </div>
                                        
                                        <div class="col-xl-4 col-lg-6 col-12 form-group oldimage">
                                            <label>Current Image</label>
                                            <image src="img/questions/<?php echo $students['image'];?>">
                                            </image>
                                        </div>
                                        
                                        <?php }?>
                                        
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