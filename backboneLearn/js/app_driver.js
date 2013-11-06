var app = app || {};

app.driver={}
app.driver.model = Backbone.Model.extend({
    url: "/index.php/service/driver.json",
    initialize:function(){

        this.on('editme',formview.fill);
    }
});
app.driver.collection=Backbone.Collection.extend({
    model:app.driver.model,
    url: "/index.php/service/drivers.json"
})
app.driver.grid={};
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
app.driver.grid.columns = [{
    name: "id", // The key of the model attribute
    label: "ID", // The name to display in the header
    editable: false, // By default every cell in a column is editable, but *ID* shouldn't be
    // Defines a cell type, and ID is displayed as an integer without the ',' separating 1000s.
    cell:"string"
}, {
    name: "fullname",
    label: "نام و نام خانوادگی",
    // The cell type can be a reference of a Backgrid.Cell subclass, any Backgrid.Cell subclass instances like *id* above, or a string
    cell: "string" // This is converted to "StringCell" and a corresponding class in the Backgrid package namespace is looked up
}, {
    name: "phonenumber",
    label: "شماره تلفن",
    cell: "string" // An integer cell is a number cell that displays humanized integers
},{
    name: "vehicleID",
    label: "شماره خودرو",
    cell: "string" // An integer cell is a number cell that displays humanized integers
},
    {
        name:"edit",
        label:"ویرایش",
        cell:editCell
    }];
app.driver.list= Backbone.View.extend({
    el:$('#drivergrid'),
    initialize:function(){
        this.model=new app.driver.collection;
        this.model.on('sync',this.render,this);
        this.model.fetch();
    },
    render:function(){
            console.log('List Render');
            this.grid = new Backgrid.Grid({
            columns:app.driver.grid.columns,
            collection: this.model
        });

        this.$el.append(this.grid.render().el);

    }
})
app.driver.form = Backbone.View.extend({
    el:$('#driverform'),
    initialize:function(){
        _.bindAll(this);
        console.log("form init");
    },
    events:{
        "click button":"formsubmit"
    },
    fill:function(model){
        this.model=model;
        this.$el.find('input[name=fullname]').val(this.model.get('fullname'));
        this.$el.find('input[name=phonenumber]').val(this.model.get('phonenumber'));
    },
    formsubmit:function(e){
        console.log("form submited");
        e.preventDefault();
        this.model=new app.driver.model();
        this.model.set({
            fullname:$('#driverform input[name=fullname]').val(),
            phonenumber:$('#driverform input[name=phonenumber]').val()
        });
        this.model.save();
    }


})
var formview
$(document).ready(function(){
formview  =  new app.driver.form();
driverlist= new app.driver.list();


})

;
