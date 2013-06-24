<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//check status Depended On Out put Data
//output1=0 You doesnt have any order
//output1=1 You Have Order
//input2=0 I'm not In mission
//input2=1 I'm In Mission
//input1=0 i'm switched Off
//input1=0 i'm Switched On
// When We receive an input1=0 and and if previous status was input1=1 we should set output1=0;

$config['device-status-lang']['input1']['1'] = "Switched Off";
$config['device-status-lang']['input1']['0'] = "Switched On";
$config['device-status-lang']['input2']['0'] = "Not In Mission";
$config['device-status-lang']['input2']['1'] = "In Mission";
$config['device-status-lang']['output1']['0'] = "Not Informed";
$config['device-status-lang']['output1']['1'] = "Informed";
$config['device-status-lang']['output2']['0'] = "Reserved";
$config['device-status-lang']['output2']['1'] = "Reserved";

$config['device-status-value']['input1']['1'] = "0";
$config['device-status-value']['input1']['0'] = "1";
$config['device-status-value']['input2']['0'] = "2";
$config['device-status-value']['input2']['1'] = "3";
$config['device-status-value']['output1']['0'] = "4";
$config['device-status-value']['output1']['1'] = "5";
$config['device-status-value']['output2']['0'] = "6";
$config['device-status-value']['output2']['1'] = "7";
// We gonna hace something Like 0246 which means Switched Off ,Not in Mission,not Informed,But Has Order
$config['vehicle-status-lang']['1'] = "Switched Off";
$config['vehicle-status-lang']['0'] = "Switched On";
$config['vehicle-status-lang']['2'] = "Not In Misssion";
$config['vehicle-status-lang']['3'] = "In Mission";
$config['vehicle-status-lang']['4'] = "Not Informed";
$config['vehicle-status-lang']['5'] = "Informed";
$config['vehicle-status-lang']['7'] = "";//Output 2 Reserved
$config['vehicle-status-lang']['8'] = "";//Output 2 Reserved
$config['vehicle-status-lang']['9'] = "Have Order";
$config['vehicle-status-lang']['10'] = "Not Have Order";

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
$config['status_type']['input1']="1";
$config['status_type']['input2']="2";
$config['status_type']['output1']="3";
$config['status_type']['Admin']="4";