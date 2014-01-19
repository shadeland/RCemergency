var app = app || {};

app.vehicle={}
app.vehicle.model = Backbone.Model.extend({
    url: "/index.php/service/vehicleList.json",
    initialize:function(){

        this.on('editme',formview.fill);
    },
    validate:function(attrs,option){

    },
    submit:function(data){
        var _this=this;
        this.save(data,{wait:true,success:function(){
            if(_this.serverError) delete _this.serverError;
        },error:function(model,response,options){
            console.log(response);
            errors=jQuery.parseJSON(response.responseText);
            _this.serverError=errors;
           _this.trigger("servererror");
        }})



    }
});
app.vehicle.collection=Backbone.Collection.extend({
    model:app.vehicle   .model,
    url: "/index.php/service/vehiclesFullList.json"
})
app.vehicle.grid={};
var editCell=Backgrid.Cell.extend({
    template: _.template('<button>Edit</button>'),
    events: {
        "click": "editRow"
    },
    editRow: function (e) {
        console.log("Hello");
        e.preventDefault();
        this.model.trigger('editme',this.model);
    },
    render: function () {
        this.$el.html(this.template());
        this.delegateEvents();
        return this;
    }
});
var Boolean2Cell = Backgrid.Cell.extend({

    /** @property */
    className: "boolean-cell",

    /** @property */
    editor: Backgrid.BooleanCellEditor,

    /** @property */
    events: {
        "click": "enterEditMode"
    },

    /**
     Renders a checkbox and check it if the model value of this column is true,
     uncheck otherwise.
     */
    render: function () {
        this.$el.empty();
        this.$el.append($("<input>", {
            tabIndex: -1,
            type: "checkbox",
            checked: (this.formatter.fromRaw(this.model.get(this.column.get("name")))==0)?false:true
        }));
        this.delegateEvents();
        return this;
    }

});
app.vehicle.grid.columns = [{
    name: "id", // The key of the model attribute
    label: "ID", // The name to display in the header
    editable: false, // By default every cell in a column is editable, but *ID* shouldn't be
    // Defines a cell type, and ID is displayed as an integer without the ',' separating 1000s.
    cell:"string"
}, {
    name: "OID",
    label: "شماره سازمانی",
    // The cell type can be a reference of a Backgrid.Cell subclass, any Backgrid.Cell subclass instances like *id* above, or a string
    cell: "string" // This is converted to "StringCell" and a corresponding class in the Backgrid package namespace is looked up
}, {
    name: "type",
    label: "نوع خودرو",
    cell: "string" // An integer cell is a number cell that displays humanized integers
}, {
    name: "center",
    label: "مالکیت",
    cell: "string" // An integer cell is a number cell that displays humanized integers
}
    , {
    name: "SID",
    label: "شماره گیرنده",
    cell: "string" // An integer cell is a number cell that displays humanized integers
},{
    name: "phonenumber",
    label: "شماره تلفن گیرنده",
    cell: "string" // An integer cell is a number cell that displays humanized integers
},
    {
        name:"edit",
        label:"ویرایش",
        cell:editCell
    }];
app.vehicle.list= Backbone.View.extend({
    el:$('#vehiclegrid'),
    initialize:function(){
        this.model=new app.vehicle.collection;
        this.grid = new Backgrid.Grid({
            columns:app.vehicle.grid.columns,
            collection: this.model
        });

        app.vehicle.list.collection=this.model;
        this.model.on('sync',this.render,this);
        this.model.fetch();
    },
    render:function(){
            console.log('List Render');


        this.$el.html(this.grid.render().el);

    }
})
app.vehicle.form = Backbone.View.extend({
    el:$('#driverform'),
    initialize:function(){

        $('#center').tree({
            dragAndDrop: true
        });
        $('#center').bind(
            'tree.open',
            function(e) {
                node1=$('#center').tree('getNodeById', 1);
                parents=[];

                parents.push(e.node);
                parent= e.node.parent;
                while(parent!=null){
                    parents.push(parent);
                    parent=parent.parent;
                }


                n1c=node1.children;


                others=_.difference(n1c,parents);

                _.each(others,function(node){

                    $('#center').tree('closeNode', node);
                })

//                current= e.node.parent;
//                console.log(current);
//                current=current.getNextSibling();
//               while(current!=null){
//
//                   $('#center').tree('closeNode', current);
//                   current=current.getNextSibling();
//               }
            }
        );
//        $('#center').bind( 'tree.select',function(e){
//            if(this.model){
//                this.model.set()
//            }
//        })
        _.bindAll(this);

//        AutoCoplete Init

        $('#btnnewform').on("click",this.newform);

    },
    events:{
        "click button":"formsubmit",
        "click #cancel":"formCancel"
    },
    fill:function(model){

        if(this.previeusModel && this.previeusModel.isNew())this.previeusModel.destroy();
        this.model=model;
        console.log(model)

        this.model.on("servererror",this.showerror);
        this.$el.find('input[name=OID]').val(this.model.get('OID')||"");
        this.$el.find('input[name=SID]').val(this.model.get('SID')||"");
        if(this.model.isNew()){
            this.$el.find("#submit").attr('disabled','disabled');
        }else{
            this.$el.find("#submit").removeAttr('disabled');
           this.$el.find("#vtype option[value='"+this.model.get('typeID')+"']").attr('selected','selected');

            var node = $('#center').tree('getNodeById',this.model.get('centerID'));

            $('#center').tree('selectNode', node);

        }



        this.$el.find('input[name=outofservice]').prop("checked",(this.model.get('outofservice')==1)?true:false);
        this.previeusModel=this.model
    },
    newform:function(e){
        e.preventDefault();
        this.model=new app.vehicle.model();
        app.vehicle.list.collection.add(this.model);
        this.fill(this.model);


    },
    formsubmit:function(e){

        e.preventDefault();
        node=$('#center').tree('getSelectedNode');

        data={
            OID:$('#driverform input[name=OID]').val(),

            vtype:$('#driverform select[name=vtype]').val()
            ,center:node.id

        };

        this.model.submit(data);

    },
    enableVehicle:function(){
      if(_.isNumber(this.model.get('id'))){
          this.$el.find('input[name=vehicleBox]').prop('disabled',false);
      }
    },
    showerror:function(){
        $('#error').empty();
        _.each(this.model.serverError,function(error){
           $('#error').append(error['error']);
        });
    },
    formCancel:function(e){
        e.preventDefault();
        this.model.destroy();
    },
    vehicleSelect:function(e,data){

      $('input[name=vehicleID]').val(data.ID);
    }


})
var formview
$(document).ready(function(){
formview  =  new app.vehicle.form();
    setTimeout(function(){
        driverlist= new app.vehicle.list();

    },500)

    setTimeout(function(){
    $.getJSON( "/index.php/service/vehicletype.json")
        .done(function( json ) {
            console.log(json);
            _.each(json,function(item){
                    console.log(item.lang_fa);
                $("#vtype").append('<option value="'+item.ID+'">'+item.lang_fa+'</option>');
            })
        })},1200);

})

;
