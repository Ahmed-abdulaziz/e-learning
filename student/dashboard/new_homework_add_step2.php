<?php 
include_once 'connect.php';
session_start();
if(!isset($_SESSION['adminname'])){
    header("Location:index.php?msg=".urlencode("Please Login First"));
    exit();
}
function check($var){
    return htmlentities(trim(strip_tags(stripcslashes($var))));
  }
$name=$_SESSION['adminname'];
$stmt=$con->prepare("SELECT type FROM admins WHERE name=?");
$stmt->execute(array($name));
$result=$stmt->fetch();
$type=$result['type'];
if($type!="super admin" && $type!="admin"){
header("Location:index3.php?msg=You Not Have A Permission");
}

$subjectid =$_SESSION['subject'];
$qID = '';
$no = '';
 if(isset($_GET['qID']) && isset($_GET['no'])){
       $qID = $_GET['qID'];
       $no = $_GET['no'];
 }
    $stmt=$con->prepare("SELECT * FROM new_homeworks WHERE id = ?");
    $stmt->execute(array($qID));
    $quiz=$stmt->fetch();


    if(isset($_POST['done'])){

        $count = count($_POST);
        $qName = 1;
        foreach($_POST as $qs){
            if (--$count <= 0) {
                break;
            }
            $stmt=$con->prepare("INSERT INTO questions (question , choice1 , choice2 , choice3 , choice4 , answer,subjectid,type) 
            VALUES(:question ,:choice1 , :choice2 , :choice3 , :choice4 , :answer, :subjectid,:type)");
            $stmt->execute(array(
                'question'=>$qName,
                'choice1'=>'A',
                'choice2'=>'B',
                'choice3'=>'C',
                'choice4'=>'D',
                'answer'=>$qs,
                'subjectid'=>$subjectid,
                'type'=>"1"
            ));
            $last_id_q = $con->lastInsertId();
    
            $stmt=$con->prepare("INSERT INTO new_homework_questions (homeworkid , questionid ) 
            VALUES(:homeworkid , :questionid )");
            $stmt->execute(array(
                'homeworkid'=>$qID,
                'questionid'=>$last_id_q,
            ));
            $qName++;
        }

        header("Location:new_homeworks.php?msg=".urlencode("Done Adding Homework"));
        exit();
       
    }


?>
<!doctype html>
<html class="no-js" lang="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>E-Learning System | Add Quiz</title>
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
    <link rel="stylesheet" href="style.css?v=1">
    <!-- Modernize js -->
    <script src="js\modernizr-3.6.0.min.js"></script>
    <link rel="stylesheet" href="bubbleSheet_style.css?v=1">
<!-----------------------------------------------------------------------------------------------  -->

<!-----------------------------------------------------------------------------------------------  -->
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
                    <h3>Add Homeworks</h3>
                    <ul>
                        <li>
                            <a href="index2.php">Home</a>
                        </li>
                        <li>Add Homeworks</li>
                    </ul>
                </div>
                <!-- Breadcubs Area End Here -->
                <!-- Account Settings Area Start Here -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="heading-layout1">
                                    <div class="item-title">
                                        <h3>Add Homework - <?php echo $quiz['name']; ?></h3>
                                        <?php if(!empty($errr)){  ?>
                                             <div class="alert alert-danger errors alert-dismissible fade show adminerror" role="alert">
                                                    <?php echo $errr['namee']; ?>
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                        <span aria-hidden="true">×</span>
                                                    </button>
                                             </div>
                                        <?php }?>
                                    </div>
                                </div>  

                                <form method="POST" class="b_s">
                                <!-- <input id="sideways" type="checkbox"/> -->
                                <!-- <label for="sideways">Turn Sideways</label> -->
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

                                <?php for($i = 1; $i<=$no ; $i++){  ?>

                                        <strong><?php echo $i; ?></strong>
                                        <input type="radio" required value="A" name="_<?php echo $i; ?>" id="_<?php echo $i; ?>a"/>
                                        <label class="bubble" for="_<?php echo $i; ?>a">
                                        <div class="bubble-inner">A</div>
                                        </label>

                                        <input type="radio"  required value="B" name="_<?php echo $i; ?>" id="_<?php echo $i; ?>b"/>
                                        <label class="bubble" for="_<?php echo $i; ?>b">
                                        <div class="bubble-inner">B</div>
                                        </label>

                                        <input type="radio" required value="C" name="_<?php echo $i; ?>" id="_<?php echo $i; ?>c"/>
                                        <label class="bubble" for="_<?php echo $i; ?>c">
                                        <div class="bubble-inner">C</div>
                                        </label>

                                        <input type="radio" required value="D" name="_<?php echo $i; ?>" id="_<?php echo $i; ?>d"/>
                                        <label class="bubble" for="_<?php echo $i; ?>d">
                                        <div class="bubble-inner">D</div>
                                        </label>

                                        <input type="radio" required disabled name="_<?php echo $i; ?>" id="_<?php echo $i; ?>e"/>
                                        <label class="bubble" for="_<?php echo $i; ?>e">
                                        <div class=" bubble-inner"></div>
                                        </label>


                                        <?php } ?>
                                        
                                    </div>
                                    
                                    
                                    </div>

                                
                                </main>
                                <input type="submit" name="done" value="Finish" class="btn-fill-lg btn-gradient-yellow btn-hover-bluedark mt-4">
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

    <script>
        $(document).ready(function () {
        setTimeout(function () {
            $('.alert').fadeOut();
        }, 4000);
        });

        function fetch_select(val)
                {
                    $.ajax({
                        type: 'post',
                        url: 'select.php',
                        data: {
                        get_option:val
                        },
                        success: function (response) {
                        document.getElementById("new_select").innerHTML=response; 
                        }
                    });
                }

    </script>

</body>

</html>