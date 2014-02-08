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
        $this->db->insert('orders');

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
      $order=$this->db->select('*')->where('vehicle_ID',$vehicleID)->where("endpoint IS NULL")->limit(1)->order_by('submit_date','ASC')->get('orders')->row_array();
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
    function getIncident($orderID){
        $data=$this->db->query("SELECT i.*,p.lat,p.lon from orders left join incident i on orders.incident_ID = i.ID left join position p on i.position_ID=p.ID where orders.ID = $orderID")->row_array();
        return $data;
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

        $this->db->where('ID',$orderID['ID'])->delete('orders');
        if ($this->db->affected_rows()==1){
            return true;
        }else{
            return false;
        }

    }
//    Reports
    function getOrderList($type,$value,$date1,$date2){
        $this->load->library('gpsdata');
        $this->load->library('jalaliDate');
        $query="SELECT od.*,
gpsstart.lat startlat,gpsstart.lng startlng,gpsstart.recivedate startdate,
gpsend.lat endlat,gpsend.lng endlng,gpsend.recivedate enddate,
gpsdest.lng destlng,gpsdest.lat destlat,gpsdest.recivedate destdate,
vid.driverID driverID
,drivers.fullname drivername
,vehicle.OID vehicleOID,vehicle_type.lang_fa vehicletype
 from orders od
left join (SELECT ic.ID as id , it.type as type from incident ic left join incident_type it on ic.incident_type_ID = it.ID) as ic
on	od.incident_ID = ic.ID
left join (select vd1.ID vdID,vd1.driverID ,vd1.vehicleID vehID,vd1.assigndate as ass1,IFNULL(min(vd2.assigndate),'2020-01-11 00:00:00') ass2 from vehicle_driver as vd1
left join vehicle_driver as vd2 on vd1.vehicleID = vd2.vehicleID and vd1.assigndate < vd2.assigndate  and vd1.driverID <> vd2.driverID
group by vd1.ID) vid
on od.vehicle_ID = vid.vehID AND od.submit_date > vid.ass1 AND od.submit_date < vid.ass2
left join vehicle on vehicle.ID=od.vehicle_ID
left join vehicle_type on vehicle.vehicle_type_ID=vehicle_type.ID
left join drivers on vid.driverID = drivers.ID
left join gps_data gpsstart on od.startpoint=gpsstart.ID
left join gps_data gpsdest on od.startpoint=gpsdest.ID
left join gps_data gpsend on od.startpoint=gpsend.ID ";
        if($type=="driver"){
            $query=$query."where driverID = '$value'";
        }
        if($type=="vehicle"){
            $query=$query."where driverID = '$value'";
        }

        $result=$this->db->query($query)->result_array();
        foreach($result as $key=>$r){
            $result[$key]['datesubmit']=$this->jalalidate->mds_date('Y/m/d',strtotime($r['submit_date']));
            $result[$key]['maplink']="<a class='popup' href=ordermap.php?orderID=". $result[$key]['ID']."&lat=".$r['startlat']."&lon=".$r['startlng'].">Map</a>";
            $result[$key]['status']="عدم شروع";
            if(!is_null($r['startlat'])){
                $result[$key]['datestart']=$this->jalalidate->mds_date('Y/m/d',strtotime($r['startdate']));
                $result[$key]['status']="شروع حرکت";
            }
            if(!is_null($r['destlat'])){
                $result[$key]['datedest']=$this->jalalidate->mds_date('Y/m/d',strtotime($r['destdate']));
                $result[$key]['status']="به مقصد رسیده";
            }
            if(!is_null($r['endlat'])){
                $result[$key]['dateend']=$this->jalalidate->mds_date('Y/m/d',strtotime($r['enddate']));
                $result[$key]['status']="پایان یافته";
                $result[$key]['distance']=$this->gpsdata->calcDistance($r['startlat'],$r['startlng'],$r['destlat'],$r['destlng'])+$this->gpsdata->calcDistance($r['endlat'],$r['endlng'],$r['destlat'],$r['destlng']);
            }

        }



        return $result;
    }
function setStartPoint($orderID,$gpsID){
    $this->db->set('startpoint',$gpsID)->where('ID',$orderID)->update('orders');
}
    function setDestPoint($orderID,$gpsID){
        $this->db->set('destpoint',$gpsID)->where('ID',$orderID)->update('orders');
    }
    function setEndPoint($orderID,$gpsID){
        $this->db->set(array('endpoint'=>$gpsID,'status'=>'5'))->where('ID',$orderID)->update('orders');
    }
    
}
    

?>