<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>نقشه ما موریت</title>
<!--    JQwdgets Incldes Start-->

    <link href="//netdna.bootstrapcdn.com/bootswatch/2.3.1/cerulean/bootstrap.min.css" rel="stylesheet">

    <script type="text/javascript" src="scripts/jquery-2.0.2.min.js"></script>
    <script src="../js/underscore.js"></script>
    <script src="../js/backbone.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxcore.js"></script>

<!--    <script type="text/javascript" src="js/jquery.ui.datepicker-cc.all.min.js"></script>-->
<!--    <script type="text/javascript" src="js/jquery.ui.datepicker-cc.all.min.js"></script>-->
<!--    <script type="text/javascript" src="scripts/jquery.ui.core.js"></script>-->
<!---->
<!--    <script type="text/javascript" src="scripts/jquery.ui.datepicker-cc.js"></script>-->
<!--    <script type="text/javascript" src="scripts/calendar.js"></script>-->
<!--    <script type="text/javascript" src="scripts/jquery.ui.datepicker-cc-fa.js"></script>-->
    <link rel="stylesheet" href="jqwidgets/styles/jqx.base.css" type="text/css" />
    <link rel="stylesheet" href="jqwidgets/styles/jqx.bootstrap.css" type="text/css" />
    <link rel="stylesheet" href="jqwidgets/styles/jqx.bootstrap.css" type="text/css" />
    <link rel="stylesheet" href="js/style/jquery-ui-1.8.14.css" type="text/css" />
    <script type="text/javascript" src="scripts/jquery-2.0.2.min.js"></script>
    <script type="text/javascript" src="http://code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxcore.js"></script>
    <!--    <script type="text/javascript" src="js/jquery.ui.datepicker-cc.all.min.js"></script>-->
    <!--    <script type="text/javascript" src="js/jquery.ui.datepicker-cc.all.min.js"></script>-->
    <!--    <script type="text/javascript" src="scripts/jquery.ui.core.js"></script>-->
    <!---->
    <!--    <script type="text/javascript" src="scripts/jquery.ui.datepicker-cc.js"></script>-->
    <!--    <script type="text/javascript" src="scripts/calendar.js"></script>-->
    <!--    <script type="text/javascript" src="scripts/jquery.ui.datepicker-cc-fa.js"></script>-->
    <script type="text/javascript" src="jqwidgets/jqxdata.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxbuttons.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxscrollbar.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxlistbox.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxdropdownlist.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxdatatable.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxdata.export.js"></script>
    <script src="js/pwt-date.js"></script>
    <link href="css/pwt-datepicker.css" rel="stylesheet">
    <script src="js/pwt-datepicker.js"></script>
    <link href="css/bootstrap-timepicker.min.css" rel="stylesheet">
    <script src="js/bootstrap-timepicker.min.js"></script>
    <script  type="text/javascript" src="https://rawgithub.com/RobinHerbots/jquery.inputmask/2.x/dist/jquery.inputmask.bundle.js"></script>

    <script type="text/javascript" src="http://openlayers.org/api/2.12/OpenLayers.js"></script>
    <script type="text/javascript" src="http://rawgithub.com/chriso/validator.js/master/validator.min.js"></script>
    <script type="text/javascript" src="js/jalali.js"></script>
    <link href="css/style.css" rel="stylesheet">


<!--    Jqwidgets Include END-->
<!--    APP-->

    <script type="text/javascript" src="js/vehiclemap.js"></script>
<!--    APP END-->
    <script type="text/javascript">
        app.lon=51.26086;
            app.lat=35.6567;
    </script>

    <link rel="stylesheet" href="css/style.css"/>



</head>
<body >
<div class="container">

    <div class="row">
        <div class=" span12 main_container">
            <div style="margin-top: 20px" class="row">

                <div style="text-align: right" class="offset1 span4">

                    <label>خودرو</label>
                    <div style="float: right" id="driverSelect">

                    </div>
                </div>
                <div style="text-align: right" class="span4">
                    <label>انتخاب بازه زمانی</label>
                    <div class="controls controls-row">

                        <input  type="text" id="fromDate" name="fromDate" class="input-small ltr center date " placeholder="" value="">
                        <input  type="text" id="fromTime" name="fromDate" class="input-small ltr center time " placeholder="" value="">
                        <div style="float: right ;padding-top: 5px" class="span1  rtl">از تاریخ :</div>


                    </div>
                    <div class="controls controls-row">
                        <div class="indicator"></div>
                        <input type="text" id="toDate" name="toDate" class="input-small ltr center date " placeholder="" value="" >
                        <input  type="text" id="toTime" name="fromDate" class="input-small ltr center time " placeholder="" value="">
                        <div  style="float: right ;padding-top: 5px"  class="span1  rtl">تا تاریخ :</div>
                        <div id="error" style="display: none" class="alert alert-error">

                        </div>
                        <span class="help-inline">تاریخ باید به صورت :1392/04/09 وارد شود</span>
                    </div>
                    <div class="controls controls-row">
                        <button id="subimtdate" style="margin-left: 0px" class="span2" value="اعمال">اعمال</button>
                    </div>
                </div>
            </div>
            <div style="margin-top: 20px;padding-left: 40px;padding-right: 20px" class="row">
                <div style="width:100%; height: 500px; " id="map" ></div>
            </div>

        </div>

    </div>
</div>

</body>





</body>
</html>
<?php
/**
 * Created by JetBrains PhpStorm.
 * User: shadel
 * Date: 1/15/14
 * Time: 5:28 AM
 * To change this template use File | Settings | File Templates.
 */
?>