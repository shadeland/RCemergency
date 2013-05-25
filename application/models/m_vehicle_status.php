<?php
class M_vehicle_status extends CI_Model {
    //We should know every returned Object is an Array with ["ID"] ,....
    //every Vehicle Should have Only One Valid status at time
    function __construct()
    {
        parent::__construct();
    }
   function updateStatus($data){
        if(isset($data['vehicleID'])){
            $fields=array();
            $fields['vehicle_ID']=$data['vehicleID'];
            $fields['status_ID']=$data['statusID'];
            $fields['validity']=1;
            $fields['create_date']= date("Y-m-d H:i:s");
//            TODO level1 :should add Gpsdata and Order Fields
            $currentStatus=self::getStatus($fields['vehicle_ID']);

            if($fields['status_ID']!=$currentStatus['status_ID']){ // If currentStatus was Changed
            $this->db->set("validity",'0')->where("( vehicle_ID = '".$fields['vehicle_ID']."' AND validity = '1')")->update('vehicle_status');//Set Previus Status To Unvalid
            $this->db->set($fields)->insert('vehicle_status');
              return $this->db->insert_id();
            }else{
                return false;
            }

        }else{
            return false;
        }

    }
    function getStatus($vehicleID){
       return $this->db->where("vehicle_ID = $vehicleID AND validity = 1")->get('vehicle_status')->row_array();
    }
    function getStatusByID($statusID){
        return $this->db->where("$statusID == ID AND validity==1")->get('vehicle_status')->row_array();
    }
    
}
    

?>