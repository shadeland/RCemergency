<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class gpstool {
    public $gpsdata;
    public function parse($data)
    {   
        // To parse Recieved Data ,and return it as an Array of Segments ;
        // Data is Like (((((000003,E5124.215306,N3540.949062,1166.832520,20130114185702.0.93049300000,280.094666000,0000000000000000)
        // which is (((((Device Number , Longitude,Latitude ,?,?,?,?)
        if(isset ($data) && preg_match("/^\({5}([\w\.]*,?){7}\)$/", $data)){// Check wether data is in vali format or Else Return flase
            preg_match_all("/([\w\.]*)[,&\)]/", $data,$result);
            
            $gpsdata['deviceID']=$result[1][0];
            $gpsdata['long']=$this->DMmtoDd($result[1][1]);
            $gpsdata['lat']=  $this->DMmtoDd($result[1][2]);
            $gpsdata['amsl']=$result[1][3];
            $gpsdata['date']=$this->formatDate($result[1][6]);
            $gpsdata['direction']=$result[1][5];
            $gpsdata['speed']=$result[1][4];
            $gpsdata['message']="";
            return $gpsdata;
        }else{
            return false ;
        }
        
    }
    public function DMmtoDd($dmm){
        if(preg_match("/^[NSEW]\d{4}\.\d*$/",$dmm )){
            $dd=(($dmm[0]=='N')?'+':'').(($dmm[0]=='S')?'-':'').(($dmm[0]=='E')?'+':'').(($dmm[0]=='W')?'+':'');
            
            $dd.=(substr($dmm, 1,2))+(substr($dmm, 3)/60);
            return $dd;
        }else{
            return false ;
        }
    }
    public function formatDate($date){
       return date("Y-m-d H:i:s",strtotime($date)); 
    }
    public function lastSettingSend($deviceID){
       $CI =& get_instance();
       $query="SELECT COUNT(ID) as numofrows FROM gpsdata WHERE deviceID = '$deviceID' AND ID >
       ( SELECT MAX(ID) FROM gpsdata WHERE deviceID = '$deviceID' AND setting_send_flag = '1')";
       $result=$CI->db->query($query)->row();
       return $result->numofrows;
    }
}

/* End of file Someclass.php */
?>
