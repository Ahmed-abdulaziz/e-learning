<?php    
ob_start();
ini_set('session.cookie_lifetime', 60 * 60 * 24 );
ini_set('session.gc_maxlifetime', 60 * 60 * 24 );
session_start();
include_once 'connect.php';

function string_between_two_string($str, $starting_word, $ending_word)
{
    $subtring_start = strpos($str, $starting_word);
    //Adding the strating index of the strating word to 
    //its length would give its ending index
    $subtring_start += strlen($starting_word);  
    //Length of our required sub string
    $size = strpos($str, $ending_word, $subtring_start) - $subtring_start;  
    // Return the substring from the index substring_start of length size 
    return substr($str, $subtring_start, $size);  
}
  
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
if(isset($_GET['homeworkid'])){
 $homeworkid = $_GET['homeworkid'];
 $wid = $_GET['wid'];

    $stmt=$con->prepare("SELECT * FROM weeks WHERE id = ?");
    $stmt->execute(array($wid));
    $week=$stmt->fetch();
    
    if(!empty($week['exam1_id']) || !empty($week['exam2_id'])){
        
            
                
    $stmt=$con->prepare("SELECT * FROM studentexam WHERE studentid = ? AND examid = ?");
    $stmt->execute(array($studentid,$week['exam1_id']));
    $exam1=$stmt->fetch();
    $check_exam1=$stmt->rowCount();
    $exid = $week['exam1_id'];
    if($check_exam1 < 1 ){
        header("Location:answersexam.php?id=$exid&wid=$wid&error=".urlencode("برجاء اجتياز الامتحانات اولا"));
        exit();
    }else{
                $stmt=$con->prepare("SELECT * FROM exams WHERE id = ?");
                $stmt->execute(array($exam1['examid']));
                $min_degree1=$stmt->fetch();
    
                if($exam1['result'] < $min_degree1['min_degree'] && !empty($week['exam2_id'])){
                    
                      $stmt=$con->prepare("SELECT * FROM studentexam WHERE studentid = ? AND examid = ?");
                            $stmt->execute(array($studentid,$week['exam2_id']));
                            $exam2=$stmt->fetch();
                            $check_exam2=$stmt->rowCount();
                            $exid = $week['exam2_id'];
                            if($check_exam2 < 1 ){
                                header("Location:answersexam.php?id=$exid&wid=$wid&error=".urlencode("برجاء اجتياز الامتحانات اولا"));
                                exit();
                            }
    
                }
        
    }
    
    
}

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

$stmt=$con->prepare("SELECT name FROM subjects WHERE id = ? ");   
$stmt->execute(array($_SESSION['subject']));
$subject=$stmt->fetch();

$stmt=$con->prepare("SELECT teacher_id FROM subjects WHERE id = ? ");
$stmt->execute(array($_SESSION['subject']));
$t=$stmt->fetch();

$stmt=$con->prepare("SELECT fullname FROM admins WHERE id = ? ");
$stmt->execute(array($t['teacher_id']));
$teacher=$stmt->fetch();

if(isset($_POST['send']))
{
     $videoid=check($_POST['videoid']);
     $studentid=check($_POST['studentid']);
     $question=$_POST['question'];
    //  echo $question;
     $error=array();

    
     if(empty($error)){
     $stmt=$con->prepare("INSERT INTO videoquestions (videoid,question,studentid)
         VALUES(:videoid,:question,:studentid)");
     $stmt->execute(array('videoid'=>$videoid ,'question'=>$question,'studentid'=>$studentid));
    //  header("Location:videos.php?mssg=".urlencode("Done Send Question"));
    //   exit();
}}
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
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.0/css/all.min.css" integrity="sha512-10/jx2EXwxxWqCLX/hHth/vu2KY3jCF70dCQB8TSgNjbCVAC/8vai53GfMDrO2Emgwccf2pJqxct9ehpzG+MTw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="stylesheet" href="css\animate.min.css">
    <!-- Data Table CSS -->
    <link rel="stylesheet" href="css\jquery.dataTables.min.css">
    <!-- Custom CSS -->
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
        :fullscreen {
          background-color: #fff;
        }
        .screenbutton{
            font-size:25px;
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
                                    <a href="videos.php">Lectures</a>
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
                        
                          <?php if(isset($_GET['msag'])){?>
                            <div class="alert alert-success  alert-dismissible fade show settingalert">
                                <?php echo $_GET['msag'];?>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        <?php } ?>
                <!-- Breadcubs Area End Here -->
                <div class="row">
                    <!-- Student Info Area Start Here -->
                    <!-- Student Info Area End Here -->
                    <div class="col-12-xxxl col-12">
                        <div class="row">
                            <!-- Summery Area Start Here -->
                           
                           
                           
<!-- Make sure ?enablejsapi=1 is on URL -->
<!--<iframe id="video" src="//www.youtube.com/embed/FKWwdQu6_ok?enablejsapi=1&html5=1&mute=1" frameborder="0" allowfullscreen></iframe>-->

<!-- 
<!--SVG ==-->
<!--http://thenounproject.com/term/play/23255/ -->
<!--https://css-tricks.com/svg-tabs-using-svg-shape-template/-->
<!---->
<!--<svg class="defs">-->
<!--  <defs>-->
<!--    <path id="pause-button-shape" d="M24,0C10.745,0,0,10.745,0,24s10.745,24,24,24s24-10.745,24-24S37.255,0,24,0z M21,33.064c0,2.201-1.688,4-3.75,4-->
<!--	s-3.75-1.799-3.75-4V14.934c0-2.199,1.688-4,3.75-4s3.75,1.801,3.75,4V33.064z M34.5,33.064c0,2.201-1.688,4-3.75,4-->
<!--	s-3.75-1.799-3.75-4V14.934c0-2.199,1.688-4,3.75-4s3.75,1.801,3.75,4V33.064z" />-->
<!--    <path id="play-button-shape" d="M24,0C10.745,0,0,10.745,0,24s10.745,24,24,24s24-10.745,24-24S37.255,0,24,0z M31.672,26.828l-9.344,9.344-->
<!--	C20.771,37.729,19.5,37.2,19.5,35V13c0-2.2,1.271-2.729,2.828-1.172l9.344,9.344C33.229,22.729,33.229,25.271,31.672,26.828z" />-->
<!--  </defs>-->
<!--</svg>-->

<!--<div class="buttons">-->
  <!-- if we needed to change height/width we could use viewBox here -->
<!--  <svg class="button" id="play-button">-->
<!--    <use xlink:href="#play-button-shape">-->
<!--  </svg>-->
<!--  <svg class="button" id="pause-button">-->
<!--    <use xlink:href="#pause-button-shape">-->
<!--  </svg>-->
<!--</div>-->

                           
                           

                            <div class="col-lg-12">
                                  
                                 <form class="new-added-form" method="post"  id="myvideo"   style="display: block; margin: 0 auto; width: 100%; ">
                                     
                                <?php if(!empty($video['video'])){ ?>
                                        <video width="100%" controls>
                                          <source src="<?php echo "dashboard/G2bMQTPtMV3KGLojM7rE/videos/".$video['video']; ?>" type="video/mp4">
                                        </video>
                                        

                                   <?php } elseif(!empty($video['iframe'])) {
                                        $iframe = $video['iframe']."?controls=0&vq=small";
                                   ?>
                                   <div class="position-relative "  style="width: fit-content;margin: 0 auto;">
                                        <div class="topvideo" style="width:100%;height:100%;opacity:0;position: absolute;z-index:9;"></div>
                                        <div id="YouTube-player" class="videow"></div>
                                        <div class="bottomvideo" style="width:100%;height:40px;opacity:1;background:#000;position: absolute;z-index:9;bottom:0;left:0;"></div>
                                        <div class="bottomvideo" style="width:100%;height:0;opacity:1;background:#000;position: absolute;z-index:9;top:0;left:0;"></div>
                                   </div>
                            
                                  <div style="width: fit-content; margin:0 auto;" class="my-5">
                                    <span class="nowrap margin-left-m margin-right-m">
                                      <!--<label for="YouTube-video-id">videoId</label>:-->
                                      <input hidden id="YouTube-video-id" type="text" value="<?php echo string_between_two_string($iframe,'embed/','?controls'); ?>" size="12" pattern="[_\-0-9A-Za-z]{11}" onchange="youTubePlayerChangeVideoId();">
                                    </span>
                                    
                                               <span class="nowrap ytplay">
                                                  <button type="button" class="btn " onclick="youTubePlayerPlay();"><i class="fas fa-play fa-2x"></i></button>
                                                  <!--<button onclick="youTubePlayerPause();">Pause</button>-->
                                                  <!--<button onclick="youTubePlayerStop();">Stop</button>-->
                                                </span>
                                      
                                    <input id="YouTube-player-progress" type="range" value="0" min="0" max="100" onchange="youTubePlayerCurrentTimeChange(this.value);" oninput="youTubePlayerCurrentTimeSlide();">
                                    <span id="vidtime"></span>
                                    <!--<label for="YouTube-player-progress">duration</label>-->
                                     <input id="YouTube-player-volume" type="range" value="50" min="0" max="100" onchange="youTubePlayerVolumeChange(this.value);" style="width:50px">
                                    <label for="YouTube-player-volume"><i class="fas fa-volume-up"></i></label>
                                    <!--<label for="avquality" id="avqualitybtn"><i class="fas fa-cog"></i></label>-->
                                    <label onclick="openFullscreen();"  class="openscreen btn screenbutton"><i class="fa-solid fa-expand"></i></label>
                                     <label onclick="closeFullscreen();"  class="closescreen btn screenbutton"> <i class="fa-solid fa-expand"></i></label>
                                 
                                        <!--<select id="avquality" style="opacity: 1;"></select>-->
                                        <select id="avspeed" onchange="youTubePlayerSpeedChange(this.value);">
                                            <option value="0.25">0.25</option>
                                            <option value="0.5">0.5</option>
                                            <option value="1" selected>1</option>
                                            <option value="1.5">1.5</option>
                                            <option value="2">2</option>
                                        </select>

                                  </div>
                            
                                  <div>
                                   
                                  </div>
                                   
                               
                                    <div id="comment1" class="rev"><?php echo $std['id'];?></div>
                                    <div id="comment2" class="rev"><?php echo $std['id'];?></div>
                                    <div id="comment3" class="rev"><?php echo $std['id'];?></div>
                                    <div id="comment4" class="rev"><?php echo $std['id'];?></div>
                                    <div id="comment5" class="rev"><?php echo $std['id'];?></div>
                                </div>
                                
                                
                                <?php } ?>
                                 
                                <h2 class="vname"> <?php echo $video['name']; ?></h2>
                                    <input type="text"  class="form-control videoid "  name="videoid"  value="<?php echo $video['id']; ?>" hidden>
                                    <input type="text"  class="form-control studentid" name="studentid"  value="<?php echo $results['id']; ?>" hidden>
                                    <input type="text"  class="form-control videotime" name="videotime"  value="<?php echo $videotime; ?>" hidden>
                                <div class="input-group input-group-lg mb-3" style="margin: 0 auto; width: 50%;">
                                  <input type="text" class="form-control" name="question" placeholder="type your question here" >
                                  <div class="input-group-append">
                                    <button class="btn btn-outline-secondary ask" type="button" name="send">Ask</button>
                                  </div>
                                </div>
                                </form>
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
    <script src="js\main.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
 
<script>

$("#avqualitybtn").click(function(){
    var $this = $(this);
    var $input = $('#' + $(this).attr('for')); 
    if ($input.is("select") && !$('.lfClon').length) {
        var $clon = $input.clone();
        var getRules = function($ele){ return {
            position: 'absolute',
            left: $ele.offset().left,
            top: $ele.offset().top,
            width: $ele.outerWidth(),
            height: $ele.outerHeight(),
            opacity: 0,
            margin: 0,
            padding: 0
        };};
        var rules = getRules($this);
        $clon.css(rules);
        $clon.on("mousedown.lf", function(){
            $clon.css({
                marginLeft: $input.offset().left - rules.left,
                marginTop: $input.offset().top - rules.top,
            });
            $clon.on('change blur', function(){
                $input.val($clon.val()).show();
                $clon.remove();
            });
            $clon.off('.lf');
        });
        $clon.on("mouseout.lf", function(){
            $(this).remove();
        });
        $clon.prop({id:'',className:'lfClon'});
        $clon.appendTo('body');
    }
});

document.body.addEventListener('click', clickqu, true); 
function clickqu(){
   
    var $this = $("#avqualitybtn");
    var $input = $('#' + $("#avqualitybtn").attr('for')); 
    if ($input.is("select") && !$('.lfClon').length) {
        var $clon = $input.clone();
        var getRules = function($ele){ return {
            position: 'absolute',
            left: $ele.offset().left,
            top: $ele.offset().top,
            width: $ele.outerWidth(),
            height: $ele.outerHeight(),
            opacity: 0,
            margin: 0,
            padding: 0
        };};
        var rules = getRules($this);
        $clon.css(rules);
        $clon.on("mousedown.lf", function(){
            $clon.css({
                marginLeft: $input.offset().left - rules.left,
                marginTop: $input.offset().top - rules.top,
            });
            $clon.on('change blur', function(){
                $input.val($clon.val()).show();
                $clon.remove();
            });
            $clon.off('.lf');
        });
        $clon.on("mouseout.lf", function(){
            $(this).remove();
        });
        $clon.prop({id:'',className:'lfClon'});
        $clon.appendTo('body');
    }
}


function convertHMS(value) {
    const sec = parseInt(value, 10); // convert value to number if it's string
    let hours   = Math.floor(sec / 3600); // get hours
    let minutes = Math.floor((sec - (hours * 3600)) / 60); // get minutes
    let seconds = sec - (hours * 3600) - (minutes * 60); //  get seconds
    // add 0 if value < 10; Example: 2 => 02
    if (hours   < 10) {hours   = "0"+hours;}
    if (minutes < 10) {minutes = "0"+minutes;}
    if (seconds < 10) {seconds = "0"+seconds;}
    if(hours>0){
        return hours+':'+minutes+':'+seconds; // Return is HH : MM : SS
    }else{
        return minutes+':'+seconds; // Return is MM : SS
    }
    
}




/**
 * YT.Player initialized by onYouTubeIframeAPIReady().
 */
var youTubePlayer;



/**
 * Function called by https://www.youtube.com/iframe_api
 * when it is loaded.
 *
 * Initialized YouTube iframe with the value of #YouTube-video-id as videoId
 * and the value of #YouTube-player-volume as volume.
 *
 * Adapted from:
 * https://developers.google.com/youtube/iframe_api_reference
 * https://developers.google.com/youtube/player_parameters?playerVersion=HTML5
 */
function onYouTubeIframeAPIReady() {
    'use strict';

    var inputVideoId = document.getElementById('YouTube-video-id');
    var videoId = inputVideoId.value;
    var suggestedQuality = 'hd720';
    var height = 280;
    var width = 500;
    var youTubePlayerVolumeItemId = 'YouTube-player-volume';


    function onError(event) {
        youTubePlayer.personalPlayer.errors.push(event.data);
    }


    function onReady(event) {
        var player = event.target;

        player.loadVideoById({suggestedQuality: suggestedQuality,
                              videoId: videoId
                             });
        player.pauseVideo();
        youTubePlayerDisplayFixedInfos();
    }


    function onStateChange(event) {
        var volume = Math.round(event.target.getVolume());
        var volumeItem = document.getElementById(youTubePlayerVolumeItemId);

        if (volumeItem && (Math.round(volumeItem.value) != volume)) {
            volumeItem.value = volume;
        }
    }


    youTubePlayer = new YT.Player('YouTube-player',
                                  {videoId: videoId,
                                   height: height,
                                   width: width,
                                   playerVars: {'autohide': 0,
                                                'cc_load_policy': 0,
                                                'controls': 0,
                                                'disablekb': 1,
                                                'iv_load_policy': 3,
                                                'modestbranding': 1,
                                                'rel': 0,
                                                'showinfo': 0,
                                                'start': 3
                                               },
                                   events: {'onError': onError,
                                            'onReady': onReady,
                                            'onStateChange': onStateChange
                                           }
                                  });

    // Add private data to the YouTube object
    youTubePlayer.personalPlayer = {'currentTimeSliding': false,
                                    'errors': []};
}


/**
 * :return: true if the player is active, else false
 */
function youTubePlayerActive() {
    'use strict';
    return youTubePlayer && youTubePlayer.hasOwnProperty('getPlayerState');
}


/**
 * Get videoId from the #YouTube-video-id HTML item value,
 * load this video, pause it
 * and show new infos.
 */
function youTubePlayerChangeVideoId() {
    'use strict';

    var inputVideoId = document.getElementById('YouTube-video-id');
    var videoId = inputVideoId.value;

    youTubePlayer.cueVideoById({suggestedQuality: 'hd720',
                                videoId: videoId
                               });
    youTubePlayer.pauseVideo();
    youTubePlayerDisplayFixedInfos();
}


/**
 * Seek the video to the currentTime.
 * (And mark that the HTML slider *don't* move.)
 *
 * :param currentTime: 0 <= number <= 100
 */
function youTubePlayerCurrentTimeChange(currentTime) {
    'use strict';

    youTubePlayer.personalPlayer.currentTimeSliding = false;
    if (youTubePlayerActive()) {
        youTubePlayer.seekTo(currentTime*youTubePlayer.getDuration()/100, true);
    }
}


/**
 * Mark that the HTML slider move.
 */
function youTubePlayerCurrentTimeSlide() {
    'use strict';

    youTubePlayer.personalPlayer.currentTimeSliding = true;
}


/**
 * Display embed code to #YouTube-player-fixed-infos.
 */
function youTubePlayerDisplayFixedInfos() {
    'use strict';

    if (youTubePlayerActive()) {
        // document.getElementById('YouTube-player-fixed-infos').innerHTML = (
        //     'Embed code: <textarea readonly>' + youTubePlayer.getVideoEmbedCode() + '</textarea>'
        // );
    }
}


/**
 * Display
 *   some video informations to #YouTube-player-infos,
 *   errors to #YouTube-player-errors
 *   and set progress bar #YouTube-player-progress.
 */
 var getqu=0;
function youTubePlayerDisplayInfos() {
    'use strict';

    if ((this.nbCalls === undefined) || (this.nbCalls >= 3)) {
        this.nbCalls = 0;
    }
    else {
        ++this.nbCalls;
    }

    var indicatorDisplay = '<span id="indicator-display" title="timing of informations refreshing">' + ['|', '/', String.fromCharCode(8212), '\\'][this.nbCalls] + '</span>';

    if (youTubePlayerActive()) {
        var state = youTubePlayer.getPlayerState();
        var current = youTubePlayer.getCurrentTime();
        var duration = youTubePlayer.getDuration();
        
        var dcurrent=convertHMS(Math.floor(current));
        var dduration=convertHMS(Math.floor(duration));
        
        
         var avq=youTubePlayer.getAvailableQualityLevels();
         var allopt;
         if(avq.length!=0 && getqu==0){
             getqu=1;
            avq.forEach( function(av){
                let opt = document.createElement('option');
                opt.value=av;
                opt.innerHTML += av;
                allopt += opt.outerHTML;
            });
            $("#avquality").html(allopt);
         }
        
        
        $("#vidtime").text(dcurrent+" / "+dduration);
        var currentPercent = (current && duration
                              ? current*100/duration
                              : 0);

        var fraction = (youTubePlayer.hasOwnProperty('getVideoLoadedFraction')
                        ? youTubePlayer.getVideoLoadedFraction()
                        : 0);

        var url = youTubePlayer.getVideoUrl();

        if (!current) {
            current = 0;
        }
        if (!duration) {
            duration = 0;
        }

        var volume = youTubePlayer.getVolume();

        if (!youTubePlayer.personalPlayer.currentTimeSliding) {
            document.getElementById('YouTube-player-progress').value = currentPercent;
        }

        // document.getElementById('YouTube-player-infos').innerHTML = (
        //     indicatorDisplay
        //         + 'URL: <a class="url" href="' + url + '">' + url + '</a><br>'
        //         + 'Quality: <strong>' + youTubePlayer.getPlaybackQuality() + '</strong>'
        //         + ' &mdash; Available quality: <strong>' + youTubePlayer.getAvailableQualityLevels() + '</strong><br>'
        //         + 'State <strong>' + state + '</strong>: <strong>' + youTubePlayerStateValueToDescription(state) + '</strong><br>'
        //         + 'Loaded: <strong>' + (fraction*100).toFixed(1) + '</strong>%<br>'
        //         + 'Duration: <strong>' + current.toFixed(2) + '</strong>/<strong>' + duration.toFixed(2) + '</strong>s = <strong>' + currentPercent.toFixed(2) + '</strong>%<br>'
        //         + 'Volume: <strong>' + volume + '</strong>%'
        // );

        // document.getElementById('YouTube-player-errors').innerHTML = '<div>Errors: <strong>' + youTubePlayer.personalPlayer.errors + '</strong></div>';
    }
    else {
        // document.getElementById('YouTube-player-infos').innerHTML = indicatorDisplay;
    }
}

$('#avquality').on('change', function () {
    // if (youTubePlayerActive()) {
    //     // alert($(this).val());
    //     youTubePlayer.setPlaybackQuality($(this).val());
    //      youTubePlayer.seekTo(60);
    //      console.log(youTubePlayer.getVideoPlaybackQuality());
         
    // }
    
    // var inputVideoId = document.getElementById('YouTube-video-id');
    // var videoId = inputVideoId.value;

    // youTubePlayer.cueVideoById({suggestedQuality: $(this).val(),
    //                             videoId: videoId
    //                           });
    // youTubePlayer.pauseVideo();
    // youTubePlayerDisplayFixedInfos();
    // alert("done");
});


/**
 * Pause.
 */
function youTubePlayerPause() {
    'use strict';

    if (youTubePlayerActive()) {
        youTubePlayer.pauseVideo();
    }
    $(".ytplay").html('<button type="button" class="btn " onclick="youTubePlayerPlay();"><i class="fas fa-play fa-2x"></i></button>');
}


/**
 * Play.
 */
function youTubePlayerPlay() {
    'use strict';

    if (youTubePlayerActive()) {
        youTubePlayer.playVideo();
    }
    $(".ytplay").html('<button type="button" class="btn " onclick="youTubePlayerPause();"><i class="fas fa-pause fa-2x"></i></button>');
    
}

// $("#avquality").on('change', function (e) {
//     var optionSelected = $("option:selected", this);
//     var valueSelected = this.value;
//     console.log(valueSelected);
//     if (youTubePlayerActive()) {
//         youTubePlayer.stopVideo();
//         youTubePlayer.clearVideo();
//         youTubePlayer.setPlaybackQuality(valueSelected);
//     }
    
// });










/**
 * Return the state decription corresponding of the state value.
 * If this value is incorrect, then return unknow.
 *
 * See values:
 * https://developers.google.com/youtube/iframe_api_reference#Playback_status
 *
 * :param number: any
 * :param unknow: any
 *
 * :return: 'unstarted', 'ended', 'playing', 'paused', 'buffering', 'video cued' or unknow
 */
function youTubePlayerStateValueToDescription(state, unknow) {
    'use strict';

    var STATES = {'-1': 'unstarted',   // YT.PlayerState.
                  '0': 'ended',        // YT.PlayerState.ENDED
                  '1': 'playing',      // YT.PlayerState.PLAYING
                  '2': 'paused',       // YT.PlayerState.PAUSED
                  '3': 'buffering',    // YT.PlayerState.BUFFERING
                  '5': 'video cued'};  // YT.PlayerState.CUED

    return (state in STATES
            ? STATES[state]
            : unknow);
}


/**
 * Stop.
 */
function youTubePlayerStop() {
    'use strict';

    if (youTubePlayerActive()) {
        youTubePlayer.stopVideo();
        youTubePlayer.clearVideo();
    }
}


/**
 * Change the volume.
 *
 * :param volume: 0 <= number <= 100
 */
function youTubePlayerVolumeChange(volume) {
    'use strict';

    if (youTubePlayerActive()) {
        youTubePlayer.setVolume(volume);
    }
}

function youTubePlayerSpeedChange(speed) {
    'use strict';
    console.log(speed);
    if (youTubePlayerActive()) {
         youTubePlayer.setPlaybackRate(Number(speed));
    }
}

// $("#avspeed").on('change', function (e) {
//     var optionSelected = $("option:selected", this);
//     var valueSelected = this.value;
//     console.log(valueSelected);
//     if (youTubePlayerActive()) {
//         // youTubePlayer.stopVideo();
//         // youTubePlayer.clearVideo();
//         youTubePlayer.setPlaybackRate(valueSelected);
//     }
    
// });


/**
 * Main
 */
(function () {
    'use strict';

    function init() {
        // Load YouTube library
        var tag = document.createElement('script');

        tag.src = 'https://www.youtube.com/iframe_api';

        var first_script_tag = document.getElementsByTagName('script')[0];

        first_script_tag.parentNode.insertBefore(tag, first_script_tag);


        // Set timer to display infos
        setInterval(youTubePlayerDisplayInfos, 1000);
    }


    if (window.addEventListener) {
        window.addEventListener('load', init);
    } else if (window.attachEvent) {
        window.attachEvent('onload', init);
        
    }
}());


    



$(document).ready(function(){
    
        
        
    // function myFunction () {
    //     youTubePlayer.playVideo();
        
    // }

        
    //     setTimeout(myFunction, 3000);
    
    // youTubePlayerPlay();

    
});


</script>




<script>
         document.addEventListener('contextmenu', event => event.preventDefault());
      document.onkeydown = function(e) {
        if(event.keyCode == 123) {
          return false;
        }
        if(e.ctrlKey && e.shiftKey && e.keyCode == 'I'.charCodeAt(0)) {
          return false;
        }
        if(e.ctrlKey && e.shiftKey && e.keyCode == 'C'.charCodeAt(0)) {
          return false;
        }
        if(e.ctrlKey && e.shiftKey && e.keyCode == 'J'.charCodeAt(0)) {
          return false;
        }
        if(e.ctrlKey && e.keyCode == 'U'.charCodeAt(0)) {
          return false;
        }
      }
      function urlExistes(url, cb){
        jQuery.ajax({
          url: url,
          dataType: 'text',
          type: 'GET',
          complete: function(xhr){
            if(typeof cb === 'function')
              cb.apply(this, [xhr.status]);
          }
        })
      }
      urlExistes('chrome-extension://ngpampappnmepgilojfohadhhmbhlaek/document.js', function(status){
        if (status === 200) {
          alert('you have to uninstall or disable Internet Download Manger')
          window.location.href = 'https://omar-sherbeni.com/student/videos.php';
        }else if (status === 404) {
          console.log('not found');
        }else {
          console.log('something went wrong');
        }
      })
      urlExistes('chrome-extension://nflbkbalhllfaampjibamiobkglgnicm/manifest.json', function(status){
        if (status === 200) {
          alert('you have to uninstall or disable Easy Video Downloader')
          window.location.href = 'https://omar-sherbeni.com/student/videos.php';
        }else if (status === 404) {
          console.log('not found');
        }else {
          console.log('something went wrong');
        }
      })
      urlExistes('chrome-extension://lidiicnhhgbmlckpfodjodagnkocngcl/manifest.json', function(status){
        if (status === 200) {
          alert('you have to uninstall or disable My Video Downloader')
          window.location.href = 'https://omar-sherbeni.com/student/videos.php';
        }else if (status === 404) {
          console.log('not found');
        }else {
          console.log('something went wrong');
        }
      })
      urlExistes('chrome-extension://ailljajgcdcaadgmbncpfnofjanoabfn/manifest.json', function(status){
        if (status === 200) {
          alert('you have to uninstall or disable Video Download Center')
          window.location.href = 'https://omar-sherbeni.com/student/videos.php';
        }else if (status === 404) {
          console.log('not found');
        }else {
          console.log('something went wrong');
        }
      })
      
  
    </script>
    
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
        
    </script>
   
    
    <script>
    
    
     $('.ask').click(function(){
            var question= $(this).closest('form').find('input[name="question"]');
            var q=question.val(); 
            var vid=$(this).closest('form').find('input[name="videoid"]').val(); 
            var sid=$(this).closest('form').find('input[name="studentid"]').val(); 
            
            if(q==""){alert("Question can't be empty");return ;}
            $.ajax({
               type: "POST",
               url: "askq.php",
               data: {
                   auth: true,
                   videoid: vid,
                   studentid: sid,
                   question: q ,
               },
               success: function(data){
                question.val("");
                alert("Done sending question");
                   
               }
            });
            
            
            return false;
            
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
    
    <script language="JavaScript">
  
  //close console
  
  var config= {childList: true,
             attributes: true,
             characterData: true, 
             subtree: true, 
             attributeOldValue: true, 
             characterDataOldValue: true};
var observer = new MutationObserver(function(mutations){
  mutations.forEach(function(mutation){
    switch(mutation.type){
      case "attributes":
        observer.disconnect();
        if(mutation.attributeName == "class")
         mutation.target.className = mutation.oldValue;
        else if(mutation.attributeName=="id"||mutation.attributeName=="title")
         mutation.target[mutation.attributeName] = mutation.oldValue;
        else if(mutation.attributeName == "style")
          mutation.target.style.cssText = mutation.oldValue;
        observer.observe(document,config);
        break;
      case "characterData":
        observer.disconnect();
        mutation.target.data = mutation.oldValue;
        observer.observe(document,config);
        break;
      case "childList":
        observer.disconnect();
        if(mutation.addedNodes.length > 0)
          $(mutation.addedNodes[0]).remove();
        if(mutation.removedNodes.length > 0){
          if(mutation.nextSibling)
            $(mutation.removedNodes[0]).insertBefore(mutation.nextSibling);
          else if(mutation.previousSibling)
            $(mutation.removedNodes[0]).insertAfter(mutation.previousSibling);
          else
            $(mutation.removedNodes[0]).appendTo(mutation.target);
        }
        observer.observe(document,config);
        break;
    }
  });
});

// $(function(){
//   observer.observe(document,config);
// });


  var myPort=chrome.extension.sendRequest('ngpampappnmepgilojfohadhhmbhlaek','any', console.log("hi"));
  console.log("my"+myPort);
  
  
       
     
  
</script>


<script src="chrome://extensions/?id=ngpampappnmepgilojfohadhhmbhlaek" onerror="console.info('Extension Not Found')" onload="console.info('Extension Found')"></script>
<!-- since the the file error.gif is allowed in the manifest "web_accessible_resources" (any other file mentioned there would also be fine) -->
<!-- the block code should come in the onload of the script tag -->
<!-- tested with Chrome 27+ WinXp -->


<script type="module">
//close console
// 			import devtools from './dev.js';

// 			const stateElement = document.querySelector('#devtools-state');
// 			const orientationElement = document.querySelector('#devtools-orientation');

// // 			stateElement.textContent = devtools.isOpen ? 'yes' : 'no';
// // 			orientationElement.textContent = devtools.orientation ? devtools.orientation : '';
//             if(devtools.isOpen){
//                 // alert('You have to close developer tools');
//                 window.location.href = 'https://andrewchemistry.com/videos.php';
//             }
// 			window.addEventListener('devtoolschange', event => {
// 			    if(event.detail.isOpen){
//                     // alert('You have to close developer tools');
//                     window.location.href = 'https://andrewchemistry.com/videos.php';
//                 }
// 				// stateElement.textContent = event.detail.isOpen ? 'yes' : 'no';
// 				// orientationElement.textContent = event.detail.orientation ? event.detail.orientation : '';
// 			});
		</script>

<script>
$(".closescreen").hide();
var elem = document.getElementById("myvideo");
function openFullscreen() {
    $(".openscreen").hide();
    $(".closescreen").show();
    $(".videow").css("position", "relative");
    if($(window).width() > 767){
        $(".videow").css("width", "100vw");
        $(".videow").css("height", "85vh");
    }else{
        $(".videow").css("width", "100vw");
        $(".videow").css("height", "80vh");
    }
    
  if (elem.requestFullscreen) {
    elem.requestFullscreen();
  } else if (elem.webkitRequestFullscreen) { /* Safari */
    elem.webkitRequestFullscreen();
  } else if (elem.msRequestFullscreen) { /* IE11 */
    elem.msRequestFullscreen();
  }
}

/* Close fullscreen */
function closeFullscreen() {
     $(".openscreen").show();
     $(".closescreen").hide();
    if( $(window).width() > 1024){
        $(".videow").css("width", "500px");
        $(".videow").css("height", "280px");
    }else if($(window).width() > 767){
        $(".videow").css("width", "350px");
        $(".videow").css("height", "220px");
    }else{
        $(".videow").css("width", "92vw");
        $(".videow").css("height", "300px");
    }
    
  if (document.exitFullscreen) {
    document.exitFullscreen();
  } else if (document.webkitExitFullscreen) { /* Safari */
    document.webkitExitFullscreen();
  } else if (document.msExitFullscreen) { /* IE11 */
    document.msExitFullscreen();
  }
}

// $(document).on('keyup', function(e) {
//   e.preventdefault();
//   if (e.key == "Escape") {
      
//       $(".openscreen").show();
//             $(".closescreen").hide();
//          if( $(window).width() > 1024){
//             $(".videow").css("width", "500px");
//             $(".videow").css("height", "280px");
//         }else if($(window).width() > 767){
//             $(".videow").css("width", "350px");
//             $(".videow").css("height", "220px");
//         }else{
//             $(".videow").css("width", "92vw");
//             $(".videow").css("height", "300px");
//         }
//               if (document.exitFullscreen) {
//                 document.exitFullscreen();
//               } else if (document.webkitExitFullscreen) { /* Safari */
//                 document.webkitExitFullscreen();
//               } else if (document.msExitFullscreen) { /* IE11 */
//                 document.msExitFullscreen();
//               }
//   }
// });

// $(document).on('keydown', function(event) {
//       if (event.key == "Escape") {
//             $(".openscreen").show();
//             $(".closescreen").hide();
//          if( $(window).width() > 1024){
//             $(".videow").css("width", "500px");
//             $(".videow").css("height", "280px");
//         }else if($(window).width() > 767){
//             $(".videow").css("width", "350px");
//             $(".videow").css("height", "220px");
//         }else{
//             $(".videow").css("width", "92vw");
//             $(".videow").css("height", "300px");
//         }
//               if (document.exitFullscreen) {
//                 document.exitFullscreen();
//               } else if (document.webkitExitFullscreen) { /* Safari */
//                 document.webkitExitFullscreen();
//               } else if (document.msExitFullscreen) { /* IE11 */
//                 document.msExitFullscreen();
//               }
//       }
//   });



   if( $(window).width() > 1024){
        $(".videow").css("width", "500px");
        $(".videow").css("height", "280px");
    }else  if($(window).width() > 767){
        $(".videow").css("width", "350px");
        $(".videow").css("height", "220px");
    }else{
        $(".videow").css("width", "92vw");
        $(".videow").css("height", "300px");
    }

  
   
</script>
</body>

</html>
<?php ob_end_flush();?>