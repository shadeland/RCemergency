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
    $("#driverSelect").on('select', function (event) {
        if (event.args) {
            var item = event.args.item;
            if (item) {
                console.log(item.value);
            }
        }
    });
},500)


    ///////////////////List///////////
    var url = "/index.php/service_report/orderlist.json";
// prepare the data
    var source =
    {
        datatype: "json",
        datafields: [
            { name: 'SubmitDate', map: 'submit_date' },
            { name: 'Vehicle', map: 'vehicle_ID' }
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
            columns: [
                { text: 'تاریخ ثبت', dataField: 'SubmitDate', width: 100 },
                { text: 'خودرو', dataField: 'Vehicle', width: 100 },

            ],
            height:"500px",
            width:"450px"
        });
})
