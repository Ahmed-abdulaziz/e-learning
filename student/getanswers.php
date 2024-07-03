<?php 

include "connect.php";

$examid=$_POST['id'];
$studentid=$_POST['studentid'];

$stmt=$con->prepare("SELECT * FROM studentexam WHERE studentid = ? AND examid = ?");
$stmt->execute(array("zstudentid"=>$studentid,"zexamid"=>$examid));
if($stmt->rowCount()==0){
    $stmt=$con->prepare("INSERT INTO studentexam (studentid,examid) VALUES (:zstudentid,:zexamid) ");
    $stmt->execute(array("zstudentid"=>$studentid,"zexamid"=>$examid));
}




if(!empty($_POST['essay']) && !empty($_POST['essayids'])){
$essayanswers=$_POST['essay'];
$essayids=$_POST['essayids'];
foreach (array_combine($essayids, $essayanswers) as $essayid => $essayanswer) {
    
    $stmt=$con->prepare("SELECT * FROM examsanswer WHERE studentid = ? AND examid = ? AND questionid = ? AND type=?");
    $stmt->execute(array($studentid,$examid,$essayid,"1"));
    
    if($stmt->rowCount()==0){
        $stmt=$con->prepare("INSERT INTO examsanswer (studentid,questionid,type,examid,answer) VALUES (:zstudentid,:zquestionid,:ztype,:zexamid,:zanswer) ");
        $stmt->execute(array("zstudentid"=>$studentid,"zquestionid"=>$essayid,"ztype"=>"1","zexamid"=>$examid,"zanswer"=>$essayanswer));
    }

  }
}

if(!empty($_POST['radio']) && !empty($_POST['multichoiceids'])){
$multichoiceanswers=$_POST['radio'];
$multichoiceids=$_POST['multichoiceids'];
  foreach (array_combine($multichoiceids, $multichoiceanswers) as $multichoiceid => $multichoiceanswer) {
      
    $stmt=$con->prepare("SELECT * FROM examsanswer WHERE studentid = ? AND examid = ? AND questionid = ? AND type=?");
    $stmt->execute(array($studentid,$examid,$multichoiceid,"0"));
      
    if($stmt->rowCount()==0){
        $stmt=$con->prepare("INSERT INTO examsanswer (studentid,questionid,type,examid,answer) VALUES (:zstudentid,:zquestionid,:ztype,:zexamid,:zanswer) ");
        $stmt->execute(array("zstudentid"=>$studentid,"zquestionid"=>$multichoiceid,"ztype"=>"0","zexamid"=>$examid,"zanswer"=>$multichoiceanswer));
    }

  }
}




?>