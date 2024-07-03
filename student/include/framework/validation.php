<?php


/*

validation class to validate data

*/

class Validation
{

   public function Text($Text)  // validate text
     {
      
      return  filter_var(trim( strip_tags($Text)),  FILTER_SANITIZE_STRING );

     }

   public function Email($Email) // validate email  
      {  

          $Email= filter_var( trim ( strip_tags($Email)) , FILTER_SANITIZE_EMAIL);

            return  filter_var( $Email , FILTER_VALIDATE_EMAIL );

     }

   public function Number($Number)  // validate number 
     {
    		 $expr = '/^[1-9][0-9]*$/';
    	    if (preg_match($expr, trim(strip_tags($Number))) && filter_var(trim(strip_tags($Number)), FILTER_VALIDATE_INT)) {
    			    return $Number;
    			} 
         
         return null;

     }

   public function TextSpecialChars($Text)  // validate sepical
     {
      
      return  filter_var(trim( htmlspecialchars($Text)), FILTER_SANITIZE_STRING);

     }

   public function MaxMin($String,$Max,$Min )  // validate Max & Min
     {
        $String=trim( $String);            
        if(strlen($String)<= $Max && strlen($String) >= $Min  )
           {
            return $String;
           } 
        return null ;

     }   


}