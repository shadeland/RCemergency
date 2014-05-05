<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Example
 *
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array.
 *
 * @package		CodeIgniter
 * @subpackage	Rest Server
 * @category	Controller
 * @author		Phil Sturgeon
 * @link		http://philsturgeon.co.uk/code/
*/

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH.'/libraries/REST_Controller.php';

class service_report extends REST_Controller
{


    function orderlist_get()
    {
       
        $this->load->model('m_order');
        $data=$this->m_order->getOrderList();
        $this->response($data,"200");
        // $user = $this->some_model->getSomething( $this->get('id') );
    	
    }
    function driverlist_get(){
        $this->load->model('m_driver');
        $data=$this->m_driver->getDriverList();
        $this->response($data,"200");

    }



  
}