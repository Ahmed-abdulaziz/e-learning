<?php 
include_once "../connect.php";
function check($var){
    return htmlentities(trim(strip_tags(stripcslashes($var))));
  }
  
if(isset($_POST['auth']) && $_POST['auth']=="savehw"){
    $studentid = check($_POST['studentid']);
    $degree = check($_POST['degree']);
    $quizid = check($_POST['quizid']);

$stmt=$con->prepare("SELECT * FROM quizstudent WHERE studentid=? AND quizid = ?");
$stmt->execute(array($studentid, $quizid));
$result=$stmt->fetchAll();
$check = $stmt->rowCount();

if($check > 0){

  $stmt=$con->prepare("UPDATE quizstudent SET degree=? WHERE studentid=? AND quizid = ?");
  $stmt->execute(array($degree,$studentid, $quizid));

}else{

  $stmt=$con->prepare("INSERT INTO quizstudent (studentid,quizid,degree) VALUES(:studentid,:quizid,:degree)");
  $stmt->execute(array('studentid'=>$studentid,'quizid'=>$quizid,'degree'=>$degree));
}

$stmt=$con->prepare("SELECT * FROM quizes WHERE id =? ");
$stmt->execute(array($quizid));
$quiz=$stmt->fetch();

$stmt=$con->prepare("SELECT * FROM students WHERE id =? ");
$stmt->execute(array($studentid));
$student=$stmt->fetch();
$msg =  "أكاديمية" ." E-Learning System %0a ". "  درجة الطالب".$student['name']." في امتحان ".$quiz['name']." %0a ".$degree." / ".$quiz['final_degree'];
 
$ch = curl_init();
$url= "https://smssmartegypt.com/sms/api/?username=msuahmed777@gmail.com&password=Msu12345&sendername=MrAhmedSaid&mobiles=2".$student['phone1']."&message=".urlencode($msg);
curl_setopt($ch, CURLOPT_URL,$url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$server_output = curl_exec($ch);
curl_close ($ch);
  
}

?>