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
    function addDriver($driverName,$driverPhonenumber,$description){
        if($driverName!="" && $driverPhonenumber!= ""){
            $this->db->set(array('fullname'=>$driverName,'phonenumber'=>$driverPhonenumber,'description'=>$description,'outofservice'=>0))->insert('drivers');
            return $this->db->insert_id();
        }
    }

    function getDriversList(){
//        TODO : Add filtering feature
        return $this->db->query("SELECT drivers.ID as id ,fullname,phonenumber,vehicleID FROM drivers left join vehicle_driver on vehicle_driver.driverID=drivers.ID ")->result_array();
    }

    function editDriver($driverID,$driverName,$driverPhonenumber,$description,$outOfService){
        if($driverName!="" && $driverPhonenumber!= ""){
            $this->db->where('ID',$driverID)->set(array('fullname'=>$driverName,'phonenumber'=>$driverPhonenumber))->update('drivers');
            return  $this->db->affected_rows();
        }
    }
   
	
}
?>
