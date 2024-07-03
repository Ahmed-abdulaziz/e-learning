<?php
$name = $_SESSION['adminname'];
$stmt = $con->prepare("SELECT type FROM admins WHERE name=?");
$stmt->execute(array($name));
$result = $stmt->fetch();
$type = $result['type'];

$stmt = $con->prepare("SELECT id FROM livevideos ");
$stmt->execute();
$videos = $stmt->fetch();


$stmt = $con->prepare("SELECT * FROM educationallevels ");
$stmt->execute();
$educationallevels = $stmt->fetchAll();


if (isset($_GET['do'])) {
    $do = $_GET['do'];

    if ($do == 'delete') {
        if ($_GET['id']) {
            $id = $_GET['id'];
        }
        $stmt = $con->prepare("DELETE  FROM livevideos WHERE id = :zid");
        $stmt->bindParam(":zid", $id);
        $stmt->execute();
        //      header("Location:livenew.php?msg=".urlencode("Done Deleteing Live Video"));

    }
}

//   echo $type;
//   die();


?>
<div class="height">
    <div class="sidebar-main sidebar-menu-one sidebar-expand-md sidebar-color">
        <div class="mobile-sidebar-header d-md-none">
            <div class="header-logo">
                <a href="index2.php"><img src="img\logo.png" alt="logo"></a>
            </div>
        </div>
        <div class="sidebar-menu-content">
            <ul class="nav nav-sidebar-menu sidebar-toggle-view">
                <li class="nav-item">
                    <a href="index3.php" class="nav-link"><i class="flaticon-multiple-users-silhouette"></i><span>Choose Subject</span></a>
                </li>
                <?php if (isset($_SESSION['subject'])) { ?>
                    <li class="nav-item">
                        <a href="statistics.php" class="nav-link"><i class="flaticon-dashboard"></i><span>Statistics</span></a>
                    </li>
                <?php } ?>

                <?php if ($type == "super admin" || $type = "assistant") { ?>
                    <li class="nav-item">
                        <a href="allstudents2.php" class="nav-link"><i class="flaticon-dashboard"></i><span>Students</span></a>
                    </li>
                    <!--<li class="nav-item sidebar-nav-item">-->
                    <!--    <a href="#" class="nav-link"><i class="flaticon-multiple-users-silhouette"></i><span>Students Excel</span></a>-->
                    <!--    <ul class="nav sub-group-menu">-->
                    <!--        <?php foreach ($educationallevels as $educationallevel) { ?>-->
                    <!--            <li class="nav-item">-->
                    <!--                <a href="allstudents.php?level=<?= $educationallevel['id'] ?>" class="nav-link"><span><?= $educationallevel['name'] ?></span></a>-->
                    <!--            </li>-->
                    <!--        <?php } ?>-->
                    <!--    </ul>-->
                    <!--</li>-->
                <?php } ?>

                <?php if ($type == "teacher") { ?>
                    <?php if (isset($_SESSION['subject'])) { ?>
                        <li class="nav-item">
                            <a href="allstudents2.php" class="nav-link"><i class="flaticon-dashboard"></i><span>Students</span></a>
                        </li>
                        <li class="nav-item sidebar-nav-item">
                            <a href="#" class="nav-link"><i class="flaticon-multiple-users-silhouette"></i><span>Students Excel</span></a>
                            <ul class="nav sub-group-menu">
                                <?php foreach ($educationallevels as $educationallevel) { ?>
                                    <li class="nav-item">
                                        <a href="allstudents.php?level=<?= $educationallevel['id'] ?>" class="nav-link"><span><?= $educationallevel['name'] ?></span></a>
                                    </li>
                                <?php } ?>
                            </ul>
                        </li>
                <?php }
                } ?>


                <?php if ($_SESSION['type'] == "super admin") { ?>

                    <li class="nav-item">
                        <a href="index2.php" class="nav-link"><i class="flaticon-multiple-users-silhouette"></i><span>Admins</span></a>
                    </li>

                    <li class="nav-item">
                        <a href="teachers.php" class="nav-link"><i class="flaticon-couple"></i><span>Doctors</span></a>
                    </li>
                    <li class="nav-item">
                        <a href="subjects.php" class="nav-link"><i class="fas fa-align-left"></i><span>Courses</span></a>
                    </li>

                    <!--<li class="nav-item">-->
                    <!--    <a href="classes.php" class="nav-link"><i class="flaticon-multiple-users-silhouette"></i><span>Classes</span></a>-->
                    <!--</li>-->

                    <!--<li class="nav-item sidebar-nav-item">-->
                    <!--    <a href="#" class="nav-link"><i class="fa fa-play-circle" aria-hidden="true"></i><span>Codes</span></a>-->
                    <!--    <ul class="nav sub-group-menu">-->

                    <!--        <li class="nav-item">-->
                    <!--            <a href="generatecode.php" class="nav-link"><span>Generat codes</span></a>-->
                    <!--        </li>-->
                            <!--    <li class="nav-item">-->
                            <!--    <a href="groups.php" class="nav-link" ><span>Groups</span></a>-->
                            <!--</li>-->
                    <!--        <li class="nav-item">-->
                    <!--            <a href="codes-category.php" class="nav-link"><span>Categories</span></a>-->
                    <!--        </li>-->
                    <!--        <li class="nav-item">-->
                    <!--            <a href="codes-used.php" class="nav-link"><span>Used Codes</span></a>-->
                    <!--        </li>-->

                    <!--        <li class="nav-item">-->
                    <!--            <a href="codes_show.php" class="nav-link"><span>Show All Codes</span></a>-->
                    <!--        </li>-->

                    <!--    </ul>-->
                    <!--</li>-->
                <?php } ?>

                <!--<?php if ($_SESSION['type'] == "super admin" || $_SESSION['type'] == 'assistant') { ?>-->
                <!--    <li class="nav-item sidebar-nav-item">-->
                <!--        <a href="#" class="nav-link"><i class="fa fa-play-circle" aria-hidden="true"></i><span>Website</span></a>-->
                <!--        <ul class="nav sub-group-menu">-->

                <!--            <li class="nav-item">-->
                <!--                <a href="website_showteacher.php" class="nav-link"><span>Teacher</span></a>-->
                <!--            </li>-->
                            <!--    <li class="nav-item">-->
                            <!--    <a href="groups.php" class="nav-link" ><span>Groups</span></a>-->
                            <!--</li>-->
                <!--            <li class="nav-item">-->
                <!--                <a href="website_showvideo.php" class="nav-link"><span>Videos</span></a>-->
                <!--            </li>-->
                <!--            <li class="nav-item">-->
                <!--                <a href="website_showsubject.php" class="nav-link"><span>Subjects</span></a>-->
                <!--            </li>-->
                <!--        </ul>-->
                <!--    </li>-->
                <!--<?php } ?>-->

                <!--<li class="nav-item">-->
                <!--    <a href="generatecode.php" class="nav-link"><i class="flaticon-multiple-users-silhouette"></i><span>codes</span></a>-->
                <!--</li>-->

                <!--  <li class="nav-item">-->
                <!--    <a href="subjects.php" class="nav-link"><i class="fa fa-play-circle" aria-hidden="true"></i><span>Subjects</span></a>-->
                <!--</li> -->
                <!--<?php // }elseif($type=="teacher"){ 
                    ?>-->
                <!-- <li class="nav-item">
                            <a href="ass.php" class="nav-link"><i class="flaticon-couple"></i><span>Assistants</span></a>
                    </li> -->
                <!--<?php // }
                    ?>-->



                <?php if (isset($_SESSION['subject'])) { ?>
                    <li class="nav-item">
                        <a href="chapters.php" class="nav-link"><i class="fab fa-accusoft"></i><span>Chapters</span></a>
                    </li>
                    <li class="nav-item">
                        <a href="lessons.php" class="nav-link"><i class="fas fa-align-left"></i><span>Lessons</span></a>
                    </li>

                    <!--<li class="nav-item sidebar-nav-item">-->
                    <!--    <a href="#" class="nav-link"><i class="fa fa-play-circle" aria-hidden="true"></i><span>Live Video</span></a>-->
                    <!--    <ul class="nav sub-group-menu" >-->
                    <!--        <li class="nav-item">-->
                    <!--            <a href="livenew.php" class="nav-link" ><span>New Live</span></a>-->
                    <!--        </li>-->
                    <!--        <li class="nav-item">-->
                    <!--            <a href="livenew.php?do=delete" class="nav-link"><span>End Live</span></a>-->
                    <!--        </li>-->
                    <!--    </ul>-->
                    <!--</li>-->

                    <li class="nav-item">
                        <a href="week_chapter.php" class="nav-link"><i class="fas fa-box-open"></i><span>Week Chapters</span></a>
                    </li>
                    <li class="nav-item">
                        <a href="weeks.php" class="nav-link"><i class="far fa-calendar"></i><span>Weeks</span></a>
                    </li>

                    <li class="nav-item">
                        <a href="videos.php" class="nav-link"><i class="fa fa-play-circle"></i><span>Videos</span></a>
                    </li>



                    <li class="nav-item">
                        <a href="files.php" class="nav-link"><i class="fa fa-file-pdf"></i><span>Material</span></a>
                    </li>
                    <!-- <li class="nav-item">-->
                    <!--    <a href="sheets.php" class="nav-link"><i class="fa fa-file-pdf"></i><span>Pocklets</span></a>-->
                    <!--</li>-->

                    <li class="nav-item">
                        <a href="homeworks.php" class="nav-link"><i class="flaticon-maths-class-materials-cross-of-a-pencil-and-a-ruler text-magenta"></i><span>Assignments</span></a>
                    </li>
                    <!--<li class="nav-item">-->
                    <!--    <a href="exams.php" class="nav-link"><i class="flaticon-shopping-list"></i><span>Exams</span></a>-->
                    <!--</li>-->
                    <li class="nav-item">
                        <a href="exams.php" class="nav-link"><i class="flaticon-shopping-list"></i><span>Quizes</span></a>
                    </li>
                    <!--  <li class="nav-item">-->
                    <!--    <a href="new_homeworks.php" class="nav-link"><i class="flaticon-shopping-list"></i><span>Bubble Sheet</span></a>-->
                    <!--</li>-->
                    <!-- <li class="nav-item">-->
                    <!--    <a href="quizes.php" class="nav-link"><i class="flaticon-shopping-list"></i><span>Monthly Exams</span></a>-->
                    <!--</li>-->
                    <li class="nav-item">
                        <a href="multichoice.php" class="nav-link"><i class="flaticon-checklist"></i><span>MultiChoice Questions</span></a>
                    </li>
                    <li class="nav-item">
                        <a href="essay.php" class="nav-link"><i class="flaticon-open-book"></i><span>Essay Questions</span></a>
                    </li>
                    <!-- <li class="nav-item">-->
                    <!--    <a href="givens.php" class="nav-link"><i class="fab fa-audible"></i><span>Given Questions</span></a>-->
                    <!--</li>-->



                    <!--<li class="nav-item sidebar-nav-item">-->
                    <!--    <a href="#" class="nav-link"><i class="flaticon-classmates" aria-hidden="true"></i><span>Students</span></a>-->
                    <!--    <ul class="nav sub-group-menu" >-->
                    <!--         <li class="nav-item ">-->
                    <!--            <a href="students.php" class="nav-link"><span>Pending Student</span></a>-->
                    <!--        </li>-->
                    <!--        <li class="nav-item">-->
                    <!--            <a href="stuaprov.php" class="nav-link"><span>Approved Student</span></a>-->
                    <!--        </li>-->
                    <!--        <li class="nav-item">-->
                    <!--            <a href="studisaprov.php" class="nav-link"><span>Disapproved Student</span></a>-->
                    <!--        </li>-->
                    <!--    </ul>-->
                    <!--</li>-->


                <?php } ?>



            </ul>
        </div>
    </div>
</div>