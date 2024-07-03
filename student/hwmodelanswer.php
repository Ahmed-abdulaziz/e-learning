<?php 
session_start();
if(!isset($_SESSION['name'])){
    header("Location:index.php?msg=".urlencode("Please Login First"));
    exit();
}
include_once "connect.php";
$email=$_SESSION['name'];
$stmt=$con->prepare("SELECT id FROM students WHERE email = ?");
$stmt->execute(array($email));
$result=$stmt->fetch();
$studentid=$result['id'];


if(isset($_GET['id'])){
    $id=$_GET['id'];
 
    
        $stmt=$con->prepare("SELECT * FROM homeworkquestions WHERE homeworkid =?");
        $stmt->execute(array($id));
        $questions=$stmt->fetchAll();
        $questionsno=count($questions);
        $radiono=0;
        $givenno=0;
    
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



?>

<!doctype html>
<html class="no-js" lang="">

<head>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-145403987-2"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        
        gtag('config', 'UA-145403987-2');
    </script>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>E-Learning System | Model Answer</title>
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
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="css\style.css">
    <!-- Modernize js -->
    <script src="js\modernizr-3.6.0.min.js"></script>
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
                    <div class="row">
                        <div class="col-lg-8">
                            <h3>Student Dashboard</h3>
                            <ul>
                                <li>
                                    <a href="hwmodelanswer.php">HomeworkModelAnswer</a>
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
                <!-- Breadcubs Area End Here -->
                <!-- Student Table Area Start Here -->
                <div class="card height-auto">
                    <div class="card-body">
                        <div class="heading-layout1">
                            <div class="item-title">
                                <h3>Model Answer</h3>
                            </div>
                        </div>                      
                    <form method="post">
                        <div class="row">
                        <?php   
                            $i=1;
                            foreach($questions as $a){
                            $questionid=$a['questionid'];
                      
                            $type=$a['type'];
                            if($type=='0') { 
                                
                                $stmt=$con->prepare("SELECT * FROM answers WHERE homeworkid =? AND studentid = ? AND questionid = ? AND type = ?");
                                $stmt->execute(array($id,$studentid,$questionid , 0));
                                $ans=$stmt->fetch();
                                $answer=$ans['answer'];

                                $stmt=$con->prepare("SELECT * FROM questions WHERE id = ?");
                                $stmt->execute(array($questionid));
                                $currentquestion=$stmt->fetch();

                                
                                ?>
                                <div class="col-lg-8">
                                <input type="text" name="multichoiceids[]" hidden value="<?php echo $questionid; ?>" >
                                <?php if(!empty($currentquestion['image'])) {?>
                                <img class="cimg" src="dashboard/img/questions/<?php echo $currentquestion['image'];?>">
                                <?php }?>
                                <p class="pp"><?php echo $i.") ".$currentquestion['question'];?> </p>
                                <label class="container"><?php echo $currentquestion['choice1'];?>
                                <img class="cimg w-50" src="dashboard/img/questions/<?php echo $currentquestion['imgchoice1'];?>">
                                <input type="radio" disabled  value="<?php echo '1'; ?>" <?php if($answer==1 || ($currentquestion['answer']==1 && $ans['answer'] !=1 )) echo "checked";?>>
                                <span class="checkmark <?php if($currentquestion['answer']==1){ echo "true";} elseif($answer==1){ echo "wrong";}?>"></span>
                                
                                      <?php 
                                    if($ans['answer']== 1) { echo "Your Answer is ";
                                        
                                        
                                        if($ans['answer'] == 1 && $currentquestion['answer']==1){echo " Correct";} else{ echo " Wrong";}
                                        
                                    }
                                       if($currentquestion['answer']==1 && $ans['answer'] != 1 ){echo " Correct";}
                                    
                                    ?>
                                </label>
                                <label class="container"><?php echo $currentquestion['choice2'];?>
                                <img class="cimg w-50" src="dashboard/img/questions/<?php echo $currentquestion['imgchoice2'];?>">
                                <input type="radio" disabled  value="<?php echo "2"; ?>" <?php if($answer==2 || ($currentquestion['answer']==2 && $ans['answer'] != 2 )) echo "checked";?>>
                                <span class="checkmark  <?php if($currentquestion['answer']==2){ echo "true";} elseif($answer==2){ echo "wrong";}?>"></span>
                                  <?php 
                                    if($ans['answer']== 2) { echo "Your Answer is ";
                                        
                                        
                                        if($ans['answer'] == 2 && $currentquestion['answer']==2){echo " Correct";} else{ echo " Wrong";}
                                        
                                    }
                                       if($currentquestion['answer']==2 && $ans['answer'] != 2 ){echo " Correct";}
                                    
                                    ?>
                                </label>
                                 <?php if(!empty($currentquestion['choice3'])){?>
                                <label class="container"><?php echo $currentquestion['choice3'];?>
                               <img class="cimg w-50" src="dashboard/img/questions/<?php echo $currentquestion['imgchoice3'];?>">
                                <input type="radio" disabled  value="<?php echo "3";?>" <?php if($answer==3 || ($currentquestion['answer']==3 && $ans['answer'] != 3 )) echo "checked";?>>
                                <span class="checkmark <?php if($currentquestion['answer']==3){ echo "true";} elseif($answer==3){ echo "wrong";}?>"></span>
                                
                                  <?php 
                                    if($ans['answer']== 3) { echo "Your Answer is ";
                                        
                                        
                                        if($ans['answer'] == 3 && $currentquestion['answer']==3){echo " Correct";} else{ echo " Wrong";}
                                        
                                    }
                                       if($currentquestion['answer']==3 && $ans['answer'] != 3 ){echo " Correct";}
                                    
                                    ?>
                                </label>
                                <?php }if(!empty($currentquestion['choice4'])){?>
                                <label class="container"><?php echo $currentquestion['choice4'];?>
                                <img class="cimg w-50" src="dashboard/img/questions/<?php echo $currentquestion['imgchoice4'];?>">
                                <input type="radio" disabled  value="<?php echo "4";?>"
                                <?php if($answer==4 || ($currentquestion['answer']==4 && $ans['answer'] != 4 )) echo "checked";?> >
                                <span class="checkmark <?php if($currentquestion['answer']==4){ echo "true";} elseif($answer==4){ echo "wrong";}?>"></span>
                                    
                                      <?php 
                                    if($ans['answer']== 4) { echo "Your Answer is ";
                                        
                                        
                                        if($ans['answer'] == 4 && $currentquestion['answer']==4){echo " Correct";} else{ echo " Wrong";}
                                        
                                    }
                                       if($currentquestion['answer']==4 && $ans['answer'] != 4 ){echo " Correct";}
                                    
                                    ?>
                                </label>
                                <?php }?>
                                
                                </div>

                                <div class="col-lg-4 degree">
                                
                                    <label>Degree: </label>
                                    <input type="number" class="form-control" name="multidegrees[]" 
                                    value="<?php 
                                        if($answer==$currentquestion['answer']) echo $a['degree'];
                                        else echo "0";
                                    ?>"  readonly><span> / </span>
                                
                                    <input type="text" class="form-control" value="<?php echo $a['degree']?>" disabled>
                            
                                </div>
                            
                             <hr>
                            <?php $radiono++;} elseif($type=='1'){ 

                                $stmt=$con->prepare("SELECT * FROM answers WHERE homeworkid =? AND studentid = ? AND questionid = ? AND type = ?");
                                $stmt->execute(array($id,$studentid,$questionid , 1));
                                $ans=$stmt->fetch();
                                $answer=$ans['answer'];
                                
                                $stmt=$con->prepare("SELECT * FROM essay WHERE id =?");
                                $stmt->execute(array($questionid));
                                $currentquestion=$stmt->fetch();
                                
                                $stmt=$con->prepare("SELECT * FROM homeworkquestions WHERE homeworkid =? AND questionid = ? AND type = ?");
                                $stmt->execute(array($id,$questionid,1));
                                $essaydegree=$stmt->fetch();
                                
                                
                            ?>
                            
                                <div class="col-lg-8">
                                    <input type="text" name="essayids[]" hidden value="<?php echo $questionid; ?>" >
                                    <?php if(!empty($currentquestion['image'])) {?>
                                    <img class="cimg" src="dashboard/img/questions/<?php echo $currentquestion['image'];?>">
                                    <?php }?>
                                    <p class="pp"><?php echo $i;?>) <?php echo $currentquestion['question'];?> ?</p>
                                    <p><?php echo $answer;?></p>
                                </div>
                                <div class="col-lg-4 degree">
                                
                                    <label>Degree: </label>
                                    <input type="number" disabled value="<?php echo $ans['degree']?>" min="0" class="form-control" name="essaydegrees[]" required><span> / </span>
                                
                                    <input type="text" class="form-control" value="<?php echo $essaydegree['degree'];?>" disabled>
                            
                                </div>
                            
                       <hr>
                        <?php }elseif($type=='2'){
                        
                                 
                                
                               
                                    $stmt=$con->prepare("SELECT * FROM questions WHERE givens = ?");
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
                                    
                                $stmt=$con->prepare("SELECT * FROM answers WHERE homeworkid =? AND studentid = ? AND questionid = ? AND type = ?");
                                $stmt->execute(array($id,$studentid,$currentquestion['id'] , 2));
                                $ans=$stmt->fetch();
                                $answer=$ans['answer'];

                                    
                        ?>
                        
                         <div class="col-lg-8">
                                <input type="text" name="givenids[]" hidden value="<?php echo $currentquestion['id']; ?>" >
                                <?php if(!empty($currentquestion['image'])) {?>
                                <img class="cimg" src="dashboard/img/questions/<?php echo $currentquestion['image'];?>">
                                <?php }?>
                                <p class="pp"><?php echo $g.") ".$currentquestion['question'];?> </p>
                                <label class="container"><?php echo $currentquestion['choice1'];?>
                                <input type="radio" disabled name="given[<?php echo $givenno; ?>]" value="<?php echo '1'; ?>" <?php if($answer==1) echo "checked";?>>
                                <span class="checkmark <?php if($currentquestion['answer']==1){ echo "true";} elseif($answer==1){ echo "wrong";}?>"></span>
                                </label>
                                <label class="container"><?php echo $currentquestion['choice2'];?>
                                <input type="radio" disabled name="given[<?php echo $givenno; ?>]" value="<?php echo "2"; ?>" <?php if($answer==2) echo "checked";?>>
                                <span class="checkmark <?php if($currentquestion['answer']==2){ echo "true";} elseif($answer==2){ echo "wrong";}?>"></span>
                                </label>
                                 <?php if(!empty($currentquestion['choice3'])){?>
                                <label class="container"><?php echo $currentquestion['choice3'];?>
                                <input type="radio" disabled name="given[<?php echo $givenno; ?>]" value="<?php echo "3";?>" <?php if($answer==3) echo "checked";?>>
                                <span class="checkmark <?php if($currentquestion['answer']==3){ echo "true";} elseif($answer==3){ echo "wrong";}?>"></span>
                                </label>
                                <?php }if(!empty($currentquestion['choice4'])){?>
                                <label class="container"><?php echo $currentquestion['choice4'];?>
                                <input type="radio" disabled name="given[<?php echo $givenno; ?>]" value="<?php echo "4";?>"
                                <?php if($answer==4) echo "checked";?> >
                                <span class="checkmark <?php if($currentquestion['answer']==4){ echo "true";} elseif($answer==4){ echo "wrong";}?>"></span>
                                </label>
                                <?php }?>
                                
                                </div>

                                <div class="col-lg-4 degree">
                                
                                    <label>Degree: </label>
                                    <input type="number" class="form-control" name="givendegrees[]" 
                                    value="<?php 
                                        if($answer==$currentquestion['answer']) echo "1";
                                        else echo "0";
                                    ?>"  readonly><span> / </span>
                                
                                    <input type="text" class="form-control" value="1" disabled>
                            
                                </div>
                             <hr>
                            
                        <?php $givenno++;$g++; } }
                    $i++;
                    }?>
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

</body>

</html>