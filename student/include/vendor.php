<?php 

/*

vendor file to work with system and define variabols 
 
*/

  date_default_timezone_set('Africa/Cairo');    // set defult date 
  ini_set('upload_max_filesize', '10M');        // set max file upload
  ini_set('post_max_size', '20M');              // set max post size 
  ini_set('memory_limit', '128M');              // set memory limit 
  //ini_set('display_errors', 0);               // remove error message  
  //error_reporting(1);                         // remove error message  
  


// Database Define
define('DB_NAME','Mr. Ibrahim Gad');                     //set database name 
define('DB_USER','root');                          //set dabase user 
define('DB_PASS','');                              //set database password
define('DB_HOST','localhost');                     //set darabase host


// API Define 
define('API_KEY','apikey');                              //set API KEY


include_once ('framework/database.php');             //include database  
include_once ('framework/functions.php');            //include functions 
include_once ('framework/validation.php');           //include validation
include_once ("framework/upload.php");               //include uploade 
include_once ("framework/authentication.php");       //include authentication  


$DataBase              =new DataBase();                       // define Database class
$Functions             =new Functions();                      // define function calss
$Validation            =new Validation();                     // define Validation calss
$Upload                =new Upload('uploads');                // define upload file calss And take folder name 
$Authentication        =new Authentication();                 // define authentication calss


