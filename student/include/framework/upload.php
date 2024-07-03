<?php


/**
  upload files
*/
  

class Upload
{   
    

	  private $Direction;
  
  function __construct($Direction)
    {
      $this->Direction=$Direction;

    }

    public function UploadImage($Image,$Direction='')  // uploade image 
    	{
		       $ImageName =basename($Image['name']);
           $ImageType =strtolower(pathinfo($ImageName, PATHINFO_EXTENSION)); 
           $ImageRename =md5(date('U').rand(12012,5841948948)).'.'.$ImageType;
           $ImageFile = $Direction.$this->Direction.'/'.$ImageRename;
           $ImageUploaded=1;
           $ImageCheck = getimagesize($Image["tmp_name"]);

            if($ImageCheck == false)
           	  {
           	  	$ImageUploaded=0;
           	  }

             
            if($ImageType !='png' && $ImageType !='jpg' && $ImageType !='jpeg' && $ImageType !='ico' ) 
               {
                 $ImageUploaded=0;	
               } 
              

            if($ImageUploaded == 1)
               {
                   if(move_uploaded_file($Image["tmp_name"], $ImageFile))
                      {
                       	return $ImageRename;
                       } else {
                              return false; 
                           }

               }else {
                       return false; 
                       }
                
           
           }

    public function UploadMultipleImage($Images,$Direction='')  // uploade Multi image 
        { 
           $ImagesNumber=count($Images['name']);
           $ArrayImages=array();
 
            for($Number=0 ;$Number < $ImagesNumber ;$Number++ )
                {
				       $ImageName =basename($Images['name'][$Number]);
		           $ImageType =strtolower(pathinfo($ImageName, PATHINFO_EXTENSION)); 
		           $ImageRename =md5(date('U').rand(12012,5841948948)).'.'.$ImageType;
		           $ImageFile =  $Direction.$this->Direction.'/'.$ImageRename;
		           $ImageUploaded=1;
		           $ImageCheck = getimagesize($Images["tmp_name"][$Number]);

		            if($ImageCheck == false)
		           	  {
		           	  	$ImageUploaded=0;
		           	  }

		             
		            if($ImageType !='png' && $ImageType !='jpg' && $ImageType !='jpeg' && $ImageType !='ico' ) 
		               {
		                 $ImageUploaded=0;	
		               } 
		              

		            if($ImageUploaded == 1)
		               {
		                   if(move_uploaded_file($Images["tmp_name"][$Number], $ImageFile))
		                      {
		                      	$ArrayImages[]=$ImageRename;
		                       } 
		               }

                  }
                               
             if (count($ArrayImages)>0) {
                  return $ArrayImages;  
             }

           return false;

           }
   


      

	
}



