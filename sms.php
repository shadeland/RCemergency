harhci
<?php
error_reporting(-1);
ini_set('display_errors', TRUE);
require('webservice/nusoap/nusoap.php');

$client = new nusoap_client('http://opp.co.ir/uws/service.asmx?wsdl', true);
$username="lordofnaz";
$password="1331365";
$method = "SendSMS2";
$message = "تست";
$senderNumber = "10009123820013";
$recipientNumber = "09363210770";
$params = array(
    'username'=>$username,
    'password'=>$password,
    'telno'=>$senderNumber,
    'numbers'=>  $recipientNumber,
    'message'=>$message
);

$client->soap_defencoding = 'UTF-8';
$client->decode_utf8 = false;
$result = $client->call($method, $params);

print_r("harchi");
print_r($result);
?>