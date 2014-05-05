<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class kml_service extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
       $vhicleID=$this->input->get('vehicleID');
        $this->load->model('m_vehicles');
        $data=$this->m_vehicles->getLastPositions($vhicleID,10);
        $this->dataProxy(false,false,false,$data);
//        // Creates the Document.
//        $dom = new DOMDocument('1.0', 'UTF-8');
//
//// Creates the root KML element and appends it to the root document.
//        $node = $dom->createElementNS('http://earth.google.com/kml/2.1', 'kml');
//        $parNode = $dom->appendChild($node);
//
//// Creates a KML Document element and append it to the KML element.
//        $dnode = $dom->createElement('Document');
//        $docNode = $parNode->appendChild($dnode);
//        $node = $dom->createElement('Placemark');
//        $placeNode = $docNode->appendChild($node);
//        $lnode=$dom->createElement('LineString');
//        $placeNode->appendChild($lnode);
//        $coor="";
//
//    foreach( $data as $d){
//        $coor.=$d['lng']." , ".$d['lat']." , 0 \n";
//
//    }
//        $coorNode=$dom->createElement('coordinates',$coor);
//        $lnode->appendChild($coorNode);
//        $kmlOutput = $dom->saveXML();
//        header('Content-type: application/vnd.google-earth.kml+xml');
//        echo $kmlOutput;
    }
    public function order($orderID){
       echo <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<kml xmlns="http://earth.google.com/kml/2.2">
<Document>
  <name>masir</name>
  <description><![CDATA[]]></description>
  <Style id="style3">
    <LineStyle>
      <color>73FF0000</color>
      <width>5</width>
    </LineStyle>
  </Style>
   <Style id="style5">
    <IconStyle>
      <Icon>
        <href>http://maps.gstatic.com/mapfiles/ms2/micons/red-dot.png</href>
      </Icon>
    </IconStyle>
  </Style>
   <Style id="style4">
    <IconStyle>
      <Icon>
        <href>http://maps.gstatic.com/mapfiles/ms2/micons/green-dot.png</href>
      </Icon>
    </IconStyle>
  </Style>
  <Style id="style1">
    <IconStyle>
      <Icon>
        <href>http://maps.gstatic.com/mapfiles/ms2/micons/blue-dot.png</href>
      </Icon>
    </IconStyle>
  </Style>
  <Style id="style2">
    <IconStyle>
      <Icon>
        <href>http://maps.gstatic.com/mapfiles/ms2/micons/blue-dot.png</href>
      </Icon>
    </IconStyle>
  </Style>
  <Placemark>
    <name>masir</name>
    <description><![CDATA[]]></description>
    <styleUrl>#style3</styleUrl>
    <LineString>
      <tessellate>1</tessellate>
      <coordinates>
        51.261322,35.666084,0.000000
        51.264240,35.664410,0.000000
        51.263039,35.654785,0.000000
        51.235058,35.665524,0.000000
        51.232483,35.683098,0.000000
        51.253769,35.706936,0.000000
        51.252396,35.716133,0.000000
        51.215660,35.718643,0.000000
        51.153004,35.742054,0.000000
        51.104595,35.743168,0.000000
        51.040394,35.774094,0.000000
        51.001942,35.791084,0.000000
        50.968216,35.798882,0.000000
        50.890625,35.735924,0.000000
        50.763596,35.704704,0.000000
        50.974396,35.656738,0.000000
        51.124084,35.647812,0.000000
        51.165283,35.643349,0.000000
        51.220215,35.687977,0.000000
        51.245621,35.684628,0.000000
        51.273773,35.675705,0.000000
        51.262787,35.671803,0.000000
      </coordinates>
    </LineString>
  </Placemark>
   <Placemark>
    <name>پایان</name>
    <description><![CDATA[<div dir="rtl">نطقه پایان</div>]]></description>
    <styleUrl>#style5</styleUrl>
    <Point>
      <coordinates> 51.262787,35.671803,0.000000</coordinates>
    </Point>
  </Placemark>
  <Placemark>
    <name>آغاز</name>
    <description><![CDATA[<div dir="rtl">نقطه آغاز</div>]]></description>
    <styleUrl>#style4</styleUrl>
    <Point>
      <coordinates>51.261322,35.666084,0.000000</coordinates>
    </Point>
  </Placemark>
  <Placemark>
    <name>توقف</name>
    <description><![CDATA[<div dir="rtl">به مدت 1 ساعت</div>]]></description>
    <styleUrl>#style1</styleUrl>
    <Point>
      <coordinates>50.763702,35.700802,0.000000</coordinates>
    </Point>
  </Placemark>
  <Placemark>
    <name>توقف</name>
    <description><![CDATA[<div dir="rtl">به مدت 0.5 ساعت</div>]]></description>
    <styleUrl>#style2</styleUrl>
    <Point>
      <coordinates>51.166763,35.643349,0.000000</coordinates>
    </Point>
  </Placemark>
</Document>
</kml>

EOT;
    }
    function dataProxy($ID,$startDate,$endDate,$points=false){
        $this->load->library('gpsdata');
        $this->load->model('m_vehicles');
        if(!$points){
        $vehicle=$this->m_vehicles->sendVehicle($ID);
        $SID=$vehicle['SID'];
        $startDate=urldecode($startDate);
        $endDate=urldecode($endDate);
        $query=$this->db->select('*')->where("SID = $SID AND recivedate >= '$startDate' AND recivedate <= '$endDate'")->order_by('recivedate ASC')->get('gps_data');
        $res=$query->result_array();
        $len=$query->num_rows();
//        print_r($res);

        }else{
            $res=$points;//Points Provided
            $len=count($points);
        }
        if(!$res && count($res)==0){
            return false;
        }
        $point=array();
        $sumpoint=array('lat'=>'0','lng'=>'0','duration'=>0,'desc'=>0);
        for($i = 0 ; $i<$len;$i++){

            if($this->gpsdata->calcDistance($res[$i]['lat'],$res[$i]['lng'],$sumpoint['lat'],$sumpoint['lng'])>0.01){
            //if new point
                if($sumpoint['lat']!='0'){//not first one
                    array_push($point,$sumpoint);
                }
                $sumpoint=$res[$i];
                $sumpoint['duration']=0;
                $sumpoint['desc']=0;
                $sumpoint['lasttime']=$res[$i]['recivedate'];
            }else{//in the same ponint
//
                $sumpoint['duration']= $sumpoint['duration']+((strtotime($res[$i]['recivedate'])-strtotime($sumpoint['lasttime'])));
                $sumpoint['desc']=$sumpoint['desc']+1;
                $sumpoint['lasttime']=$res[$i]['recivedate'];
                //if first stop
                //if first move
            }
            // if last one


        }
        $this->kmlGen($point);

    }
    function kmlGen($points){

        require_once(APPPATH.'libraries/KML.php');
        $this->load->library('gpsdata');
        $this->load->library('jalaliDate');
        $kml = new KML('TETRIS');

        $document = new KMLDocument('tetris', 'TETRIS');

        /**
         * Style definitions
         */

        $style = new KMLStyle('stop');
        $style->setIconStyle('http://maps.gstatic.com/mapfiles/ms2/micons/red-dot.png', 'ffffffff', 'normal', 1);
        $style->setLineStyle('ffffffff', 'normal', 2);
        $document->addStyle($style);

        $style = new KMLStyle('point');
        $style->setIconStyle('http://maps.gstatic.com/mapfiles/ms2/micons/blue-dot.png', 'ffffffff', 'normal', 1);
        $style->setLineStyle('ff0000ff', 'normal', 3);
        $document->addStyle($style);
        $style = new KMLStyle('start');
        $style->setIconStyle('http://maps.gstatic.com/mapfiles/ms2/micons/green-dot.png', 'ffffffff', 'normal', 1);
        $style->setLineStyle('ff0000ff', 'normal', 3);
        $document->addStyle($style);
        $style = new KMLStyle('end');
        $style->setIconStyle('http://maps.gstatic.com/mapfiles/ms2/micons/yellow-dot.png', 'ffffffff', 'normal', 1);
        $style->setLineStyle('ff0000ff', 'normal', 3);
        $document->addStyle($style);

        $style = new KMLStyle('plotStyle');
        $style->setLineStyle('73FF0000', 'normal', 5);
        $document->addStyle($style);

        $style = new KMLStyle('portStyle');
        $style->setIconStyle('images/port.png');
        $document->addStyle($style);

        $style = new KMLStyle('polyStyle');
        $style->setPolyStyle('660000ff');
        $document->addStyle($style);
        $trackpoint=array();


        $vehicleFolder = new KMLFolder('', 'Vehicle');
        $i=0;
        $pstyle="";
        foreach($points as $p){
            if($p['duration']!=0){//Stop point

                array_push($trackpoint,array($p['lng'],$p['lat'],0));
                $dur=$this->gpsdata->secondsToTime($p['duration']);
                $desc=" توقف به مدت ".$dur['d']." روز ". $dur['h']." ساعت ".$dur['m']." دقیقه ";
                $desc.="<br>";
                $desc.= "از تاریخ ". $this->jalalidate->mds_date('Y/m/d H:i:s',strtotime($p['recivedate']));
                $desc.="<br>";
                $desc.= "تا تاریخ  ". $this->jalalidate->mds_date('Y/m/d H:i:s',strtotime($p['lasttime']));
                $point = new KMLPlaceMark('', 'point',$desc, true);
                $point->setGeometry(new KMLPoint($p['lng'], $p['lat'], 0));
                if($i==0){
                    $pstyle="#start";
                }elseif($i== count($points)-1){
                    $pstyle="#end";
                }else{
                    $pstyle="#stop";
                }
                $point->setStyleUrl($pstyle);
                $vehicleFolder->addFeature($point);
            }else{

                array_push($trackpoint,array($p['lng'],$p['lat'],0));


                $desc= "در تاریخ". $this->jalalidate->mds_date('Y/m/d H:i:s',strtotime($p['recivedate']));
                $desc.="<br>";
                $desc.= "سرعت ".($p['speed']*3.6)." کیلومتر";
            $point = new KMLPlaceMark('', 'point', $desc, true);
            $point->setGeometry(new KMLPoint($p['lng'], $p['lat'], 0));
            if($i==0){
              $pstyle="#start";
            }elseif($i== count($points)-1){
                $pstyle="#end";
            }else{
                $pstyle="#point";
            }
            $point->setStyleUrl($pstyle);
            $vehicleFolder->addFeature($point);
            }
            $i++;
        }
        //Add Tracking Line
        $vehicleTrace = new KMLPlaceMark(null, 'Suivi de piste', '', true);
        $vehicleTrace->setGeometry (new KMLLineString( $trackpoint), true, '', true);

        $vehicleTrace->setStyleUrl('#plotStyle');
        $vehicleFolder->addFeature($vehicleTrace);

//
//
//
//        /**
//         * Boat adds
//         */
//        $boatFolder = new KMLFolder('', 'ESPERANZA');
//
////Ajout Infos sur le bateau
//        $boatDetail = new KMLPlaceMark('', 'ESPERANZA', 'Un bateau de greenpeace', true);
//        $boatDetail->setGeometry(new KMLPoint(-1, 2, 0));
//        $boatDetail->setStyleUrl('#boatStyle');
//
//        $style = new KMLStyle();
//        $style->setBalloonStyle ('Bateau suspecté de piraterie...');
//        $boatDetail->addStyle($style);
//        $boatFolder->addFeature($boatDetail);
//
//
//
////Ajout des pistes
//        $boatTrace = new KMLPlaceMark(null, 'Suivi de piste', '', true);
//        $boatTrace->setGeometry (new KMLLineString( Array (
//                array ( 4, 3,0),
//                array ( 2, 4,0),
//                array (-1, 3,0),
//                array (-1, 2,0)
//            ), true, '', true)
//        );
//        $boatTrace->setTimePrimitive(new KMLTimeStamp('','2008-05-01','2008-05-25'));
//        $boatTrace->setStyleUrl('#boatStyle');
//        $boatFolder->addFeature($boatTrace);
//
////Ajout de l'historique des positions
//        $boatHistoFolder = new KMLFolder('', 'Historique des positions');
//
//
//        $boatFollow = new KMLPlaceMark('', '1', '', true);
//        $boatFollow->setGeometry(new KMLPoint( 4, 3, 0));
//        $boatFollow->setStyleUrl('#plotStyle');
//        $boatFollow->setTimePrimitive(new KMLTimeStamp('','2008-05-01'));
//        $boatHistoFolder->addFeature($boatFollow);
//
//        $boatFollow = new KMLPlaceMark('', '2', '', true);
//        $boatFollow->setGeometry(new KMLPoint( 2, 4, 0));
//        $boatFollow->setStyleUrl('#plotStyle');
//        $boatFollow->setTimePrimitive(new KMLTimeStamp('','2008-05-05'));
//        $boatHistoFolder->addFeature($boatFollow);
//
//        $boatFollow = new KMLPlaceMark('', '3', '', true);
//        $boatFollow->setGeometry(new KMLPoint(-1, 3, 0));
//        $boatFollow->setStyleUrl('#plotStyle');
//        $boatFollow->setTimePrimitive(new KMLTimeStamp('','2008-05-15'));
//        $boatHistoFolder->addFeature($boatFollow);
//
//        $boatFollow = new KMLPlaceMark('', '4', '', true);
//        $boatFollow->setGeometry(new KMLPoint(-1, 2, 0));
//        $boatFollow->setStyleUrl('#plotStyle');
//        $boatFollow->setTimePrimitive(new KMLTimeStamp('','2008-05-25'));
//        $boatHistoFolder->addFeature($boatFollow);
//
//
//        $boatFolder->addFeature($boatHistoFolder);
//
////Ajout du bateau à la liste
//        $boatListFolder->addFeature($boatFolder);
//
//        /**
//         * Ajout d'un port
//         */
//
//        $portFolder = new KMLFolder('', 'Ports');
//
//
//        $port = new KMLPlaceMark('', 'Brest');
//        $port->setGeometry(new KMLPoint(-1.5, 5,0));
//        $port->setStyleUrl('#portStyle');
//        $portFolder->addFeature($port);
//
//        $port = new KMLPlaceMark('', 'Le Havre');
//        $port->setGeometry(new KMLPoint(5, 5,0));
//        $port->setStyleUrl('#portStyle');
//        $portFolder->addFeature($port);
//
//
//
//
//        /**
//         * Ajout d'une zone
//         */
//        $areaFolder = new KMLFolder('', 'Zones');
//
//        $mediterranee = new KMLPlaceMark('', 'Mediterranee');
//        $mediterranee->setGeometry (new KMLPolygon( Array (
//                array ( 2, 0,0),
//                array (-4, 0,0),
//                array (-5, 5,100),
//                array ( 1, 5,0),
//                array ( 2, 0,0)
//            ), true, '', true)
//        );
//
//        $mediterranee->setStyleUrl('#polyStyle');
//
//        $areaFolder->addFeature($mediterranee);

        $document->addFeature($vehicleFolder);
//        $document->addFeature($portFolder);
//        $document->addFeature($areaFolder);

        /**
         * Ajout du répertoire
         */
        $kml->setFeature($document);




        /**
         * Output result
         */

//echo '<pre>';
//echo htmlspecialchars($kml->output('S'));
//echo '</pre>';

//echo $kml->output('S');
       echo $kml->output('S');

//        $kml->output('F', 'output/test.kml');
//        $kml->output('Z', 'output/test.kmz');

    }
