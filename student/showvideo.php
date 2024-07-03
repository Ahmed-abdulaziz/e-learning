<?php    
ob_start();
ini_set('session.cookie_lifetime', 60 * 60 * 24 );
ini_set('session.gc_maxlifetime', 60 * 60 * 24 );
session_start();
include_once 'connect.php';

if(!isset($_SESSION['name'])){
header("location:index.php?msg=".urldecode("please login first"));
exit();
}
function check($var){
  return trim(strip_tags(stripcslashes($var)));
}
$id=$_GET['id'];
$email=$_SESSION['name'];

//get student id
$stmt=$con->prepare("SELECT * FROM students WHERE email=?");
$stmt->execute(array($email));
$std=$stmt->fetch();
$studentid = $std['id'];

$homeworkid = $_GET['homeworkid'];
$wid = $_GET['wid'];
 
$stmt=$con->prepare("SELECT * FROM week_details WHERE product_id=? AND week_id=?");
$stmt->execute(array($id,$wid));
$videodet=$stmt->fetch();

    $stmt=$con->prepare("SELECT * FROM weeks WHERE id = ?");
    $stmt->execute(array($wid));
    $week=$stmt->fetch();
      if($week['arrange'] == 1){
             
                    $stmt=$con->prepare("SELECT * FROM week_details WHERE week_id = ? AND pro_order < ? ORDER BY pro_order ASC");
                    $stmt->execute(array($wid,$videodet['pro_order']));
                    $details=$stmt->fetchAll();
      
        
                     foreach($details as $item){
                         
                         if($item['type'] == 'video' && $item['product_id'] != $id){
                                $stmt=$con->prepare("SELECT * FROM videocount WHERE videoid = ? AND studentid = ?");
                                $stmt->execute(array($item['product_id'],$studentid));
                                $check=$stmt->rowCount();
                                if($check < 1){
                                    header("Location:showvideo.php?id=".$item['product_id']."&wid=$wid&error=".urlencode("برجاء مشاهدة هذا الفيديو اولا اولا"));
                                    exit();
                                }
                                
                         }elseif($item['type'] == 'video' && $item['product_id'] == $id){
                          
                             break;
                         }elseif($item['type'] == 'exam'){
                                
                                $stmt=$con->prepare("SELECT * FROM exams WHERE id = ?");
                                $stmt->execute(array($item['product_id']));
                                $exam=$stmt->fetch();
                             
                                $stmt=$con->prepare("SELECT * FROM studentexam WHERE studentid = ? AND examid = ?");
                                $stmt->execute(array($studentid,$item['product_id']));
                                $ex=$stmt->fetch();
                                $solveexam=$stmt->rowCount();
                                if($solveexam < 1){
                                    header("Location:myweek.php?wid=$wid&error=".urlencode("برجاء اجتياز الامتحانات اولا"));
                                    // header("Location:answersexam.php?id=".$item['product_id']."&wid=$wid&error=".urlencode("برجاء اجتياز الامتحانات اولا"));
                                    exit();
                                }elseif($ex['result']<$exam['min_degree']){
                                    $stmt=$con->prepare("SELECT * FROM exams WHERE parentexam = ?");
                                    $stmt->execute(array($exam['id']));
                                    $exam2=$stmt->fetch();
                                    $name2=$exam2['name'];
                                    
                                    $stmt=$con->prepare("SELECT * FROM studentexam WHERE studentid = ? AND examid = ?");
                                    $stmt->execute(array($studentid,$exam2['id']));
                                    $ex2=$stmt->fetch();
                                    
                                    if(empty($ex2)){
                                        header("Location:myweek.php?wid=$wid&error=".urlencode("برجاء اجتياز الامتحانات اولا"));
                                        exit();
                                    }
                                    
                                    if($ex2['result']<$exam2['min_degree']){
                                        if($ex2['pass']=="0"){
                                            header("Location:myweek.php?wid=$wid&error=".urlencode("برجاء الرجوع للادارة لفتح الفيديو"));
                                        }
                                    }
                                }
                                                                
                               
                         }elseif($item['type'] == 'homework'){
                             
                                $stmt=$con->prepare("SELECT * FROM studenthw WHERE studentid = ? AND homeworkid = ?");
                                $stmt->execute(array($studentid,$item['product_id']));
                                $homewrok=$stmt->fetch();
                                $check_homework = $stmt->rowCount();
                                $hmwid = $quiz['id'];
                                $wid = $week['id'];
                                
                                  if($homewrok['result']==NULL){
                                            header("Location:answers.php?id=".$item['product_id']."&wid=$wid&error=".urlencode("برجاء حل الواجب  اولا"));
                                            exit();
                                  }
                               
                         }
                     }
                     
                    //  Next  - Perv ------------------------------------------------------------------
                    
                    $stmt=$con->prepare("SELECT * FROM week_details WHERE product_id = ?");
                    $stmt->execute(array($id));
                    $data=$stmt->fetch();
                    
                    // Next ----------->
                    $stmt=$con->prepare("SELECT * FROM week_details WHERE pro_order = ?");
                    $stmt->execute(array($data['pro_order'] + 1));
                    $nextdata=$stmt->fetch();
                    $checknext = $stmt->rowCount();
                    if($checknext > 0){
                        $next = 1;
                        if($nextdata['type'] == 'homework'){
                             $urlnext = "answers.php?id=".$nextdata['product_id']."&wid=$wid";
                        }elseif($nextdata['type'] == 'exam'){
                              $urlnext = "answersexam.php?id=".$nextdata['product_id']."&wid=$wid";
                        }elseif($nextdata['type'] == 'video'){
                              $urlnext = "showvideo.php?id=".$nextdata['product_id']."&wid=$wid";
                        }
                    }
                    
                     // prev ----------->
                    $stmt=$con->prepare("SELECT * FROM week_details WHERE pro_order = ?");
                    $stmt->execute(array($data['pro_order']- 1));
                    $prevdata=$stmt->fetch();
                    $checkprev = $stmt->rowCount();
                    if($checkprev > 0){
                        $prev = 1;
                        if($prevdata['type'] == 'homework'){
                             $urlprev = "answers.php?id=".$prevdata['product_id']."&wid=$wid";
                        }elseif($prevdata['type'] == 'exam'){
                              $urlprev = "answersexam.php?id=".$prevdata['product_id']."&wid=$wid";
                        }elseif($prevdata['type'] == 'video'){
                              $urlprev = "showvideo.php?id=".$prevdata['product_id']."&wid=$wid";
                        }
                    }
                // ------------------------------------------------------------------------------------------------
                    
      }else{
               //  Next  - Perv ------------------------------------------------------------------
                    
                    $stmt=$con->prepare("SELECT * FROM week_details WHERE product_id = ?");
                    $stmt->execute(array($id));
                    $data=$stmt->fetch();
                    
                    // Next ----------->
                    $stmt=$con->prepare("SELECT * FROM week_details WHERE id = ?");
                    $stmt->execute(array($data['id'] + 1));
                    $nextdata=$stmt->fetch();
                    $checknext = $stmt->rowCount();
                    if($checknext > 0){
                        $next = 1;
                        if($nextdata['type'] == 'homework'){
                             $urlnext = "answers.php?id=".$nextdata['product_id']."&wid=$wid";
                        }elseif($nextdata['type'] == 'exam'){
                              $urlnext = "answersexam.php?id=".$nextdata['product_id']."&wid=$wid";
                        }elseif($nextdata['type'] == 'video'){
                              $urlnext = "showvideo.php?id=".$nextdata['product_id']."&wid=$wid";
                        }
                    }
                    
                     // prev ----------->
                    $stmt=$con->prepare("SELECT * FROM week_details WHERE id = ?");
                    $stmt->execute(array($data['id']- 1));
                    $prevdata=$stmt->fetch();
                    $checkprev = $stmt->rowCount();
                    if($checkprev > 0){
                        $prev = 1;
                        if($prevdata['type'] == 'homework'){
                             $urlprev = "answers.php?id=".$prevdata['product_id']."&wid=$wid";
                        }elseif($prevdata['type'] == 'exam'){
                              $urlprev = "answersexam.php?id=".$prevdata['product_id']."&wid=$wid";
                        }elseif($prevdata['type'] == 'video'){
                              $urlprev = "showvideo.php?id=".$prevdata['product_id']."&wid=$wid";
                        }
                    }
                // ------------------------------------------------------------------------------------------------
      }
                                                

    


