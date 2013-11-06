<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <title>Map</title>
    <link rel="stylesheet" href="css/screen.css"/>

    <link rel="stylesheet" href="../assets/jqwidgets/styles/jqx.base.css" type="text/css"/>
    <link href="//netdna.bootstrapcdn.com/bootswatch/2.3.1/cerulean/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="http://fonts.googleapis.com/earlyaccess/droidarabicnaskh.css"/>
    <link rel="stylesheet" href="css/style.css"/>
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css"/>
    <script type="text/javascript" src="js/jquery.js"></script>

    <script src="js/underscore.js"></script>
    <script src="js/backbone.js"></script>
    <script type="text/javascript" src="../assets/jqwidgets/jqxcore.js"></script>

    <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>

    <script src="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="css/backgrid.min.css"/>
    <script type="text/javascript" src="js/backgrid.js"></script>
    <script type="text/javascript" src="https://github.com/epeli/underscore.string/raw/master/dist/underscore.string.min.js"></script>
    <script type="text/javascript" src="js/jquery.tabSlideOut.v1.3.js"></script>
    <script type="text/javascript" src="js/jquery.mCustomScrollbar.concat.min.js"></script>
    <link rel="stylesheet" href="js/jquery.mCustomScrollbar.css"/>
    <style>
        html, body {
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 0;
        }
    </style>


</head>
<body>

<div id="drivergrid">

</div>


<form id="driverform" class="well span8">
    <a class="close" href="#">&times;</a>
    <div class="row">
        <div class="span3">
            <label>نام و نام خانوادگی</label>
            <input type="text" name="fullname" class="span3" id="lat" placeholder=""  >
            <label>شماره تلفن</label>
            <input type="text" name="phonenumber" class="span3" id="lon" placeholder=""   >


        </div>

        <button type="submit" class="btn btn-primary pull-right">ثبت</button>
    </div>
 </form>
<script src="js/app_driver.js"></script>
</body>
</html>