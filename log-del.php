<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Shadel
 * Date: 6/24/13
 * Time: 6:05 PM
 * To change this template use File | Settings | File Templates.
 */
$file = fopen("log.txt",'w');
fwrite($file, "[[[[[[[[[[[[[[[[[[[" . date(DATE_COOKIE) . "]]]]]]]]]]]]]]]]]]]]]]]]]]\n");

fclose($file);
?>