$stmt=$con->prepare("SELECT * FROM videos WHERE id=?");
$stmt->execute(array($id));
$video=$stmt->fetch();
$videotime=$video['minutesview'];

//count student watch open

$stmt=$con->prepare("SELECT * FROM videocount WHERE videoid = ? AND studentid = ? AND timer IS NOT NULL AND timer >= ?");
$stmt->execute(array($id,$studentid,$videotime));


$no = $video['no'];
if($stmt->rowCount()>=$video['no']){
    header("Location:videos.php?mssg=".urlencode("You Watch This Video $no .You exceeded view limits"));
    exit();
}else{
   
        $stmt=$con->prepare("SELECT * FROM videocount WHERE  videoid = ? AND studentid = ? AND (timer IS NULL OR timer <= ?)");
        $stmt->execute(array($id,$studentid,$videotime));
        
    if($stmt->rowCount() < 1){
        
        $stmt=$con->prepare("INSERT INTO videocount (videoid,studentid) VALUES (:vid,:sid)");
        $stmt->execute(array("vid"=>$id,"sid"=>$studentid));
    }
    
}




$email=$_SESSION['name'];
$stmt=$con->prepare("SELECT id FROM students WHERE email = ?");
$stmt->execute(array($email));
$results=$stmt->fetch();

