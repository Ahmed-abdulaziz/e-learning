<?php

include 'connect.php';


 if(!empty($_POST['edu_id'])){
    $edu_id = $_POST['edu_id'];

    $stmt = $con->prepare("SELECT name , id FROM subjects WHERE edu_id = ? GROUP BY name");
    $stmt->execute(array($edu_id));
    $subjects = $stmt->fetchAll();
    
   if(!empty($subjects)){
        foreach($subjects as $sub) {

        $stmt = $con->prepare("SELECT * FROM classes WHERE parentid IS NULL AND s_id IN (SELECT id FROM subjects WHERE name = ? AND edu_id = ?)");
        $stmt->execute(array($sub['name'] , $edu_id));
        $classes = $stmt->fetchAll();
        
    
  
         if(!empty($subjects)){
            foreach($classes as $class){ 

                $stmt=$con->prepare("SELECT * FROM studentssubjects WHERE classid = ?");
                $stmt->execute(array($class['id']));
                $left=$class['no']-($stmt->rowCount());  
                
           ?>
                        <option value="<?php echo $class['id'] ?>"><?php echo $class['day'] . " - " . date('h:i a ',strtotime($class['time'])) ." - ". $class['center_name'];?></option>
                    
         
                    <?php }
             
                         }
                    }       
                }
            }

?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
