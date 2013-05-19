<?php

$file=fopen("log.txt","a");
fwrite($file,"[[[[[[[[[[[[[[[[[[[".date(DATE_COOKIE)."]]]]]]]]]]]]]]]]]]]]]]]]]]\n"); 
fwrite($file,print_r($_SERVER,true)); 
fwrite($file,print_r($_POST,true)); 
fwrite($file,print_r($_GET,true)); 
fwrite($file,print_r($_FILES,true)); 
fwrite($file,"[[[[[[[[[[[[[[[[[[[END OF REQUEST]]]]]]]]]]]]]]]]]]]]]]]]]]\n"); 
fclose($file);
echo ("*000000000000*01*0$");
?>
 