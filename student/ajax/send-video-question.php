<?php 

include "../connect.php";

function check($var){
    return htmlentities(trim(strip_tags(stripcslashes($var))));
  }
  
  if(isset($_POST['videoid'])){
      
        $studentid=check($_POST['studentid']);
        $videoid=check($_POST['videoid']);
        $question=check($_POST['question']);
        
        $stmt=$con->prepare("INSERT INTO videoquestions (studentid,videoid,question) VALUES (:studentid,:videoid,:question) ");
        $stmt->execute(array("studentid"=>$studentid,"videoid"=>$videoid,"question"=>$question));
  }

 ?>