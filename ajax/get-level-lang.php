<?php
session_start();
include "../connect.php";

function check($var){
    return htmlentities(trim(strip_tags(stripcslashes($var))));
  }
 
  if(isset($_POST['type'])){
      
            $type = $_POST['type'];
            $stmt = $con->prepare("SELECT * FROM educationallevels  WHERE lang = ?");
            $stmt -> execute(array($type));
            $levels = $stmt->fetchAll();
            
            foreach($levels as $item){?>
            
            <option value="<?=$item['id']?>" <?php if(!empty($err) && $level == $item['id'] ) echo "Selected";  ?>><?=$item['name_appper']?> </option>
            <?php  }
  }
?>