<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>لیست ماموریتها</title>
<!--    JQwdgets Incldes Start-->
    <link rel="stylesheet" href="jqwidgets/styles/jqx.base.css" type="text/css" />
    <link rel="stylesheet" href="jqwidgets/styles/jqx.bootstrap.css" type="text/css" />
    <link rel="stylesheet" href="js/style/jquery-ui-1.8.14.css" type="text/css" />
    <script type="text/javascript" src="scripts/jquery-2.0.2.min.js"></script>
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


<!--    Jqwidgets Include END-->
<!--    APP-->
    <script type="text/javascript" src="js/missionlist.js"></script>
<!--    APP END-->
    <link rel="stylesheet" href="../css/bootstrap.css"/>
    <link rel="stylesheet" href="css/style.css"/>



</head>
<body >
<div class="container">

    <div class="row">
        <div class="offset1 span10 main_container">
            <div style="margin-top: 20px" class="row">

                <div  class="offset1 span4">


                    <div style="float: right" id="driverSelect">

                    </div>
                </div>
                <div style="text-align: right" class="span4">
                    <label>انتخاب بازه زمانی</label>
                    <div class="controls controls-row">
                        <input type="text" id="fromDate" name="fromDate" class="span2 ltr center date " placeholder="" value="">
                        <div class="span  rtl">از تاریخ :</div>


                    </div>
                    <div class="controls controls-row">
                        <div class="indicator"></div>
                        <input type="text" id="toDate" name="toDate" class="span2 ltr center date " placeholder="" value="" >
                        <div class="span  rtl">تا تاریخ :</div>

                        <span class="help-inline">نمایش گزارش متناسب با دوره زمانی انتخاب شده</span>
                    </div>
                </div>
            </div>
            <div style="margin-top: 20px" class="row">
                <div style="display: inline-block" id="dataTable">
                </div>
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