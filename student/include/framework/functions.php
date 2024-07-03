<?php


/*

Function class to include global functions 
*/

class Functions
{
    

  public function ElapsedTime($datetime, $full = false) {   // to get get elapsed time 

            $now = new DateTime;
            $ago = new DateTime($datetime);
            $diff = $now->diff($ago);

            $diff->w = floor($diff->d / 7);
            $diff->d -= $diff->w * 7;

            $string = array(
                'y' => 'year',
                'm' => 'month',
                'w' => 'week',
                'd' => 'day',
                'h' => 'hr',
                'i' => 'min',
                's' => 'Sec',
            );
            foreach ($string as $k => &$v) {
                if ($diff->$k) {
                    $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
                } else {
                    unset($string[$k]);
                }
            }

            if (!$full) $string = array_slice($string, 0, 1);
            if (@$string['w'] == "1 week" || @$string['w'] == "2 week" || @$string['w'] == "3 week") return date("g:ia d/m/Y",strtotime($datetime));
            if (@$string['d'] == "1 day") return "Yesterday at ".date('g', strtotime($datetime)).":".date('ia', strtotime($datetime));
            return $string ? implode(', ', $string)  : 'Just now';
        }

  public function PrintTime($Time) {     // to Print Time 
   
      return date("F j ", strtotime($Time)).'at'.date(" g:ia", strtotime($Time));  
    }

 

}