<?php
class M_avl extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }
    


    function getSID($avlID){
//       $this->load->model('m_gpsdata');
    return $this->db->select('SID')->where('ID',$avlID)->get('avl')->row()->SID;
    }
    function getLastPosition($avlID){
        $SID=self::getSID($avlID);
        return $this->db->where('SID',$SID)->order_by('ID','desc')->limit(1)->get('gps_data')->row_array();
    }

    
   
	
}
?>
