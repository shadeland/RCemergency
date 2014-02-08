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

    <script type="text/javascript" src="http://openlayers.org/api/2.12/OpenLayers.js"></script>
    <link href="css/style.css" rel="stylesheet">


<!--    Jqwidgets Include END-->
<!--    APP-->

    <script type="text/javascript" src="js/ordermap.js"></script>
<!--    APP END-->
    <script type="text/javascript">
        app.orderID=<?php echo $_GET['orderID']?>;
<!--        app.lon=--><?php //echo $_GET['lon']?><!--;-->
<!--        app.lat=--><?php //echo $_GET['lat']?><!--;-->
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

                <div style="text-align: right" class="offset1 span8">
                    <div style="width:780px; height: 500px;" id="map" ></div>

                </div>

            </div>
            <div style="margin-top: 20px" class="row">

            </div>

        </div>

    </div>
</div>

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