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
$subjectid=$_SESSION['subject'];
$stmt=$con->prepare("SELECT * FROM questions WHERE subjectid = ? AND givens IS NULL AND type IS NULL");
$stmt->execute(array($subjectid));
$mcq=$stmt->fetchAll();

$stmt=$con->prepare("SELECT * FROM essay WHERE subjectid = ? AND givens IS NULL");
$stmt->execute(array($subjectid));
$essay=$stmt->fetchAll();

$stmt=$con->prepare("SELECT * FROM givens WHERE subjectid = ? ");
$stmt->execute(array($subjectid));
$givens=$stmt->fetchAll();

//get account type
$name=$_SESSION['adminname'];
$stmt=$con->prepare("SELECT type FROM admins WHERE name=?");
$stmt->execute(array($name));
$result=$stmt->fetch();
$type=$result['type'];

//get subject name
$stmt=$con->prepare("SELECT * FROM subjects  WHERE id = ?  ");
$stmt->execute(array($subjectid));
$subject=$stmt->fetch();

//get educationallevel
$stmt=$con->prepare("SELECT name FROM educationallevels WHERE id=?");
$stmt->execute(array($subject['edu_id']));
$edulevel=$stmt->fetch();
$levelname = explode("-", $edulevel['name'], 2)[0];

if($type=="admin"){
    $stmt=$con->prepare("SELECT * FROM classes  WHERE s_id IN (SELECT id FROM subjects WHERE name = ? AND edu_id IN (SELECT id FROM educationallevels WHERE name LIKE '$levelname%'))   ");
    $stmt->execute(array($subject['name']));
    $classes=$stmt->fetchAll();
}else{

    $stmt=$con->prepare("SELECT * FROM classes  WHERE s_id IN (SELECT id FROM subjects WHERE name = ? AND teacher_id = ? AND edu_id IN (SELECT id FROM educationallevels WHERE name LIKE '$levelname%'))   ");
    $stmt->execute(array($subject['name'],$subject['teacher_id']));
    $classes=$stmt->fetchAll();
}


