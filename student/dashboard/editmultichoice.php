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
if(isset($_GET['id']) && isset($_GET['do'])){
 $id = $_GET['id'];
 $do = $_GET['do'];
$s_id=$_SESSION['subject'];
if($do=='edit') {
$stmt= $con->prepare ("SELECT * FROM questions WHERE id=?");
$stmt->execute(array($id));
$students=$stmt->fetch();
$image=$students['image'];
}}
if(isset($_GET['given_id'])){
    
    $given_id = $_GET['given_id'];
  
}else{
        $given_id = NULL;
        
}
if(isset($_POST['edit']))
    {
    $question=$_POST['editor'];
    $choice1=$_POST['choice1'];
    $choice2=$_POST['choice2'];
    $choice3=$_POST['choice3'];
    $choice4=$_POST['choice4'];
    $answer=check($_POST['answer']);
    $status=check($_POST['status']);
    $lessonid=check($_POST['lesson']);
    $given_id=check($_POST['given_id']);
  if(empty($lessonid)){
      $lessonid = $students['lesson'];;
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
        if(!empty($students['image'])){
             unlink('img/questions/'.$students['image']); // delete Old
        }
       
        move_uploaded_file($imageTmp , "img/questions//" . $image);
    }else{
        $image = $students['image'];
    }  
        //--------------Choices-----------------------------------------------------------------------
    
         if(!empty($_FILES['imgchoice1']['name'])){
            $imgchoice1Name = $_FILES['imgchoice1']['name']; 
            $imgchoice1Size = $_FILES['imgchoice1']['size'];
            $imgchoice1Tmp  = $_FILES['imgchoice1']['tmp_name'];
            $imgchoice1Type = $_FILES['imgchoice1']['type'];
            $exp = explode('.' , $imgchoice1Name);
            $imgchoice1Extension = strtolower(end($exp));
            $imgchoice1 = rand(0,100000) . '_' .$imgchoice1Name;
              if(!empty($students['imgchoice1'])){
                 unlink('img/questions/'.$students['imgchoice1']); // delete Old
              }
            move_uploaded_file($imgchoice1Tmp , "img/questions//" . $imgchoice1);
        }else{
            $imgchoice1 =  $students['imgchoice1'];
        }
        // -------------------
        if(!empty($_FILES['imgchoice2']['name'])){
            $imgchoice2Name = $_FILES['imgchoice2']['name']; 
            $imgchoice2Size = $_FILES['imgchoice2']['size'];
            $imgchoice2Tmp  = $_FILES['imgchoice2']['tmp_name'];
            $imgchoice2Type = $_FILES['imgchoice2']['type'];
            $exp = explode('.' , $imgchoice2Name);
            $imgchoice2Extension = strtolower(end($exp));
            $imgchoice2 = rand(0,100000) . '_' .$imgchoice2Name;
             if(!empty($students['imgchoice2'])){
                 unlink('img/questions/'.$students['imgchoice2']); // delete Old
              }
            move_uploaded_file($imgchoice2Tmp , "img/questions//" . $imgchoice2);
        }else{
            $imgchoice2 = $students['imgchoice2'];
        }
        // ----------------------------
        
         if(!empty($_FILES['imgchoice3']['name'])){
            $imgchoice3Name = $_FILES['imgchoice3']['name']; 
            $imgchoice3Size = $_FILES['imgchoice3']['size'];
            $imgchoice3Tmp  = $_FILES['imgchoice3']['tmp_name'];
            $imgchoice3Type = $_FILES['imgchoice3']['type'];
            $exp = explode('.' , $imgchoice3Name);
            $imgchoice3Extension = strtolower(end($exp));
            $imgchoice3 = rand(0,100000) . '_' .$imgchoice3Name;
             if(!empty($students['imgchoice13'])){
                 unlink('img/questions/'.$students['imgchoice3']); // delete Old
              }
            move_uploaded_file($imgchoice3Tmp , "img/questions//" . $imgchoice3);
        }else{
            $imgchoice3 = $students['imgchoice3'];
        }
        
        // ----------------------------------------------
        
         if(!empty($_FILES['imgchoice4']['name'])){
            $imgchoice4Name = $_FILES['imgchoice4']['name']; 
            $imgchoice4Size = $_FILES['imgchoice4']['size'];
            $imgchoice4Tmp  = $_FILES['imgchoice4']['tmp_name'];
            $imgchoice4Type = $_FILES['imgchoice4']['type'];
            $exp = explode('.' , $imgchoice4Name);
            $imgchoice4Extension = strtolower(end($exp));
            $imgchoice4 = rand(0,100000) . '_' .$imgchoice4Name;
             if(!empty($students['imgchoice4'])){
                 unlink('img/questions/'.$students['imgchoice4']); // delete Old
              }
            move_uploaded_file($imgchoice4Tmp , "img/questions//" . $imgchoice4);
        }else{
            $imgchoice4 = $students['imgchoice4'];
        }
    
    
    // -------------------------------------------------------------------------------------------------------------
    
         $stmt=$con->prepare("UPDATE  questions SET question=? ,choice1=? , choice2=?  ,choice3=?,choice4=? ,answer=?,image=?,status=?,lesson=? , imgchoice1 = ?,imgchoice2 = ? ,imgchoice3 = ?,imgchoice4 = ? WHERE id=?");     
         $stmt->execute(array($question,$choice1,$choice2,$choice3,$choice4,$answer,$image,$status,$lessonid, $imgchoice1 , $imgchoice2 , $imgchoice3,$imgchoice4,$id));
   
    
       if($given_id != NULL){
            header("Location:multichoice.php?given_id=$given_id&msg=".urlencode("Done Editing Question"));
            exit();
      }else{
         header("Location:multichoice.php?msg=".urlencode("Done Editing Question"));
         exit(); 
      }

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
    <title>E-Learning System | Account Setting</title>
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
    
    
    <script src="integration/WIRISplugins.js"></script>
    <script src="node_modules/@wiris/mathtype-generic/wirisplugin-generic.js"></script>

<style>
    button:disabled,
    button[disabled]{
        background: #999;
        color: #333;
    }
    button:disabled:hover,
    button[disabled]:hover{
        background: #999;
        color: #333;
    }
</style>
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
                                <li>Edit Multichoice</li>
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
                              <input type="text" hidden value="<?php echo $given_id;?>"  class="form-control" name="given_id">
                                    <?php if(!empty($students['image'])){?>
                                    <div class="row">
                                        <div class="col-xl-8 col-lg-6 col-12 form-group">
                                    <?php }?>
                                        <div class="row">
                                            
                                             <div class="col-xl-12 col-lg-12 col-12 form-group">
                                            <label>Question *</label>
                                            <!--<textarea name="editor" class="form-control" ></textarea>-->
                                            <div id="toolbar"></div>
                                            <hr>
                                            <div style="min-height:100px;border:1px solid #333;border-radius: 5px;" class="p-3" id="htmlEditor"  contenteditable="true"><?php echo $students['question']; ?></div>
                                            <input  name="editor" id="editor" hidden>
                                        </div>
                                      
                                        <div class="col-6-xxxl col-lg-4 col-12 form-group">
                                            <label>Level *</label>
                                            <select class="select2" name="status"  required>
                                                <!-- <option >Please Select</option> -->
                                                <option>Easy</option>
                                                <option>Medium</option>
                                                <option>Difficult</option>
                                            </select>
                                        </div>
                                        
                                        <div class="col-xl-6 col-lg-6 col-12 form-group image">
                                            <label>Change Image</label>
                                            <input type="file"  class="form-control" name="image">
                                        </div>

                                         <div class="col-xl-3 col-lg-6 col-12 form-group">
                                            <label>Choice1 *</label>
                                            <div id="toolbar2"></div>
                                            <div class="choices" style="min-height: 40px;background-color:#fff;padding: 5px;border:1px solid #333;border-radius: 5px;" id="htmlEditor2"  contenteditable="true"><?php echo $students['choice1']; ?></div>
                                            <input value="test " name="choice1" id="editor2" hidden>
                                            <!--<input type="text" placeholder="" class="form-control " name="choice1">-->
                                        </div>
                                          <div class="col-xl-3 col-lg-6 col-12 form-group">
                                            <label>Choice2 *</label>
                                            <div id="toolbar3"></div>
                                            <div class="choices" style="min-height: 40px;background-color:#fff;padding: 5px;border:1px solid #333;border-radius: 5px;" id="htmlEditor3"  contenteditable="true"><?php echo $students['choice2']; ?></div>
                                            <input value="test " name="choice2" id="editor3" hidden>
                                            <!--<input type="text" placeholder="" class="form-control " name="choice1">-->
                                        </div>
                                          <div class="col-xl-3 col-lg-6 col-12 form-group">
                                            <label>Choice3 *</label>
                                            <div id="toolbar4"></div>
                                            <div class="choices" style="min-height: 40px;background-color:#fff;padding: 5px;border:1px solid #333;border-radius: 5px;" id="htmlEditor4"  contenteditable="true"><?php echo $students['choice3']; ?></div>
                                            <input value="test " name="choice3" id="editor4" hidden>
                                            <!--<input type="text" placeholder="" class="form-control " name="choice1">-->
                                        </div>
                                           <div class="col-xl-3 col-lg-6 col-12 form-group">
                                            <label>Choice4 *</label>
                                            <div id="toolbar5"></div>
                                            <div class="choices" style="min-height: 40px;background-color:#fff;padding: 5px;border:1px solid #333;border-radius: 5px;" id="htmlEditor5"  contenteditable="true"><?php echo $students['choice4']; ?></div>
                                            <input value="test " name="choice4" id="editor5" hidden>
                                            <!--<input type="text" placeholder="" class="form-control " name="choice1">-->
                                        </div>
                                             <!---->
                                            <div class="col-xl-3 col-lg-6 col-12 form-group">
                                            <label>Image Choice1 *</label>
                                            <input type="file" placeholder="" class="form-control " name="imgchoice1">
                                        </div>
                                     <div class="col-xl-3 col-lg-6 col-12 form-group">
                                            <label>Image Choice2 *</label>
                                            <input type="file" placeholder="" class="form-control " name="imgchoice2">
                                        </div>
                                           <div class="col-xl-3 col-lg-6 col-12 form-group">
                                            <label>Image Choice3 *</label>
                                            <input type="file" placeholder="" class="form-control " name="imgchoice3">
                                        </div>
                                           <div class="col-xl-3 col-lg-6 col-12 form-group">
                                            <label>Image Choice4 *</label>
                                            <input type="file" placeholder="" class="form-control " name="imgchoice4" >
                                        </div>
                                        <!---->
                                         <div class="col-12-xxxl col-lg-6 col-12 form-group">
                                            <label>Answer</label>
                                              <?php $answer=$students['answer'];?>
                                            <select class="select2" name="answer" required>
                                               
                                                <option <?php if($answer=="1") echo "selected"; ?> value="1">Choice1</option>
                                                <option <?php if($answer=="2") echo "selected"; ?> value="2">Choice2</option>
                                                <option <?php if($answer=="3") echo "selected"; ?> value="3">Choice3</option>
                                                <option <?php if($answer=="4") echo "selected"; ?> value="4">Choice4</option>
                                              
                                            </select>
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
                                <?php } ?>
                                     <input type="text" value="<?php echo $s_id;?>" id="s_id" hidden>

                                        <div class="col-12 form-group mg-t-8">
                                           
                                            <button type="button" class="btn-fill-lg bg-blue-dark btn-hover-yellow save" >Save Changes</button>
                                             <button type="submit" class="btn-fill-lg bg-blue-dark btn-hover-yellow add" name="edit" disabled>Edit</button>

                                        </div>
                                          <?php if(!empty($students['image'])){?>
                                       
                                        </div>
                                        </div>
                                       
                                        <div class="col-xl-4 col-lg-6 col-12 form-group oldimage">
                                            <label>Current Image</label>
                                            <image src="img/questions/<?php echo $students['image'];?>">
                                        </div>
                                        <?php }?>
                                  
                                         <?php if(!empty($students['imgchoice1'])){?>
                                        <div class="col-xl-4 col-lg-6 col-12 form-group oldimage">
                                            <label>Current Image Choice 1</label>
                                            <image src="img/questions/<?php echo $students['imgchoice1'];?>">
                                        </div>
                                        <?php }?>
                                        
                                         <?php if(!empty($students['imgchoice2'])){?>
                                        <div class="col-xl-4 col-lg-6 col-12 form-group oldimage">
                                            <label>Current Image Choice 2</label>
                                            <image src="img/questions/<?php echo $students['imgchoice2'];?>">
                                        </div>
                                        <?php }?>
                                        
                                         <?php if(!empty($students['imgchoice3'])){?>
                                        <div class="col-xl-4 col-lg-6 col-12 form-group oldimage">
                                            <label>Current Image Choice 3</label>
                                            <image src="img/questions/<?php echo $students['imgchoice3'];?>">
                                        </div>
                                        <?php }?>
                                         <?php if(!empty($students['imgchoice4'])){?>
                                        <div class="col-xl-4 col-lg-6 col-12 form-group oldimage">
                                            <label>Current Image Choice 4</label>
                                            <image src="img/questions/<?php echo $students['imgchoice4'];?>">
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

 <script>
 
 
            $(".save").click(function(){

                var x = document.getElementById("htmlEditor").innerHTML;
                document.getElementById("editor").value=x;
                var i=1;
                $('.choices').each(function() {
                    $(this).next("input").val($(this).html());
                    i++;
                });
                
                console.log(x);
                
                $(".add").removeAttr("disabled");
            });
 
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
    
        mathType('');
        mathType(2);
        mathType(3);
        mathType(4);
        mathType('5');
        
        
    </script>
</body>

</html>