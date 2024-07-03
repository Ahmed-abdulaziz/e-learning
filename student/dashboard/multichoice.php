<?php 

include_once 'connect.php';
ob_start();
 session_start();
 if(!isset($_SESSION['adminname'])){
    header("Location:index.php?msg=".urlencode("Please Login First"));
    exit();
}
if(isset($_SESSION['adminname']))
{
if(isset($_SESSION['subject']))
$subject=$_SESSION['subject'];
$name=$_SESSION['adminname'];

if(isset($_GET['given_id'])){
    
    $given_id = $_GET['given_id'];
    $stmt=$con->prepare("SELECT * FROM questions WHERE subjectid = ? AND givens = ? ");
    $stmt->execute(array($subject , $given_id));
    $questions=$stmt->fetchAll();
    
}else{
        $given_id = NULL;
        $stmt=$con->prepare("SELECT * FROM questions WHERE subjectid = ? AND givens IS NULL AND type IS NULL ");
        $stmt->execute(array($subject));
        $questions=$stmt->fetchAll();
        
}

$s_id=$subject;
if(isset($_GET['do'])){
$do = $_GET['do'];

 if($do == 'delete'){
    if($_GET['id']){
       $id = $_GET['id'];
    }
    
      $stmt = $con->prepare("SELECT image  FROM questions WHERE id = ?");
      $stmt->execute(array($id));
      $r=$stmt->fetch();
      $image=$r['image'];
      if(!empty($image)){
         if (file_exists('img/questions/'.$image)) {
            unlink('img/profile/'.$image);
        }
      }
    
      $stmt = $con->prepare("DELETE  FROM questions WHERE id = :zid");
      $stmt->bindParam(":zid", $id);
      $stmt->execute();
      
      if(isset($_GET['given_id'])){
          $given_id = $_GET['given_id'];
            header("Location:multichoice.php?given_id=$given_id&msg=".urlencode("Done Deleteing Question"));
            exit();
      }else{
         header("Location:multichoice.php?msg=".urlencode("Done Deleteing Question"));
         exit(); 
      }
      
   }
}

}
 if (!isset($_SESSION['adminname'])) {
           header("Location:index.php?dmsg=".urlencode("please login first"));
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

  <?php include('header/head.php'); ?>

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
                    <div class="row">
                        <div class="col-lg-8">
                            <h3>Admin Dashboard</h3>
                            <ul>
                                <li>
                                    <a href="statistics.php">Statistics</a>
                                </li>
                                <li>Multichoice</li>
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
                <!-- Teacher Table Area Start Here -->
                <div class="card height-auto">
                    <div class="card-body">
                        <div class="heading-layout1">
                            <div class="item-title">
                                <h3>All Questions Data</h3>
                            </div>
                        </div>


                        <!-- msg of adding-->
                        
                          <?php if(isset($_GET['msgg'])){?>
                                        <div class="alert alert-success  alert-dismissible fade show settingalert">
                                            <?php echo $_GET['msgg'];?>
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div> <?php } ?>
<!--mssg for delete-->
                              <?php if(isset($_GET['msg'])){?>
                                        <div class="alert alert-success  alert-dismissible fade show settingalert">
                                            <?php echo $_GET['msg'];?>
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                       <?php } ?>
<!--msg for edit-->
                         <?php if(isset($_GET['msssg'])){?>
                                        <div class="alert alert-success  alert-dismissible fade show settingalert">
                                            <?php echo $_GET['msssg'];?>
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                       <?php } ?>
                               
                                    
                        <?php if($given_id == NULL){ ?>           
                        <form class="mg-b-20">
                            <div class="row gutters-8">
                             
                                <div class="col-1-xxxl col-xl-2 col-lg-3 col-12 form-group">
                               <a href="addmultichoice.php"  class="fw-btn-fill btn-gradient-yellow"> Add Question</a>
                                </div>
                            </div>
                        </form>
                        
                        
                        <?php }?>
                        
                         <?php if($given_id != NULL){ ?> 
                         
                           <form class="mg-b-20">
                            <div class="row gutters-8 d-flex justify-content-end mr-5">
                             
                                <div class="col-1-xxx1 col-xl-2 col-lg-6 col-12 form-group">
                                     <a href="addmultichoice.php?given_id=<?php echo $given_id;?>"  class="fw-btn-fill btn-gradient-yellow"> Add Question</a>
                                </div>
                                <div class="col-1-xxxl col-xl-2 col-lg-3 col-12 form-group">
                               <a href="givens.php"  class="fw-btn-fill btn-gradient-yellow">Givens Question</a>
                                </div>
                            </div>
                        </form>
                        
                         <form class="mg-b-20">
                            <div class="row ">
                                  <div class="col-12-xxxl col-xl-12 col-lg-3 col-12 form-group">
                                  
                                  
                             <div class="card">
                              <div class="card-header">
                                Givens
                              </div>
                              <div class="card-body">
                                    <?php 
                                        $stmt=$con->prepare("SELECT * FROM givens WHERE id = ? ");
                                        $stmt->execute(array($given_id));
                                        $given=$stmt->fetch();
                                        
                                        
                                       
                                    ?>
                                        <h5 class="card-title">
                                                        <?php
                                                        if(!empty( $given['text'])){
                                                          echo $given['text']."<br>";
                                                        }
                                                        ?>
                                        </h5>
                                           <?php  if(!empty( $given['image'])){ ?>
                                                 <img style="width: 75%;height: 345px;object-fit: cover;" src="img/givens/<?php echo $given['image'];?>">
                                          <?php }?>
                                      </div>
                                    </div>

                              
                                </div>
                            </div>
                        </form>
                        
                       
                        
                         <?php }?>
                         
                         
                      
                        <div class="table-responsive">
                            <table class="table display data-table text-nowrap" id="myTable">
                                <thead>
                                    <tr>
                                       
                                        <th>Image</th>
                                        <th>Question</th>
                                        <th>Level</th>
                                        <th>Lesson</th>
                                        <th>Chapter</th>
                                         <?php if($_SESSION['subject'] == 10){ ?>
                                              <th>Unit</th>
                                        <?php }?>
                                        <th>Choice1</th>
                                        <th>Choice2</th>
                                        <th>Choice3</th>
                                        <th>Choice4</th>
                                        <th>Answer</th>
                                        <th>Controls</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                      <?php if(isset($_SESSION['adminname'])){ foreach($questions as $question){ ?>
                                        
                                        <td><?php if(isset($question['image'])){?><img src="img/questions/<?php echo $question['image']?>" class="qimg"><?php }else {echo "No Image";}?></td>
                                        <td><?php echo $question['question']  ?></td>
                                        <td><?php echo $question['status']  ?></td>
                                        <td>
                                        <?php
                                            $lessonid  = $question['lesson'];
                                            
                                                        $stmt=$con->prepare("SELECT * FROM branches WHERE id = ? ");
                                                        $stmt->execute(array($lessonid));
                                                        $lesson=$stmt->fetch();
                                                        echo $lesson['name'];
                                        
                                        ?></td>
                                         <td>
                                          <?php
                                            $chapterid  = $lesson['chapter'];
                                            
                                                        $stmt=$con->prepare("SELECT name FROM branches WHERE id = ? ");
                                                        $stmt->execute(array($chapterid));
                                                        $chapter=$stmt->fetch();
                                                        echo $chapter['name'];
                                        
                                        ?></td>
                                           <?php if($_SESSION['subject'] == 10){ ?>
                                            <td>
                                                <?php

                                                                $stmt=$con->prepare("SELECT name FROM branches WHERE id = ? ");
                                                                $stmt->execute(array($lesson['units']));
                                                                $unit=$stmt->fetch();
                                                                echo $unit['name'];
                                                
                                                ?></td>
                                           <?php }?>
                                        <td><?php echo $question['choice1']  ?></td>
                                        <td><?php echo $question['choice2']  ?></td>
                                        <td><?php echo $question['choice3']  ?></td>
                                        <td><?php echo $question['choice4']  ?></td>
                                         <td><?php echo $question['answer']  ?></td>
                                         <td>
                
                                            <a href="multichoice.php?do=delete&id=<?php echo $question["id"];if($given_id !=NULL){echo "&given_id=".$given_id;}?>"  class="fw-btn-fill btn-gradient-yellow">Delete</a>
                                         
                                              <a href="editmultichoice.php?do=edit&id=<?php echo $question["id"];if($given_id !=NULL){echo "&given_id=".$given_id;}?>"  class="fw-btn-fill btn-gradient-yellow">Edit </a>
            
                                        </td>
                                    </tr>                                            
     <?php }} ?>                
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
     <script>
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
            $('#myTable').dataTable({ });
        
        });
    </script>
       
       

</body>

</html>