<?php 
include '../connect.php';
if(isset($_POST['id'])&&isset($_POST['u']))
{
    $u=$_POST['u'];
    $id=$_POST['id'];
    //  echo $u;

}

$stmt=$con->prepare("SELECT * FROM student_wallet WHERE studentid = ? AND product_id=?");
    $stmt->execute(array($std,$id));
    $productid=$stmt->fetch();
    $result=$stmt->rowCount();
    // print_r($result);
   
    if($stmt->rowCount()==0){
    echo "confirm";
    }
    else
    {
  echo "confirm2";
    }
