<?php
ob_start();
ini_set('session.cookie_lifetime', 60 * 60 * 24);
ini_set('session.gc_maxlifetime', 60 * 60 * 24);
session_start();
include_once 'connect.php';
if (!isset($_SESSION['adminname'])) {
    header("Location:index.php?msg=" . urlencode("Please Login First"));
    exit();
}
function check($var)
{
    return trim(strip_tags(stripcslashes($var)));
}

if (isset($_POST['add'])) {
    $name = check($_POST['name']);
    $email = check($_POST['email']);

    $password = check($_POST['password']);

    $error = array();
    $error2 = array();
    if (isset($_GET['edulevel'])) $edulevel = $_GET['edulevel'];
    else $edulevel = $_POST['edulevel'];
    $stmt = $con->prepare("SELECT * FROM students WHERE email=? ");
    $stmt->execute(array($email));
    if ($stmt->rowCount() > 0) {
        $error['email'] = "email Used Before";
    }

    if (empty($error)) {
        $stmt = $con->prepare("INSERT INTO students (name,email,password,eductionallevel)
         VALUES(:name,:email,:password,:eductionallevel)");
        $stmt->execute(array('name' => $name, 'email' => $email, 'password' => $password, 'eductionallevel' => $edulevel));
        header("Location:students.php?edulevel=" . $edulevel . "&msgg=" . urlencode("Done Adding Student"));
        exit();
    }
}
?>
<!doctype html>
<html class="no-js" lang="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>E-Learning System | Add Student</title>
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
    <link rel="stylesheet" href="style.css">
    <!-- Modernize js -->
    <script src="js\modernizr-3.6.0.min.js"></script>
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
                    <h3>Add Student</h3>
                    <ul>
                        <li>
                            <a href="students.php">All Students</a>
                        </li>
                        <li>Add Student</li>
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
                                        <h3>Add New Student</h3>
                                    </div>

                                </div>

                                <form class="new-added-form" method="post">
                                    <?php if (isset($error) && !empty($error)) { ?>
                                        <div class="alert alert-danger errors alert-dismissible fade show" role="alert">
                                            <?php foreach ($error as $e) { ?>
                                                <li><?php echo $e ?></li>
                                            <?php } ?>
                                        </div>
                                    <?php } ?>
                                    <div class="row">
                                        <div class="col-xl-3 col-lg-6 col-12 form-group">
                                            <label>Name *</label>
                                            <input type="text" placeholder="" class="form-control" name="name" required>
                                        </div>
                                        <div class="col-xl-3 col-lg-6 col-12 form-group">
                                            <label>Email/Phone *</label>
                                            <input type="text" placeholder="" class="form-control" name="email" required>
                                        </div>

                                        <div class="col-xl-3 col-lg-6 col-12 form-group">
                                            <label>Password *</label>
                                            <input type="password" placeholder="" class="form-control" name="password" required>
                                        </div>

                                        <?php if (!isset($_GET['edulevel'])) { ?>
                                            <div class="col-12-xxxl col-lg-6 col-12 form-group">
                                                <label>Eductional Level</label>
                                                <select class="select2" name="edulevel" required>
                                                    <!-- <option >Please Select</option> -->
                                                    <option value="1">1 Primary</option>
                                                    <option value="2">2 Primary</option>
                                                    <option value="3">3 Primary</option>
                                                    <option value="4">4 Primary</option>
                                                    <option value="5">5 Primary</option>
                                                    <option value="6">6 Primary</option>

                                                    <option value="7">1 Preparatory</option>
                                                    <option value="8">2 Preparatory</option>
                                                    <option value="9">3 Preparatory</option>
                                                    <option value="10">1 secondary</option>
                                                    <option value="11">2 secondary</option>
                                                    <option value="12">3 secondary</option>
                                                </select>
                                            </div>
                                        <?php } ?>


                                        <div class="col-12 form-group mg-t-8">

                                            <button type="submit" class="btn-fill-lg bg-blue-dark btn-hover-yellow" name="add">Add</button>
                                        </div>
                                    </div>
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
    <?php include_once "footer.php"; ?>
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

</body>

</html>
<?php ob_end_flush(); ?>