<!DOCTYPE html >
<head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
    <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
    <title>Map Viewport</title>  
    <script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=AIzaSyA6Ivm6W3m96W923T8H2ki08QMFTPwcQxo&sensor=false"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.js"></script> 
    <script type="text/javascript" src="<?php echo base_url(); ?>js/jquery-ui.js"></script> 

    <script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.showLoading.js"></script> 
    <script type="text/javascript" src="<?php echo base_url(); ?>js/gmaps.js"></script> 
    <script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.form.js"></script> 
    <script type="text/javascript" src="<?php echo base_url(); ?>js/mymaps.js<?PHP //echo random_string()                    ?>"></script> 
    <script type="text/javascript">
        function customRange(input) {   
            if (input.id == 'dateto') {     
                var x=$('#datefrom').datepicker("getDate");
                $( "#dateto" ).datepicker( "option", "minDate",x ); 
            } 
            else if (input.id == 'datefrom') {     
                var x=$('#dateto').datepicker("getDate");
                $( "#datefrom" ).datepicker( "option", "maxDate",x ); 
            } } 
        $(document).ready(function(){
           
            $("#mapmenu").dialog();
            $( "#menu_selectdevice" ).accordion({ collapsible: true,
                active:false,
                heightStyle: "content",
                activate: function( event, ui ) {
                    if(ui.newHeader.size()==0){ 
                        console.log('close');
                    }else{
                        console.log('open');
                    }
                    
                }
            });
            $('#datefrom').datepicker({
                beforeShow: customRange
                    
                
            });
            $('#dateto').datepicker({
                beforeShow: customRange
            });;
            $('#but_sendsetting').button().click(function(){
                $('#frm_setting').ajaxSubmit({
                    beforeSerialize: function($form, options){
                        $('#settingID').val($('#sel_device').val());
                    },
                    beforeSubmit: function(arr, $form, options) { 
                        $form.parent().showLoading();
                    },
                    success: function(response,arr, xhr, $form) { 
                        $('#setting_log i').html(response);  
                        $form.parent().hideLoading();
                    }

                });
                        
            })
            $('#but_date').button().click(function(){
                $('#frm_date').ajaxSubmit({
                    beforeSerialize: function($form, options){
                        $('#dateID').val($('#sel_device').val());
                    },
                    beforeSubmit: function(arr, $form, options) { 
                        $form.parent().showLoading();
                    },
                    success: function(data,arr, xhr, $form) { 
                        $form.parent().hideLoading();
                        map.removePolylines();
                        map.removeMarkers();
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
               
        
                    }

                });
                        
            });
            $( "#menu-button" )
            .button({
                icons: {
                    primary: "ui-icon-gear"
                }})
            .click(function( event ) {
                
                event.preventDefault();
                $("#mapmenu").dialog('open');
            }).hover(function (){
                $(this).toggleClass("transparent_class");
            })
            .draggable();
            $( "#menu-button" ).position();
        

                
           
        })
    </script>
    <link type="text/css" rel="stylesheet" href="<?php echo base_url() ?>css/css.css">
    <link type="text/css" rel="stylesheet" href="<?php echo base_url() ?>css/hot-sneaks/jquery-ui.css">
    <link type="text/css" rel="stylesheet" href="<?php echo base_url() ?>css/showLoading.css">

    <style>
        html{
            height: 100%;
            font-size: 7pt;
        }
        body{
            height: 100%;
        }

    </style>

</head>

<body >

    <div id="map" style="width: 100% ;height: 100%;position: absolute;top: 0px;left: 0px;"></div>
    <div id="mapmenu" class="mapmenu" title="User Menu">

        <div  id="menu_selectdevice">
            <h3>Select Device</h3>
            <div>
                <label >Device ID</label>
                <select id="sel_device">
                    <option  value="0001">0001</option>
<!-- #TODO Add Device Select Functionality                   -->
                </select>
            </div>
            <h3>Date Filter</h3>
            <div>
                <!--  #TODO check if it should be validated or blank for unlimited               -->
                <form action="xmlserver" id="frm_date" method="POST" >
                    <input id="dateID" name="dateID" type="hidden" />
                    <label >From</label>
                    <input name="datefrom" id="datefrom" type="text"/></br>
                    <label >To</label>
                    <input name="dateto" id="dateto" type="text"/>
                    <a name="submitButton" id="but_date" href="#">Filter</a>
                    <p><i>Leave Blank For No Limitation</i></p>
                </form>
            </div>
            <h3>Device Setting</h3>
            <div>
                <form action="xmlserver/settingListener" id="frm_setting" method="POST" >
                    <input id="settingID" name="settingID" type="hidden" />
                    <label >Option</label>
                    <select name="settingoption" id="settingoption">
                        <?php
                        foreach ($devicelang as $key => $value) {
                            echo "<option value='" . $key . "'>";
                            echo $value['lang'];
                            echo "</option>";
                        }
                        ?>
                    </select>
                    <label >Value</label>
                    <input name="settingvalue" type="text"/>
                    <a name="submitButton" id="but_sendsetting" href="#">Send</a>
                    <div id="setting_log">
                        <i></i>
                    </div>
                </form>
            </div>
        </div>

    </div>
    <div id="menu-icon" >
        <a href="#" class="transparent_class" id="menu-button" >User Menu</a>
    </div>



</body>

</html>
