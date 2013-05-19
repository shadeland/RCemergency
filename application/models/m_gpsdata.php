<?php
class M_gpsdata extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

     function insertData($array){
        $this->load->library('gpsdata');
        foreach($array as $key => $value){
            $this->db->set($key,$value);
        }
        $this->db->set('submitdate',date("Y-m-d H:i:s"));
        $this->db->insert('gps_data');
         //check status Depended On Out put Data
         //O1 = 0 free
         //O1 = 1 not Free
         //O2 = 0 out Mission
         //O2 = 1 in Mission
         //O3 = 0
         //03 = 1
         $this->load->library('gpsdata');
        $status= $this->gpsdata->parseStatus($array);//Parse Stattus From Data
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
    
   
	
}
?>
