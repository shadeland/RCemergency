<?php
class M_vehicle_incident extends CI_Model {
    //We should know every returned Object is an Array with ["ID"] ,....
    function __construct()
    {
        parent::__construct();
    }
    function findVehiclesForIncident($incident,$status,$distance=""){
        if($distance==""){
            $distance=50;
        }
//        TODO too many Connection;
        $this->load->model('m_incident');
        $incidnet=$this->m_incident->getIncident($incident);
//        print_r($incidnet);
            $this->load->model("m_vehicles");
        $vehicles=$this->m_vehicles->getVehicleWithStatus($status);
        $result=array();
        $distances=array();
        foreach($vehicles as $vehicle){
            $vehicleDistance=$this->m_vehicles->getVehicleDistance($vehicle['ID'],$incidnet['position']);
            if($vehicleDistance<$distance){
//                print_r($vehicle);
                $distances[$vehicle['ID']]=$vehicleDistance;
                array_push($result,$vehicle);
            };

        }

        $vehicleObjects= $this->m_vehicles->getVehicles($result);
        foreach($vehicleObjects as  $key => $obj){
//            print_r($distances);

            $vehicleObjects[$key]['distance']=$distances[$obj['ID']];
//           print_r($obj);
        }
        return $vehicleObjects;


    }
    function addVehicle($vehicleID,$incidentID){
        $this->load->model('m_order');
        $this->load->model('m_vehicle_status');
        $this->db->set(array('vehicle_ID' => $vehicleID,'incident_ID'=>$incidentID,'validity' => '1','register_date'=>date('Y-m-d H:i:s')));
        $this->db->insert('vehicle_incident');
        //Add Order To vehicle Orders
        $orderData['vehicle_ID']=$vehicleID;
        $orderData['incident_ID']=$incidentID;
        $orderID=$this->m_order->createOrder($orderData);
        //Change Vehicle Status
        $this->m_vehicle_status->updateStatus(array('vehicleID' => $vehicleID ,'statusID'=>1));
        return $orderID;
    }
    function removeVehicle($vehicleID,$incident){

    }
    function getVehicles($incident){

    }
    
}
    

?>