<?php
class M_order extends CI_Model {
    //We should know every returned Object is an Array with ["ID"] ,....
    function __construct()
    {
        parent::__construct();
    }
    function createOrder($data){
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
      $order=$this->db->select('*')->where('vehicle_ID',$vehicleID)->where("send_date IS NULL")->limit(1)->order_by('submit_date','ASC')->get('order')->row_array();
        if(isset($order['ID'])){
            return $order['ID'];
        }
        return false;
    }
    function getOrder($orderID){
        $data=$this->db->where('ID',$orderID)->get('order')->row_array();
        return $data;
    }
    function makeSended($orderID){
        $this->db->set('send_date',date('Y-m-d H:i:s'))->where('ID',$orderID)->update('order');
    }
    function cancelOrder(){

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
    
}
    

?>