if(isset($_POST['add'])){
     
        $name=check($_POST['name']);
       
        $mcq = $_POST['mcq'];
        $mcqdegree = $_POST['mcqdegree'];
        $essay = $_POST['essay'];
        $essaydegree = $_POST['essaydegree'];
        $givens = $_POST['given'];
        
     
       
        if(!empty($_FILES['file']['name'])){
            
                    $file = $_FILES['file']['name'];
                    $filesize = $_FILES['file']['size'];
                    $filetmp = $_FILES['file']['tmp_name'];
                    
                    $allowedExts = array("pdf","docx","doc","odt","pptx","ppt","odp","jpeg","jpg","png");
                    $exp = explode('.' , $file);
                    $imageExtension = strtolower(end($exp));
                    
                    
                    $err=array();
                    if(!in_array($imageExtension,$allowedExts) ){
                    $err['file']="Only word, pd, powerpoint and images are allowed";
                    }
                    
                    
                    if (!empty($file)){
                    $Mimage = rand(0,100000) . '_' .$file ;
                    move_uploaded_file($filetmp, "uploads/homework model answer//". $Mimage);
                    }

        }else{
            $Mimage = NULL;
        }


     $err2=array();

     $stmt=$con->prepare("INSERT INTO homeworks (name,subjectid,answer)
         VALUES(:name,:subjectid,:answer)");
     $stmt->execute(array('name'=>$name ,'subjectid'=>$_SESSION['subject'],'answer'=>$Mimage));
     $last_id = $con->lastInsertId();
     
     $total=0;

// Mcq Question
    if(!empty($mcq)){
         
        foreach ($mcq as $q) {
                if($q != 0){
                   
                    $stmt=$con->prepare("INSERT INTO homeworkquestions (homeworkid,questionid,type , degree)
                    VALUES(:homeworkid,:questionid,:type ,:degree)");
                    $stmt->execute(array('homeworkid'=> $last_id ,'questionid'=>$q,'type'=>'0' , 'degree'=>1));
                    $total = $total + 1;
                
                }
       
     } 
        
    }
    
     

  
       
//  Essay Question -------------
  if(!empty($essay)){
        
        foreach (array_combine($essay, $essaydegree) as $q => $degree) {
    
                if($q != 0){
                    if(empty($degree)){
                        $degree = 1;
                    }
                    $stmt=$con->prepare("INSERT INTO homeworkquestions (homeworkid,questionid,type , degree)
                    VALUES(:homeworkid,:questionid,:type ,:degree)");
                    $stmt->execute(array('homeworkid'=> $last_id ,'questionid'=>$q,'type'=>'1' , 'degree'=>$degree));
                    $total = $total + $degree;
                
                }
       
     } 
        
    }
    
    
    //  Given  Question -------------

    foreach($givens as $given){
        
                $degree = 1;
                $stmt=$con->prepare("INSERT INTO homeworkquestions (homeworkid,questionid,type , degree)
                VALUES(:homeworkid,:questionid,:type ,:degree)");
                $stmt->execute(array('homeworkid'=> $last_id ,'questionid'=>$given,'type'=>'2' , 'degree'=>$degree));
                
                 $stmt=$con->prepare("SELECT * FROM questions WHERE givens = ? ");
                $stmt->execute(array($given));
                $givens=$stmt->fetchAll();
                foreach($givens as $given){
                    $total = $total + $degree;
                }
                
              
                    
    }
  
  


     $stmt=$con->prepare("UPDATE homeworks SET degree = ? WHERE id = ?");
     $stmt->execute(array($total, $last_id));


    header("Location:homeworks.php?msgg=".urlencode("Done Adding Homework"));
    exit();

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
    <title>E-Learning System | Add Homework</title>
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
                    <div class="row">
                        <div class="col-lg-8">
                            <h3>Admin Dashboard</h3>
                            <ul>
                                <li>
                                    <a href="statistics.php">Statistics</a>
                                </li>
                                <li>Add Homework</li>
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
                <div class="row addhomework">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="heading-layout1">
                                    <div class="item-title">
                                        <h3>Add New Homework</h3>
                                    </div>
                                 
                                </div>  
                                <form class="new-added-form" method="post" enctype="multipart/form-data">
                                    <div class="row">
                                       <div class="col-xl-6 col-lg-6 col-12 form-group">
                                              
                                            <label>Name</label>
                                            <input type="text" placeholder="" class="form-control" name="name" required>
                                           
                                         </div>
                                         
                                         <!--  <div class="col-xl-6 col-lg-6 col-12 form-group">-->
                                              
                                         <!--   <label>Time</label>-->
                                         <!--   <input type="number" placeholder="" class="form-control" name="timer" required>-->
                                           
                                         <!--</div>-->

 
                                        <!--<div class="col-xl-6 col-lg-6 col-12 form-group">-->
                                        <!--    <label>Pdf</label>-->
                                        <!--    <input type="file" placeholder="Upload Pdf" class="form-control" name="file" >-->
                                          
                                        <!--</div>-->
                                        
                                       
                                            
                                        <div class="col-lg-12">
                                            
                                       
                                        <table class="table table-striped display data-table  text-center overflow-auto h-75" id="myTable">
                                                      <thead>
                                                        <tr>
                                                          <th scope="col">#</th>
                                                          <th scope="col">question</th>
                                                          <th scope="col">Type</th>
                                                             <?php if($_SESSION['subject'] == 10){ ?>
                                                                <th>Unit</th>
                                                                <?php }?>
                                                           <th scope="col">Chapter</th>
                                                            <th scope="col">Lesson</th>
                                                        </tr>
                                                      </thead>
                                                      <tbody>
                                                         <?php 
                                                         $i=0;
                                                         foreach($mcq as $q){
                                                              $mcqname = 'mcq';
                                                            $stmt=$con->prepare("SELECT name,chapter FROM branches WHERE id = ? ");   
                                                            $stmt->execute(array($q['lesson']));
                                                            $lesson=$stmt->fetch();
                                                            
                                                            $stmt=$con->prepare("SELECT * FROM branches WHERE id = ? ");   
                                                            $stmt->execute(array($lesson['chapter']));
                                                            $chapter=$stmt->fetch();
                                                            
                                                            $stmt=$con->prepare("SELECT * FROM branches WHERE id = ? ");   
                                                            $stmt->execute(array($chapter['units']));
                                                            $unit=$stmt->fetch();
                                                         ?>
                                                        <tr>
                                                          <th scope="row">
                                                              
                                                              <input type="checkbox" class="form-check-input" name="<?php echo $mcqname?>[<?php echo $i?>]" value="0" hidden checked>
                                                              <input type="checkbox" class="form-check-input" name="<?php echo $mcqname?>[<?php echo $i?>]" value="<?php echo $q['id'];?>">
                                                            </th>
                                                                  <td><?php
                                                          
                                                             if(isset($q['image'])) { ?>
                                                           <img class="addimg" src="img/questions/<?php echo $q['image'];?>">
                                                         <?php }
                                                           echo $q['question'];
                                                          ?></td>
                                                          <!--<td class="w-50"><?php echo $q['question']; ?></td>-->
                                                          <td>Multichoice</td>
                                                             <?php if($_SESSION['subject'] == 10){ ?>
                                                                <td>
                                                                    <?php if(!empty($unit['name'])){echo $unit['name']; } ?>
                                                                </td>
                                                                <?php }?>
                                                          <td><?php if(!empty($chapter['name'])){echo $chapter['name']; } ?></td> 
                                                          <td><?php if(!empty($lesson['name'])){echo $lesson['name']; } ?></td>
                                                         <input class="form-control" hidden name="<?php echo $mcqname?>degree[<?php echo $i?>]" type="number"  placeholder="Enter degree of question">
                                                                 
                                                        </tr>
                                                            <?php $i++;}?>
                                                        <?php 
                                                        $i=0;
                                                        foreach($essay as $q){
                                                             $essayname = 'essay';
                                                        
                                                            $stmt=$con->prepare("SELECT name,chapter FROM branches WHERE id = ? ");   
                                                            $stmt->execute(array($q['lesson']));
                                                            $lesson=$stmt->fetch();
                                                            
                                                             
                                                            $stmt=$con->prepare("SELECT * FROM branches WHERE id = ? ");   
                                                            $stmt->execute(array($lesson['chapter']));
                                                            $chapter=$stmt->fetch();
                                                            
                                                            $stmt=$con->prepare("SELECT * FROM branches WHERE id = ? ");   
                                                            $stmt->execute(array($chapter['units']));
                                                            $unit=$stmt->fetch();
                                                            
                                                         ?>
                                                        <tr>
                                                          <th scope="row">
                                                              <input type="checkbox" class="form-check-input" name="<?php echo $essayname?>[<?php echo $i?>]" value="0" hidden checked>
                                                              <input type="checkbox" class="form-check-input" name="<?php echo $essayname?>[<?php echo $i?>]" value="<?php echo $q['id'];?>">
                                                              
                                                            </th>
                                                          <td><?php
                                                          
                                                             if(isset($q['image'])) { ?>
                                                           <img class="addimg" src="img/questions/<?php echo $q['image'];?>">
                                                         <?php }
                                                           echo $q['question'];
                                                          ?></td>
                                                          <td>Essay</td>
                                                            <?php if($_SESSION['subject'] == 10){ ?>
                                                                <td>
                                                                    <?php if(!empty($unit['name'])){echo $unit['name']; } ?>
                                                                </td>
                                                                <?php }?>
                                                          <td><?php if(!empty($chapter['name'])){echo $chapter['name']; } ?></td> 
                                                          <td><?php if(!empty($lesson['name'])){echo $lesson['name']; } ?></td>
                                                            
                                                        </tr>
                                                            <?php $i++;}?>
                                                     
                                                     <?php 
                                                         $i=0;
                                                         foreach($givens as $q){
                                                              $name = 'given';
                                                        
                                                         ?>
                                                        <tr>
                                                          <th scope="row">
                                                              
                                                              <input type="checkbox" class="form-check-input" name="<?php echo $name?>[<?php echo $i?>]" value="0" hidden checked>
                                                              <input type="checkbox" class="form-check-input" name="<?php echo $name?>[<?php echo $i?>]" value="<?php echo $q['id'];?>">
                                                            </th>
                                                          <td class="w-50">
                                                              <?php
                                                                if(!empty($q['text'])){
                                                                    echo $q['text'];
                                                                    }
                                                                if(!empty($q['image'])){
                                                              ?>
                                                              <img style="width: 102px;height: 103px;object-fit: cover;" src="img/givens/<?php echo $q['image'];?>">
                                                              <?php }?>
                                                          </td>
                                                          <td>Givens</td>
                                                           <td></td>
                                                            <td></td>
                                                         <td></td>
                                                        </tr>
                                                            <?php $i++;}?>
                                                            
                                                     
                                                      </tbody>
                                                    </table>
                                                </div>
                                        
                                 
                                       

                                        <div class="col-12 form-group mg-t-8">
                                            <button type="submit" class="btn-fill-lg btn-gradient-yellow btn-hover-bluedark" name="add">Add</button>
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


         <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.print.min.js"></script>
    
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
            aLengthMenu: [
            [25, 50, 100, 200, -1],
            [25, 50, 100, 200, "All"]
            ],
            iDisplayLength: -1
            });
        
        });
    </script>
    

</body>

</html>