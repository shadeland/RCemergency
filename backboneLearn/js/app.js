
app = {};
app.gpsdata = Backbone.Model.extend({});

app.gpsdatas = Backbone.Collection.extend({
  model: app.gpsdata,
  url: "/index.php/service/gpsData.json"
}); 
app.gpsdataList = new app.gpsdatas();
app.gridOptions = {};
app.gridOptions.columns=
     [{
  name: "ID", // The key of the model attribute
  label: "ID", // The name to display in the header
  editable: false, // By default every cell in a column is editable, but *ID* shouldn't be
  // Defines a cell type, and ID is displayed as an integer without the ',' separating 1000s.
  cell: "string"
}, {
  name: "name",
  label: "Name",
  // The cell type can be a reference of a Backgrid.Cell subclass, any Backgrid.Cell subclass instances like *id* above, or a string
  cell: "string" // This is converted to "StringCell" and a corresponding class in the Backgrid package namespace is looked up
}, {
  name: "lat",
  label: "Latitude",
  cell: "string"  // An integer cell is a number cell that displays humanized integers
}, {
  name: "lng",
  label: "Longitude",
  cell: "string" // A cell type for floating point value, defaults to have a precision 2 decimal numbers
}, {
  name: "recivedate",
  label: "Date",
  cell: "datetime"
}, {
  name: "alt",
  label: "Altitude",
  cell: "string" // Renders the value in an HTML <a> element
},{
    name:"edit",
    label:"EDIT",
    cell : Backgrid.Cell.extend({
  
  // Cell default class names are the lower-cased and dasherized
  // form of the the cell class names by convention.
  className: "buttoncell",
  events:{
      "click ":"deleteThis"
  },
  render: function(){
      this.$el.empty().append("<a href='#'>salam</a>");
      return this;
  },
  deleteThis:function(e){
      console.log("clicked");
      e.preventDefault();
      app.grid.removeRow(this.model);

  }
    })
    
}];
 app.grid = new Backgrid.Grid({
  columns: app.gridOptions.columns,
  collection: app.gpsdataList
});
app.render = function(){
    app.gpsdataList.fetch({reset:true});
    $("#grid").append(app.grid.render().$el);
    
}


