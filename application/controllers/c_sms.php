<?php


if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class C_Sms extends CI_Controller {


    public function index() {
        error_reporting(-1);
        ini_set('display_errors', TRUE);




        $this->load->library('Sms');

        $this->sms->enqueueSample('09122836784',"Gps Sms Service Test \n Second Line ");


    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
?>