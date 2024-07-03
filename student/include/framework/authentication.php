<?php

/**
 
authentication class to authenticate users


*/
class Authentication 
{
	

   public function HashPassword($Password) {     // to hash password
   
      return sha1('cdbdba75e5'.md5('96979fb6'.$Password.'8da895595').'46501d41043623e9');
    
    }

   public function APIAuthentication() {     // to hash password
  
		  if ( !isset($_GET['API_KEY']) || $_GET['API_KEY'] != API_KEY) {
			die (json_encode(array('error'=>1 ,'message'=>'API Not Authenticated')));
			}
    
    }
}


