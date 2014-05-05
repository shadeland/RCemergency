/**
 * Created with JetBrains PhpStorm.
 * User: shadel
 * Date: 1/15/14
 * Time: 5:45 AM
 * To change this template use File | Settings | File Templates.
 */
//$.extend($.datepicker,{_checkOffset:function(inst,offset,isFixed){return offset}});

$(function(){
    //date
//    $('#fromDate').datepicker();
//    console.log($('#fromDate'));
//    $('#toDate').datepicker({dateFormat: 'yy/mm/dd',maxDate: '0'});
    $('#fromDate').inputmask("9999/99/99");
    $('#toDate').inputmask("9999/99/99");
    ////////////Drive/////////////
setTimeout(function(){
    var durl = "/index.php/service_report/driverlist.json";
    // prepare the data
    var dsource =
    {
        datatype: "json",
        datafields: [
            { name: 'id' },
            { name: 'fullname' }
        ],
        id: 'id',
        url: durl
    };
    var ddataAdapter = new $.jqx.dataAdapter(dsource);
    // Create a jqxListBox
    $("#driverSelect").jqxListBox({ source: ddataAdapter, displayMember: "fullname", valueMember: "id", width: 200, height: 150,rtl:true});

},500)
    $("#driverSelect").on('select', function (event) {
        if (event.args) {
            var item = event.args.item;
            if (item) {
                console.log(item.value);
                source.data={
                    query:item.value,
                    type:'driver'
                }
                dataAdapter.dataBind();
            }
        }
    });

    ///////////////////List///////////
    var url = "/index.php/service_report/orderlist.json";
// prepare the data
    var source =
    {
        datatype: "json",
        datafields: [
            { name: 'vehicleOID', map: 'vehicleOID' },
            { name: 'vehicletype', map: 'vehicletype' },
            { name: 'datestart', map: 'datestart' },
            { name: 'dateend', map: 'dateend' },
            { name: 'datedest', map: 'datedest' },
            { name: 'status', map: 'status' },
            { name: 'drivername', map: 'drivername' },
            { name: 'datesubmit', map: 'datesubmit' },
            { name: 'distance', map: 'distance' },
            { name: 'maplink', map: 'maplink' }
        ],

        url: url
    };
    var dataAdapter = new $.jqx.dataAdapter(source);
    $("#dataTable").jqxDataTable(
        {
            source: dataAdapter,
            pageable: true,
            pagerButtonsCount: 10,
            rtl: true,
            aggregatesHeight: 25,
            showAggregates: true,
            columns: [
                { text: 'راننده',dataField: 'drivername', width: 90 },
                { text: 'خودرو',dataField: 'vehicleOID', width: 90 },
                { text: 'نوع خودرو',dataField: 'vehicletype', width: 90 },
                { text: 'نوع ماموریت', width: 100 },
                { text: 'وضعیت',dataField: 'status', width: 100 },
                { text: 'تاریخ ثبت',dataField: 'datesubmit', width: 90 },
                { text: 'تاریخ شروع',dataField: 'datestart', width: 100 },
                { text: 'تاریخ مقصد',dataField: 'datedest', width: 100 },
                { text: 'تاریخ پایان',dataField: 'dateend', width: 100 },
                {
                    text: 'مسافت',dataField: 'distance', width: 90,
                     aggregates: ['sum'],
                    aggregatesRenderer: function (aggregates, column, element) {
                        var renderString = "<div style='margin: 4px; float: right;  height: 100%;'>";
                        renderString += "<strong>مجموع: </strong>" + aggregates.sum + "</div>";
                        return renderString;
                    }
                },
                { text: ' ',dataField: 'maplink', width: 50 }


            ],
            height:"500px",
            width:"1000px"
        });
    $("#excelExport").click(function () {
        $("#dataTable").jqxDataTable('exportData', 'xls');
    });
    $("#xmlExport").click(function () {
        $("#dataTable").jqxDataTable('exportData', 'xml');
    });

    $(document).on('click','a.popup', function(e){
        e.preventDefault();
        newwindow=window.open($(this).attr('href'),'','height=540,width=988');
        if (window.focus) {newwindow.focus()}
        return false;
    });
})
