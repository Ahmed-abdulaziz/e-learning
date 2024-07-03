<?php
    include 'connect.php';
function check($var){
    return htmlentities(trim(strip_tags(stripcslashes($var))));
  }

   if(isset($_POST['get_lesson'])){
    $state = check($_POST['get_lesson']);
    $s_id = check($_POST['s_id']);

    $stmt= $con->prepare("SELECT name,id FROM branches WHERE chapter= ? AND subjectid=?");
    $stmt->execute(array($state,$s_id));
    $lessons=$stmt->fetchAll();
    $check=$stmt->rowCount();
    if($check > 0 ){
      ?>
      <option value="">Select Lesson</option>
      <?php
          }else{?>
      
      <option value="">NO Found Lesson</option>
      
      
          <?php }
    foreach($lessons as $lesson)
    {?>
   
     <option value="<?php echo $lesson['id']?>"><?php echo $lesson['name']?></option>
     
    <?php }
    exit;
   }elseif(isset($_POST['get_chapters'])){
            $state = check($_POST['get_chapters']);
            $s_id = check($_POST['s_id']);
        
            $stmt=$con->prepare("SELECT * FROM branches WHERE units= ? AND   chapter IS NULL AND subjectid = ?");
            $stmt->execute(array($state, $s_id));
            $chapters=$stmt->fetchAll();
            $check=$stmt->rowCount();
       
            if($check > 0 ){
              ?>
              <option value="">Select Chapter</option>
              <?php
                  }else{?>
              
              <option value="">NO Found Chapter</option>
              
              
                  <?php }
            foreach($chapters as $chapter)
            {?>
           
             <option value="<?php echo $chapter['id']?>"><?php echo $chapter['name']?></option>
             
            <?php }
            exit;
   }
   
 
?>