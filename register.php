<?php
$page_title="R";
 require "connect.php";  
$stmt=$con->prepare("SELECT * FROM website_teacher");
$stmt->execute();
$allsubjects=$stmt->fetchAll();
  function check($var){
    return htmlentities(trim(strip_tags(stripcslashes($var))));
  }
   
  if(isset($_POST['submit'])){

        $name=check($_POST['name']);
        $username=check($_POST['username']);
        $email=check($_POST['email']);
        $pass=check($_POST['password']);
        $confpassword=check($_POST['confpassword']);
        $phone=check($_POST['phone']);
        $phone2=check($_POST['phone2']);
        $level=check($_POST['edu']);
        // $pUsername=check($_POST['pUsername']);
        // $pPassword=check($_POST['pPassword']);
        
        $governorate=check($_POST['governorate']);
        $school=check($_POST['school']);
        $area=check($_POST['area']);
    
    $err=[];
    
    if(!$name || !filter_var($name,FILTER_SANITIZE_STRING) ){
        $err['name']="اسم غير صحيح";
    }
    if($phone == $phone2){
          $err['phoneequel']="يجب أن يكون رقم هاتف الطالب مختلفًا عن رقم هاتف ولي الأمر";
    }
    
    //check for username duplication
    $stmt = $con->prepare("SELECT * FROM students WHERE username = ? ");
    $stmt -> execute(array($username));
    $row = $stmt->fetch();
    $cou = $stmt->rowCount();
     if(!$username || !filter_var($username,FILTER_SANITIZE_STRING) || $cou > 0  ){
        $err['name']="اسم المستخدم غير صالح";
    }


    
    
    //check for email duplication
    $stmt = $con->prepare("SELECT * FROM students WHERE email = ? ");
    $stmt -> execute(array($email));
    $row = $stmt->fetch();
    $cou = $stmt->rowCount();
    if(!$email || !filter_var($email,FILTER_VALIDATE_EMAIL) || $cou > 0 ){
        $err['email']="تم تسجيل عنوان البريد الإلكتروني هذا بالفعل ، يرجى المحاولة مرة أخرى";
        }
    
    if(!$pass || !filter_var($pass,FILTER_SANITIZE_STRING) ){
        $err['pass']="رمز مرور خاطئ";
    }
     if(!$confpassword || !filter_var($confpassword,FILTER_SANITIZE_STRING) ){
        $err['confpass']="تاكيد كلمة المرور غير صحيح";
    }

    if($pass != $confpassword){
        $err['confpassnotequel']="تاكيد كلمة المرور وكلمة المرور غير متطابقان";
    }

        
    //check for phone duplication
    $stmt = $con->prepare("SELECT * FROM students WHERE phone = ? ");
    $stmt -> execute(array($phone));
    $row = $stmt->fetch();
    $cou = $stmt->rowCount();
    if(!$phone || !filter_var($phone,FILTER_SANITIZE_NUMBER_INT) || $cou > 0 ){
        $err['phone']="رقم الهاتف مسجل بالفعل ، يرجى تجربة رقم آخر";
        }
    
    if(!$phone2 || !filter_var($phone2,FILTER_SANITIZE_NUMBER_INT) ){
        $err['phone1']="هاتف الوالد غير صالح";
    }
    
    if(!$level || !filter_var($level,FILTER_SANITIZE_STRING) ){
        $err['level']="مستوى تعليمي غير صحيح";
    }

    

    
    

    if(empty($err)){
        code:
        $code=rand(100000,999999);
        $stmt = $con->prepare("SELECT * FROM students WHERE code = ? ");
        $stmt -> execute(array($code));
        $row = $stmt->fetch();
        if ($stmt->rowCount() > 0){
          goto code;
        }
        
        // print_r($_POST);die;
        // move_uploaded_file($imageTmp , "student/img/profile//" . $Mimage);
        $stmt = $con->prepare("INSERT INTO students (username , name , email ,password, phone, phone2,eductionallevel,approve,code,governorate,school,area)
        VALUES(:zusername,:zname,:zemail,:zpassword,:zphone,:zphone2,:zlevel,:zapprove,:zcode,:zgovernorate,:zschool,:zarea)");
        $stmt->execute(array(
            'zusername' => $username,
            'zname' => $name,
            'zemail' => $email,
            'zpassword' => $pass,
            'zphone' => $phone,
            'zphone2' => $phone2,
            'zlevel'=>$level,
            'zapprove'=>"1",
            'zcode'=>$code,
            'zgovernorate'=>$governorate,
            'zschool'=>$school,
            'zarea'=>$area,
        
        ));
        $stu_last_id = $con->lastInsertId();
    
        $stmt = $con->prepare("INSERT INTO studentssubjects (studentid , subjectid ,classid,approve)
        VALUES(:zstid , :zsubid , :zclassid ,:zapprove)");
        $stmt->execute(array(
            'zstid' => $stu_last_id,
            'zsubid' => $level,
            'zclassid' => $level,
            'zapprove'=>"1"
        ));
        
        $stmt = $con->prepare("INSERT INTO student_wallet(studentid , money ,date)
        VALUES(:zstid , :zmon , :zdate)");
        $stmt->execute(array(
            'zstid' => $stu_last_id,
            'zmon' => "0",
            'zdate' => date('Y-m-d')
        ));
        
        $msg = "تم التسجل بنجاح في منصة EZY-STUDIES للطالب ($name)";
        
        $ch = curl_init();
        $url= "https://smssmartegypt.com/sms/api/?username=easyphysics&password=75E824*tR&sendername=Academy&mobiles=2".$phone2."&message=".urlencode($msg);
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec($ch);
        curl_close ($ch);

        $done = '<script>swal("Now You Are Registered"," Your Platform Code : '.$code.'" ,"success" );</script>';
    }
  }

?>
  
  
<!DOCTYPE HTML>
<html lang="en">

<?php require "head.php"; ?>

<body>
    
    <?php if(isset($done) && !empty($done)) echo $done;?>
    <!-- Preloader Start -->
    <div class="preloader">
        <img src="assets\images\preloader.svg" alt="preloader">
    </div>
    <!-- Preloader End -->
    
    
 <!-- Mobile Menu Start -->
    <aside class="aside_bar aside_bar_left aside_mobile">
        <!-- logo -->
        <a href="index.php" class="logo">
            <img src="assets\images\logo.png" alt="logo" style="width: 100px;">
        </a>
        <!-- logo -->
        <!-- Menu -->
        <nav>
            <ul class="main-menu">
                <li class="menu-item menu-item"><a href="index.php">Home</a></li>
                <li class="menu-item menu-item"><a href="index.php#vi">About US</a></li>
                <!--about-2.html-->
                <li class="menu-item menu-item"><a href="index.php#con">Contact us</a></li>
                <!--services.html-->
                <li class="menu-item menu-item"><a href="register.php">Registration</a></li>
                 <li class="menu-item menu-item"><a href="student/index.php">Login</a></li>
            </ul>
        </nav>
        <!-- Menu -->
    </aside>
    <div class="aside-overlay trigger-left"></div>
    <!-- Mobile Menu End -->
    <!-- Header Start -->
    <header class="header header-3">
        <div class="topbar bg-thm-color-two">
            <div class="container">
                <div class="row">
                    <div class="col-lg-7">
                        <!--<div class="left-side">-->
                        <!--    <p>Admission Going On, Hurry To Enroll Now</p>-->
                        <!--    <div class="countdown-timer" data-countdown="2022/05/06">0</div>-->
                        <!--</div>-->
                    </div>
                    <div class="col-lg-5">
                        <ul class="right-side">
                            <li>
                                <a href="tel:0123456789">
                                    <i class="fal fa-phone"></i>
                                    Call : 01201217769
                                </a>
                            </li>
                            <li>
                                <a href="/cdn-cgi/l/email-protection#583d20393528343d183d20393528343d763b3735">
                                    <i class="fal fa-envelope"></i>
                                    <span class="__cf_email__" data-cfemail="3a5f425b574a565f7a5f425b574a565f14595557">info@ezystudies.com</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="nav_sec">
            <div class="container">
                <!-- inner -->
                <div class="nav_warp">
                    <nav>
                        <!-- logo start -->
                        <div class="logo">
                            <a href="index.php">
                                <img src="assets\images\logo.png" alt="logo" class="image-fit" style="width: 130px;">
                            </a>
                        </div>
                        <!-- logo end -->
                        <!-- Navigation Start -->
                        <ul class="main-menu">
                             <li class="menu-item menu-item"><a href="index.php">Home</a></li>
                            <li class="menu-item menu-item"><a href="index.php#vi">ABOUT US</a></li>
                            <!--about-2.html-->
                            <li class="menu-item menu-item"><a href="index.php#con">Contact us</a></li>
                            <!--services.html-->
                            <li class="menu-item menu-item"><a href="register.php">Registration</a></li>
                             <li class="menu-item menu-item"><a href="student/index.php">Login</a></li>
                        </ul>
                        <!-- Navigation Ens -->
                        <!-- Head Actions -->
                        <div class="head_actions">
                            <a href="register.php" class="thm-btn bg-thm-color-three thm-color-three-shadow btn-rectangle">Enroll Now <i class="fal fa-chevron-right ml-2"></i></a>
                            <button type="button" class="head_trigger mobile_trigger">
                                <span></span>
                                <span></span>
                                <span></span>
                            </button>
                        </div>
                        <!-- Head Actions -->
                    </nav>
                </div>
                <!-- inner -->
            </div>
        </div>
   
    </header>
    <!-- Header End -->
    
    
   
    <!-- Subheader Start -->
    <!--<div class="subheader relative z-1" style="background-image: url(assets/images/subheader.jpg);">-->
    <!--    <div class="container relative z-1">-->
    <!--        <div class="row">-->
    <!--            <div class="col-12">-->
    <!--                <h1 class="page_title">Contact Us</h1>-->
    <!--                <div class="page_breadcrumb">-->
    <!--                    <nav aria-label="breadcrumb">-->
    <!--                        <ol class="breadcrumb">-->
    <!--                            <li class="breadcrumb-item"><a href="index.html">Home</a></li>-->
    <!--                            <li class="breadcrumb-item active" aria-current="page">Contact Us</li>-->
    <!--                        </ol>-->
    <!--                    </nav>-->
    <!--                </div>-->
    <!--            </div>-->
    <!--        </div>-->
    <!--        <img src="assets\images\elements\element_19.png" alt="element" class="element_1 slideRightTwo">-->
    <!--        <img src="assets\images\elements\element_10.png" alt="element" class="element_2 zoom-fade">-->
    <!--        <img src="assets\images\elements\element_20.png" alt="element" class="element_3 rotate_elem">-->
    <!--        <img src="assets\images\elements\element_21.png" alt="element" class="element_4 rotate_elem">-->
    <!--    </div>-->
    <!--</div>-->
    <!-- Subheader End -->
    
    
 <!-- Contact Form Start -->
    <section class="">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="section-title wow fadeInDown">
                        <p class="subtitle">
                            <i class="fal fa-book"></i>
                           E-Learning System
                        </p>
                        <h3 class="title">New Student Registeration</h3>
                    </div>
                </div>
            </div>
            
           <div class="row">
                <?php
                  if(isset($_GET['dmsg'])){
                    if(filter_var($_GET['dmsg'],FILTER_SANITIZE_STRING) ){
                        echo '<p class="alert alert-danger" style="text-align: right; direction:rtl">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="float: right !important;"> 
                        <span aria-hidden="true">&times;</span></button>' . check($_GET['dmsg']) .
                        '<button type="button" class="close" data-dismiss="alert" aria-label="Close" style="float: right !important;">
                        <span aria-hidden="true">&times;</span></button></p>';
                    }
                  }
                
                  if(isset($err) && !empty($err)){
                   
                  ?> 
                      <div class="alert w-100 alert-danger alert-dismissible fade show" role="alert" style="text-align: right;" >
                              <?php  foreach($err as $e){ echo $e."<br>"; }?>
                              <button type="button" class="close" data-dismiss="alert" aria-label="Close" >
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>

                  <?php  }
                  
                ?>
           </div>
            
            <form class="wow fadeInUp"  method="post" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-lg-6">
                         <div class="form-group form_style">
                            <label>UserName <span class="h3 text-danger">*</span></label>
                            <input type="text" value="<?php if(!empty($err) ) echo $username;  ?>" required name="username" class="form-control" autocomplete="off" placeholder="Username">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group form_style">
                            <label>Full Name <span class="h3 text-danger">*</span></label>
                            <input type="text" value="<?php if(!empty($err) ) echo $name;  ?>" required name="name" class="form-control" autocomplete="off" placeholder="Full Name">
                        </div>
                    </div>
                     <div class="col-lg-6">
                        <div class="form-group form_style">
                            <label>Email Address <span class="h3 text-danger">*</span></label>
                            <input type="email" name="email" value="<?php if(!empty($err) ) echo $email;  ?>" required  class="form-control" autocomplete="off" placeholder="Email Address">
                        </div>
                    </div>
                     
                    <div class="col-lg-6">
                        <div class="form-group form_style">
                            <label>Student's phone <span class="h3 text-danger">*</span></label>
                            <input type="text" name="phone" value="<?php if(!empty($err) ) echo $phone;  ?>" required class="form-control" autocomplete="off" placeholder="Student's phone">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group form_style">
                            <label>Parents' phone <span class="h3 text-danger">*</span></label>
                            <input type="text" name="phone2" value="<?php if(!empty($err) ) echo $phone2;  ?>" required class="form-control" autocomplete="off" placeholder="Parents' phone">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group form_style">
                            <label>Password <span class="h3 text-danger">*</span></label>
                            <input type="password" name="password" value="" required  class="form-control" autocomplete="off" placeholder="Password">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group form_style">
                            <label>Repeat Password <span class="h3 text-danger">*</span></label>
                            <input type="password" name="confpassword" value="" required  class="form-control" autocomplete="off" placeholder="Repeat Password">
                        </div>
                    </div>
                   
                   
                    <!-- <div class="col-lg-6">-->
                    <!--    <div class="form-group form_style">-->
                    <!--        <label>Parents's Username <span class="h3 text-danger">*</span></label>-->
                    <!--        <input type="text" name="pUsername" value="<?php //if(!empty($err) ) echo $pUsername;  ?>" required class="form-control" autocomplete="off" placeholder="Parents's UserName">-->
                    <!--    </div>-->
                    <!--</div>-->
                    <!--<div class="col-lg-6">-->
                    <!--    <div class="form-group form_style">-->
                    <!--        <label>Parents' Password <span class="h3 text-danger">*</span></label>-->
                    <!--        <input type="password" name="pPassword" value="<?php // if(!empty($err) ) echo $pPassword;  ?>" required class="form-control" autocomplete="off" placeholder="Parents' Password">-->
                    <!--    </div>-->
                    <!--</div>-->
                    
                    
                       <div class="col-lg-6">
                        <div class="form-group form_style">
                            <label>School <span class="h3 text-danger">*</span></label>
                            <input type="text" name="school" value="<?php if(!empty($err) ) echo $school;  ?>" required class="form-control" autocomplete="off" placeholder="School">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group form_style">
                            <label>Governorate <span class="h3 text-danger">*</span></label>
                             <select class="form-control" id="exampleSelectVendor" name="governorate" required>
                                   <option selected="selected">القاهرة</option>
                                    <option>الشرقية</option>
                                    <option >الغربية</option>
                                    <option>الإسكندرية</option>
                                    <option>الإسماعيلية</option>
                                    <option>كفر الشيخ</option>
                                    <option>أسوان</option>
                                    <option>أسيوط</option>
                                    <option>الأقصر</option>
                                    <option>الوادي الجديد</option>
                                    <option>شمال سيناء</option>
                                    <option>البحيرة</option>
                                    <option>بني سويف</option>
                                    <option>بورسعيد</option>
                                    <option>البحر الأحمر</option>
                                    <option>الجيزة</option>
                                    <option>الدقهلية</option>
                                    <option>جنوب سيناء</option>
                                    <option>دمياط</option>
                                    <option>سوهاج</option>
                                    <option>السويس</option>
                                    <option>الغربية</option>
                                    <option>الفيوم</option>
                                    <option>القليوبية</option>
                                    <option>قنا</option>
                                    <option>مطروح</option>
                                    <option>المنوفية</option>
                                    <option>المنيا</option>
                            
                        </select>
                        </div>
                    </div>
                     <div class="col-lg-6">
                        <div class="form-group form_style">
                            <label>Area <span class="h3 text-danger">*</span></label>
                            <input type="text" name="area" value="<?php if(!empty($err) ) echo $area;  ?>" required class="form-control" autocomplete="off" placeholder="area">
                        </div>
                    </div>
                      <div class="col-lg-6">
                            <div class="form_style">
                                <label>Study language <span class="h3 text-danger">*</span></label>
                                <select class="form-control language-type" required>
                                    <option value="">choose Study language...</option>
                                    <option value="en" ><?='English';?> </option>
                                    <option value="ar" ><?='Arabic';?> </option>
                                </select>
                            </div>
                    </div>
                    <div class="col-lg-6">
                            <div class="form_style">
                                <label>school Class <span class="h3 text-danger">*</span></label>
                                <select class="form-control level" required name="edu">
                                    <option value="">choose level...</option>
                                   
                                </select>
                            </div>
                    </div>
                    
                   
                    
                    <div class="col-lg-12 text-center pt-5">
                        <input type="submit" name="submit" class="thm-btn bg-thm-color-three thm-color-three-shadow btn-rectangle" value="Register"><br><br>
                    </div>
                </div>
            </form>
    </section>
        </div>
    <!-- Contact Form End -->
    
    
    
      <!-- Team Start -->
    <section class="section-padding bg-thm-color-two-gradient style_2 z-1 team_page_bg" style="background-image: url(assets/images/bg/bg_6.png);">
        <img src="assets\images\elements\element_31.png" class="element_1" alt="Element">
        <img src="assets\images\elements\element_32.png" class="element_2" alt="Element">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="section-title wow fadeInDown">
                        <p class="subtitle">
                            <i class="fal fa-book"></i>
                            Our Professionals
                        </p>
                        <h3 class="title">Meet Our Professionals Teachers</h3>
                    </div>
                </div>
            </div>
            <div class="row">
                
              <?php foreach($allsubjects as $teacher){ ?>
                <!-- Block Start -->
                <div class="col-lg-4 col-md-6">
                    <div class="team_block style_2 style_3 wow fadeInDown" data-wow-delay=".60ms">
                        <div class="team_img">
                            <img src="assets\images\<?php echo $teacher['img']; ?>" alt="img" class="image-fit">
                            <!--<a href="team-details.html" class="thm-btn bg-thm-color-two thm-color-two-shadow btn-circle link">-->
                            <!--    <i class="fal fa-plus"></i>-->
                            <!--</a>-->
                        </div>
                        <h6 class="mb-1"><a href="team-details.html"><?php echo $teacher['name']; ?></a></h6>
                        <p class="thm-color-two mb-0 font-weight-bold"><?php echo $teacher['title']; ?></p>
                    </div>
                </div>
                <?php } ?>
                
   
                
 
              
                <!-- Block Start -->
                <!--<div class="col-lg-3 col-md-6">-->
                <!--    <div class="team_block style_2 style_3 add_team wow fadeInDown" data-wow-delay=".120ms">-->
                <!--        <a href="contact.html" class="video_btn video_text popup-youtube">-->
                <!--            <i class="fal fa-plus video_icon bg-thm-color-three thm-btn p-0 thm-color-three-shadow"></i>-->
                <!--        </a>-->
                <!--    </div>-->
                <!--</div>-->
                <!-- Block End -->
            </div>

        </div>
    </section>
    <!-- Team End -->
   
    
    <?php require "footer.php"; ?>
     
     
    <a href="#" data-target="html" class="back-to-top ft-sticky">
        <i class="fal fa-long-arrow-up"></i>
    </a>
    <!-- Scripts -->
    <script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script><script src="assets\js\plugins\jquery-3.6.0.min.js"></script>
    <script src="assets\js\plugins\bootstrap.bundle.min.js"></script>
    <script src="assets\js\plugins\slick.min.js"></script>
    <script src="assets\js\plugins\imagesloaded.min.js"></script>
    <script src="assets\js\plugins\isotope.pkgd.min.js"></script>
    <script src="assets\js\plugins\jquery.magnific-popup.min.js"></script>
    <script src="assets\js\plugins\jquery.counterup.min.js"></script>
    <script src="assets\js\plugins\jquery.inview.min.js"></script>
    <script src="assets\js\plugins\jquery.easypiechart.js"></script>
    <script src="assets\js\plugins\jquery.countdown.min.js"></script>
    <script src="assets\js\plugins\wow.min.js"></script>
    <script src="assets\js\custom.js"></script>
    <!-- Maps -->
    <script src="assets\js\plugins\leaflet.js"></script>
    <script src="assets\js\map.js"></script>
    <!-- Form -->
    <script src="assets\js\plugins\jquery.validate.min.js"></script>
    <script src="assets\js\form.js"></script>
    
    <script>
    
    $(".language-type").change(function(){
        
          $.ajax({
              type: "POST",
              url: "ajax/get-level-lang.php",
              data:{
                 type: $(this).val(),
              }, success: function(data) {
                   
                         $(".level").html(data);
                      
                        
                     
                  }
            });
    });
        
    </script>
</body>

</html>