<?php
class M_gpsdata extends CI_Model {

    function __construct()
    {
        parent::__construct();
        $this->load->config('gps_config');
    }

     function insertData($array){
        $this->load->library('gpsdata');
         if($array['FIX']=='0'){
             return false;
         }
        foreach($array as $key => $value){
            $this->db->set($key,$value);
        }
        $this->db->set('submitdate',date("Y-m-d H:i:s"));
        $this->db->insert('gps_data');
         $gpsID=$this->db->insert_id();
//         Check for order of vehicle if it has order then and it  to its point
         $this->load->model('m_order');
         $vehicleID=self::getVehicle($array);

         if($vehicleID){
             $order=$this->m_order->hasOrder($vehicleID);
            if($order){

                if(is_null($order['startpoint'])){//set ut as start point
                    $this->m_order->setStartPoint($order['ID'],$gpsID);
                    echo " kjsdlkf";
                }elseif(is_null($order['destpoint'])){//set up dest point
                    $maxdistance = 30;
                    $incident=$this->m_order->getIncident($order['ID']);

                    $distance=$this->gpsdata->calcDistance($array['lat'],$array['lng'],$incident['lat'],$incident['lon']);
                   if($distance<$maxdistance){
                       $this->m_order->setDestPoint($order['ID'],$gpsID);
                   }
                }elseif(is_null($order['endpoint'])){//set up dest point
                    $maxdistance = 30;
                    $incident=$this->m_order->getIncident($order['ID']);

                    $distance=$this->gpsdata->calcDistance($array['lat'],$array['lng'],$incident['lat'],$incident['lon']);
                    if($distance<$maxdistance){
                        $this->m_order->setEndPoint($order['ID'],$gpsID);
                    }
                }
            }
         }


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


         if ($vehicleID){
             $statusData=array('vehicleID'=>$vehicleID,'statusID'=>$status);

         $this->m_vehicle_status->updateStatus($statusData);//Update Status
         }

    }
    function getVehicle($data){
        if(!isset ($data['SID'])){
            return false;
        }
        $data=$this->db->select('vehicle.ID')->where('avl.SID',$data['SID'])->join('avl','vehicle.avl_ID =avl.ID')->get('vehicle')->row_array();
        if(isset($data['ID'])){
        return $data['ID'];
        }else{
            return false;
        }

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
        return $this->db->where("SID",$SID)->LIMIT(1)->ORDER_BY('recivedate','desc')->get('gps_data');
    }
    function getLastPositions($SID,$LIMIT=3){
        return $this->db->where("SID",$SID)->LIMIT($LIMIT)->ORDER_BY('recivedate','desc')->get('gps_data');
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