(function ($) {

    //demo data
    var vent = _.extend({}, Backbone.Events);
    //    var data = [
    //        {name: "tehran",id:1, parent: 0,  type: "city"},
    //        {name: "kermanshah",id:2, parent: 0,  type: "city"},
    //        {name: "tehran1",id:3, parent: 1,  type: "city"},
    //        {name: "tehran2",id:4, parent: 1,  type: "city"},
    //        {name: "tehran3",id:5, parent: 3,  type: "city"},
    //        {name: "kermanshah",id:6, parent: 2,  type: "city"},
    //        {name: "shahryar",id:7, parent: 1,  type: "city"},
    //
    //    ];
    var locationType = Backbone.Model.extend({
         defaults: {
                ID: ""
            }
    });
    var locationTypes = Backbone.Collection.extend({
        model:locationType ,
        url: '/index.php/service/centerTypes.json' ,
        initialize:function(){
            
            this.on("all",function(e){
                console.log(e);
            })
            
            
        }
    });
    var locationTypesView = Backbone.View.extend({
        el:"#locations",
        tagName : "ul",
        collection: new locationTypes(),
        //        events:{
        //          "click .crumbItem": "moveToCrumb"  
        //        },
        initialize:function(){
            //            this.navi=new Array;
          
             this.collection.on("sync",this.render,this);
            var locationTypeForm= new locationTypeFormView({collection:this.collection});
            this.collection.fetch();
           
        //            this.collection.findChilds(null);
        //            
        },
        //        createNavi:function(){
        //            var dom=$("<div/>");
        //            _.each(this.navi,function(item){
        //               dom.append("<a class='crumbItem' value="+item.get('id')+">"+item.get("name")+"<a> /");
        //                
        //            },this);
        //            
        //            return dom;
        //            
        //        },
        //        moveToCrumb:function(e){
        //            var name = $(e.currentTarget).text();
        //           this.collection.reset(data);
        //           var clickedParent=this.collection.findWhere({
        //               name:name
        //           });
        //           this.collection.findChilds(clickedParent);
        //        },
       
        render:function(){
            //       if (this.collection.lastParent){
            //            this.navi.push(this.collection.lastParent);
            //       }
      
            $(this.el).html("").hide("slow");
            //            $(this.el).append(this.createNavi());
//             $("#locationtypelist").jqxListBox({source : this.collection.pluck("type")});
            _.each(this.collection.models,function(item){
                //                var location=new locationView({model:item});
                $(this.el).append(item.get('ID')+item.get('type')+"</br>").show("slow");
            },this)
            
        //            $('#locations').html(this.el);
        }
    });
    var locationTypeFormView = Backbone.View.extend({
        el : "#typeform",
       
        initialize: function(){
           
        },
        render : function(){
            
        },
        events : {
            "click #addType" : "toggleForm",
            "click #addtypeSubmit": "addType"
        },
        toggleForm: function (e){
            e.preventDefault();
          console.log("toggle Form");
          this.$el.find("form").toggle("slow");
        },
        addType: function(e){
            e.preventDefault()
            console.log("add Type submit with data :"+this.$el.find("input").val());
            this.collection.create({
                type: this.$el.find("input").val()
            },{wait: true});
            
        }
        
    });
    var location = Backbone.Model.extend({
        
        });
    var locations= Backbone.Collection.extend({
        model: location,
        lastParent:null,
        initialize:function(){
           
            console.log( "this.findChilds call");
        },
        findChilds:function(model){
            this.lastParent=model,
            console.log( "this.findChilds start"); 
            this.reset(data);
            var father = "0";
            if(model){
                father = model.get("id");  
            }
          
            var filtered = _.filter(this.models,function(item){
                return item.get("parent")==father;
            },this);
           
            this.reset(filtered);
            this.trigger("publish");
            console.log("hi"+this.length);
        }
    });
    var locationsView = Backbone.View.extend({
        el:"#locations",
        tagName : "ul",
        collection: new locations(),
        events:{
            "click .crumbItem": "moveToCrumb"  
        },
        initialize:function(){
            this.navi=new Array;
            this.collection.on("publish",this.render,this);
            this.collection.findChilds(null);
            
        },
        createNavi:function(){
            var dom=$("<div/>");
            _.each(this.navi,function(item){
                dom.append("<a class='crumbItem' value="+item.get('id')+">"+item.get("name")+"<a> /");
                
            },this);
            
            return dom;
            
        },
        moveToCrumb:function(e){
            var name = $(e.currentTarget).text();
            this.collection.reset(data);
            var clickedParent=this.collection.findWhere({
                name:name
            });
            this.collection.findChilds(clickedParent);
        },
        render:function(){
            if (this.collection.lastParent){
                this.navi.push(this.collection.lastParent);
            }
      
            $(this.el).html("");
            $(this.el).append(this.createNavi());
           
            _.each(this.collection.models,function(item){
                var location=new locationView({
                    model:item
                });
                $(this.el).append(location.render().el);
            },this)
            
        //            $('#locations').html(this.el);
        }
    });
    var locationView = Backbone.View.extend({
        tagName : "li",
       
        initialize:function(){
           
        },
        moveToChild : function(e){
            this.model.collection.findChilds(this.model);
        },
        render:function(){
            console.log(this.el);
            var that=this;
       
            $(this.el).append($("<a/>",{
                html: this.model.get("name")
                }));
            $(this.el).find("a").click(function(e){
                that.moveToChild(e)
                });
            return this;
        }
    });

    //    //define product model
    //    var Contact = Backbone.Model.extend({
    //        defaults: {
    //            photo: "/img/placeholder.png"
    //        },
    //         initialize:function(){
    //          this.childern=new directory();
    //          this.childern.parnet=this.get("parent");
    //          this.childern.reset();
    //         }
    //    });
    //
    //    //define directory collection
    //    var Directory = Backbone.Collection.extend({
    //        model: Contact,
    //        initialize:function(){
    //            
    //        }
    //    });
    //
    //    //define individual contact view
    //    var ContactView = Backbone.View.extend({
    //        initialize:function(options){
    //            this.vent = options.vent;
    //            this.render();
    //        },
    //        tagName: "article",
    //        className: "contact-container",
    //        template: $("#contactTemplate").html(),
    //        events:{
    //          "click a":"movenext"  
    //        },
    //        
    //        movenext:function(e){
    //            e.preventDefault();
    ////           this.$el.hide(); 
    //          this.vent.trigger("changeContact", this.$el);
    //        },
    //        render: function () {
    //            var tmpl = _.template(this.template);
    //            
    //            $(this.el).html(tmpl(this.model.toJSON()));
    //            $('body').append(this.$el).show('slow');
    //            return this;
    //        }
    //    });
    //
    //    //define master view
    //    var DirectoryView = Backbone.View.extend({
    //        el: $("#contacts"),
    //
    //        initialize: function (options) {
    //            _.bindAll(this,"changeContact");
    //            options.vent.bind("changeContact",this.changeContact)
    //            this.collection = new Directory(contacts);
    //            this.render();
    //            this.$el.find('#filter').append(this.createFilter());
    ////            this.on("change:filterType", this.filterByType, this);
    //            this.collection.on("reset", this.render, this);
    //            
    //        },
    //        getTypes:function(){
    //            return _.unique(this.collection.pluck("type"));
    //        },
    //        createFilter:function(){
    //            var select = $('<select/>',{html: "<option value='all'>All</option>"});
    //            _.each(this.getTypes(),function(item){
    //              var option = $("<option/>", {
    //                    value: item,
    //                    text: item
    //                }).appendTo(select); 
    //            });
    //            return select;
    //        },
    //        events:{
    //             "change #filter select": "setFilter"
    //             
    //        },
    //         setFilter: function (e) {
    //            this.filterType = e.currentTarget.value;
    ////            this.trigger("change:filterType");
    //            this.filterByType();
    //        },
    //        changeContact:function (e){
    //           
    //            e.hide('slow');
    //           var id=e.find('a').attr('id');
    //           var filtered = _.find(this.collection.models,function(item){
    //               return item.get('parent') == id; 
    //           })
    //           var changedContact=new ContactView({model:filtered})
    //          
    //           
    //        },
    //        filterByType: function(){
    //          if(this.filterType==="all"){
    //              this.collection.reset(contacts);
    //          }else{
    //               this.collection.reset(contacts,{silent: true});
    //               var filterType = this.filterType,
    //                    filtered = _.filter(this.collection.models, function (item) {
    //                        return item.get("type") === filterType;
    //                    });
    //                this.collection.reset(filtered);
    //          }  
    //        },
    //        render: function () {
    //            var that = this;
    //            var vent=this.vent;
    //            this.$el.find("article").remove();
    //            _.each(this.collection.models, function (item) {
    //             var contactView = new ContactView({
    //                vent: this.options.vent,
    //                model: item
    //            });
    //            }, this);
    //        }
    //
    //        
    //    });
    //
    //    //create instance of master view
    //    var locations = new locationsView({level:0});
    var locationTypes = new locationTypesView();
} (jQuery));