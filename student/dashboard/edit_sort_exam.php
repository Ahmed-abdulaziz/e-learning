<?php 

include_once 'connect.php';
ob_start();
 session_start();
 if(!isset($_SESSION['adminname'])){
    header("Location:index.php?msg=".urlencode("Please Login First"));
    exit();
}

  $title="exams";

if(isset($_SESSION['adminname']))
{
if(isset($_SESSION['subject']))
$subject=$_SESSION['subject'];
$name=$_SESSION['adminname'];


if(isset($_GET['id'])){
    $examid=$_GET['id'];
   
        
        $stmt = $con->prepare("SELECT * FROM exams WHERE id = ?");
        $stmt->execute(array($examid));
        $exam = $stmt->fetch();
        
        
        $stmt = $con->prepare("SELECT * FROM examquestions WHERE examid = ? ORDER BY qorder");
        $stmt->execute(array($examid));
        $ques = $stmt->fetchAll();
    

}

if(isset($_POST['sort'])){
    
    $ques=$_POST['quesids'];
    $orders=$_POST['orders'];
    $examid=$_POST['exid'];

    $i=1;
    foreach($ques as $q){
        $stmt=$con->prepare("UPDATE examquestions SET qorder = ?  WHERE id =?");
        $stmt->execute(array($i,$q));
        $i++;
    }
    
    header("Location:exams.php?msg=" . urlencode("Done Edit Sorting Exam"));
    exit();
}



}
 if (!isset($_SESSION['adminname'])) {
           header("Location:index.php?dmsg=".urlencode("please login first"));
exit();
 }
$stmt=$con->prepare("SELECT name,id FROM subjects WHERE id = ? ");   
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
    <title>E-Learning System  | Questions</title>
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
    
    
     <link rel="stylesheet" href="css/guppy-default-osk.min.css">
     <link rel="stylesheet" href="css/jodit.min.css">


    <script type="text/javascript" src="js/guppy_osk.js"></script>
    <script type="text/javascript" src="js/guppy.js"></script>
    <script src="js/jodit.min.js"></script>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=es6"></script>
    <script id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-chtml.js"></script>
    <script src="https://raw.githack.com/SortableJS/Sortable/master/Sortable.js"></script>



    <script>
      MathJax = {
        tex: {
          inlineMath: [['$', '$'], ['\\(', '\\)']]
        }
      };
    </script>
</head>

<body>
    <!-- Preloader Start Here -->   
    <!--<div id="preloader"></div>-->
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
                                <h3 class="text-primary "><?=$exam['name'];?></h3>
                            </div>
                        </div>
                        
                        <div class="heading-layout1">
                            <div class="item-title">
                                <h3>Sorting Questions Data</h3>
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
                                            
                                
                        <form method="POST">
                            <input hidden value="<?php echo $examid; ?>" name="exid">
                                <div id="sortTrue" class="list-group">
                                     <?php 
                                     $i=1;
                                    //  print_r($ques);
                                     foreach($ques as $question){ 
                                        $stmt = $con->prepare("SELECT * FROM questions WHERE id = ? ");
                                        $stmt->execute(array($question['questionid']));
                                        $q= $stmt->fetch();

                                        if($q['status'] == 'easy'){
                                            $alert="success";
                                        }elseif($q['status'] == 'medium'){
                                            $alert="warning";
                                        }elseif($q['status'] == 'hard'){
                                             $alert="danger";
                                        }
                                     ?>
                                        <div class="list-group-item over position-relative question<?php echo $question['questionid'];?>">
                                             <input type="text" class="qid" hidden value="<?php echo $question['questionid'];?>">
                                             <input type="text" class="examid" hidden value="<?php echo $examid;?>">
                                          <span class="qno"><?= $i; ?></span>
                                          <?php echo $q['question'];  ?>
                                          <input type="text"  hidden value="<?php echo $question['id'];?>" name="quesids[]">
                                          <!--<input type="text" hidden value="<?php //echo $question['id'];?>" name="orders[]">-->
                                             <br>
                                         <span class="font-weight-bold" ><?php
                                          $topic= $q['lesson'];
                                          
                                            $stmt = $con->prepare("SELECT name FROM branches WHERE id = ? ");
                                            $stmt->execute(array($topic));
                                            $t= $stmt->fetch();
                                            echo $t['name'];
                                        
                                          ?></span>
                                          <span style="display: block;;width:150px" class="alert  alert-<?php echo $alert;?> mt-5 text-center">MCQ - <?php echo  $q['status']?></span>
                                        </div>
                                      <?php $i++;}?>
                                </div>

                            <button type="submit" name="sort" class="btn btn-success mt-3 btn-lg sortbtn">Sort Questions</button>
                        </form>
                                    
                                        
                                      
