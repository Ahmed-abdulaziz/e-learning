<?php 
session_start();
include "../connect.php";

function check($var){
    return htmlentities(trim(strip_tags(stripcslashes($var))));
  }
    
    $email= $_SESSION['name'];
    $stmt=$con->prepare("SELECT id FROM students WHERE email = ?");
    $stmt->execute(array($email));
    $result=$stmt->fetch();
    $studentid=$result['id'];

  if(isset($_POST['do'])){
      $do = $_POST['do'];
      $qid = $_POST['qid'];
      $examid = $_POST['examid'];
      
     if($do == 'next'){
         $qid++;
     }elseif($do == 'previous'){
         $qid--;
     }
    $stmt=$con->prepare("SELECT * FROM examsanswer WHERE examid =? AND id = ? AND studentid=?");
    $stmt->execute(array($examid,$qid , $studentid));
    $questions=$stmt->fetch();
    $check = $stmt->rowCount();
    if($check < 1){
        return false;
    }
  
  ?>
  
                            <input type="text" class="question-id" hidden value="<?php echo $questions['id']; ?>" >
                            <input type="text" class="exam-id" hidden value="<?php echo $examid; ?>" >
                              <?php
                            $i=1;
                            $q =  $questions ;
                            $type=$q['type'];
                            $questionid=$q['questionid'];
                           $radiono =$qid; 
                            if($type=='0') { 
                                $stmt=$con->prepare("SELECT * FROM questions WHERE id =?");
                                $stmt->execute(array($questionid));
                                $currentquestion=$stmt->fetch();
                                   
                                $stmt=$con->prepare("SELECT * FROM examsanswer WHERE examid=? AND studentid=? AND questionid=?");
                                $stmt->execute(array($examid,$studentid,$currentquestion['id']));
                                $oldan=$stmt->fetch();
                                ?>
                                <div>
                                <input hidden type="text" class="type" value="0">
                                <input type="text" name="multichoiceids[]" hidden value="<?php echo $questionid; ?>" class="qid">
                                
                                <pre class="pp "><?php echo '<span class="qnum">'.$i."</span>) ".$currentquestion['question'];?> </pre>
                                
                                <?php if(!empty($currentquestion['image'])) {?>
                                <img class="qimg" src="dashboard/img/questions/<?php echo $currentquestion['image'];?>">
                                <?php }?>


                                <label class="container ">
                                    <?php if(!empty($currentquestion['imgchoice1'])){ ?>
                                         <img class="w-25" src="dashboard/img/questions/<?php echo $currentquestion['imgchoice1'];?>">
                                    <?php }?>
                                    <?php echo $currentquestion['choice1'];?>
                                <input type="radio" name="radio[<?php echo $radiono; ?>]" value="1" <?php if(!empty($oldan) && $oldan['answer']==1) echo "checked";?>>
                                <span class="checkmark"></span>
                                </label>

                                <label class="container ">
                                    <?php if(!empty($currentquestion['imgchoice2'])){ ?>
                                         <img class="w-25" src="dashboard/img/questions/<?php echo $currentquestion['imgchoice2'];?>">
                                    <?php }?>
                                    <?php echo $currentquestion['choice2'];?>
                                <input type="radio" name="radio[<?php echo $radiono; ?>]" value="2" <?php if(!empty($oldan) && $oldan['answer']==2) echo "checked";?>>
                                <span class="checkmark"></span>
                                </label>
                                <label class="container ">
                                    <?php if(!empty($currentquestion['imgchoice3'])){ ?>
                                         <img class="w-25" src="dashboard/img/questions/<?php echo $currentquestion['imgchoice3'];?>">
                                    <?php }?>
                                    <?php echo $currentquestion['choice3'];?>
                                <input type="radio" name="radio[<?php echo $radiono; ?>]" value="3" <?php if(!empty($oldan) && $oldan['answer']==3) echo "checked";?>>
                                <span class="checkmark"></span>
                                </label>
                                <label class="container ">
                                    <?php if(!empty($currentquestion['imgchoice4'])){ ?>
                                         <img class="w-25" src="dashboard/img/questions/<?php echo $currentquestion['imgchoice4'];?>">
                                    <?php }?>
                                    <?php echo $currentquestion['choice4'];?>
                                <input type="radio" name="radio[<?php echo $radiono; ?>]" value="4" <?php if(!empty($oldan) && $oldan['answer']==4) echo "checked";?>>
                                <span class="checkmark"></span>
                                </label>
                                
                                <label class="container" Style="display:none;"> 
                                <input type="radio" name="radio[<?php echo $radiono; ?>]" value="5" <?php if(empty($oldan)) echo "checked";?>>
                                <span class="checkmark"></span>
                                </label>
                                </div>
                            <hr>
                            
                            <?php $radiono++;} elseif($type=='1'){ 

                                $stmt=$con->prepare("SELECT * FROM essay WHERE id =?");
                                $stmt->execute(array($questionid));
                                $currentquestion=$stmt->fetch();
                                
                            ?>
                            
                            <input type="text" name="essayids[]" hidden value="<?php echo $questionid; ?>" >
                            <?php if(!empty($currentquestion['image'])) {?>
                            <img class="qimg" src="dashboard/img/questions/<?php echo $currentquestion['image'];?>">
                            <?php }?>
                            <pre class="pp"><?php echo $i;?>) <?php echo $currentquestion['question']; ?></pre>
                            <textarea placeholder="Enter Your Answer" class="text" name="essay[]"></textarea>
                         <hr>
                        <?php }elseif($type=='2'){

                                $stmt=$con->prepare("SELECT * FROM questions WHERE givens =?");
                                $stmt->execute(array($questionid));
                                $givens=$stmt->fetchAll();
                                
                                $stmt=$con->prepare("SELECT * FROM givens WHERE id =?");
                                $stmt->execute(array($questionid));
                                $q=$stmt->fetch();
                                
                                ?>
                                  <?php
                                  echo '<h6 class="pp">'.$i." )</h6>" ;
                                    if(!empty($q['text'])){
                                        echo $q['text']."<br>";
                                        }
                                    if(!empty($q['image'])){
                                  ?>
                                  <img style="width: 500px;height: 500px;object-fit: cover;" src="dashboard/img/givens/<?php echo $q['image'];?>">
                                  <?php }?>
                                                              
                        <?php
                               $g="A";
                                foreach($givens as $currentquestion){
                                    
                                    
                                    $stmt=$con->prepare("SELECT * FROM examsanswer WHERE examid=? AND studentid=? AND questionid=?");
                                    $stmt->execute(array($examid,$studentid,$currentquestion['id']));
                                    $oldan=$stmt->fetch();
                                 
                        ?>
                        
                         <div>
                                <input hidden type="text" class="type" value="2">
                                <input type="text" name="givenids[]" hidden value="<?php echo $currentquestion['id']; ?>" class="qid">
                                <?php if(!empty($currentquestion['image'])) {?>
                                <img class="qimg" src="dashboard/img/questions/<?php echo $currentquestion['image'];?>">
                                <?php }?>

                                <pre class="pp"><?php echo $g.") ".$currentquestion['question'];?> </pre>

                                <label class="container"><?php echo $currentquestion['choice1'];?>
                                <input type="radio" name="given[<?php echo $givenno; ?>]" value="1" <?php if(!empty($oldan) && $oldan['answer']==1) echo "checked";?>>
                                <span class="checkmark"></span>
                                </label>

                                <label class="container"><?php echo $currentquestion['choice2'];?>
                                <input type="radio" name="given[<?php echo $givenno; ?>]" value="2" <?php if(!empty($oldan) && $oldan['answer']==2) echo "checked";?>>
                                <span class="checkmark"></span>
                                </label>
                                <label class="container"><?php echo $currentquestion['choice3'];?>
                                <input type="radio" name="given[<?php echo $givenno; ?>]" value="3" <?php if(!empty($oldan) && $oldan['answer']==3) echo "checked";?>>
                                <span class="checkmark"></span>
                                </label>
                                <label class="container"><?php echo $currentquestion['choice4'];?>
                                <input type="radio" name="given[<?php echo $givenno; ?>]" value="4" <?php if(!empty($oldan) && $oldan['answer']==4) echo "checked";?>>
                                <span class="checkmark"></span>
                                </label>
                                
                                <label class="container" Style="display:none;"> 
                                <input type="radio" name="given[<?php echo $givenno; ?>]" value="5" <?php if(empty($oldan)) echo "checked";?>>
                                <span class="checkmark"></span>
                                </label>
                                </div>
                                
                        
                            
                             <hr>
                        
                   <?php $givenno++;$g++;} }$i++;
                    ?>
                    
            <?php }?>
