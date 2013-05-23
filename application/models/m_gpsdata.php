<?php
class M_gpsdata extends CI_Model {

    function __construct()
    {
        parent::__construct();
        $this->load->config('gps_config');
    }

     function insertData($array){
        $this->load->library('gpsdata');
        foreach($array as $key => $value){
            $this->db->set($key,$value);
        }
        $this->db->set('submitdate',date("Y-m-d H:i:s"));
        $this->db->insert('gps_data');
         //check status Depended On Out put Data
         //O1=0 You doesnt have any order
         //O1=1 You Have Order
         //I2=0 I'm not In mission
         //I2=1 I'm In Mission
         //I1=0 i'm switched Off
         //I1=0 i'm Switched On
         // When We receive an I1=0 and and if previous status was I1=1 we should set O1=0;
         $this->load->library('gpsdata');
        $status= self::parseStatus($array);//Parse Stattus From Data somthing like "1256"

         $this->load->model('m_vehicle_status');
         $statusData=array('vehicleID'=>self::getVehicle($array),'statusID'=>$status);
         $this->m_vehicle_status->updateStatus($statusData);//Update Status


    }
    function getVehicle($data){
        if(!isset ($data['SID'])){
            return false;
        }
        $data=$this->db->select('vehicle.ID')->where('avl.SID',$data['SID'])->join('avl','vehicle.avl_ID =avl.ID')->get('vehicle')->row_array();
        return $data['ID'];

    }
    function getAll($dateFrom="",$dateUntil="",$device=""){
       if($dateFrom != ""){
           $this->db->where("recivedate >= $dateFrom");
       } 
       if($dateUntil != ""){
           $this->db->where("recivedate <= $dateUntil");
       } 
       if($device != ""){
           $this->db->where("SID",$device);
       }
      return $this->db->get('gps_data');
       
    }
    function getLastPosition($SID){
        return $this->db->where("SID",$SID)->LIMIT(1)->ORDER_BY('recivedate','desc')->get('gpd_data');
    }
    function parseStatus($data){
        $ref=$this->config->item('device-status-value');
        $status="";
        foreach($ref as $key=>$value){


                $status .= $value[$data[$key]];;


        }
        return $status;
    }
    
   
	
}
?>
