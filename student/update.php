<?php
include_once 'connect.php';
session_start();
function check($var)
{
    return trim(strip_tags(stripcslashes($var)));
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    if (isset($_POST['register'])) {

        $gender = check($_POST['gender']);

        $imageName = $_FILES['image']['name'];
        if (!empty($imageName)) {

            $imageSize = $_FILES['image']['size'];
            $imageTmp  = $_FILES['image']['tmp_name'];
            $imageType = $_FILES['image']['type'];
            $exp = explode('.', $imageName);
            $imageExtension = strtolower(end($exp));
            $Mimage = rand(0, 100000) . '_' . $imageName;
            move_uploaded_file($imageTmp, "img/profile//" . $Mimage);
        }




        $err = array();
        if (empty($gender)) {
            $err['gender'] = "Gender can't be empty";
        }

        if (empty($err)) {

            if (!empty($imageName)) {
                $stmt = $con->prepare("UPDATE students SET gender = ?, image = ? WHERE id = ?");
                $stmt->execute(array($gender, $Mimage, $id));
            } else {
                $stmt = $con->prepare("UPDATE students SET gender = ? WHERE id = ?");
                $stmt->execute(array($gender, $id));
            }
            header('location:index3.php');
        }
    }
}
?>
<!doctype html>
<html class="no-js" lang="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>E-Learning System | Register </title>
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
    <!-- Data Table CSS -->
    <link rel="stylesheet" href="css\jquery.dataTables.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="css/style.css">
    <!-- Modernize js -->
    <script src="js\modernizr-3.6.0.min.js"></script>
</head>

<body>
    <!-- Preloader Start Here -->
    <div id="preloader"></div>
    <!-- Preloader End Here -->
    <div id="wrapper" class="wrapper bg-ash">

        <!-- Page Area Start Here -->
        <div class="dashboard-page-one">

            <div class="dashboard-content-one">

                <!-- Breadcubs Area End Here -->
                <!-- All Subjects Area Start Here -->
                <div class="row">
                    <div class="col-3-xxxl col-12"></div>
                    <div class="col-6-xxxl col-12">
                        <div class="alphalogo">
                            <img src="img/logo.png">
                        </div>
                        <div class="card height-auto">
                            <div class="card-body">
                                <div class="heading-layout1">
                                    <div class="item-title">
                                        <h3>PLease Fill This information First</h3>
                                    </div>

                                </div>
                                <form class="new-added-form" method="post" enctype="multipart/form-data">
                                    <?php if (isset($err) && !empty($err)) { ?>
                                        <div class="alert alert-danger errors alert-dismissible fade show" role="alert">
                                            <?php foreach ($err as $e) { ?>
                                                <li><?php echo $e; ?></li>
                                            <?php } ?>
                                        </div>
                                    <?php } ?>
                                    <div class="row">

                                        <div class="col-12-xxxl col-lg-6 col-12 form-group gender">
                                            <h3>Gender *</h3>
                                            <label class="container">Male
                                                <input type="radio" name="gender" value="male" required>
                                                <span class="checkmark"></span>
                                            </label>
                                            <label class="container">Female
                                                <input type="radio" name="gender" value="male" required>
                                                <span class="checkmark"></span>
                                            </label>
                                        </div>

                                        <div class="col-12-xxxl col-lg-6 col-12 form-group image">
                                            <label>Profile Picture</label>
                                            <input type="file" name="image" class="form-control">
                                        </div>


                                        <div class="col-12 form-group mg-t-8">
                                            <input type="submit" class="btn-fill-lg btn-gradient-yellow btn-hover-bluedark" name="register" value="Update and Continue">
                                        </div>
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

    <!-- start footer -->
    <?php include_once "footer.php"; ?>
    <!-- end footer -->

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