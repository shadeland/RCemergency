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
        $this->load->model('m_gpsdata');
        return $this->m_gpsdata->getLastPosition($SID)->row_array();
    }
    function getLastPositions($avlID,$LIMIT){
        $SID=self::getSID($avlID);
        $this->load->model('m_gpsdata');
        return $this->m_gpsdata->getLastPositions($SID,$LIMIT)->result_array();
    }

    
   
	
}
?>
