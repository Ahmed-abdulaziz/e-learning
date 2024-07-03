<?php 
session_start();
if(!isset($_SESSION['name'])){
    header("Location:index.php?msg=".urlencode("Please Login First"));
    exit();
}
ini_set('max_execution_time', 0);
include_once "connect.php";
date_default_timezone_set("Africa/Cairo");
$email= $_SESSION['name'];
$stmt=$con->prepare("SELECT id FROM students WHERE email = ?");
$stmt->execute(array($email));
$result=$stmt->fetch();
$studentid=$result['id'];


if(isset($_GET['id'])){
    
    $homeworkid=$_GET['id'];
     $wid=$_GET['wid'];
    $page=$_GET['page'];

         
    $stmt=$con->prepare("SELECT * FROM weeks WHERE id = ?");
    $stmt->execute(array($wid));
    $week=$stmt->fetch();
  
    if($week['arrange'] == 1){
             
                    $stmt=$con->prepare("SELECT * FROM week_details WHERE week_id = ? ORDER BY pro_order ASC");
                    $stmt->execute(array($wid));
                    $details=$stmt->fetchAll();
      
        
                     foreach($details as $item){
                         
                         if($item['type'] == 'video' ){
                                $stmt=$con->prepare("SELECT * FROM videocount WHERE videoid = ? AND studentid = ?");
                                $stmt->execute(array($item['product_id'],$studentid));
                                $check=$stmt->rowCount();
                                if($check < 1){
                                    header("Location:showvideo.php?id=".$item['product_id']."&wid=$wid&error=".urlencode("برجاء مشاهدة هذا الفيديو اولا اولا"));
                                    exit();
                                }
                                
                         }elseif($item['type'] == 'homework' && $item['product_id'] == $homeworkid){
                             break;
                         }elseif($item['type'] == 'exam'){
                             
                                $stmt=$con->prepare("SELECT * FROM studentexam WHERE studentid = ? AND examid = ?");
                                $stmt->execute(array($studentid,$item['product_id']));
                                $ex=$stmt->fetch();
                                $solveexam=$stmt->rowCount();
                                if($solveexam < 1){
                                    header("Location:answersexam.php?id=".$item['product_id']."&wid=$wid&error=".urlencode("برجاء اجتياز الامتحانات اولا"));
                                    exit();
                                }
                         }elseif($item['type'] == 'homework' && $item['product_id'] != $homeworkid){
                             
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
      }
   
   
    
    $stmt=$con->prepare("SELECT * FROM studenthw WHERE studentid = ? AND homeworkid = ?");
    $stmt->execute(array($studentid,$homeworkid));
    $solved=$stmt->fetchAll();
    if(COUNT($solved)!=0){
        header("Location:myweek.php?wid=$wid&error=".urlencode("Homework is already solved!"));
        exit();
    }
    
    $stmt=$con->prepare("SELECT * FROM homeworks WHERE id =?");
    $stmt->execute(array($homeworkid));
    $t=$stmt->fetch();
    $cdate=date("Y-m-d");
  

    
    $stmt=$con->prepare("SELECT * FROM homeworkquestions WHERE homeworkid =?");
    $stmt->execute(array($homeworkid));
    $questions=$stmt->fetchAll();
    $questionsno=count($questions);
    $radiono=0;
    $givenno=0;

}

if(isset($_POST['finish'])){

$wid=$_POST['wid'];
$total = 0;
   
if(isset($_POST['essay']) && isset($_POST['essayids'])){
$essayanswers=$_POST['essay'];
$essayids=$_POST['essayids'];  
foreach (array_combine($essayids, $essayanswers) as $essayid => $essayanswer) {
    $stmt=$con->prepare("INSERT INTO answers (studentid,questionid,type,homeworkid,answer) VALUES (:zstudentid,:zquestionid,:ztype,:zhomeworkid,:zanswer) ");
    $stmt->execute(array("zstudentid"=>$studentid,"zquestionid"=>$essayid,"ztype"=>"1","zhomeworkid"=>$homeworkid,"zanswer"=>$essayanswer));

  }
}

if(isset($_POST['radio']) && isset($_POST['multichoiceids'])){
$multichoiceanswers=$_POST['radio'];
$multichoiceids=$_POST['multichoiceids'];

        
    

  foreach (array_combine($multichoiceids, $multichoiceanswers) as $multichoiceid => $multichoiceanswer) {
      
        $stmt=$con->prepare("SELECT answer FROM questions WHERE id =?");
        $stmt->execute(array($multichoiceid));
        $question=$stmt->fetch();
        
        if($question['answer'] == $multichoiceanswer){
            $degree = 1;
            $total++;
        }else{
            $degree = 0;
        }
        
    $stmt=$con->prepare("INSERT INTO answers (studentid,questionid,type,homeworkid,answer,degree) VALUES (:zstudentid,:zquestionid,:ztype,:zhomeworkid,:zanswer,:zdegree) ");
    $stmt->execute(array("zstudentid"=>$studentid,"zquestionid"=>$multichoiceid,"ztype"=>"0","zhomeworkid"=>$homeworkid,"zanswer"=>$multichoiceanswer,'zdegree'=>$degree));

  }
  
}

if(isset($_POST['given']) && isset($_POST['givenids'])){
$givenanswers=$_POST['given'];
$givenids=$_POST['givenids'];


  foreach(array_combine($givenids, $givenanswers) as $givenid => $givenanswer) {
    $stmt=$con->prepare("INSERT INTO answers (studentid,questionid,type,homeworkid,answer) VALUES (:zstudentid,:zquestionid,:ztype,:zhomeworkid,:zanswer) ");
    $stmt->execute(array("zstudentid"=>$studentid,"zquestionid"=>$givenid,"ztype"=>"2","zhomeworkid"=>$homeworkid,"zanswer"=>$givenanswer));

  }
  
}

    $stmt=$con->prepare("INSERT INTO studenthw (studentid,homeworkid,result) VALUES (:zstudentid,:zhomeworkid,:zresult) ");
    $stmt->execute(array("zstudentid"=>$studentid,"zhomeworkid"=>$homeworkid,'zresult'=>$total));
    


header("Location:myweek.php?wid=$wid&msg=".urlencode("Done Solving H.W"));


}
?>

<!doctype html>
<html class="no-js" lang="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>E-Learning System | Answers</title>
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
    <!-- Date Picker CSS -->
    <link rel="stylesheet" href="css\datepicker.min.css">
    <!-- Select 2 CSS -->
    <link rel="stylesheet" href="css\select2.min.css">
    <!-- Data Table CSS -->
    <link rel="stylesheet" href="css\jquery.dataTables.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="style.css?v=0.1">
    <link rel="stylesheet" href="css\style.css">
    <!-- Modernize js -->
    <script src="js\modernizr-3.6.0.min.js"></script>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-145403987-2"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        
        gtag('config', 'UA-145403987-2');
    </script>
</head>

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
            <!-- Sidebar Area Start Here -->
      <?php include_once'sidebar.php' ?>
            <!-- Sidebar Area End Here -->
            <div class="dashboard-content-one">
                <!-- Breadcubs Area Start Here -->
                <div class="breadcrumbs-area">
                    <h3>Answers</h3>
                    <ul>
                        <li>
                            <a href="index3.php">Home</a>
                        </li>
                        <li>Answers</li>
                    </ul>
                </div>
                <!-- Breadcubs Area End Here -->
                <!-- Student Table Area Start Here -->
                <div class="card height-auto">
                    <div class="card-body">
                        <div class="heading-layout1">
                            <div class="item-title">
                                <h3>Answers</h3>
                            </div>

                        </div>
                        <div class="heading-layout1 text-center">
                            <div class="item-title w-100">
                                <h3><?php echo $t['name'];?></h3>
                            </div>

                        </div>
                        
                          <?php if(isset($_GET['msg'])){?>
                            <div class="alert alert-success  alert-dismissible fade show settingalert">
                                <?php echo $_GET['msg'];?>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        <?php } ?>
                        
                    <form method="post">
                        <input type="text" name="wid" hidden value="<?php echo $wid; ?>" >
                        <?php   
                            $i=1;
                            foreach($questions as $q){
                            $type=$q['type'];
                            $questionid=$q['questionid'];
                            
                            if($type=='0') { 
                                $stmt=$con->prepare("SELECT * FROM questions WHERE id =?");
                                $stmt->execute(array($questionid));
                                $currentquestion=$stmt->fetch();
                                
                                ?>
                                <input type="text" name="multichoiceids[]" hidden value="<?php echo $questionid; ?>" >
                                <?php if(!empty($currentquestion['image'])) {?>
                                <img class="qimg" src="dashboard/img/questions/<?php echo $currentquestion['image'];?>">
                                <?php }?>
                                <pre class="pp"><?php echo $i.") ".$currentquestion['question'];?> </pre>
                                <label class="container">
                                    <?php if($currentquestion['imgchoice1']){ ?>
                                     <img class="w-25" src="dashboard/img/questions/<?php echo $currentquestion['imgchoice1'];?>">
                                    <?php }?>
                                    <?php echo $currentquestion['choice1'];?>
                                    
                                <input type="radio" name="radio[<?php echo $radiono; ?>]" value="1">
                                <span class="checkmark"></span>
                                </label>
                                <label class="container">
                                        <?php if($currentquestion['imgchoice2']){ ?>
                                     <img class="w-25" src="dashboard/img/questions/<?php echo $currentquestion['imgchoice2'];?>">
                                    <?php }?>
                                    <?php echo $currentquestion['choice2'];?>
                                <input type="radio" name="radio[<?php echo $radiono; ?>]" value="2">
                                <span class="checkmark"></span>
                                </label>
                                <?php if(!empty($currentquestion['choice3'])){?>
                                <label class="container">
                                        <?php if($currentquestion['imgchoice3']){ ?>
                                     <img class="w-25" src="dashboard/img/questions/<?php echo $currentquestion['imgchoice3'];?>">
                                    <?php }?>
                                    <?php echo $currentquestion['choice3'];?>
                                <input type="radio" name="radio[<?php echo $radiono; ?>]" value="3">
                                <span class="checkmark"></span>
                                </label>
                                <?php }if(!empty($currentquestion['choice4'])){?>
                                <label class="container">
                                        <?php if($currentquestion['imgchoice4']){ ?>
                                     <img class="w-25" src="dashboard/img/questions/<?php echo $currentquestion['imgchoice4'];?>">
                                    <?php }?>
                                    <?php echo $currentquestion['choice4'];?>
                                <input type="radio" name="radio[<?php echo $radiono; ?>]" value="4">
                                <span class="checkmark"></span>
                                </label>
                                <?php }?>
                                
                                <label class="container" Style="display:none;"> 
                                <input type="radio" name="radio[<?php echo $radiono; ?>]" value="5" checked>
                                <span class="checkmark"></span>
                                </label>
                             <hr>
                            
                            <?php $radiono++;} elseif($type=='1'){ 

                                $stmt=$con->prepare("SELECT * FROM essay WHERE id =?");
                                $stmt->execute(array($questionid));
                                $currentquestion=$stmt->fetch();
                                
                            ?>
                            
                            <input type="text" name="essayids[]" hidden value="<?php echo $questionid; ?>" >
                            <?php if(!empty($currentquestion['image'])) {?>
                            <img class="qimg" src="dashboard/img/questions/<?php echo $currentquestion['image'];?>">
                            <?php }?>
                            <pre class="pp"><?php echo $i;?>) <?php echo $currentquestion['question']; ?></pre>
                            <textarea placeholder="Enter Your Answer" class="text form-control" name="essay[]"></textarea>
                                <hr>
                        <?php }elseif($type=='2'){

                                $stmt=$con->prepare("SELECT * FROM questions WHERE givens =?");
                                $stmt->execute(array($questionid));
                                $givens=$stmt->fetchAll();
                                
                                $stmt=$con->prepare("SELECT * FROM givens WHERE id =?");
                                $stmt->execute(array($questionid));
                                $q=$stmt->fetch();
                                
                                ?>
                                
                        <div class="col-lg-12">
                            <h6 class="pp"><?php echo $i.")";?> </h6>
                             <?php
                                    if(!empty($q['text'])){
                                        echo "<h2>".$q['text']."</h2>";
                                    }
                                    
                                    if(!empty($q['image'])){ ?>
                                  <img style="width: 300px;height: 300px;object-fit: cover;" src="dashboard/img/givens/<?php echo $q['image'];?>">
                                  <?php }?>
                        </div>
                        
                                                              
                        <?php
                        $g="A";
                        
                                foreach($givens as $currentquestion){
                        ?>
                        
                           <input type="text" name="givenids[]" hidden value="<?php echo $currentquestion['id']; ?>" >
                                <?php if(!empty($currentquestion['image'])) {?>
                                <img class="qimg" src="dashboard/img/questions/<?php echo $currentquestion['image'];?>">
                                <?php }?>
                                <pre class="pp"><?php echo $g.") ".$currentquestion['question'];?> </pre>
                                <label class="container"><?php echo $currentquestion['choice1'];?>
                                <input type="radio" name="given[<?php echo $givenno; ?>]" value="1">
                                <span class="checkmark"></span>
                                </label>
                                <label class="container"><?php echo $currentquestion['choice2'];?>
                                <input type="radio" name="given[<?php echo $givenno; ?>]" value="2">
                                <span class="checkmark"></span>
                                </label>
                                <?php if(!empty($currentquestion['choice3'])){?>
                                <label class="container"><?php echo $currentquestion['choice3'];?>
                                <input type="radio" name="given[<?php echo $givenno; ?>]" value="3">
                                <span class="checkmark"></span>
                                </label>
                                <?php }if(!empty($currentquestion['choice4'])){?>
                                <label class="container"><?php echo $currentquestion['choice4'];?>
                                <input type="radio" name="given[<?php echo $givenno; ?>]" value="4">
                                <span class="checkmark"></span>
                                </label>
                                <?php }?>
                                
                                <label class="container" Style="display:none;"> 
                                <input type="radio" name="given[<?php echo $givenno; ?>]" value="5" checked>
                                <span class="checkmark"></span>
                                </label>
                            
                             <hr>
                        
                   <?php $givenno++;$g++;} } $i++;
                    }?>

                        <div class="row gutters-8">
                                    
                                           <div class="col-12 form-group mg-t-8">
                                            <button type="submit" class="btn-fill-lg btn-gradient-yellow btn-hover-bluedark confirmation" name="finish"  >Finish Homework</button>
                                         </div>
                        </div>
                    </form>
                        


                    </div>
                    </div>
                </div>
                <!-- Student Table Area End Here -->
               
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
    <!-- Select 2 Js -->
    <script src="js\select2.min.js"></script>
    <!-- Scroll Up Js -->
    <script src="js\jquery.scrollUp.min.js"></script>
    <!-- Date Picker Js -->
    <script src="js\datepicker.min.js"></script>
    <!-- Data Table Js -->
    <script src="js\jquery.dataTables.min.js"></script>
    <!-- Custom Js -->
    <script src="js\main.js"></script>
    <script type="text/javascript">
        var elems = document.getElementsByClassName('confirmation');
        var confirmIt = function (e) {
            if (!confirm('هل تريد انهاء الواجب الآن؟')) e.preventDefault();
        };
        for (var i = 0, l = elems.length; i < l; i++) {
            elems[i].addEventListener('click', confirmIt, false);
        }
    </script>

</body>

</html>