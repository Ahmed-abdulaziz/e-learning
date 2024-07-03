<!DOCTYPE HTML>
<html lang="en">

<?php include "head.php"; 

include_once 'connect.php';

$stmt=$con->prepare("SELECT * FROM website_teacher");
$stmt->execute();
$allsubjects=$stmt->fetchAll();


$stmt=$con->prepare("SELECT * FROM website_videos");
$stmt->execute();
$allvideos=$stmt->fetchAll();

?>

<body>
    
    <!-- Preloader Start -->
    <div class="preloader">
        <img src="assets\images\preloader.svg" alt="preloader">
    </div>
    <!-- Preloader End -->
    
<?php require "nav.php"; ?>

 <!-- Banner Start -->
    <div class="bg-banner relative z-1" style="background-image: url(assets/images/banner/banner-1.jpg);">
        <div class="container">
            <div class="row">
                <div class="col-xl-7 col-lg-10 relative z-1">
                    <div class="banner_text">
                        <h1 class="title thm-color-white wow fadeInDown" data-wow-delay=".30ms">E-Learning System ONLINE COURSES</h1>
                        <form class="wow fadeInUp" data-wow-delay=".40ms">
                            <!--<div class="input-group">-->
                            <!--    <span class="input-group-preappend">-->
                            <!--        <i class="fal fa-search"></i>-->
                            <!--    </span>-->
                            <!--    <input type="text" name="#" class="form-control" placeholder="Search Coach" autocomplete="off">-->
                            <!--    <div class="input-group-append">-->
                            <!--        <button type="submit" class="thm-btn bg-thm-color-two thm-color-two-shadow btn-rectangle ml-0">-->
                            <!--            Search Now-->
                            <!--            <i class="fal fa-chevron-right ml-2"></i>-->
                            <!--        </button>-->
                            <!--    </div>-->
                            <!--</div>-->
                            <div class="form-check-inline">
                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input v2" name="coach" checked="">Physics 
                                </label>
                            </div>
                            <div class="form-check-inline">
                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input v3" name="coach">Bio
                                </label>
                            </div>
                            <div class="form-check-inline disabled">
                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input v4" name="coach">Chemistry 
                                </label>
                            </div>
                              <div class="form-check-inline disabled">
                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input v5" name="coach">Math 
                                </label>
                            </div>
                              <div class="form-check-inline disabled">
                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input v6" name="coach">English 
                                </label>
                            </div>
                        </form>
                    </div>
                    <img src="assets\images\elements\element_19.png" alt="element" class="element_1 slideRightTwo">
                    <img src="assets\images\elements\element_10.png" alt="element" class="element_2 zoom-fade">
                    <img src="assets\images\elements\element_21.png" alt="element" class="element_3 rotate_elem">
                    <img src="assets\images\elements\element_20.png" alt="element" class="element_4 rotate_elem">
                </div>
            </div>
            <div class="think_box wow fadeInDown" data-wow-delay=".50ms">
                <img src="assets\images\elements\think_element.png" alt="element" class="think_element">
                <div class="text_box">
                    <p class="top mb-0"><span>100</span>% Success</p>
                    <p class="bottom mb-0">We Care Your<br>Student</p>
                </div>
            </div>
        </div>
    </div>
    <!-- Banner End -->
    
     <!-- About Us Start -->
    <section class="section section-bg about_bg about style_2" id="v2" style="background-image: url(assets/images/bg/bg_1.png);">
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-lg-6">
                    <div class="image_boxes relative z-1 mb-md-80 h-100">
                        <!--<img src="assets\images\about\small.jpg" class="small_img wow fadeInUp" alt="img">-->
                        <img src="assets\images\11.jpeg" style="border-radius:15px;" class="big_img wow fadeInDown" alt="img">
                        <!-- elements -->
                        <img src="assets\images\elements\element_22.png" class="element_1" alt="Element">
                        <img src="assets\images\elements\element_23.png" class="element_2 rotate_elem" alt="Element">
                        <img src="assets\images\elements\element_24.png" class="element_3 rotate_elem" alt="Element">
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="section-title left-align wow fadeInDown">
                        <p class="subtitle">
                            <i class="fal fa-book"></i>
                            Who We Are
                        </p>
                        <h3 class="title">Dr.David Saead</h3>
                        <p class="mb-0">A physical teacher and supervisor in Cairo - Giza taught over 10,000 students with a great experience in the new system tenchu and questions</p>
                    </div>
                    <ul class="about_list style_2 mb-xl-30 wow fadeInUp">
                        <li>
                            Physics
                        <li>
                            Bio.
                        </li>
                        <li>
                            Chemistry
                        </li>
                        <li>
                            Math
                        </li>
                        <li>
                            English
                        </li>
                    </ul>
                    <a href="register.php" class="thm-btn bg-thm-color-two thm-color-two-shadow btn-rectangle wow fadeInDown">
                         Register Now
                        <i class="fal fa-chevron-right ml-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>
    
    <section class="section section-bg about_bg about style_2 dnone" id="v3" style="background-image: url(assets/images/bg/bg_1.png);">
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-lg-6">
                    <div class="image_boxes relative z-1 mb-md-80 h-100">
                        <!--<img src="assets\images\about\small.jpg" class="small_img wow fadeInUp" alt="img">-->
                        <img src="assets\images\97611_marco2.jpg" style="border-radius:15px;" class="big_img wow fadeInDown" alt="img">
                        <!-- elements -->
                        <img src="assets\images\elements\element_22.png" class="element_1" alt="Element">
                        <img src="assets\images\elements\element_23.png" class="element_2 rotate_elem" alt="Element">
                        <img src="assets\images\elements\element_24.png" class="element_3 rotate_elem" alt="Element">
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="section-title left-align wow fadeInDown">
                        <p class="subtitle">
                            <i class="fal fa-book"></i>
                            Who We Are
                        </p>
                        <h3 class="title text-right">Dr. Marcos A.hedra</h3>
                        <p class="mb-0 ar_Font">
                                Dr. Marcos A.hedra <br>
                                شرح تفصيلي لمادة البايولوجى بصورة مبسطة لفهم المنهج ، مع كم كبير جدا من الاسئلة والتدريبات العمليه على كامل المنهج طبقا لاختبارات الوزارة
                        </p>
                    </div>
                    <ul class="about_list style_2 mb-xl-30 wow fadeInUp">
                        <li>
                            Physics
                        <li>
                            Bio.
                        </li>
                        <li>
                            Chemistry
                        </li>
                        <li>
                            Math
                        </li>
                        <li>
                            English
                        </li>
                    </ul>
                    <a href="register.php" class="thm-btn bg-thm-color-two thm-color-two-shadow btn-rectangle wow fadeInDown">
                         Register Now
                        <i class="fal fa-chevron-right ml-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>
    
    <section class="section section-bg about_bg about style_2 dnone" id="v4" style="background-image: url(assets/images/bg/bg_1.png);">
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-lg-6">
                    <div class="image_boxes relative z-1 mb-md-80 h-100">
                        <!--<img src="assets\images\about\small.jpg" class="small_img wow fadeInUp" alt="img">-->
                        <img src="assets\images\46424_WhatsApp%20Image%202022-10-05%20at%208.24.37%20PM.jpeg" style="border-radius:15px;" class="big_img wow fadeInDown" alt="img">
                        <!-- elements -->
                        <img src="assets\images\elements\element_22.png" class="element_1" alt="Element">
                        <img src="assets\images\elements\element_23.png" class="element_2 rotate_elem" alt="Element">
                        <img src="assets\images\elements\element_24.png" class="element_3 rotate_elem" alt="Element">
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="section-title left-align wow fadeInDown">
                        <p class="subtitle">
                            <i class="fal fa-book"></i>
                            Who We Are
                        </p>
                        <h3 class="title">Wait for the surprise</h3>
                        <!--<p class="mb-0 ar_Font">منصة تعليمية تهتم بالارتقاء بمستوى التعليم عن بعد وتقديم خدمات تعليمية للثانوية العامة في شتى التخصصات</p>-->
                    </div>
                    <ul class="about_list style_2 mb-xl-30 wow fadeInUp">
                        <li>
                            Physics
                        <li>
                            Bio.
                        </li>
                        <li>
                            Chemistry
                        </li>
                        <li>
                            Math
                        </li>
                        <li>
                            English
                        </li>
                    </ul>
                    <a href="register.php" class="thm-btn bg-thm-color-two thm-color-two-shadow btn-rectangle wow fadeInDown">
                         Register Now
                        <i class="fal fa-chevron-right ml-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>
    
    <section class="section section-bg about_bg about style_2 dnone" id="v5" style="background-image: url(assets/images/bg/bg_1.png);">
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-lg-6">
                    <div class="image_boxes relative z-1 mb-md-80 h-100">
                        <!--<img src="assets\images\about\small.jpg" class="small_img wow fadeInUp" alt="img">-->
                        <img src="assets\images\mrkarimtamam.jpeg" style="border-radius:15px;" class="big_img wow fadeInDown" alt="img">
                        <!-- elements -->
                        <img src="assets\images\elements\element_22.png" class="element_1" alt="Element">
                        <img src="assets\images\elements\element_23.png" class="element_2 rotate_elem" alt="Element">
                        <img src="assets\images\elements\element_24.png" class="element_3 rotate_elem" alt="Element">
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="section-title left-align wow fadeInDown">
                        <p class="subtitle">
                            <i class="fal fa-book"></i>
                            Who We Are
                        </p>
                        <h3 class="title  text-right">Mr. Karim Tamam </h3>
                        <p class="mb-0 ar_Font">
                                    خبير مادة Maths لطلبة الثانوية العامة
                                    مادة علميه وتدريبات عمليه منظمة وشرح مبسط وامتحانات بدون تعقيد
                        </p>
                    </div>
                    <ul class="about_list style_2 mb-xl-30 wow fadeInUp">
                        <li>
                            Physics
                        <li>
                            Bio.
                        </li>
                        <li>
                            Chemistry
                        </li>
                        <li>
                            Math
                        </li>
                        <li>
                            English
                        </li>
                    </ul>
                    <a href="register.php" class="thm-btn bg-thm-color-two thm-color-two-shadow btn-rectangle wow fadeInDown">
                         Register Now
                        <i class="fal fa-chevron-right ml-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>
    
    
    
    
    
    <section class="section section-bg about_bg about style_2 dnone" id="v6" style="background-image: url(assets/images/bg/bg_1.png);">
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-lg-6">
                    <div class="image_boxes relative z-1 mb-md-80 h-100">
                        <!--<img src="assets\images\about\small.jpg" class="small_img wow fadeInUp" alt="img">-->
                        <img src="assets\images\abdelhamid hamid.jpeg" style="border-radius:15px;" class="big_img wow fadeInDown" alt="img">
                        <!-- elements -->
                        <img src="assets\images\elements\element_22.png" class="element_1" alt="Element">
                        <img src="assets\images\elements\element_23.png" class="element_2 rotate_elem" alt="Element">
                        <img src="assets\images\elements\element_24.png" class="element_3 rotate_elem" alt="Element">
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="section-title left-align wow fadeInDown">
                        <p class="subtitle">
                            <i class="fal fa-book"></i>
                            Who We Are
                        </p>
                        <h3 class="title">Mr. Abdelhamid Hamid</h3>
                        <p class="mb-0 ar_Font">
                            
                        </p>
                    </div>
                    <ul class="about_list style_2 mb-xl-30 wow fadeInUp">
                        <li>
                            Physics
                        <li>
                            Bio.
                        </li>
                        <li>
                            Chemistry
                        </li>
                        <li>
                            Math
                        </li>
                        <li>
                            English
                        </li>
                    </ul>
                    <a href="register.php" class="thm-btn bg-thm-color-two thm-color-two-shadow btn-rectangle wow fadeInDown">
                         Register Now
                        <i class="fal fa-chevron-right ml-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>
    <!-- About Us End -->


    
    
    <!-- Vision Start -->
    <section class="mt-5 section_counter">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="service_block shadow_1 wow fadeInUp" wow-data-delay=".10ms">
                        <div class="icon">
                            <img src="assets\images\service\4.png" alt="icon">
                        </div>
                        <div class="text">
                            <h5 class="title"><a href="#">Vision</a></h5>
                            <p class="mb-0 ar_Font"> التدريب المتميز على نظم الامتحانات الحديثة وفقاً لرؤية وزارة التربية والتعليم وسعياً لاكتساب الطلاب الدارسين لمهارات التعليم ، خدمة متكاملة للطالب بأحدث الأساليب التكنولوجية وبإشراف نخبه من أفضل الدكاترة </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="service_block shadow_1 wow fadeInDown" wow-data-delay=".20ms">
                        <div class="icon">
                            <img src="assets\images\service\2.png" alt="icon">
                        </div>
                        <div class="text">
                            <h5 class="title"><a href="#">Desire</a></h5>
                            <p class="mb-0 ar_Font">تقديم الدعم الكامل للطالب بطرق أكثر تفاعليه وسهوله في الوصول الى المعلومات لتحقيق أكبر استفادة من العملية التعليمية .</p>
                        </div>
                    </div>
                </div>
            </div>
           
        </div>
    </section>
    <!-- Services End -->
    
        <!-- Counter Box Start -->
    <div class="">
        <div class="container">
            <div class="row">
                <!-- Box -->
                <div class="col-lg-3 col-md-6">
                    <div class="counter_box wow fadeInUp" data-wow-delay=".2s">
                        <div class="icon">
                            <img src="assets\images\icons\icon_6.png" alt="icon" class="image-fit">
                        </div>
                        <div class="text">
                            <div class="counter">
                                <b data-from="0" data-to="369">0</b>
                            </div>
                            <p class="mb-0">Course Content</p>
                        </div>
                    </div>
                </div>
                <!-- Box -->
                <!-- Box -->
                <div class="col-lg-3 col-md-6">
                    <div class="counter_box wow fadeInUp" data-wow-delay=".4s">
                        <div class="icon">
                            <img src="assets\images\icons\icon_7.png" alt="icon" class="image-fit">
                        </div>
                        <div class="text">
                            <div class="counter">
                                <b data-from="0" data-to="264">0</b>
                            </div>
                            <p class="mb-0">Edu Ideas</p>
                        </div>
                    </div>
                </div>
                <!-- Box -->
                <!-- Box -->
                <div class="col-lg-3 col-md-6">
                    <div class="counter_box wow fadeInUp" data-wow-delay=".6s">
                        <div class="icon">
                            <img src="assets\images\icons\icon_8.png" alt="icon" class="image-fit">
                        </div>
                        <div class="text">
                            <div class="counter">
                                <b data-from="0" data-to="259">0</b>
                            </div>
                            <p class="mb-0">Video Tutorials</p>
                        </div>
                    </div>
                </div>
                <!-- Box -->
                <!-- Box -->
                <div class="col-lg-3 col-md-6">
                    <div class="counter_box wow fadeInUp" data-wow-delay=".8s">
                        <div class="icon">
                            <img src="assets\images\icons\icon_9.png" alt="icon" class="image-fit">
                        </div>
                        <div class="text">
                            <div class="counter">
                                <b data-from="0" data-to="568">0</b>
                            </div>
                            <p class="mb-0">Top Student</p>
                        </div>
                    </div>
                </div>
                <!-- Box -->
            </div>
        </div>
    </div>
    <!-- Counter Box End -->
    
    
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
                <!-- Block End -->
                
                 <!-- Block Start -->
                <!--<div class="col-lg-4 col-md-6">-->
                <!--    <div class="team_block style_2 style_3 wow fadeInDown" data-wow-delay=".60ms">-->
                <!--        <div class="team_img">-->
                <!--            <img src="assets\images\marco.jpeg" alt="img" style="object-position: left;object-fit:cover;">-->
                            <!--<a href="team-details.html" class="thm-btn bg-thm-color-two thm-color-two-shadow btn-circle link">-->
                            <!--    <i class="fal fa-plus"></i>-->
                            <!--</a>-->
                <!--        </div>-->
                <!--        <h6 class="mb-1"><a href="team-details.html">Dr. Marcos A.hedra</a></h6>-->
                <!--        <p class="thm-color-two mb-0 font-weight-bold">Biology Teacher</p>-->
                <!--    </div>-->
                <!--</div>-->
                <!-- Block End -->
            
           
               
 
              
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
    
    <!-- Video Quote Start -->
    <!--<section id="vi2" class="mt-5 bg-thm-color-two-gradient z-1 video_quote pb-3">-->
    <!--    <img src="assets\images\elements\element_2.png" class="element_2" alt="Element">-->
    <!--    <div class="container-fluid p-0">-->
    <!--        <div class="row no-gutters align-items-center">-->
    <!--            <div class="col-lg-6">-->
    <!--                <div class="video_warp relative z-1 h-100 wow fadeInLeft">-->
    <!--                    <img src="assets\images\900.png" alt="img" class="image-fit">-->
    <!--                    <a href="https://www.youtube.com/watch?v=-6UM8N561dA" class="popup-youtube video_btn transform-center justify-content-center d-flex style_2">-->
    <!--                        <i class="fas fa-play video_icon bg-thm-color-three pulse-animated"></i>-->
    <!--                    </a>-->
    <!--                </div>-->
    <!--            </div>-->
    <!--            <div class="col-lg-6">-->
    <!--                <div class="arrows slideRight">-->
    <!--                    <div class="arrow"></div>-->
    <!--                    <div class="arrow"></div>-->
    <!--                    <div class="arrow"></div>-->
    <!--                    <div class="arrow"></div>-->
    <!--                    <div class="arrow"></div>-->
    <!--                </div>-->
    <!--                <img src="assets\images\elements\element_11.png" class="element_4 rotate_elem" alt="img">-->
    <!--                <div class="quote_sec about relative z-1">-->
    <!--                    <img src="assets\images\elements\element_17.png" class="element_5 rotate_elem" alt="img">-->
    <!--                    <div class="section-title left-align wow fadeInUp">-->
    <!--                        <p class="subtitle">-->
    <!--                            <i class="fal fa-book"></i>-->
    <!--                             E-Learning System-->
    <!--                        </p>-->
    <!--                        <h6 class="mb-0">DR. DAVIED SAIED : A physics teacher and supervisor in Cairo – Giza taught over 10,000 students with a great experience in the new system Tenchu and questions</h6>-->
    <!--                    </div>-->

    <!--                      <a href="register.php" class="thm-btn bg-thm-color-two thm-color-two-shadow btn-rounded mr-4 mb-4 wow fadeInRight" data-wow-delay=".50ms">New Student Register<i class="fal fa-chevron-right ml-2"></i></a>-->
                            
    <!--                </div>-->
    <!--            </div>-->
    <!--        </div>-->
    <!--    </div>-->
    <!--</section>-->
    <!-- Video Quote End -->
    
    
    
    
    
    
    
    
        
    <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
                   

      <div class="carousel-inner">
    <!--          <?php foreach($allvideos as $video){ ?>-->
    <!--    <div class="carousel-item active">-->
        
    <!--    <section id="vi2" class="mt-5 bg-thm-color-two-gradient z-1 video_quote pb-3">-->
    <!--    <img src="assets\images\elements\element_2.png" class="element_2" alt="Element">-->
    <!--    <div class="container-fluid p-0">-->
    <!--        <div class="row no-gutters align-items-center">-->
             
    <!--            <div class="col-lg-6">-->
    <!--                <div class="video_warp relative z-1 h-100">-->
    <!--                    <img src="assets\images\<?php echo $video['img']; ?>" alt="img" class="image-fit">-->
    <!--                    <a href="<?php echo $video['link']; ?>" class="popup-youtube video_btn transform-center justify-content-center d-flex style_2">-->
    <!--                        <i class="fas fa-play video_icon bg-thm-color-three pulse-animated"></i>-->
    <!--                    </a>-->
    <!--                </div>-->
    <!--            </div>-->
    <!--            <div class="col-lg-6">-->
    <!--                <div class="arrows slideRight">-->
    <!--                    <div class="arrow"></div>-->
    <!--                    <div class="arrow"></div>-->
    <!--                    <div class="arrow"></div>-->
    <!--                    <div class="arrow"></div>-->
    <!--                    <div class="arrow"></div>-->
    <!--                </div>-->
    <!--                <img src="assets\images\elements\element_11.png" class="element_4 rotate_elem" alt="img">-->
    <!--                <div class="quote_sec about relative z-1">-->
    <!--                    <img src="assets\images\elements\element_17.png" class="element_5 rotate_elem" alt="img">-->
    <!--                    <div class="section-title left-align">-->
    <!--                        <p class="subtitle">-->
    <!--                            <i class="fal fa-book"></i>-->
    <!--                             E-Learning System-->
    <!--                        </p>-->
    <!--                        <h6 class="mb-0"><?php echo $video['descryption	']; ?></h6>-->
    <!--                    </div>-->

    <!--                      <a href="register.php" class="thm-btn bg-thm-color-two thm-color-two-shadow btn-rounded mr-4 mb-4" >New Student Register<i class="fal fa-chevron-right ml-2"></i></a>-->
                            
    <!--                </div>-->
    <!--            </div>-->
    <!--        </div>-->
    <!--    </div>-->
       
    <!--</section>-->

    <!--    </div>-->
    <!--   <?php  }?>       -->
    
     <div class="carousel-item active">
        <section id="vi2" class="mt-5 bg-thm-color-two-gradient z-1 video_quote pb-3">
        <img src="assets\images\elements\element_2.png" class="element_2" alt="Element">
        <div class="container-fluid p-0">
            <div class="row no-gutters align-items-center">
                <div class="col-lg-6">
                    <div class="video_warp relative z-1 h-100">
                        <img src="assets\images\900.png" alt="img" class="image-fit">
                        <a href="https://www.youtube.com/watch?v=-6UM8N561dA" class="popup-youtube video_btn transform-center justify-content-center d-flex style_2">
                            <i class="fas fa-play video_icon bg-thm-color-three pulse-animated"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="arrows slideRight">
                        <div class="arrow"></div>
                        <div class="arrow"></div>
                        <div class="arrow"></div>
                        <div class="arrow"></div>
                        <div class="arrow"></div>
                    </div>
                    <img src="assets\images\elements\element_11.png" class="element_4 rotate_elem" alt="img">
                    <div class="quote_sec about relative z-1">
                        <img src="assets\images\elements\element_17.png" class="element_5 rotate_elem" alt="img">
                        <div class="section-title left-align">
                            <p class="subtitle">
                                <i class="fal fa-book"></i>
                                 E-Learning System
                            </p>
                            <h6 class="mb-0">Dr. David Saied : A physics teacher and supervisor in Cairo ? Giza taught over 10,000 students with a great experience in the new system Tenchu and questions</h6>
                        </div>

                          <a href="register.php" class="thm-btn bg-thm-color-two thm-color-two-shadow btn-rounded mr-4 mb-4" >New Student Register<i class="fal fa-chevron-right ml-2"></i></a>
                            
                    </div>
                </div>
            </div>
        </div>
    </section>
        </div>
      
        <div class="carousel-item">
        <section id="vi2" class="mt-5 bg-thm-color-two-gradient z-1 video_quote pb-3">
        <img src="assets\images\elements\element_2.png" class="element_2" alt="Element">
        <div class="container-fluid p-0">
            <div class="row no-gutters align-items-center">
                <div class="col-lg-6">
                    <div class="video_warp relative z-1 h-100">
                        <img src="assets\images\bio.jpg" alt="img" class="image-fit">
                        <a href="https://www.youtube.com/watch?v=puc1K0tHRKg" class="popup-youtube video_btn transform-center justify-content-center d-flex style_2">
                            <i class="fas fa-play video_icon bg-thm-color-three pulse-animated"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="arrows slideRight">
                        <div class="arrow"></div>
                        <div class="arrow"></div>
                        <div class="arrow"></div>
                        <div class="arrow"></div>
                        <div class="arrow"></div>
                    </div>
                    <img src="assets\images\elements\element_11.png" class="element_4 rotate_elem" alt="img">
                    <div class="quote_sec about relative z-1">
                        <img src="assets\images\elements\element_17.png" class="element_5 rotate_elem" alt="img">
                        <div class="section-title left-align">
                            <p class="subtitle">
                                <i class="fal fa-book"></i>
                                 E-Learning System
                            </p>
                            <h6 class="mb-0">Dr. Marcos A.hedra : شرح تفصيلي لمادة البايولوجى بصورة مبسطة لفهم المنهج ، مع كم كبير جدا من الاسئلة والتدريبات العمليه على كامل المنهج طبقا لاختبارات الوزارة</h6>
                        </div>

                          <a href="register.php" class="thm-btn bg-thm-color-two thm-color-two-shadow btn-rounded mr-4 mb-4" >New Student Register<i class="fal fa-chevron-right ml-2"></i></a>
                            
                    </div>
                </div>
            </div>
        </div>
    </section>
        </div>
        <div class="carousel-item">
        <section id="vi2" class="mt-5 bg-thm-color-two-gradient z-1 video_quote pb-3">
        <img src="assets\images\elements\element_2.png" class="element_2" alt="Element">
        <div class="container-fluid p-0">
            <div class="row no-gutters align-items-center">
                
                <div class="col-lg-6">
                    <div class="video_warp relative z-1 h-100">
                        <img src="assets\images\be.jpg" alt="img" class="image-fit">
                        <a href="https://www.youtube.com/watch?v=4r6smO24Coc" class="popup-youtube video_btn transform-center justify-content-center d-flex style_2">
                            <i class="fas fa-play video_icon bg-thm-color-three pulse-animated"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="arrows slideRight">
                        <div class="arrow"></div>
                        <div class="arrow"></div>
                        <div class="arrow"></div>
                        <div class="arrow"></div>
                        <div class="arrow"></div>
                    </div>
                    <img src="assets\images\elements\element_11.png" class="element_4 rotate_elem" alt="img">
                    <div class="quote_sec about relative z-1">
                        <img src="assets\images\elements\element_17.png" class="element_5 rotate_elem" alt="img">
                        <div class="section-title left-align">
                            <p class="subtitle">
                                <i class="fal fa-book"></i>
                                 E-Learning System
                            </p>
                            <h6 class="mb-0">Mr. Karim Tamam : 
                                    خبير مادة Maths لطلبة الثانوية العامة
                                    مادة علميه وتدريبات عمليه منظمة وشرح مبسط وامتحانات بدون تعقيد</h6>
                        </div>

                          <a href="register.php" class="thm-btn bg-thm-color-two thm-color-two-shadow btn-rounded mr-4 mb-4" >New Student Register<i class="fal fa-chevron-right ml-2"></i></a>
                            
                    </div>
                </div>
            </div>
        </div>
    </section>
        </div>
        
        
        
      </div>
     <button class="carousel-control-prev" type="button" data-target="#carouselExampleControls" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
      </button>
      <button class="carousel-control-next" type="button" data-target="#carouselExampleControls" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
      </button>
    </div>
    
   
    
   
    <!-- Video Start -->
    <!--<section class="section-padding  isotope-filter-items">-->
    <!--    <div class="container">-->
    <!--        <div class="row">-->
    <!--            <div class="col-12">-->
    <!--                <div class="section-title wow fadeInDown">-->
    <!--                    <p class="subtitle">-->
    <!--                        <i class="fal fa-book"></i>-->
    <!--                        Latest video gallery-->
    <!--                    </p>-->
    <!--                    <h3 class="title">We Provide Life-Changing Video Tutorials</h3>-->
    <!--                </div>-->
    <!--            </div>-->
    <!--        </div>-->
    <!--        <div class="row justify-content-center">-->
    <!--            <div class="col-lg-12">-->
    <!--                <div class="filter-btns mb-5">-->
    <!--                    <ul>-->
    <!--                        <li class="active filter-trigger" data-filter="*">-->
    <!--                            <a href="#">All</a>-->
    <!--                        </li>-->
    <!--                        <li class="filter-trigger" data-filter=".lifestyle">-->
    <!--                            <a href="#">Lifestyle</a>-->
    <!--                        </li>-->
    <!--                        <li class="filter-trigger" data-filter=".goals">-->
    <!--                            <a href="#">Goals</a>-->
    <!--                        </li>-->
    <!--                        <li class="filter-trigger" data-filter=".fashion">-->
    <!--                            <a href="#">Fashion</a>-->
    <!--                        </li>-->
    <!--                        <li class="filter-trigger" data-filter=".health">-->
    <!--                            <a href="#">Health</a>-->
    <!--                        </li>-->
    <!--                        <li class="filter-trigger" data-filter=".business">-->
    <!--                            <a href="#">Business</a>-->
    <!--                        </li>-->
    <!--                    </ul>-->
    <!--                </div>-->
    <!--            </div>-->
    <!--        </div>-->
    <!--        <div class="row filteritems">-->
                <!-- Box Start -->
    <!--            <div class="col-lg-4 col-md-6 masonry-item lifestyle fashion business">-->
    <!--                <div class="video_box wow fadeInDown" data-wow-delay=".1s">-->
    <!--                    <img src="assets\images\video\1.jpg" alt="img" class="image-fit">-->
    <!--                    <div class="video_badges">-->
    <!--                        <span class="tag_badge bg-thm-color-four">Free</span>-->
    <!--                    </div>-->
    <!--                    <a href="https://www.youtube.com/watch?v=TKnufs85hXk" class="popup-youtube video_btn">-->
    <!--                        <i class="fas fa-play"></i>-->
    <!--                    </a>-->
    <!--                    <div class="video_text">-->
    <!--                        <h6 class="text-white mb-0">How To Gain Knowledge</h6>-->
    <!--                        <p class="mb-0 thm-color-light">By Frank L. Pierce</p>-->
    <!--                    </div>-->
    <!--                </div>-->
    <!--            </div>-->
                <!-- Box End -->
                <!-- Box Start -->
    <!--            <div class="col-lg-4 col-md-6 masonry-item goals health lifestyle">-->
    <!--                <div class="video_box wow fadeInUp" data-wow-delay=".2s">-->
    <!--                    <img src="assets\images\video\2.jpg" alt="img" class="image-fit">-->
    <!--                    <div class="video_badges">-->
    <!--                        <span class="tag_badge bg-thm-color-four">$59</span>-->
    <!--                    </div>-->
    <!--                    <a href="https://www.youtube.com/watch?v=TKnufs85hXk" class="popup-youtube video_btn">-->
    <!--                        <i class="fas fa-play"></i>-->
    <!--                    </a>-->
    <!--                    <div class="video_text">-->
    <!--                        <h6 class="text-white mb-0">Bring About Maintenance Home</h6>-->
    <!--                        <p class="mb-0 thm-color-light">By Janine T. Hostetter</p>-->
    <!--                    </div>-->
    <!--                </div>-->
    <!--            </div>-->
                <!-- Box End -->
                <!-- Box Start -->
    <!--            <div class="col-lg-4 col-md-6 masonry-item fashion business goals">-->
    <!--                <div class="video_box wow fadeInDown" data-wow-delay=".3s">-->
    <!--                    <img src="assets\images\video\3.jpg" alt="img" class="image-fit">-->
    <!--                    <div class="video_badges">-->
    <!--                        <span class="tag_badge bg-thm-color-four">$59</span>-->
    <!--                    </div>-->
    <!--                    <a href="https://www.youtube.com/watch?v=TKnufs85hXk" class="popup-youtube video_btn">-->
    <!--                        <i class="fas fa-play"></i>-->
    <!--                    </a>-->
    <!--                    <div class="video_text">-->
    <!--                        <h6 class="text-white mb-0">How To Growth Your Business</h6>-->
    <!--                        <p class="mb-0 thm-color-light">By Joan J. Young</p>-->
    <!--                    </div>-->
    <!--                </div>-->
    <!--            </div>-->
                <!-- Box End -->
    <!--        </div>-->
            
    <!--    </div>-->
    <!--</section>-->
    
    <!-- Video End -->
    
    

    
    <!-- Coach Grid Start -->
    <section class="section-padding pt-5">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="section-title wow fadeInUp">
                        <p class="subtitle">
                            <i class="fal fa-book"></i>
                            E-Learning System Levels
                        </p>
                        <h3 class="title">We Provide All Secondary Levels</h3>
                    </div>
                </div>
            </div>
            <div class="row">
                
                <!-- Coach block -->
                <div class="col-xl-4 col-md-6">
                    <div class="coach_block wow fadeInDown" data-wow-delay=".2s">
                        <div class="coach_img">
                            <img src="assets\images\coach\1.jpg" alt="img" class="image-fit">
                            <!--<div class="coach_badges">-->
                            <!--    <span class="tag_badge bg-thm-color-four">Chemistry Squad</span>-->
                            <!--</div>-->
                            <a href="#" class="thm-btn thm-color-two bg-thm-color-white thm-color-two-shadow btn-circle link">
                                <i class="fal fa-chevron-right"></i>
                            </a>
                        </div>
                        <div class="coach_caption">
                            <div class="coach_meta">
                                <a href="" class="coach_cat thm-btn bg-thm-color-three thm-color-three-shadow btn-rounded">E-Learning System</a>
                               
                            </div>
                            <h5 class="title mb-xl-20">
                                <a href="#">
                                    First Secondary
                                </a>
                            </h5>
                            <div class="author">
                                <!--<img src="assets\images\author\1.jpg" alt="img" class="image-fit">-->
                                <!--<a href="courses.html" class="thm-color-one">Mr.Mohannad Elshokey</a>-->
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Coach block -->
                
                <!-- Coach block -->
                <div class="col-xl-4 col-md-6">
                    <div class="coach_block wow fadeInUp" data-wow-delay=".4s">
                        <div class="coach_img">
                            <img src="assets\images\coach\2.jpg" alt="img" class="image-fit">
                            <!--<div class="coach_badges">-->
                            <!--    <span class="tag_badge bg-thm-color-four">Chemistry Squad</span>-->
                            <!--</div>-->
                            <a href="#" class="thm-btn thm-color-two bg-thm-color-white thm-color-two-shadow btn-circle link">
                                <i class="fal fa-chevron-right"></i>
                            </a>
                        </div>
                        <div class="coach_caption">
                            <div class="coach_meta">
                                <a href="" class="coach_cat thm-btn bg-thm-color-three thm-color-three-shadow btn-rounded">E-Learning System</a>
                                
                            </div>
                            <h5 class="title mb-xl-20">
                                <a href="#">
                                    Second Secondary
                                </a>
                            </h5>
                            <div class="author">
                                <!--<img src="assets\images\author\2.jpg" alt="img" class="image-fit">-->
                                <!--<a href="courses.html" class="thm-color-one">Mr.Mohannad Elshokey</a>-->
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Coach block -->
                
                <!-- Coach block -->
                <div class="col-xl-4 col-md-6">
                    <div class="coach_block wow fadeInDown" data-wow-delay=".6s">
                        <div class="coach_img">
                            <img src="assets\images\coach\3.jpg" alt="img" class="image-fit">
                            <!--<div class="coach_badges">-->
                            <!--    <span class="tag_badge bg-thm-color-four">Chemistry Squad</span>-->
                            <!--</div>-->
                            <a href="#" class="thm-btn thm-color-two bg-thm-color-white thm-color-two-shadow btn-circle link">
                                <i class="fal fa-chevron-right"></i>
                            </a>
                        </div>
                        <div class="coach_caption">
                            <div class="coach_meta">
                                <a href="" class="coach_cat thm-btn bg-thm-color-three thm-color-three-shadow btn-rounded">E-Learning System</a>
                                
                            </div>
                            <h5 class="title mb-xl-20">
                                <a href="#">
                                   Third Secondary
                                </a>
                            </h5>
                            <div class="author">
                                <!--<img src="assets\images\author\3.jpg" alt="img" class="image-fit">-->
                                <!--<a href="courses.html" class="thm-color-one">Mr.Mohannad Elshokey</a>-->
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Coach block -->
            </div>
        </div>
    </section>
    <!-- Coach Grid End -->
    
    

  
  
  
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
    <script>
        $(".v2").click(function (){
            $("#v3").hide();$("#v4").hide();$("#v5").hide();$("#v6").hide();
            $("#v2").show();
        });
        
        $(".v3").click(function (){
            $("#v2").hide();$("#v4").hide();$("#v5").hide();$("#v6").hide();
            $("#v3").show();
        });
        
        $(".v4").click(function (){
            $("#v3").hide();$("#v2").hide();$("#v5").hide();$("#v6").hide();
            $("#v4").show();
        });
        
        $(".v5").click(function (){
            $("#v3").hide();$("#v2").hide();$("#v4").hide();$("#v6").hide();
            $("#v5").show();
        });
        
        $(".v6").click(function (){
            $("#v3").hide();$("#v2").hide();$("#v5").hide();$("#v4").hide();
            $("#v6").show();
        });
    </script>
</body>

</html>