$stmt=$con->prepare("SELECT * FROM subjects WHERE id = ? ");   
$stmt->execute(array($_SESSION['subject']));
$subject=$stmt->fetch();
// echo $_SESSION['subject'];die;


$stmt=$con->prepare("SELECT teacher_id FROM subjects WHERE id = ? ");
$stmt->execute(array($_SESSION['subject']));
$t=$stmt->fetch();

$stmt=$con->prepare("SELECT fullname FROM admins WHERE id = ? ");
$stmt->execute(array($t['teacher_id']));
$teacher=$stmt->fetch();

// if(isset($_POST['send']))
// {
//      $videoid=check($_POST['videoid']);
//      $studentid=check($_POST['studentid']);
//      $question=$_POST['question'];
//     //  echo $question;
//      $error=array();

    
//      if(empty($error)){
//      $stmt=$con->prepare("INSERT INTO videoquestions (videoid,question,studentid)
//          VALUES(:videoid,:question,:studentid)");
//      $stmt->execute(array('videoid'=>$videoid ,'question'=>$question,'studentid'=>$studentid));
//     //  header("Location:videos.php?mssg=".urlencode("Done Send Question"));
//     //   exit();
// }}
?>
<!doctype html>
<html class="no-js" lang="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>E-Learning System | Videos </title>
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
    <!-- Data Table CSS -->
    <link rel="stylesheet" href="css\jquery.dataTables.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />

    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="css/style.css">
    <!-- Modernize js -->
    <script src="js\modernizr-3.6.0.min.js"></script>
      <style>
        .rev {
            position: absolute;
            display: none;
            width: auto;
            height:20px;
            background-color:#ffffff;
            font-weight: bolder;
            color: black;
            opacity: 0.5;
        }
    </style>
</head>

