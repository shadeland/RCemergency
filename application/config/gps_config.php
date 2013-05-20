<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//check status Depended On Out put Data
//O1=0 You doesnt have any order
//O1=1 You Have Order
//I2=0 I'm not In mission
//I2=1 I'm In Mission
//I1=0 i'm switched Off
//I1=0 i'm Switched On
// When We receive an I1=0 and and if previous status was I1=1 we should set O1=0;

$cofing['device-status-lang']['I1']['0'] = "Switched Off";
$cofing['device-status-lang']['I1']['1'] = "Switched On";
$cofing['device-status-lang']['I2']['0'] = "Not In Mission";
$cofing['device-status-lang']['I2']['1'] = "In Mission";
$cofing['device-status-lang']['01']['0'] = "Not Informed";
$cofing['device-status-lang']['01']['1'] = "Informed";


$cofing['device-status-value']['I1']['0'] = "0";
$cofing['device-status-value']['I1']['1'] = "1";
$cofing['device-status-value']['I2']['0'] = "2";
$cofing['device-status-value']['I2']['1'] = "3";
$cofing['device-status-value']['O1']['0'] = "4";
$cofing['device-status-value']['O1']['1'] = "5";

// We gonna hace something Like 026 which means Switched Off ,Not in Mission,But Has Order
$cofing['vehicle-status-lang']['0'] = "Switched Off";
$cofing['vehicle-status-lang']['1'] = "Switched On";
$cofing['vehicle-status-lang']['2'] = "In Misssion";
$cofing['vehicle-status-lang']['3'] = "Not In Mission";
$cofing['vehicle-status-lang']['4'] = "Not Informed";
$cofing['vehicle-status-lang']['5'] = "Informed";
$cofing['vehicle-status-lang']['6'] = "Have Order";
$cofing['vehicle-status-lang']['7'] = "Not Have Order";

/* Examples :
    Find Vehicle With No Order ? ,An Not In Mission ? ,And Swiched On ?
            We gonna Need An Status Type ! Which Will Show this status is in What Kind :
    Motor : 1
    Vehicle_Input : 2
    Vehicle_Output : 3
    Admin : 4
    Then Read All Valid Status ;
We Should Decide When We Add Order To A Vehicle Does We need To Set Vehicle Status ,or Only Checking its "orders list"
is enough!
*/
$cofig['status_type']['I1']="1";
$cofig['status_type']['I2']="2";
$cofig['status_type']['O1']="3";
$cofig['status_type']['Admin']="4";