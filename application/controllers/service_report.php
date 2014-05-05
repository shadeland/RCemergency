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
        $did=$this->get('query');
        $type=$this->get('type');
//        $drivername=$this->db->where("id",$did)->get('drivers')->row()->fullname;
//        //random date
//
//        $this->load->library('jalaliDate');
//        $date1=$this->jalalidate->randomdate();
//
//       $date2=$date1;
//        $data[]=array('id'=>1,'drivername'=>$drivername,'vehicleOID'=>"2675",'vehicletype'=>'اسکانیا','datestart'=>$date1,'datedest'=>$date2,'dateend'=>'','status'=>'ناتمام');
//
        $this->load->model('m_order');
        $data=$this->m_order->getOrderList('driver',$did,"skjjd","lsdjsald");
        $this->response($data,"200");
        // $user = $this->some_model->getSomething( $this->get('id') );
    	
    }
    function vehiclelist_get(){
       $data= $this->db->query("SELECT vehicle.ID id,vehicle.OID OID,vt.lang_fa as vehiclety, CONCAT(vt.lang_fa,']',OID,']') as fullname from vehicle left join vehicle_type vt on vehicle.vehicle_type_ID=vt.ID")->result_array();


        $this->response($data,"200");

    }
    function driverlist_get(){
        $this->load->model('m_driver');
        $data=$this->m_driver->getDriverList();
        $this->response($data,"200");

    }




  
}