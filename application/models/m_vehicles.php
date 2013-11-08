<?php
class M_vehicles extends CI_Model {
    //We should know every returned Object is an Array with ["ID"] ,....
    function __construct()
    {
        parent::__construct();
    }
    

    function getVehicles($vehicles="",$filter=""){
//        TODO : filter Should Implrement
        $filterArray=array();
        if($filter!=""){
            $filterArray=$filter;
        }
        if($vehicles==""){

      $vehicles=$this->db->select('ID')->like($filterArray)->get('vehicle')->result_array();
        }
      $data=array();
        foreach($vehicles as $vehicle){
            $vehicleData=self::sendVehicle($vehicle['ID']);
            array_push($data,$vehicleData);
        }
      return $data;

    }
    function sendVehicle($vehicleID){
        $this->load->model('m_order');
        $vehicle=$this->db->select('vehicle.* , vehicle_type.type AS type')->where('vehicle.ID',$vehicleID)->join('vehicle_type','vehicle.vehicle_type_ID = vehicle_type.ID')->limit(1)->get('vehicle')->row_array();
        $vehicle['LonLat']= self::getVehiclePosition($vehicleID);
        $vehicle['status']= self::getStatus($vehicleID);
        $orderID=$this->m_order->hasOrder($vehicleID);
        if($orderID){
            $vehicle['order']=$orderID;
        }

        return $vehicle;

    }
    function getVehiclePosition($vehicleID){
        $this->load->model('m_avl');
       $avlID=self::getAvlID($vehicleID);
        return $this->m_avl->getLastPosition($avlID);
    }
    function getLastPositions($vehicleID,$LIMIT){
        $this->load->model('m_avl');
        $avlID=self::getAvlID($vehicleID);
        return $this->m_avl->getLastPositions($avlID,$LIMIT);
    }

    function getAvlID($vehicleID){
        return $this->db->select('avl_ID')->where('ID',$vehicleID)->get('vehicle')->row()->avl_ID;
    }
    function getStatus($vehicleID){
        $this->load->model('m_vehicle_status');
        return $this->m_vehicle_status->getStatus($vehicleID);
    }
    function findBySID($sid){
        $result=$this->db->select('vehicle.ID')->join('avl',"vehicle.avl_ID = avl.ID")->where('avl.SID',$sid)->get('vehicle');
        if($result->num_rows()>0){
            return $result->row()->ID;
        }else{
            return 0;
        }

    }
    function getVehicleWithStatus($statusID,$output=""){

        $data= $this->db->query("SELECT vehicle.ID from vehicle where vehicle.ID in (select vehicle_ID from vehicle_status where ".self::statusHelper($statusID)." validity=1)")->result_array();
//        echo $this->db->last_query();
        if($output==""){
            return $data;
        }else{
            return self::getVehicles($data);
        }
    }
    function getVehicleDistance($vehicleID,$position){
//        print_r($position);
       $vposition=self::getVehiclePosition($vehicleID);
        $this->load->library('gpsdata');
       return $this->gpsdata->calcDistance($position['lat'],$position['lon'],$vposition['lat'],$vposition['lng']);


    }
    function statusHelper($statusID){

        $filter = array();
        if (strpos($statusID,"0")===FALSE){
           array_push($filter," status_ID LIKE '1%' ");
        }
        if (strpos($statusID,"3")===FALSE){
             array_push($filter," status_ID LIKE '%2%' ");
        }
        if (strpos($statusID,'5')===FALSE){
//           echo $statusID;
            array_push($filter,"  status_ID LIKE '%4%' ");

        }
       $res="";
        foreach($filter as $fil){
            $res .= $fil." AND ";
        }
        return $res;
    }
   /*
    * usefull query ::
    * SELECT vehicle.*,vehicle_status.*,avl.SID ,position.*
*from vehicle
 *join (select * from vehicle_status where vehicle_status.validity = 1) vehicle_status
*	join avl on avl.ID = vehicle.avl_ID
*	   join (select * from gps_data order by  recivedate desc limit 1  ) as position on position.SID = avl.SID
*
*/
//Adding Vehicles
function addVehicle($name,$vehicleType_ID,$SID){
    // Check For The SID
    $this->load->model('m_avl');
    $this->load->model('m_vehicle_status');
    $avl_ID=$this->m_avl->addAvl($SID);
    $data=array('name'=>$name
    ,'vehicle_type_ID'=>$vehicleType_ID
    ,'avl_ID'=>$avl_ID
    );
    $this->db->insert('vehicle',$data);
    $vehicleID=$this->db->insert_id();
    $result= array('avl_ID'=>$avl_ID,
    'Vehicle_ID'=>$vehicleID);
    $this->m_vehicle_status->updateStatus(array('vehicleID'=>$vehicleID, 'statusID'=>'1246'));//Set Default Status

    return $result;
}
    
   
	
}
?>
