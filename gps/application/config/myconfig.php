<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * Device Setting Patterns .Use Replace to Use Them 
 * 
 */
$config['device_setting']=array(
    "0"=>array("lang"=>"No Setting ","pattern"=>"*000000000000*500201*0$"),
    "1"=>array("lang"=>"1st Number For SOS ","pattern"=>"*dddddddddddd*500201*1$"),
    "2"=>array("lang"=>"2nd Number For SOS ","pattern"=>"*dddddddddddd*500201*2$"),
    "3"=>array("lang"=>"3rd Number For SOS ","pattern"=>"*dddddddddddd*500201*3$"),
    "4"=>array("lang"=>"4th Number For SOS ","pattern"=>"*dddddddddddd*500201*4$"),
    "5"=>array("lang"=>"Change Password ","pattern"=>"*000000dddddd*500201*W$"),
    "6"=>array("lang"=>"Change ID Number  ","pattern"=>"*000000dddddd*500201*I$"),
    "7"=>array("lang"=>"Setting the AVL Module Output","pattern"=>"*00000000000d*500201*U$"),
    "8"=>array("lang"=>"Request AVL ","pattern"=>"*00000000000?*500201*N$"),
    "9"=>array("lang"=>"GPRS Time Period ","pattern"=>"*0000000000dd*500201*P$"), 
    "10"=>array("lang"=>"Request AVL Version ","pattern"=>"*00000000000?*500201*V$")
);
/*
 * Sending Status Stored In Db setting queue,as "status" 
 */
$config['setting_status']=array(
    "1"=>"Sended",
    "0"=>"Wainting"
);

?>
