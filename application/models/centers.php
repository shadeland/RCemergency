<?php
class Centers extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }
    
     function getCentersTypes(){
        $res=$this->db->get('center_type');
        return $res->result();
        
    }
    function addCenterType($type){
        $this->db->set("type", $type)->insert("center_type");
        return $this->db->insert_id();
        
    }
    function getCenters($parent="0"){
        $query=$this->db->where('parent',$parent)->get('center');
        return $query;
    }
   
	
}
?>
