<?php 
include_once "connect.php";

function check($var){
    return htmlentities(trim(strip_tags(stripcslashes($var))));
  }

if(isset($_POST['videoid']))
{
    $videoid=check($_POST['videoid']);
    $studentid=check($_POST['studentid']);
    $videotime=check($_POST['videotime']);
    $error=array();

    if(empty($error)){
        
         $stmt=$con->prepare("SELECT * FROM videocount WHERE videoid = ? AND studentid = ? AND (timer IS NULL OR timer <= ?)");
        $stmt->execute(array($videoid,$studentid,$videotime));
        $time=$stmt->fetch();
        
         if($time['timer'] == NULL){
        
       $stmt=$con->prepare("UPDATE videocount SET timer = ? WHERE  videoid = ? AND studentid = ? AND (timer IS NULL OR timer <= ?)");
       $stmt->execute(array(1,$videoid,$studentid,$videotime));
       
         }else{
        $t=$time['timer'] + 1;
        $stmt=$con->prepare("UPDATE videocount SET timer = ?  WHERE  videoid = ? AND studentid = ? AND (timer IS NULL OR timer <= ?)");
         $stmt->execute(array( $t,$videoid,$studentid,$videotime));
         
    }
    }
}

?>