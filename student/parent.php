<?php
include_once 'connect.php';
// server should keep session data for AT LEAST 1 hour
ini_set('session.gc_maxlifetime', 21600);

// each client should remember their session id for EXACTLY 1 hour
session_set_cookie_params(21600);

if (isset($_SESSION['name'])) {
    header("Location:Chapters.php");
}

?>
<!doctype html>
<html class="no-js" lang="">

<head>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-145403987-2"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'UA-145403987-2');
    </script>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>E-Learning System | Login Onxam remon </title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="img\favicon.png">
    <!-- Normalize CSS -->
    <link rel="stylesheet" href="css\normalize.css">
    <link rel="stylesheet" href="">
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
    <!-- Data Table CSS -->
    <link rel="stylesheet" href="css\jquery.dataTables.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="css/style.css?v=0.1">
    <link rel="stylesheet" href="css/media.css">
    <link href="https://fonts.googleapis.com/css?family=Cairo&display=swap" rel="stylesheet">


    <!-- Modernize js -->
    <script src="js\modernizr-3.6.0.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>

    <!-- Preloader Start Here -->
    <div id="preloader"></div>
    <!-- Preloader End Here -->
    <?php if (isset($done) && !empty($done)) echo $done; ?>

    <section class="login">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div id="wrapper" class="wrapper bg-ash">
                        <!-- Page Area Start Here -->
                        <div class="dashboard-page-one">

                            <div class="dashboard-content-one">

                                <!-- Breadcubs Area End Here -->
                                <!-- All Subjects Area Start Here -->
                                <div class="row">
                                    <div class="col-3-xxxl col-3"></div>
                                    <div class="col-6-xxxl col-12">
                                
                                        <div class="alphalogo">
                                            <img src="img/logo.png">
                                        </div>
                                        <div class="card height-auto">
                                            <div class="card-body">
        <?php
  if(isset($_GET['msg'])){
                    if(filter_var($_GET['msg'],FILTER_SANITIZE_STRING) ){
                        echo '<p class="alert alert-danger" style="text-align: right;"><button type="button" class="close" data-dismiss="alert" aria-label="Close" style="float: left !important;"> <span aria-hidden="true">&times;</span></button>' . $_GET['msg'] . '<button type="button" class="close" data-dismiss="alert" aria-label="Close" style="float: left !important;"><span aria-hidden="true">&times;</span></button></p>';
                    }
                  } 
                  ?>
                                                <div class="heading-layout1">
                                                    <div class="item-title">
                                                        <h3>Login as a parent</h3>
                                                    </div>

                                                </div>
                                                <form class="new-added-form" method="post" action="stdinfo.php">
                                                    <div class="col-12-xxxl col-lg-6 col-12 form-group">
                                                        <label> Parent Phone * </label>
                                                        <input type="text" placeholder=" Enter Parent Phone " class="form-control" name="parentphone" value="<?php if (!empty($err2)) {
                                                                                                                                                                    echo $parentphone;
                                                                                                                                                                } ?>" required>
                                                    </div>
                                                    <div class="col-12 form-group mg-t-8">


                                                        <input type="submit" class="btn-fill-lg bg-blue-dark btn-hover-yellow" name="submit" value="submit">
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <!-- All Subjects Area End Here -->

                            </div>
                        </div>
                        <!-- Page Area End Here -->
                    </div>
                </div>



            </div>


        </div>

        <!-- start footer -->
        <?php include_once "footer/footer.php"; ?>
        <!-- end footer -->

    </section>

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
    <!-- Data Table Js -->
    <script src="js\jquery.dataTables.min.js"></script>
    <!-- Custom Js -->
    <script src="js\main.js"></script>

</body>

</html>