<div id="myModal" class="modal">
  <span class="close">&times;</span>
  <img class="modal-content" id="img01">
  <div id="caption"></div>
</div>



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
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js" integrity="sha384-SR1sx49pcuLnqZUnnPwx6FCym0wLsk5JZuNx2bPPENzswTNFaQU1RDvt3wT4gWFG" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.min.js" integrity="sha384-j0CNLUeiqtyaRmlzUHCPZ+Gy5fQu0dQ6eZ/xAww941Ai1SxSY+0EQqNXNE6DZiVc" crossorigin="anonymous"></script>
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
                  function fetch_sublesson(sublesson){
            var s_id = $( "#s_id" ).val();

            $.ajax({
                        type: 'post',
                        url: 'select.php',
                        data: {
                        get_sublesson:sublesson,
                        s_id:s_id
                        },
                        success: function (sublesson) {
                        document.getElementById("sublesson").innerHTML=sublesson; 
                        
                        
                        }
                    });
        }
     
        

        
    </script>
<script>
//Get the modal
var modal = document.getElementById("myModal");

// Get the image and insert it inside the modal - use its "alt" text as a caption
var img = document.getElementById("myImg");
var modalImg = document.getElementById("img01");
var captionText = document.getElementById("caption");


// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks on <span> (x), close the modal
span.onclick = function() { 
  modal.style.display = "none";
}

$( ".myImg" ).click(function() {
    modal.style.display = "block";
  modalImg.src = this.src;
  captionText.innerHTML = this.alt;
});
</script>
<script>
  // sort: true
Sortable.create(sortTrue, {
  group: "sorting",
  sort: true
});
<?php if($subject['id'] == 3 || $subject['id'] == 26 || $subject['id'] == 28){?>
Sortable.create(sortTrue2, {
  group: "sorting",
  sort: true
});
<?php }?>

function numbering(){
    var i=1;
    $('.qno').each(function(){
    $(this).text(i+'.').append("<span>&nbsp;&nbsp;");
        i++;
    });
    <?php if($subject['id'] == 3 || $subject['id'] == 26 || $subject['id'] == 28){?>
    i=1;
    $('.qno2').each(function(){
    $(this).text(i+'.').append("<span>&nbsp;&nbsp;");
        i++;
    });
    <?php }?>
}



setInterval(function(){ numbering(); }, 500);
</script>

<script>
        //   $(".deleteq").click(function(){
        //     var qid=$(this).closest('.over').find(".qid").val();
        //      var examid=$(this).closest('.over').find(".examid").val();
           
            
        //     console.log(qid);
        //     console.log(examid);
        
        
            
        //     $.ajax({
        //       type: "POST",
        //       url: "ajax/delete-sort.php",
        //       data:{
        //          qid: qid,
        //          examid,examid,
        //          delete:"delete",
        //       },success: function(){
        //         $(".question"+qid).remove();
        //         // window.location.href = "https://cbtgate.com/dashboard/multichoice_past_days.php";
        //       }
        //     });
            
        // });

// ----------------------------------------------------------------------------------



      $(".close-model").click(function(){
        $('#exampleModal').modal('hide');
    });
    
   
    
// -------------------------------------------------------------------------------------
</script>


</body>

</html>