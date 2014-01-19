<?php
class M_incident extends CI_Model {
    //We should know every returned Object is an Array with ["ID"] ,....
    function __construct()
    {
        parent::__construct();
    }
    function createIncident($incidentType,$pos_lat,$pos_lon,$pos_alt="",$validity,$descript,$address){
//        print_r("slalan".$pos_lat);
         $this->load->model('m_position');
        $positionID=$this->m_position->createPosition($pos_lat,$pos_lon,$pos_alt);
        $this->db->set(array('incident_type_ID' => $incidentType,'position_ID' => $positionID,'validity' => $validity,'descript'=>$descript,'address'=>$address,'create_date'=>date("Y-m-d H:i:s")))->insert('incident');
        return $this->db->insert_id();
    }
    function  getIncident($incidentID){
        $this->load->model('m_position');
        $incident=$this->db->select('incident.* ,incident_type.type as type')->where('incident.ID',$incidentID)->join('incident_type','incident.incident_type_ID=incident_type.ID')->get('incident')->row_array();

        $position=$this->m_position->getPosition($incident['position_ID']);

        $incident['position']=$position;
        return $incident;
    }
    function getIncidents($filter=""){

//        TODO : filter Should Implrement
            $incidents=$this->db->select('ID')->get('incident')->result_array();
            $data=array();
            foreach($incidents as $incident){
                $incidentData=self::getIncident($incident['ID']);
                array_push($data,$incidentData);
            }
            return $data;


    }
    
}
    

?>