<body>
    <!-- Preloader Start Here -->
    <div id="preloader"></div>
    <!-- Preloader End Here -->
    <div id="wrapper" class="wrapper bg-ash">
        <!-- Header Menu Area Start Here -->
        <?php include_once "navbar.php";?>
        <!-- Header Menu Area End Here -->

        <!-- Page Area Start Here -->
        <div class="dashboard-page-one">
            <!-- Sidebar Area Start Here -->
            <?php include_once "sidebar.php";?>
            <!-- Sidebar Area End Here -->
            <div class="dashboard-content-one">
                <!-- Breadcubs Area Start Here -->
            <div class="breadcrumbs-area">
                    <div class="row">
                        <div class="col-lg-8">
                            <h3>Student Dashboard</h3>
                            <ul>
                                <li>
                                    <a href="videos.php">Videos</a>
                                </li>
                                <li>Student</li>
                            </ul>
                        </div>
                        <div class="col-lg-4">
                            <div class="dashboard-summery-one subject">
                                <div class="item-content">
                                    <div class="item-number"><?php echo $subject['name']; echo " / ". $teacher['fullname']  ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                  <?php if(isset($_GET['mssg'])){?>
                            <div class="alert alert-success  alert-dismissible fade show settingalert">
                                <?php echo $_GET['mssg'];?>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        <?php } ?>
                        
                         <?php if(isset($_GET['error'])){?>
                            <div class="alert alert-danger errors alert-dismissible fade show" role="alert">
                                <h4 class="text-danger"> <i class="fas fa-exclamation-triangle"></i> <?php echo check($_GET['error']);?></h4>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <?php }?>
                        
                <!-- Breadcubs Area End Here -->
                <div class="row">
                    <!-- Student Info Area Start Here -->
                    <!-- Student Info Area End Here -->
                    <div class="col-12-xxxl col-12">
                        <div class="row">
                            <!-- Summery Area Start Here -->
                           

                            <div class="col-lg-12 text-center">
                                 <form class="new-added-form" method="post" >
                                <?php if(!empty($video['video'])){ ?>
                                        <video width="100%" controls>
                                          <source src="<?php echo "dashboard/uploads/videos/".$video['video']; ?>" type="video/mp4">
                                        </video>
                                        

                                   <?php } elseif(!empty($video['iframe'])) {?>
                                <iframe src="<?php echo $video['iframe'] ?>"  width="50%" allowfullscreen allowFullScreen="true" allow="encrypted-media" style="display: block; margin: 0 auto; height: 350px;"></iframe>
                                        <div id="comment1" class="rev"><?php echo $std['id'];?></div>
                                        <div id="comment2" class="rev"><?php echo $std['id'];?></div>
                                        <div id="comment3" class="rev"><?php echo $std['id'];?></div>
                                        <div id="comment4" class="rev"><?php echo $std['id'];?></div>
                                        <div id="comment5" class="rev"><?php echo $std['id'];?></div>
                                <?php } ?>
                                <h2 class="vname"> <?php echo $video['name']; ?></h2>
                                    <input type="text"  class="form-control videoid" name="videoid"  value="<?php echo $video['id']; ?>" hidden>
                                    <input type="text"  class="form-control studentid" name="studentid"  value="<?php echo $results['id']; ?>" hidden>
                                    <input type="text"  class="form-control videotime" name="videotime"  value="<?php echo $videotime; ?>" hidden>

                                <div class="input-group input-group-lg mb-3" style="margin: 0 auto; width: 50%;display:inline-flex">
                                  <input type="text" class="form-control question" name="question" placeholder="type your question here">
                                  <div class="input-group-append">
                                    <button class="btn btn-outline-secondary send-msg" type="button" name="send">Ask</button>
                                    
                                  </div>
                                  
                             
                                </div>
                                <div class="input-group input-group-lg mb-3" style="margin: 0 auto; width: 50%; display:inline">

                                 <?php 
                                //   echo $_SESSION['subject'];
                                  if($subject['whatsapp']!=NULL){
                                 ?>
                                  
                                    <a href="<?php echo $subject['whatsapp'];?>" class="btn btn-outline-success " target="_blank">
                                      <i class="fab fa-whatsapp" style="font-size: 22px;"></i>
                                    </a>
                                  <?php } ?>
                                </div>
                                </form>
                               
                                
                                
                            
                                <div class="d-flex my-5 justify-content-around">
                                    <a href="<?php echo $prev == 1 ? $urlprev : "#";?>"><button <?php echo $prev == 1 ? : "disabled";?> class="btn btn-lg btn-primary">Prev</button></a>
                                    <a href="<?php echo $next == 1 ? $urlnext : "#";?>"><button <?php echo $next == 1 ? : "disabled";?> class="btn btn-lg btn-info">Next</button></a>
                                </div>
                            </div>
                            </div>
                    </div>
                                          
                                          
                     </div>
                
            </div>
        </div>
        <!-- Page Area End Here -->
    </div>
    <?php include_once "footer.php"; ?>
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
    <!-- Data Table Js -->
    <script src="js\jquery.dataTables.min.js"></script>
    <!-- Custom Js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="js\main.js"></script>

       <!-- recorde student mintus of video-->
    <script>
            var vid=$(".videoid").val(); 
            var sid=$(".studentid").val();  
             var videotime=$(".videotime").val();  
         setInterval(function(){
              $.ajax({
              type: "POST",
              url: "videotime.php",
              data: {
                  
                  videoid: vid,
                  studentid: sid,
                  videotime:videotime,
              },
              success: function(data){}});
       
         
        
             
         }, 60000);
         
         
         $(".send-msg").click(function(){
                var vid=$(".videoid").val(); 
                var sid=$(".studentid").val(); 
                var question=$(".question").val(); 
                
                if(question != ''){
                    
                          $.ajax({
                          type: "POST",
                          url: "ajax/send-video-question.php",
                          data: {
                              
                              videoid: vid,
                              studentid: sid,
                              question:question,
                          },
                          success: function(data){
                              $(".question").val(''); 
                              
                                 Command: toastr["success"]('Done Send Message')
        
                                    toastr.options = {
                                    "closeButton": true,
                                    "debug": true,
                                    "newestOnTop": false,
                                    "progressBar": true,
                                    "positionClass": "toast-top-right",
                                    "preventDuplicates": false,
                                    "showDuration": "300",
                                    "hideDuration": "1000",
                                    "timeOut": "5000",
                                    "extendedTimeOut": "1000",
                                    "showEasing": "swing",
                                    "hideEasing": "linear",
                                    "showMethod": "fadeIn",
                                    "hideMethod": "fadeOut"
                                    }
                          }});
                  
                }
                 
                
         });
         
         

  var elements = $('.rev');
var lastShown = 0;

function fadeInRandomElement() {
    // choose random element index to show
    var randomIndex = Math.floor(Math.random()*elements.length);
    // prevent showing same element 2 times in a row
    while (lastShown == randomIndex) {
        randomIndex = Math.floor(Math.random()*elements.length);
    }
    var randomElement = elements.eq(randomIndex);
    // set random position > show > wait > hide > run this function once again
    randomElement
        .css({
            top: Math.random()*100 + "%",
            left: Math.random()*100 + "%"
        })
        .fadeIn(500)
        .delay(500)
        .fadeOut(500, fadeInRandomElement);
}

fadeInRandomElement();


        
    </script>
    
</body>

</html>
<?php ob_end_flush();?>