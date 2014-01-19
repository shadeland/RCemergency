<?php
class M_driver extends CI_Model {
    //We should know every returned Object is an Array with ["ID"] ,....
    function __construct()
    {
        parent::__construct();
    }


    /**
     * Add Driver To Db
     * @param $driverName string
     * @param $driverPhonenumber string
     */
    function addDriver($driverName,$driverPhonenumber,$description,$outofservice){
        if($driverName!="" && $driverPhonenumber!= ""){
            $this->db->set(array('fullname'=>$driverName,'phonenumber'=>$driverPhonenumber,'description'=>$description,'outofservice'=>$outofservice))->insert('drivers');
            return $this->db->insert_id();
        }
    }

    function getDriversList(){
//        TODO : Add filtering feature
        return $this->db->query("SELECT drivers.ID as id ,fullname,phonenumber,vehicleID,description,outofservice FROM drivers left join vehicle_driver on vehicle_driver.driverID=drivers.ID ")->result_array();
    }

    function editDriver($driverID,$driverName,$driverPhonenumber,$description,$outofservice){
        if($driverName!="" && $driverPhonenumber!= ""){
            $this->db->where('ID',$driverID)->set(array('fullname'=>$driverName,'phonenumber'=>$driverPhonenumber,'description'=>$description,'outofservice'=>$outofservice))->update('drivers');
            return  $this->db->affected_rows();
        }
    }
    function vehicleAssign($driverID,$vehicleID){
        $error=array();
        $query=array('driverID'=>$driverID,'vehicleID'=>$vehicleID);
//        No Change In Vehicle
        if($this->db->where($query)->where('validity','1')->from('vehicle_driver')->count_all_results()>0){
            return($error);
        }
//       Vehicle Has Already assign to another driver
        elseif($this->db->where('vehicleID',$query['vehicleID'])->where('validity','1')->from('vehicle_driver')->count_all_results()>0){
            array_push($error,array("error"=>"Vehicle has already assined to another driver"));
            return ($error);
        }else{
            $query['validity']=1;
            $query['assigndate']=date('Y-m-d H:i:s');
            $this->db->set($query)->insert('vehicle_driver');
            return ($error);
        }

    }
    ///////////Report function////
    function getDriverList(){
        $data=$this->db->select('ID as id ,fullname')->get('drivers')->result_array();
       return $data;
    }
   
	
}
?>
