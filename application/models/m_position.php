<?php
class M_position extends CI_Model {
    //We should know every returned Object is an Array with ["ID"] ,....
    function __construct()
    {
        parent::__construct();
    }

    function createPosition($lat="",$lon="",$alt=""){
            $this->db->set(array('lat' => $lat,'lon' => $lon,'alt' => $alt))->insert('position');
            return $this->db->insert_id();
    }
    function getPosition($positionID){
        return    $this->db->where('ID',$positionID)->get('position')->row_array();

    }
}
    

    

?>