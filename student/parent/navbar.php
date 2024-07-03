<?php 
include_once 'connect.php';

if(isset($_SESSION['name'])) $namee=$_SESSION['name'];
$stmt=$con->prepare("SELECT * FROM students WHERE email=?");
$stmt->execute(array($namee));
$levels=$stmt->fetch();
$l=$levels['eductionallevel'];

$stmt=$con->prepare("SELECT name FROM educationallevels WHERE id = ?");
$stmt->execute(array($l));
$result=$stmt->fetch();
$studentlevel=$result['name'];

$stmt=$con->prepare("SELECT sum(money) as totalwallet FROM student_wallet WHERE studentid = ?");
$stmt->execute(array($levels['id']));
$total=$stmt->fetch();
?>

<div class="navbar navbar-expand-md header-menu-one bg-light">
            <div class="nav-bar-header-one">
                <div class="header-logo">
                    <a href="Chapters.php" target="_blank">
                        <img src="..\img\logo.png" alt="logo">
                    </a>
                </div>
                <div class="toggle-button sidebar-toggle">
                    <button type="button" class="item-link">
                        <span class="btn-icon-wrap">
                            <span></span>
                            <span></span>
                            <span></span>
                        </span>
                    </button>
                </div>
            </div>
            <div class="d-md-none mobile-nav-bar">
                <button class="navbar-toggler pulse-animation" type="button" data-toggle="collapse" data-target="#mobile-navbar" aria-expanded="false">
                    <i class="far fa-arrow-alt-circle-down"></i>
                </button>
                <button type="button" class="navbar-toggler sidebar-toggle-mobile">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
            <div class="header-main-menu collapse navbar-collapse" id="mobile-navbar">
                <ul class="navbar-nav">
                </ul>

                <ul class="navbar-nav">
                     <li style="
    border-right: 1px solid #333;
    margin-right: 10px;
    padding-right: 15px;
    font-size: 18px;
">
                        <i  style="font-size: 24px;margin-right: 5px;" class="fas fa-wallet"></i>
                        <span><?php echo $total['totalwallet']; ?> EGP</span>
                       
                    </li>
                    <li class="navbar-item dropdown header-admin">
                        <a class="navbar-nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false">
                          <div class="admin-img">
                                <img src="<?php 
                                if(!empty($levels['image'])){ echo "img/profile/".$levels['image'];} 
                                else {
                                    if($levels['gender']=="male"){ echo "../img/figure/student1.png";}
                                    elseif($levels['gender']=="female"){ echo "../img/figure/student.png";}
                                    else echo "../img/figure/admin.jpg";
                                }
                                
                                ?>" alt="Admin">
                            </div>
                            
                            <div class="admin-title">
                                <?php if (isset($_SESSION['name'])){ ?>
                                <h5 class="item-title"><?php echo $levels['name'];?></h5>
                                <?php };?>
                                <span><?php 
                                echo $studentlevel ; 
                                ?></span>   
                                <span><?php 
                                echo $levels['code'] ; 
                                ?></span>   
                            </div>
                            

                            
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">

                           
                            <div class="item-content">
                                <ul class="settings-list">
                                    <li><a href="logout.php"><i class="flaticon-turn-off"></i>Log Out</a></li> 
                                </ul>
                            </div>
                        </div>
                    </li>
                   
                </ul>
            </div>
        </div>
