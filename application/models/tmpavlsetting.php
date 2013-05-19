<?php
class Tmpavlsetting extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }
    
     function getLastRequest(){
        $res=$this->db->where("pupdate","0000-00-00")->order_by("pushdate","asc")->limit("1")->get('tmp_request');
        return $res;
        
    }
    function addRequest($setting_string){
        $this->db->set("string", $setting_string)->set("pushdate",date("Y-m-d H:i:s"))->insert("tmp_request");
        return $this->db->insert_id();
        
        
    }
    function closeRequest($id){
        return $this->db->where('ID',$id)->set("pupdate",date("Y-m-d H:i:s"))->update("tmp_request");
    }
   
	
}
?>
