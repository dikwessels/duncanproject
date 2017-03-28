<?php


/*
 * Create a random string
 * @author XEWeb <http://www.xeweb.net>
 * @param $length the length of the string to create
 * @return $str the string
 */

//echo randomString(8);

function randomString($length = 6) {
 $str = "";
 $characters = array_merge(range('A','Z'), range('a','z'), range('0','9'));
 $max = count($characters) - 1;
 for ($i = 0; $i < $length; $i++) {
  $rand = mt_rand(0, $max);
  $str .= $characters[$rand];
 }
 
 $str=substr($str, 0,4)."-".substr($str, -4);
 
 return $str;
 
 
}

?>