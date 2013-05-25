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
    <script type="text/javascript" src="js/jquery.js"></script>
    <script src="js/underscore.js"></script>
    <script src="js/backbone.js"></script>
    <script type="text/javascript" src="../assets/jqwidgets/jqxcore.js"></script>

    <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
    <script src="http://maps.google.com/maps/api/js?v=3&amp;sensor=false"></script>

    <script type="text/javascript" src="http://openlayers.org/api/2.12/OpenLayers.js"></script>
    <script src="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.1/js/bootstrap.min.js"></script>

    <script type="text/javascript" src="https://github.com/epeli/underscore.string/raw/master/dist/underscore.string.min.js"></script>
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
<div id="toolbar"  class="overlay_list" style=" display: block;">
    <ul class="unstyled">
        <button type="button" id="insert_incident" class="btn btn-primary" data-toggle="button"><i class="icon-plus-sign icon-white"></i>سانحه </button>


    </ul>
</div>
<div id="incident_form"  class="overlay_list" style=" display: block;">

</div>

<div id="vehicle_list" class="overlay_list" style=" display: block;">
    <input type="text" id="vechicle_search" placeholder="جستجو"   />
    <div id="list">
        <ul class="unstyled">

        </ul>
    </div>
</div>

<div id="incident_list" class="overlay_list" style=" display: block;">
    <div class="handler"></div>
    <input type="text" id="vechicle_search" placeholder="جستجو"   />
<!--    TODO : implement with accardions -->

    <div class="table-wrapper"  style="height:300px !important;width: 400px;overflow:scroll;">
    <table class="table table-condensed table-hover table-striped">
        <thead>
           <tr>
               <th>شماره</th>
               <th>نوع سانحه</th>
               <th>جزییات</th>
               <th> امکانات</th>
           </tr>
        </thead>
        <tbody class="list_wrapper ">

        </tbody>
    </table>
    </div>

</div>
<div id="incident_info_box" class="overlay_list" style="display: block">
    <ul class="nav nav-tabs">
        <li class="active"><a href="#info">اطلاعات</a></li>
        <li><a href="#response">پاسخ دهی</a></li>
    </ul>
   <div class="tab-content">
       <div class="tab-pane active" id="info"></div>
       <div class="tab-pane" id="response">
           <div class="clear-fix">
               <label class="checkbox inline">
                   <input type="checkbox" id="inlineCheckbox1" value="0">ماشین خاموش
               </label>
               <label class="checkbox inline">
                   <input type="checkbox" id="inlineCheckbox2" value="3"> در ماموریت
               </label>
               <label class="checkbox inline">
                   <input type="checkbox" id="inlineCheckbox3" value="5"> در انتظار پاسخ
               </label>
           </div>
       </div>

   </div>
</div>
<script language="JavaScript">
    $('.nav-tabs').find('a').click(function(e){
        e.preventDefault();
        $(this).tab('show');
    })
</script>
<div id="info_box" class="overlay_list" style=" display: block;">

</div>
    <div id="map" >

    </div>
<script type="text/x-underscore-template" id="incident_form_template">
    <form class="well span8">
        <div class="row">
            <div class="span3">
                <label>طول جغرافیایی</label>
                <input type="text" class="span3" id="lat" placeholder="" value=<%= position.lat %> >
                <label>عرض جغرافیایی</label>
                <input type="text" class="span3" id="lon" placeholder=""  value=<%= position.lon %> >

                <label>نوع سانحه</label>
                <select id="type" name="type" class="span3">
<!-- TODO : incident Type Should Read From Server -->

                    <option value="1">تصادف</option>
                    <option value="2">غرق شده گی</option>

                </select>
            </div>
            <div class="span5">
                <label>توضیحات</label>
                <textarea name="descript" id="descript" class="input-xlarge span5" rows="10"></textarea>
            </div>

            <button type="submit" class="btn btn-primary pull-right">ثبت</button>
        </div>
    </form>
</script>
<script type="text/x-underscore-template" id="info_table_template">
    <div class="info_table">

        <table class="table table-condensed table-hover">

            <tbody>
            <tr>
                <td>نام</td>
                <td><%= name %></td>

            </tr>
            <tr>
                <td>وضعیت</td>
                <td><% switch (status.status_ID){
                        case "1":
                            %>در ماموریت
                    <% break;

                     case "0": %>
                    آزاد
                    <% break;

                    }

                    %></td>
