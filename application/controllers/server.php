<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Server extends CI_Controller {

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     * 		http://example.com/index.php/welcome
     * 	- or -  
     * 		http://example.com/index.php/welcome/index
     * 	- or -
     * Since this controller is set as the default controller in 
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see http://codeigniter.com/user_guide/general/urls.html
     */
    public function index() {
        error_reporting(-1);
        ini_set('display_errors', TRUE);

//       TODO : Shadel Validate Get Data
        $this->load->config('gps_config');
        $this->load->model("tmpavlsetting");
        $this->load->model("m_gpsdata");
        $this->load->library('gpsdata');
        $data = $this->gpsdata->parseGetData($_GET);
        if ($data) {
            // Look at features file !
            //FIND ORDER FOR DEVICE AND MAKE THEM SENDED
            $this->m_gpsdata->insertData($data);
            $this->load->model('m_vehicles');
            $vehicleID=$this->m_vehicles->findBySID($data['SID']);
//            print_r($vehicleID);
            $this->load->model('m_order');
            $orderID=$this->m_order->hasOrder($vehicleID);
            $response="#ACK;"; //Default Response If We have Nothing To Do

            if($data['input2']== "1" || $data['output1']=="1"){//in Mission Or Has Informed
                //Do Nothing
            }elseif($data['input2']== "0" && $data['output1']=="0" && $orderID ){//Not In Mission ,Not Informed And Has Order
                $response="@gprs;O1=1;";
                $this->m_order->makeSended($orderID);//Push Order Out of queue
            }

            if($data['input2']=="1" && $data['output1']=="1"){//In Mission And Informed Before
                $response="@gprs;O1=0;"; //set output1 to 0 to turn off the notifier
            }
        }else{

            $response="ERROR:DATA";
        }


//            $setting = $this->tmpavlsetting->getLastRequest();
//
//            if ($setting->num_rows() == 1) {
//                $id = $setting->row()->ID;
//                $str = $setting->row()->string;
//                $this->tmpavlsetting->closeRequest($id); //Close Request in DB
//                $response= $str;
                

         self::llog($response);

         echo $response; //Response To Device

    }

    function llog($res) {
//
        $filename="log/log.txt".date("y-m-d");
        $file = fopen($filename, "a");
        fwrite($file, "[[[[[[[[[[[[[[[[[[[" . date(DATE_COOKIE) . "]]]]]]]]]]]]]]]]]]]]]]]]]]\n");
        fwrite($file, print_r($_SERVER, true));
        fwrite($file, print_r($_POST, true));
        fwrite($file, print_r($_GET, true));
        fwrite($file, print_r($_FILES, true));
        fwrite($file, "[[[[[[[[[[[[[[[[[[[END OF REQUEST]]]]]]]]]]]]]]]]]]]]]]]]]]\n");
        fwrite($file, "[[[[[[[[[[[[[[[[[[[RESPONSE]]]]]]]]]]]]]]]]]]]]]]]]]]\n");
        fwrite($file, "$res\n");
        fwrite($file, "[[[[[[[[[[[[[[[[[[[END OF RESPONSE]]]]]]]]]]]]]]]]]]]]]]]]]]\n");
        fclose($file);
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */