<?php
class M_order extends CI_Model {
    //We should know every returned Object is an Array with ["ID"] ,....
    function __construct()
    {
        parent::__construct();
    }
    function createOrder($data){
        $this->load->library('sms');
        $this->load->model('m_vehicles');
        $this->load->model('m_incident');
        $vehicleDaya=$this->m_vehicles->sendVehicle($data['vehicle_ID']);
        $incidnetData=$this->m_incident->getIncident($data['incident_ID']);
        $vphone=$vehicleDaya['vphonenumber'];
        $dphone=$vehicleDaya['driver']->phonenumber;
        $address=$incidnetData['address'];

        ///Send SMS To Device To Switch Light On
        $this->sms->simpleEnqueueSample($vphone,"@gprs;O1=0;");
      $this->sms->simpleEnqueueSample($dphone,$address);
        $data['submit_date']=date('Y-m-d H:i:s');
        $data['admin']="1";

        $this->db->set($data);
        $this->db->insert('order');

        return $this->db->insert_id();
    }

    /**
     *Has Order
     * check orders for vehicle and return False or Order Data
     * @param $vehicleID
     * @return bool
     *
     */
    function hasOrder($vehicleID){
      $order=$this->db->select('*')->where('vehicle_ID',$vehicleID)->where("send_date IS NULL")->limit(1)->order_by('submit_date','ASC')->get('orders')->row_array();
        if(isset($order['ID'])){
            return $order;
        }
        return false;
    }
    function getOrder($orderID){
        $data=$this->db->where('ID',$orderID)->get('orders')->row_array();
        return $data;
    }
    function makeSended($orderID){
        $this->db->set('send_date',date('Y-m-d H:i:s'))->where('ID',$orderID)->update('orders');
    }


    /**
     * send order to output
     * @param $orderID
     */
    function sendOrder($orderID){
        $order=self::getOrder($orderID);
        self::makeSended($orderID);
        return $order;

    }

    /**
     * Cancel ORDER
     * IF OrderID Not Set It Will Cancel Last Order
     * return true if it was successful else false
     * @param string $orderID
     * @return boolean
     */
    function cancelOrder($orderID=""){
        $this->db->where('ID',$orderID)->delete('orders');
        if ($this->db->affected_rows()==1){
            return true;
        }else{
            return false;
        }

    }
//    Reports
//    function getOrderList(){
//        /*
//        $data=$this->db->select('vehicle.OID as OID , incident.type');
//        ->get('orders')->result_array();
//SELECT * from orders od
//left join (SELECT ic.ID as id , it.type as type from incident ic left join incident_type it on ic.incident_type_ID = it.ID) as ic
//on	od.incident_ID = ic.ID
//left join (select vd1.ID vdID,vd1.driverID ,vd1.vehicleID vehID,vd1.assigndate as ass1,IFNULL(min(vd2.assigndate),'2020-01-11 00:00:00') ass2 from vehicle_driver as vd1
//left join vehicle_driver as vd2 on vd1.vehicleID = vd2.vehicleID and vd1.assigndate < vd2.assigndate  and vd1.driverID <> vd2.driverID
//group by vd1.ID) vid
//on od.vehicle_ID = vid.vehID AND od.submit_date > vid.ass1 AND od.submit_date < vid.ass2
//        return $data;سبی
//    }
    
}
    

?>