//    public function order($orderID)
//    {
//        $query="SELECT od.*,
//gpsstart.lat startlat,gpsstart.lng startlng,gpsstart.recivedate startdate,
//gpsend.lat endlat,gpsend.lng endlng,gpsend.recivedate enddate,
//gpsdest.lng destlng,gpsdest.lat destlat,gpsdest.recivedate destdate,
//vid.driverID driverID
//,drivers.fullname drivername
//,vehicle.OID vehicleOID,vehicle_type.lang_fa vehicletype
//,avl.SID SID
// from orders od
//left join (SELECT ic.ID as id , it.type as type from incident ic left join incident_type it on ic.incident_type_ID = it.ID) as ic
//on	od.incident_ID = ic.ID
//left join (select vd1.ID vdID,vd1.driverID ,vd1.vehicleID vehID,vd1.assigndate as ass1,IFNULL(min(vd2.assigndate),'2020-01-11 00:00:00') ass2 from vehicle_driver as vd1
//left join vehicle_driver as vd2 on vd1.vehicleID = vd2.vehicleID and vd1.assigndate < vd2.assigndate  and vd1.driverID <> vd2.driverID
//group by vd1.ID) vid
//on od.vehicle_ID = vid.vehID AND od.submit_date > vid.ass1 AND od.submit_date < vid.ass2
//left join vehicle on vehicle.ID=od.vehicle_ID
//left join vehicle_type on vehicle.vehicle_type_ID=vehicle_type.ID
//left join drivers on vid.driverID = drivers.ID
//left join avl on vehicle.avl_ID=avl.ID
//left join gps_data gpsstart on od.startpoint=gpsstart.ID
//left join gps_data gpsdest on od.startpoint=gpsdest.ID
//left join gps_data gpsend on od.startpoint=gpsend.ID where od.ID = '$orderID'";
//        $result=$this->db->query($query)->row_array();
//
//        $data=$this->db->query("SELECT * from gps_data where ID >  ".$result['startpoint']." AND  ID < ".((is_null($result['endpoint']))?"100000000":$result['endpoint'])." AND SID = ".$result['SID']." ORDER BY recivedate")->result_array();
////           echo $this->db->last_query();
//
////        $this->load->model('m_vehicles');
////        $data=$this->m_vehicles->getLastPositions($vhicleID,5);
//        // Creates the Document.
//        $dom = new DOMDocument('1.0', 'UTF-8');
//
//// Creates the root KML element and appends it to the root document.
//        $node = $dom->createElementNS('http://earth.google.com/kml/2.1', 'kml');
//        $parNode = $dom->appendChild($node);
//
//// Creates a KML Document element and append it to the KML element.
//        $dnode = $dom->createElement('Document');
//        $docNode = $parNode->appendChild($dnode);
//        $node = $dom->createElement('Placemark');
//        $placeNode = $docNode->appendChild($node);
//        $lnode=$dom->createElement('LineString');
//        $placeNode->appendChild($lnode);
//        $coor="";
//
//        foreach( $data as $d){
//            $coor.=$d['lng']." , ".$d['lat']." , 0 \n";
//
//        }
//        $coorNode=$dom->createElement('coordinates',$coor);
//        $lnode->appendChild($coorNode);
//        $kmlOutput = $dom->saveXML();
//        header('Content-type: application/vnd.google-earth.kml+xml');
//        echo $kmlOutput;
//    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */