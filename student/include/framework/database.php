<?php

/*

database class to use Database

*/


class DataBase{

    private $connection ;


    public function __construct()    // connection
        {
            $this->connection = new mysqli (DB_HOST,DB_USER,DB_PASS,DB_NAME);
            
           if ($this->connection->connect_error) {
                    die('Connection failed : ' . $this->connection->connect_error);
                 }
           $this->connection->set_charset("utf8");       
        }


    public function SelectAll ($TapleName,$Extra='')  // Select All From Taple where
           {
            $result=$this->connection->query("SELECT * FROM `$TapleName` $Extra ");


            if( $result->num_rows > 0 )
            {
                $Rows=array();

                while($row=$result->fetch_assoc() )
                {
                    $Rows[]=$row;
                }
                return $Rows;
            }

            return [];
          }
          

    public function Select ($TapleName, $Id , $Extra='' )  // Select row from taple With ID 
          {  
            $Id =(int)$Id;

            $services= $this->SelectAll($TapleName ," WHERE `id`='$Id' $Extra ");

            if ($services && count($services))
                return $services[0];

            return [];
          } 

    public function SelectWhere ($TapleName, $Where )  // Select row from taple where 
            {  

                $services= $this->SelectAll($TapleName , $Where);

                if ($services && count($services))
                    return $services[0];

                return [];
            } 
     
    public function Delete ($TapleName,$Id )  //delete From databse
        {
            $this->connection->query("DELETE FROM `$TapleName` WHERE `id`=$Id");


            if( $this->connection->affected_rows>0 )
                return true ;

            return false ;



        }
    
    public function DeleteWhere ($TapleName,$Where )  //delete From databse Where ****
            {
                $this->connection->query("DELETE FROM `$TapleName` WHERE  $Where ");


                if( $this->connection->affected_rows>0 )
                    return true ;

                return false ;



            }
    
    public function __destructc()  //close connction
    {
        $this->connection->close();

    }
     public function SelectAlw ($key,$TapleName,$column ,$value)  // Select All From Taple where
    {
     $result=$this->connection->query("SELECT $key FROM `$TapleName` WHERE $column =  $value ");


     if( $result->num_rows >0 )
     {
         $Rows=array();

         while($row=$result->fetch_assoc() )
         {
             $Rows[]=$row;
         }
         return $Rows;
     }

     return [];
   }
   
   public function SelectAlww ($TapleName,$column )  // Select All From Taple where
    {
     $result=$this->connection->query("SELECT * FROM `$TapleName` WHERE $column =  ' ' ");


     if( $result->num_rows >0 )
     {
         $Rows=array();

         while($row=$result->fetch_assoc() )
         {
             $Rows[]=$row;
         }
         return $Rows;
     }

     return [];
   }


}


 