<?php 

include "../connect.php";

function check($var){
    return htmlentities(trim(strip_tags(stripcslashes($var))));
  }
$examid=check($_POST['examid']);
$studentid=check($_POST['studentid']);
$qid=check($_POST['qid']);
$answer=check($_POST['answer']);
$type=check($_POST['type']);

$stmt=$con->prepare("SELECT * FROM examsanswer WHERE examid=? AND studentid=? AND questionid=? AND type = ?");
$stmt->execute(array($examid,$studentid,$qid,$type));
$oldan=$stmt->fetch();

    $stmt=$con->prepare("SELECT answer FROM questions WHERE id =?");
        $stmt->execute(array($qid));
        $question=$stmt->fetch();
        
        if($question['answer'] == $answer){
            $degree = 1;
    
        }else{
            $degree = 0;
        }
        
if(!empty($oldan)){
     $stmt=$con->prepare("UPDATE examsanswer SET answer = ? , degree = ? WHERE id = ? ");
     $stmt->execute(array($answer , $degree,$oldan['id']));
}else{
     $stmt=$con->prepare("INSERT INTO examsanswer (studentid,questionid,type,examid,answer,sent,degree) VALUES (:zstudentid,:zquestionid,:ztype,:zexamid,:zanswer,:zsent,:zdegree) ");
     $stmt->execute(array("zstudentid"=>$studentid,"zquestionid"=>$qid,"ztype"=>$type,"zexamid"=>$examid,"zanswer"=>$answer,"zsent"=>"ajax",'zdegree'=>$degree));
}

    $stmt=$con->prepare("SELECT * FROM examsanswer WHERE studentid =?  AND examid = ? AND degree = ? AND type = ? ");
    $stmt->execute(array($studentid,$examid,"1",$type));
    $total=$stmt->rowCount();

    $total = $total * 1;

    $stmt=$con->prepare("UPDATE studentexam  SET result = ? WHERE studentid = ? AND examid = ?  ");
    $stmt->execute(array($total , $studentid,$examid));
    

?>