<!-- TODO : Localization -->
            </tr>
            <tr>
                <td class="info-category">اطلاعات ماموریت</td>
                <td></td>

            </tr>
            <tr>
                <td class="info-category"> اطلاعات آخرین موقعیت</td>
                <td> </td>

            </tr>
            <tr>
                <td>طول جفرافیایی</td>
                <td><%= LonLat.lat %></td>

            </tr>
            <tr>
                <td>عرض جفرافیایی</td>
                <td><%= LonLat.lng %></td>

            </tr>
            <tr>
                <td>ارتفاع از سطح دریا</td>
                <td><%= LonLat.alt %></td>

            </tr>
            <tr>
                <td>سرعت</td>
                <td><%= LonLat.speed %></td>

            </tr>
            <tr>
                <td>جهت</td>
                <td><%= LonLat.course %></td>

            </tr>
            <tr>
                <td>ورودی 1</td>
                <td><%= LonLat.input1 %></td>
            </tr>
            <tr>
                <td>ورودی 2</td>
                <td><%= LonLat.input2 %></td>
            </tr>
            <tr>
                <td>ورودی 3</td>
                <td><%= LonLat.input3 %></td>
            </tr>
            <tr>
                <td>خروجی 1</td>
                <td><%= LonLat.output1 %></td>
            </tr>
            <tr>
                <td>خروجی 2</td>
                <td><%= LonLat.output2 %></td>
            </tr>
            <tr>
                <td>خروجی 3</td>
                <td><%= LonLat.output3 %></td>
            </tr>
            <tr>
                <td>تاریخ</td>
                <td><%= LonLat.recivedate%></td>
            </tr>
            </tbody>
        </table>


    </div>

</script>
<script type="text/x-underscore-template" id="incidnet_info_table_template">
    <div class="info_table">

        <table class="table table-condensed table-hover">

            <tbody>
            <tr>
                <td>شماره</td>
                <td><%= ID %></td>

            </tr>
            <tr>
                <td>وضعیت</td>
                <td><%= validity %></td>
                <!-- TODO : Localization -->
            </tr>
            <tr>
                <td class="info-category">اطلاعات ماموریت</td>
                <td></td>

            </tr>
            <tr>
                <td class="info-category"> اطلاعات آخرین موقعیت</td>
                <td> </td>

            </tr>
            <tr>
                <td>طول جفرافیایی</td>
                <td><%= position.lat %></td>

            </tr>
            <tr>
                <td>عرض جفرافیایی</td>
                <td><%= position.lon %></td>

            </tr>
            <tr>
                <td>ارتفاع از سطح دریا</td>
                <td><%= position.alt %></td>

            </tr>


<!--            TODO-1 compelete this  -->
            </tbody>
        </table>


    </div>

</script>
<script type="text/x-underscore-template" id="vehicle_item_template">
    <div class="vehicle_item ">

        <a href="#"><%= name %></a>
        <span class="vehicle-type"><%= type %></span>
        <% if (!_.isNull(order)){ %>
            <span class="label label-warning">در انتظار پاسخ</span>
        <%}%>
        <% if (status.status_ID=="1246"){ %>
            <span class="label label-success ">آزاد</span>
        <%}%>
        <% if (status.status_ID=="1346" || status.status_ID=="1356" ){ %>
            <span class="label label-important">در حال ماموریت</span>
        <%}%>

    </div>

</script>
<script type="text/x-underscore-template" id="suggestion_item_template">


        <a href="#"><%= name %></a>
        <span class="vehicle-type"><%= type %></span>
        <span class="vehicle-type"><%= distance %></span>
    <a class="btn btn-primary" href="#">انتخاب</a>



</script>
<script type="text/x-underscore-template" id="incident_item_template">

        <td><%= ID %></td>
        <td><%= type %></td>
        <td><%= create_date %></td>
    <td> <button class="addMarker" >+</button>
        <button class="removeMarker">-</button>
        <button class="refreshModel">O</button>
    </td>



</script>

<script src="js/app_new.js"></script>
</body>
</html>