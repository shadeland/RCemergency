<?php

class Sms {
    // your username (fill it with your username)
    private  $USERNAME = "kishnet";

    // your password (fill it with your password)
    private  $PASSWORD = "13920617";

    // your domain (fill it with your domain - usually: "magfa")
    private  $DOMAIN = "magfa";

    // base http url
    private  $BASE_HTTP_URL = "http://messaging.magfa.com/magfaHttpService?";

    private  $ERROR_MAX_VALUE = 1000;
    private $errors;

    public function __construct() {
//        include_once('errors.php');
//        $this->errors = $errors;
        // note : always remember to "urlencode" your data which may contain special characters (like your password)
        $this->PASSWORD = urlencode($this->PASSWORD);
    }
    /**
     * method : enqueueSample
     * this method provides a sample usage of "enqueue" service
     * which you can send Mobile Terminating (MT) messages with it
     * @return void
     */
    public function enqueueSample($recipientNumber,$message) {
        $method = 'enqueue'; // name of the service

        $senderNumber = "300097500069"; // [FILL] sender number ; which is your 3000xxx number
//        $recipientNumber = "09XXXXXXXXX"; // [FILL] recipient number; the mobile number which will receive the message (e.g 0912XXXXXXX)

       $message = urlencode($message); // [FILL] the content of the message; (in url-encoded format !)
        $udh = ""; // [FILL] udh of the message ; (optional)
        // [FILL] coding of the message (optional)
        // if left blank, system will guess the message coding automatically
        $coding = "";

        $checkingMessageId = ""; // [FILL] checking message id (optional)

        // creating the url based on the information above
        $url = $this->BASE_HTTP_URL .
            "service=" . $method .
            "&username=" . $this->USERNAME . "&password=" . $this->PASSWORD . "&domain=" . $this->DOMAIN .
            "&from=" . $senderNumber . "&to=" . $recipientNumber .
            "&message=" . $message . "&coding=" . $coding . "&udh=" . $udh .
            "&chkmessageid=" . $checkingMessageId;

        // sending the request via http call
        $result = $this->call($url);

        // compare the response with the ERROR_MAX_VALUE
        if ($result <= $this->ERROR_MAX_VALUE) {
            echo " URL : $url \n";
            echo "An error occured <br> Error Number : $result";
            return false;
//            echo "Error Code : $result ; Error Title : " . $this->errors[$result]['title'] . ' {' . $this->errors[$result]['desc'] . '}';
        } else {
            echo "Message has been successfully sent ; MessageId : $result";
            return true;
        }
    }
    /**
     * method : getCreditSample
     * this method provides a sample usage of "getCredit" service
     * @return void
     */
    public function getCredit() {
        $method = 'getcredit'; // name of the service

        // creating the url
        $url = $this->BASE_HTTP_URL .
            "service=" . $method .
            "&username=" . $this->USERNAME . "&password=" . $this->PASSWORD . "&domain=" . $this->DOMAIN;

        // sending the request via http call
        $result = $this->call($url);

        // checking the response
        if ($result <= $this->ERROR_MAX_VALUE) {
            echo "An error occured <br> ";
//            echo "Error Code : $result ; Error Title : " . $this->errors[$result]['title'] . ' {' . $this->errors[$result]['desc'] . '}';
        } else {
            echo "Your Credit : $result";
        }
    }

    public function getMessageIdSample() {
        $method = 'getMessageId'; // name of the service

        $checkingMessageId = "XXX"; // [FILL] checking message id

        // creating the url
        $url = $this->BASE_HTTP_URL .
            "service=" . $method .
            "&username=" . $this->USERNAME . "&password=" . $this->PASSWORD . "&domain=" . $this->DOMAIN .
            "&chkmessageid=" . $checkingMessageId;

        // sending the request via http call
        $result = $this->call($url);

        // checking the response
        if ($result <= $this->ERROR_MAX_VALUE) {
            echo "An error occured <br> ";
//            echo "Error Code : $result ; Error Title : " . $this->errors[$result]['title'] . ' {' . $this->errors[$result]['desc'] . '}';
        } else {
            echo "Message Id : $result";
        }
    }
    /**
     * method : getMessageStatusSample
     * this method provides a sample usage of "getMessageStatus" service
     * @return void
     */
    public function getMessageStatus($messageId) {
        $method = 'getMessageStatus'; // name of the service

//        $messageId = 'XXXXXXXXX'; // [FILL] message Id (which has been returned in the "enqueue" method ) (e.g : 718570969)

        // creating the url
        $url = $this->BASE_HTTP_URL .
            "service=" . $method .
            "&username=" . $this->USERNAME . "&password=" . $this->PASSWORD . "&domain=" . $this->DOMAIN .
            "&messageid=" . $messageId;

        // sending the request via http call
        $result = $this->call($url);

        // checking the response
        if ($result <= -1) {
            echo "An error occured <br> ";
//            echo "Error Code : $result ; Error Title : " . $this->errors[$result]['title'] . '{' . $this->errors[$result]['desc'] . '}';
        } else {
            echo "message's status code : $result";
            return $result;
        }
    }

    private function call($url){
        return file_get_contents($url);
    }



}
?>