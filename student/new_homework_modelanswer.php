<?php 
include_once 'connect.php';
//set cookie lifetime for 100 days (60sec * 60mins * 24hours * 100days)
ini_set('session.cookie_lifetime',0);
ini_set('session.gc_maxlifetime', 60 * 60 * 24 * 100);
ini_set('session.gc_probability',0);
date_default_timezone_set('Africa/Cairo');
//then start the session
session_start();
if(!isset($_SESSION['name'])){
    header("Location:index.php?msg=".urlencode("Please Login First"));
    exit();
}
if(isset($_SESSION['name']))
{
$name=$_SESSION['name'];
$stmt=$con->prepare("SELECT id FROM students WHERE email = ?");
$stmt->execute(array($name));
$results=$stmt->fetch();
$studentid=$results['id'];


$homeworkID = '';
$no = '';

 if(isset($_GET['qID'])){
       $homeworkID = $_GET['qID'];
 }

$stmt=$con->prepare("SELECT * FROM new_homeworks WHERE id = ?");
$stmt->execute(array($homeworkID));
$new_homework=$stmt->fetch();
$no = $new_homework['q_no'];
$date1 =$new_homework['date1'];
$date2 =$new_homework['date2']; 


$stmt=$con->prepare("SELECT * FROM new_homework_questions WHERE homeworkid = ?");
$stmt->execute(array($homeworkID));
$new_homework_qs=$stmt->fetchAll();






$stmt=$con->prepare("SELECT name FROM subjects WHERE id = ? ");   
$stmt->execute(array($_SESSION['subject']));
$subject=$stmt->fetch();

$stmt=$con->prepare("SELECT teacher_id FROM subjects WHERE id = ? ");
$stmt->execute(array($_SESSION['subject']));
$t=$stmt->fetch();

$stmt=$con->prepare("SELECT fullname FROM admins WHERE id = ? ");
$stmt->execute(array($t['teacher_id']));
$teacher=$stmt->fetch();
}
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
    <title>E-Learning System  | Current Homeworks</title>
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
    <link rel="stylesheet" href="bubbleSheet_style.css?v=0.1">
    
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    
  <script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">
  
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
             <?php if(isset($done)) echo $done;?>
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
                                    <a href="new_homeworks.php">Homeworks</a>
                                </li>
                                <li>Student</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- Breadcubs Area End Here -->
                <!-- Student Table Area Start Here -->
                <div class="card height-auto">
                    <div class="card-body">
                        <div class="heading-layout1">
                            <div class="item-title">
                                <h3><?php echo $new_homework['name']; ?> - Homework</h3>
                            </div>
                        
                        </div>
                         <?php if(isset($_GET['err'])){?>
                            <div class="alert alert-danger errors alert-dismissible fade show" role="alert">
                                <?php echo $_GET['err'];?>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <?php }?>
                            
                            
                            <form method="POST" enctype="multipart/form-data" class="b_s">
                                <!--<div class="row border">-->
                                <!--      <div class="col-xl-6 col-lg-6 col-12 form-group">-->
                                <!--           <label>After Solving Upload your Steps (PDF) [10M For Size] - [Compress your PDF <a href="https://avepdf.com/hyper-compress-pdf">Here !</a>]</label>-->
                                <!--           <input type="file" class="form-control" required accept="application/pdf" name="hPdf"  id="file" onchange="Filevalidation()">-->
                                <!--      </div>-->
                                <!--      <div class="col-lg-6 form-group">-->
                                <!--          <input type="submit" name="done" id="Done" value="Finish" class="btn-fill-lg btn-gradient-yellow btn-hover-bluedark mt-4">-->
                                <!--      </div>-->
                                <!-- </div>-->
                                 <!--<input type="submit" name="done" value="Finish" class="btn-info">-->
                                 <!--<input type="file" name="hPdf">-->
                             
                                <main>
                                    <div class="col">
                                    </div>
                                    <div class="col">
                                        <strong>(1)</strong>
                                    </div>
                                    <div class="col">
                                    </div>
                                    <div class="col">
                                    </div>
                                    <div class="col">
                                    <div class="bubble-group">

                                        <span>(T)</span>
                                        <span>(F)</span>
                                        <span></span>
                                        <span></span>
                                
                                    </div>
                                    <div class="bubble-group">

                                <?php
                                $i = 1;
                                foreach($new_homework_qs as $q) {
                                    
                                    $stmt=$con->prepare("SELECT * FROM questions WHERE id = ?");
                                    $stmt->execute(array($q['questionid']));
                                    $currentquestion=$stmt->fetch();
                                    
                                    $stmt=$con->prepare("SELECT * FROM new_homework_answers WHERE homeworkid = ? AND studentid = ? AND questionid = ?");
                                    $stmt->execute(array($homeworkID, $studentid,$currentquestion['id']));
                                    $an=$stmt->fetch();
                                // for($i = 1; $i<=$no ; $i++){ 
                                ?>
                                        <input type="text" hidden name="qids[]" value="<?php echo $q['questionid']; ?>">
                                        <strong><?php echo $i; ?></strong>
                                        <input type="radio" required value="A"  <?php if($an['answer']=="A" || $currentquestion['answer']=="A") echo "checked";?> disabled  id="_<?php echo $i; ?>a"/>
                                        <label class="bubble  <?php if($currentquestion['answer']=="A"){ echo "bubble_true";} elseif($an['answer']=="A"){ echo "bubble_wrong";}?>" for="_<?php echo $i; ?>a">
                                        <div class="bubble-inner">A</div>
                                        </label>

                                        <input type="radio"  required value="B" <?php if($an['answer']=="B" || $currentquestion['answer']=="B") echo "checked";?> disabled id="_<?php echo $i; ?>b"/>
                                        <label class="bubble <?php if($currentquestion['answer']=="B"){ echo "bubble_true";} elseif($an['answer']=="B"){ echo "bubble_wrong";}?>" for="_<?php echo $i; ?>b">
                                        <div class="bubble-inner">B</div>
                                        </label>

                                        <input type="radio" required value="C" <?php if($an['answer']=="C" || $currentquestion['answer']=="C") echo "checked";?> disabled  id="_<?php echo $i; ?>c"/>
                                        <label class="bubble <?php if($currentquestion['answer']=="C"){ echo "bubble_true";} elseif($an['answer']=="C"){ echo "bubble_wrong";}?>" for="_<?php echo $i; ?>c">
                                        <div class="bubble-inner">C</div>
                                        </label>

                                        <input type="radio" required value="D" <?php if($an['answer']=="D" || $currentquestion['answer']=="D") echo "checked";?> disabled  id="_<?php echo $i; ?>d"/>
                                        <label class="bubble <?php if($currentquestion['answer']=="D"){ echo "bubble_true";} elseif($an['answer']=="D"){ echo "bubble_wrong";}?>" for="_<?php echo $i; ?>d">
                                        <div class="bubble-inner">D</div>
                                        </label>

                                        <input type="radio" required disabled name="ans_<?php echo $q['questionid']; ?>" id="_<?php echo $i; ?>e"/>
                                        <label class="bubble" for="_<?php echo $i; ?>e">
                                        <div class=" bubble-inner"></div>
                                        </label>


                                        <?php $i++; } ?>
                                        
                                    </div>
                                    
                                    
                                    </div>

                                
                                </main>
                                      
                                </form>
                            
                   
          
                    </div>
                 
                </div>
                <!-- Student Table Area End Here -->
               
            </div>
        </div>
   
        <!-- Page Area End Here -->
    </div>

    <?php include_once "footer.php";?>
    
    
    <script>
    Filevalidation = () => {
        const fi = document.getElementById('file');
        // Check if any file is selected.
        if (fi.files.length > 0) {
            for (const i = 0; i <= fi.files.length - 1; i++) {
  
                const fsize = fi.files.item(i).size;
                const file = Math.round((fsize / 1024));
                // The size of the file.
                if (file >= 10096) {
                    alert(
                      "PDF is too Big, please select a file less than 10mb");
                      document.getElementById('Done').disabled = true;
                }else {
                    document.getElementById('Done').disabled = false;
                }
            }
        }
    }
</script>

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