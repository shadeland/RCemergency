<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <title>Map</title>
    <link rel="stylesheet" href="css/screen.css"/>

    <link rel="stylesheet" href="../assets/jqwidgets/styles/jqx.base.css" type="text/css"/>
    <link href="//netdna.bootstrapcdn.com/bootswatch/2.3.2/cerulean/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="http://getbootstrap.com/2.3.2/assets/css/bootstrap-responsive.css"/>
    <link rel="stylesheet" href="http://fonts.googleapis.com/earlyaccess/droidarabicnaskh.css"/>
    <link rel="stylesheet" href="css/style.css"/>
    <link rel="stylesheet" href="css/screen.css"/>
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
    <script type="text/javascript" src="js/typeahead.js"></script>

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
<div class="container">
    <div class="row" >
        <div class="offset2 span8">
            <form id="driverform" class="well">
                <a class="btn btn-small btn-primary" id="btnnewform" href="#"><i class="icon-plus"></i>فرم جدید</a>

                <div id="error" class="alert alert-error">
                    <a class="close" href="#">&times;</a>
                </div>
                <div class="row">
                    <div class="span3">
                        <label>نام و نام خانوادگی</label>
                        <input type="text" name="fullname" class="span3" id="lat" placeholder=""  >
                        <label>شماره تلفن</label>
                        <input type="text" name="phonenumber" class="span3" id="lon" placeholder=""   >
                        <label>نوضیحات</label>
                        <textarea class="form-control" name="description" rows="3"></textarea>


                    </div>
                    <div class="span3">
                        <p>
                            <label>خودرو تحویلی</label>
                            <input type="text" name="vehicleBox" class="span3" id="vehicleID" placeholder="" >
                            <input type="text" name="vehicleID" class="span1" disabled="disabled">
                        </p>
                        <p>
                            <label>مرخصی  <input type="checkbox" name="outofservice" id="outofservice"  ></label>

                        </p>
                    </div>
                </div>

                <div class="row">
                    <button type="submit" class="btn btn-primary pull-right">ثبت</button>
                    <button  id="cancel" class="btn btn-primary pull-right">انصراف</button>
                </div>
            </form>
        </div>
    </div>
    <div class="row">
        <div class="span12">
            <div id="drivergrid">
                در حال فراخوانی اطلاعات...
            </div>
        </div>
    </div>
</div>




<script src="js/app_driver.js"></script>
</body>
</html>