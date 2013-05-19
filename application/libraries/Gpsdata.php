<?php

class Gpsdata {
    public function parseGetData($get){
        if(!isset($get['SID'])){
            return false;
        }
        try {
            $parsed['SID']= isset($get['SID'])? $get['SID'] : "";
            $parsed['type']=isset($get['TYPE'])? $get['TYPE'] : "" ;
            $date = self::GmtTimeToLocalTime(strtotime($get['TIME']." ".$get['DATE']));
            $parsed['recivedate']=(isset($get['TIME'])&&isset($get['DATE']))?$date:"";
            $parsed['name']=isset($get['NAME'])? $get['NAME'] : "";
            $parsed['lat']=isset($get['LAT'])? $get['LAT'] : "";
            $parsed['lng']=isset($get['LON'])? $get['LON'] : "";
            $parsed['alt']=isset($get['ALT'])? $get['ALT'] : "";
            $parsed['input1']=isset($get['I1'])? $get['I1'] : "";
            $parsed['input2']=isset($get['I2'])? $get['I2'] : "";
            $parsed['input3']=isset($get['I3'])? $get['I3'] : "";
            $parsed['output1']=isset($get['O1'])? $get['O1'] : "";
            $parsed['output2']=isset($get['O2'])? $get['O2'] : "";
            $parsed['output3']=isset($get['O3'])? $get['O3'] : "";
            $parsed['course']=isset($get['COURSE'])? $get['COURSE'] : "";
            $parsed['speed']=isset($get['SPEED'])? $get['SPEED'] : "";
        } catch (Exception $e){
            return false;
        }
        return $parsed;
    }
    function GmtTimeToLocalTime($time) {
        $date =  new DateTime(date('Y-m-d H:i:s',$time),new DateTimezone('UTC'));
        $date->setTimezone(new \DateTimezone('Asia/Tehran'));
        return $date->format("Y-m-d H:i:s");
    }
    function parseStatus($data=""){
//        print_r($data);
        $ref=array();

        $ref['output1']['1']="0";

        $ref['output2']['1']="1";

        $ref['output3']['1']="2";
//        print_r($ref);
        foreach($ref as $key => $value){
            if($data[$key]=="1"){
                return $ref[$key]['1'];
            }

        }

    }
    function calcDistance($lat1,$lon1,$lat2,$lon2){
        $r=6378;
        $pi=atan2(1,1) * 4;
        $a1=$lat1*($pi/180);
        $a2=$lat2*($pi/180);
        $b1=$lon1*($pi/180);
        $b2=$lon2*($pi/180);
       $distance= acos(cos($a1)*cos($b1)*cos($a2)*cos($b2) + cos($a1)*sin($b1)*cos($a2)*sin($b2) + sin($a1)*sin($a2)) * $r;
//        echo "\n $distance";
        return $distance;
    }
}
?>
