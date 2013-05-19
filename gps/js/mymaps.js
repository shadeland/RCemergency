//GLOBAL variables
var map;
 
function makePoliLine(markerList){
    var poliarray=[];
    $.each(markerList,function(){
        var point=[];
        point.push(this.getPosition().lat(),this.getPosition().lng()) ;
        poliarray.push(point);
    })
    return poliarray;
        
    
}
    
function changeCenter(xml){
   averagelat=0;
   averagelng=0;
   count=0
   $(xml).find('marker').each(function(){
       count++;
       averagelat+=parseFloat($(this).attr('lat'));
       averagelng+=parseFloat($(this).attr('long'));
   })
   averagelat=averagelat/count;
   averagelng=averagelng/count;
   center = {lat:averagelat,
   lng:averagelng}
   return(center);
}
function createMarkers(xml){ 
        
    var markersArray=[];
    $(xml).find('marker').each(function(){
//        deviceID="3" lat="+35.6824843667" long="+51.4035884333" amsl="1166.832520" date="2013-01-14 18:57:02" speed="280.094666000" direction="0.93049300000" message="0000000000000000" submitdate="0000-00-00 00:00:00
        lat=$(this).attr('lat');
        lng=$(this).attr('long');
        speed=$(this).attr('speed');
        deviceid=$(this).attr('deviceID');
        amsl=$(this).attr('amsl');
        date=$(this).attr('date');
        heading=$(this).attr('direction');
        address=$(this).attr('address');
        icontent="<strong>latitude: </strong>"+lat;
        icontent+="<br><strong>longitude: </strong>"+lng;
        icontent+="<br><strong>speed: </strong>"+speed;
        icontent+="<br><strong>date: </strong>"+date;
        icontent+="<br><strong>address: </strong>"+address;
        icontent+="<br><strong>direction: </strong>"+heading;
        icontent+="<br><strong>Device ID: </strong>"+deviceid;
        icontent+="<br><strong>AMSL: </strong>"+amsl;
        markersArray.push(map.createMarker({
            lat:lat,
            lng:lng,
            infoWindow:{content:icontent}
        }));
//        alert(lat);
            
            
    });
    return (markersArray);
}
function getMarkersFilter(){
    
}

$(document).ready(function () { 
      
    $.ajax({
        url:'xmlserver',
            
        success:function(data,status,jqr){
            if($(data).find('marker').length==0){
                alert("no markers");
                return false;
            }
                
            markers=createMarkers(data);
            jQuery.each(markers, function() {
                map.addMarker(this);
            });
          var center=changeCenter(data);
          
            map.setCenter(center.lat,center.lng);
            map.fitZoom();
            //            if you wanted a poliline to show the path
            poli=makePoliLine(markers);
            map.drawPolyline({
                path: poli,
                strokeColor: '#131540',
                strokeOpacity: 0.6,
                strokeWeight: 6
            });
               
        },
        error:function( jqXHR,  textStatus,  errorThrown){
            alert('There is an Error in loading server data.:'+textStatus+':'+errorThrown);
        }
        
    });



    
    map = new GMaps({
        div: '#map',
        lat: 35.043333,
        lng: 51.028333,
        zoom:3
    });
    
    
 
});

//sdksdksladkskjfkfdjfklnjffkljdsfkldfjsfjfklsfflkf
