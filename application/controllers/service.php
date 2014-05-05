<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Example
 *
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array.
 *
 * @package		CodeIgniter
 * @subpackage	Rest Server
 * @category	Controller
 * @author		Phil Sturgeon
 * @link		http://philsturgeon.co.uk/code/
*/

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH.'/libraries/REST_Controller.php';

class service extends REST_Controller
{


    function centerTypes_get()
    {
       
        $this->load->model('Centers');
        $data=$this->Centers->getCentersTypes();
        $this->response($data,"200");
        // $user = $this->some_model->getSomething( $this->get('id') );
    	
    }
    function centerTypes_post()
    {
       
        $this->load->model('Centers');
        $id=$this->Centers->addCenterType($this->post("type"));
        $data=array("type" => $this->post("type"),"ID" => $id);
        $this->response($data,"200");
        // $user = $this->some_model->getSomething( $this->get('id') );
    	
    }
    function gpsData_get(){
        $this->load->model("m_gpsdata");
        $data=$this->m_gpsdata->getAll()->result();
        $this->response($data,"200");
    }

    function vehicles_get(){
        $this->load->model('m_vehicles');
        $data=$this->m_vehicles->getVehicles();
        $this->response($data,"200");

    }
    //It has Use for Autocomplete Like Typeahead.js
    function vehiclesList_get(){
        $this->load->model('m_vehicles');
        $query=$this->get('q');
        $filter=array('OID'=>$query);
        $vehicles=$this->m_vehicles->getVehicles("",$filter);
        $data=array();
        foreach($vehicles as $vehicle){
            array_push($data,array("value"=>$vehicle['OID'],"name"=>$vehicle['name'],"ID"=>$vehicle['ID'],"token"=>$vehicle['OID']));

        }
        $this->response($data,"200");

    }
    function vehiclesFullList_get(){
        error_reporting(-1);
        ini_set('display_errors', TRUE);

        $this->load->model('m_vehicles');
        $data=$this->m_vehicles->getVehicleList();

        $this->response($data,"200");

    }
    function vehicleType_get(){
       $data= $this->db->select('*')->get('vehicle_type')->result_array();
        $this->response($data,"200");
    }
    function vehicle_get(){
//        TODO : add search feature
        error_reporting(-1);
        ini_set('display_errors', TRUE);

        $this->load->model('m_vehicles');

        $data=$this->m_vehicles->sendVehicle($this->get('vehicleID'));
        $this->response($data,"200");
    }
    function incidents_get(){
        //        TODO : add search feature
        $this->load->model('m_incident');
        $data=$this->m_incident->getIncidents();
        $this->response($data,"200");
    }
    function incident_post(){
        error_reporting(-1);
        ini_set('display_errors', TRUE);
        $position=$this->post('position');
//        print_r($position['lat'].$position['lon']);
//        print_r($this->post('position'));
        $this->load->model('m_incident');
        $ID=$this->m_incident->createIncident($this->post('type'),$position['lat'],$position['lon'],"","valid",$this->post('descript'),$this->post('address'));
        $data=$this->m_incident->getIncident($ID);
        $this->response($data,"200");
    }
    function incident_get(){

        $position=$this->post('position');
//        print_r($position['lat'].$position['lon']);
//        print_r($this->post('position'));
        $this->load->model('m_incident');
//        $ID=$this->m_incident->createIncident($this->post('type'),$position['lat'],$position['lon'],"","valid",$this->post('descript'));
        $data=$this->m_incident->getIncident($this->get('ID'));
        $this->response($data,"200");
    }
    function vehiclessuggestion_get(){
        $this->load->model('m_vehicle_incident');
        $incident=$this->get('incident');
        $distance=$this->get('distance');
        $status=$this->get('status');

        $data=$this->m_vehicle_incident->findVehiclesForIncident($incident,$status,$distance);
        if(count($data)==0){
            $this->response(null,200);
        }
        $this->response($data,200);
    }
    function vehiclesdedicated_get(){
        $incident=$this->get('incident');
        $this->load->model('m_vehicle_incident');
        $data=$this->m_vehicle_incident->getVehicles($incident);
        if(!$data){
            $this->response(null,200);
        }
        $this->response($data,200);
    }
    function orderrequest_post(){
        error_reporting(-1);
        ini_set('display_errors', TRUE);
        $this->load->model('m_vehicle_incident');
        $incident=$this->post('incident');
        $vehicle=$this->post('vehicle');
        $order=$this->m_vehicle_incident->addVehicle($vehicle,$incident);
        $data['incidnetID']=$incident;
        $data['vehicleID']=$vehicle;
        $data['orderID']=$order;
        $this->response($data,200);
    }
    function ordercancel_post(){
        error_reporting(-1);
        ini_set('display_errors', TRUE);
        $this->load->model('m_order');
        $this->load->model('m_vehicle_incident');
        $this->load->model('');
        $orderID=$this->post('order');

        $vehicle=$this->post('vehicle');
//        $incidnet=$this->m_vehicle_incident->getIncident($vehicle);
        $this->m_order->cancelOrder($orderID);
//        $this->m_vehicle_incident->removeVehicle($vehicle,$incidnet);
        $data=array('status'=>'success');
        $this->response($data,200);


    }
    //Driver From Submition
    function driver_post(){
        $this->load->model('m_driver');
        $fullname=$this->post('fullname');
        $description=$this->post('description');
        $outofservice=$this->post('outofservice');
        $phonenumber=$this->post('phonenumber');
        $vehicleID=$this->post('vehicleID');
       $newID=$this->m_driver->addDriver($fullname,$phonenumber,$description,$outofservice);

        $data=array('id'=>$newID);
        $this->response($data,200);
    }
    function driver_put(){
        $error=array();
        $this->load->model('m_driver');
        $driverID=$this->put('id');
        $fullname=$this->put('fullname');
        $description=$this->put('description');
        $outofservice=$this->put('outofservice');
        $phonenumber=$this->put('phonenumber');
        $vehicleID=$this->put('vehicleID');
        $this->m_driver->editDriver($driverID,$fullname,$phonenumber,$description,$outofservice);
        $error=$this->m_driver->vehicleAssign($driverID,$vehicleID);
        if(count($error)>0){
            $this->response($error,403);
        }
        $data=array(null);
        $this->response($data,200);
    }
    function drivers_get(){
        $this->load->model('m_driver');
        $data=$this->m_driver->getDriversList();
        $this->response($data,200);
    }
    function vehicleList_post(){
        $error=array();
        $this->load->model('m_vehicles');

        $OID=$this->post('OID');
        $type=$this->post('vtype');
        $center=$this->post('center');

        $newID=$this->m_vehicles->addVehicleList($OID,$type,$center);

        if(count($error)>0){
            $this->response($error,403);
        }
        $data=$this->m_vehicles->getVehicleFList($newID);
        $this->response($data,200);
    }
    function vehicleList_put(){
        $error=array();
        $this->load->model('m_vehicles');
        $vehicleID=$this->put('id');
        $OID=$this->put('OID');
        $type=$this->put('vtype');
        $center=$this->put('center');

        $this->m_vehicles->editVehicle($vehicleID,$OID,$type,$center);

        if(count($error)>0){
            $this->response($error,403);
        }
        $data=$this->m_vehicles->getVehicleFList($vehicleID);
        $this->response($data,200);
    }
    function centers_get(){

        error_reporting(-1);
        ini_set('display_errors', TRUE);

        if($this->get('node')!=0){
            $parent=$this->get('node');
        }else{
            $parent='0';
        }

        $this->load->model('m_centers');
//        $this->m_centers->getAll();

        $data=$this->m_centers->getChildren($parent);

        $this->response($data,200);
    }

    
  
}