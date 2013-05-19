<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class server extends CI_Controller {

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
        $this->writeLog();
        $this->load->library('gpstool');
        $this->load->config('myconfig');
        $setData = $this->config->item('device_setting');
        $autoSetDelay = -1;// How many Time AVl should send data for auto Setting Response 
        //-1 for no auto setting.

        $this->load->database();
        if (!isset($_GET['data'])) {
            echo "die";
            die();
        }
        $data = $this->gpstool->parse($this->input->get('data'));
        if ($data) {//Valid Data
            $data['submitdate'] = date("Y-m-d H:i:s");
            if ($this->db->insert('gpsdata', $data)) {
                $gpsdataID = $this->db->insert_id(); //To Set Send_flag
                //Check if there is A setting and Set The Device 
                $row = $this->db->where(array(
                            'deviceID' => $data['deviceID'],
                            'status' => '0'
                        ))->order_by("date", "desc")->get('setting_queue')->row_array();
                if (count($row) != 0) {
                    //there is some setting
                    $this->db->where('ID', $gpsdataID)->update('gpsdata', array('setting_send_flag' => '1')); //Set The Setting_send_flag to 1 ;
                    $output = preg_replace('/d+/', $row['setting_value'], $setData[$row['settingID']]['pattern']);
                    echo $output;
                    $this->db->set('status', '1')->where('ID', $row['ID'])->update('setting_queue');
                } else {
                    //no setting
                    //DO AUTO SETTING WITH STATIC ORDER  .
                    if($autoSetDelay!=-1){
                        $noSettings = $this->gpstool->lastSettingSend($data['deviceID']);
                   
                         if ($noSettings == $autoSetDelay) {
                             $this->db->where('ID', $gpsdataID)->update('gpsdata', array('setting_send_flag' => '1')); //Set The Setting_send_flag to 1 ;
                             echo $setData["8"]['pattern'];
                         }
                    } else {
                        echo $setData["0"]['pattern'];;// IF we Have Nothing to Say To AVL 
//                        #TODO for Debug should be with  refrence of library  
                    }
                }
            }
        }
    }

    private function writeLog() {
        $file = fopen("log.txt", "a");
        fwrite($file, "[[[[[[[[[[[[[[[[[[[" . date(DATE_COOKIE) . "]]]]]]]]]]]]]]]]]]]]]]]]]]\n");
        fwrite($file, print_r($_SERVER, true));
        fwrite($file, print_r($_POST, true));
        fwrite($file, print_r($_GET, true));
        fwrite($file, print_r($_FILES, true));
        fwrite($file, "[[[[[[[[[[[[[[[[[[[END OF REQUEST]]]]]]]]]]]]]]]]]]]]]]]]]]\n");
        fclose($file);
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */