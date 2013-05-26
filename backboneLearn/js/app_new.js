var app = app || {};


app.listItem = Backbone.Model.extend({

});
app.listItems = Backbone.Collection.extend({
    model: app.listItem,

    url: "/index.php/service/centers.json",
    initialize: function () {

    },
    filter:function(filter){
        filterurl=this.url+"/"+filter
        this.fetch({url:filterurl});
    }


});
app.itemView = Backbone.View.extend({

    render:function(){
        this.$el.empty();
        $(this.el).html("<option value="+this.model.get('ID')+" >"+this.model.get("name")+"</option>");
        return this
    }

});
app.listView = Backbone.View.extend({

    initialize :function (options) {
        this.listDiv = options.listDiv;
        this.select = options.select;
        this.parent=options.parent;
        $(this.listDiv).jqxListBox({ width: 200, height: 250});

        // Load the data from the Select html element.


        this.collection = new app.listItems();
        this.collection.on("sync",this.render,this);
        this.collection.fetch();


    },
    events:{
        "click a":"refresh"

    },
    itemSelect:function(e){
        console.log("select");
    },
    refresh:function(e){
        e.preventDefault();
        this.collection.filter();
    },
    render:function () {

        this.$el.find("#"+this.select).empty();
        _.each(this.collection.models,function(item){
            var itemView = new app.itemView({model : item});
            this.$el.find("#"+this.select).append(itemView.render().$el.html());
        },this)
        $(this.listDiv).jqxListBox('loadFromSelect', this.select);
        $(this.listDiv).on("select",function(){
            console.log("select");
        })
//        this.delegateEvents();
    }
});
app.map = {};

/**
 * Map View
 * With Option :mapType:"osm" for street map ,default is google.map
 * @type {*}
 */
app.map.view= Backbone.View.extend({
   el : "#map",
    initialize : function(options){
        if(!_.isUndefined(options.mapType) && options.mapType=="osm"){
            this.map = new OpenLayers.Map("map",{
                units:"dd",
                projection: new OpenLayers.Projection("EPSG:900913"),
                displayProjection: new OpenLayers.Projection("EPSG:4326"),
                controls: [
                    new OpenLayers.Control.Navigation({
                        dragPanOptions: {
                            enableKinetic: true
                        }
                    }),
                    new OpenLayers.Control.PanZoom(),
                    new OpenLayers.Control.Attribution(),
                    new OpenLayers.Control.LayerSwitcher(),
                    new OpenLayers.Control.MousePosition()
                ]


            });
            var layer = new OpenLayers.Layer.OSM("OpenStreetMap");
            this.map.addLayer(layer);
        }else{
            this.map = new OpenLayers.Map("map",{
                units:"dd",
                projection: new OpenLayers.Projection("EPSG:900913"),
                displayProjection: new OpenLayers.Projection("EPSG:4326"),
                controls: [
                    new OpenLayers.Control.Navigation({
                        dragPanOptions: {
                            enableKinetic: true
                        }
                    }),
                    new OpenLayers.Control.PanZoom(),
                    new OpenLayers.Control.Attribution(),
                    new OpenLayers.Control.LayerSwitcher(),
                    new OpenLayers.Control.MousePosition()
                ]

            });
            this.googleLayer = new OpenLayers.Layer.Google("Google Streets",{sphericalMercator: true },{


            });
            this.map.addLayer(this.googleLayer);
        }

        app.map.mapObj=this.map;
        app.map.markersLayer= new OpenLayers.Layer.Vector("Features");
        app.map.featureSelect= new OpenLayers.Control.SelectFeature(app.map.markersLayer,
            {
                multiple: false,
                toggle: true,
                multipleKey: 'shiftKey'

            }
        );
        app.map.mapObj.addControl(app.map.featureSelect);
        app.map.featureSelect.activate();


        //Config The Map And Layers
        this.map.setCenter(new OpenLayers.LonLat(34, 54).transform(this.map.displayProjection, this.map.projection),15);
        this.renderMap();
        this.renderMarkerLayer();
//        this.collection = new app.map.vehicles();
//        app.map.vehicle.collection=this.collection;
//        this.collection.on("sync",this.renderMarkers,this);
//        this.collection.fetch();
    },
    renderMap:function(){
        // Adding Basic Layers and Controls To Map

    },
    initializeMarkerLayer:function(){
        this.markersList=[];
    },
    renderMarkerLayer:function(){

        this.map.addLayer(app.map.markersLayer);
        app.map.markersLayer.events.register('featureselected', this, this.selected_feature);
        app.map.markersLayer.events.register('featureunselected', this,this.unselected_feature);
    },
    selected_feature:function(e){
        e.feature.style.fillOpacity= 1;
        e.feature.layer.redraw();
        e.feature.view.select(e);
    },
    unselected_feature:function(e){
        e.feature.style.fillOpacity= 0.7;
        e.feature.layer.redraw();
        e.feature.view.unselect(e);
    },
    //Event Handler For Feature Which Calls Native Event Handlers Of Markers View

    addMarkerToLayer:function(marker){

    }

//   renderMarkers:function(){
//       if(this.markersLayer.markers.length>0){
//           this.markersLayer.clearMarkers();
//       }
//
//       var size = new OpenLayers.Size(32, 37);
//       var offset = new OpenLayers.Pixel(-(size.w/2),-size.h);
//       var icon = new OpenLayers.Icon('img/marker.png', size, offset);
//       icon.setOpacity(0.7);
//
//       _.each(this.collection.models,function(item){
//           lonlat= new OpenLayers.LonLat(item.get('lon'), item.get('lat')).transform(this.map.displayProjection, this.map.projection);
//           icon2 = icon.clone();
//           icon2.setOpacity(0.7);
//           var marker = new OpenLayers.Marker(lonlat, icon2);
//           marker.model=item;
//           marker.model.on("someEvent",this.someFunction,marker);
//           marker.events.register("mouseover", marker,
//               function() {
//                   console.log("Over the marker "+this.id+"at place "+this.lonlat);
//                   this.inflate(1.2);
//                   this.setOpacity(1);
//               });
//           marker.events.register("mouseout", marker,
//               function() {
//                   console.log("Over the marker "+this.id+"at place "+this.lonlat);
//                   this.inflate(1/1.2);
//                   this.setOpacity(0.7);
////                   if (this.popup) {
////
////                       that.map.removePopup(this.popup);
////                       this.popup.destroy();
////                       this.popup = null;
////                   }
//               });
//            var that = this ;
//           marker.events.register("click", marker, function() {
//
//                   var popup = new OpenLayers.Popup.FramedCloud("Popup",
//                       this.lonlat, null,
//                       '<a target="_blank" href="http://openlayers.org/">We</a> ' +
//                           this.model.get('name')+":"+this.model.get('recievedate'), null,
//                       true // <-- true if we want a close (X) button, false otherwise
//                   );
//
//                   marker.popup=popup;
//                   popup.marker=marker;
//                   that.map.addPopup(popup);
//               }); this.markersLayer.addMarker(marker);
//       },this)
//
//   },
//    someFunction:function(e){
//        console.log("someFunction : with");
//        console.log(e);
//        this.destroy();
//    }
});
app.map.markerView=Backbone.View.extend({
    initialize:function(options){
        //bind to model events
        this.listenTo(this.model,'change' ,this.dataChanged);
        this.itemView =options.itemView;
        this.LonLat = new OpenLayers.LonLat(this.model.get('LonLat').lng,this.model.get('LonLat').lat).transform(app.map.mapObj.displayProjection, app.map.mapObj.projection);
        var point =  new OpenLayers.Geometry.Point (this.LonLat.lon, this.LonLat.lat);
        var that=this

        this.marker = new OpenLayers.Feature.Vector(point,  null,{
            externalGraphic: "img/"+that.model.get('type')+".png",
            graphicWidth: 30,
            graphicHeight: 48,
            graphicYOffset: -40,
            graphicXOffset: -18,
            fillOpacity: 0.7,
            label:this.model.get('LonLat').name
        });
        this.marker.view=this;

//        this.marker2 = new OpenLayers.Feature.Vector(point.clone(), null,{
//            pointRadius: 2
//        });
        this.f=[];
        this.f.push(this.marker);
    },
    render:function(){  
        app.map.markersLayer.addFeatures(this.f);
        app.map.mapObj.setCenter(this.LonLat,15);
        return this;
    },
    remove:function(){
        app.map.markersLayer.removeFeatures([this.marker]);
    },
    locate:function(){
        app.map.mapObj.panTo(this.LonLat,15);
    },
    select:function(e){
        console.log(this);
        //open info box
        app.map.infoBox.showBox(this.model);
        this.itemView.highlight();

    },
    unselect:function(e){
        console.log(this);
        //close Info Box
        app.map.infoBox.hideBox();
        this.itemView.unLight();
        this.marker.style.fillOpacity= 0.7
    },
    dataChanged:function(){
        console.log('data Changed from marker');
        this.marker.move(new OpenLayers.LonLat(this.model.get('LonLat').lng,this.model.get('LonLat').lat).transform(app.map.mapObj.displayProjection, app.map.mapObj.projection)
        )
    }


})
app.vehicle={}

app.vehicle.model = Backbone.Model.extend({
    url:function(){
        return  "/index.php/service/vehicle.json/vehicleID/"+this.get("ID")+"/format/json";
    },
    defaults:{
        type:"ambulance",
        order:null,
    },
    initialize:function(){
      this.bind('sync',function(){
          console.log('foo from model');
      })
    }
});
app.vehicle.collection = Backbone.Collection.extend({
    model : app.vehicle.model,
    url:"/index.php/service/vehicles.json"
})
app.vehicle.vehicleList=new app.vehicle.collection();
app.vehicle.itemView= Backbone.View.extend({
    tagName:'li',
    className:'vehicle-item',
    template: _.template($('#vehicle_item_template').html()),
    events:{

    },
    initialize:function(){
        _.bindAll(this,"locateMarker")
        this.model.bind('showMarker',this.locateMarker);
        this.model.view=this;//They Call It a big No no so idont know what to do :D
        this.$el.on("click",".addMarker",this,this.addMarker);
        this.$el.on("click",".removeMarker",this,this.removeMarker);
        this.$el.on("click",".refreshModel",this,this.refreshModel);
        this.$el.on("click",this,this.locateMarker);

        this.listenTo(this.model,'sync' ,this.fetch_success);
    },
    render:function(){
        this.$el.html(this.template(this.model.toJSON()));
        this.addMarker({data:this});
        return this;

    },
    fetch_success:function(){
      console.log("from Item View");
    },
    refreshModel:function(e){
        e.data.model.fetch();

    },
    addMarker:function(e){
      var self= e.data;
        if(!self.marker){
            var  _marker= new app.map.markerView({model:self.model,itemView:self});

        }
        self.marker=_marker.render();

    },
    removeMarker:function(e){

        var self= e.data;
            if(self.marker){
                self.marker.remove();
                self.marker=null;
            }

    },
    locateMarker:function(e){
        if(typeof e === "undefined"){
           var self=this;
        }else{
            var self= e.data;
        }

        if(self.marker){
            self.marker.locate();

        }

    },
    highlight:function(){
        //We Will Know that marker Has been Selected

        this.$el.addClass('highlighted_item');
    },
    unLight:function(){
        //We Will Know that marker Has been unSelected
        console.log('fromItem');
        this.$el.removeClass('highlighted_item');
    }
//    someTrigger : function(e){
//        viewObj=e.data
//        e.preventDefault();
//        console.log("some Trigger");
//        viewObj.model.trigger("someEvent",e);

//    }
})
app.vehicle.listView=Backbone.View.extend({

    el:'#vehicle_list>#list>ul',
    events:{

    },
    initialize:function(){
        this.listenTo(app.vehicle.vehicleList,'sync',this.render);

//        this.$el.parents('#vehicle_list').draggable();
//        this.$el.parents('#vehicle_list').offset({top:120,left:20});
    },
    refresh:function(e){
        e.preventDefault();
      this.collection.fetch();
    },
    render:function(){
        that=this;
        this.$el.slideUp('slow',function(){

            _.each(app.vehicle.vehicleList.models,function(item){
                if(!item.view){
               var itemV=new app.vehicle.itemView({model:item});
                this.$el.append(itemV.render().el);
                }
            },that);
            that.$el.show("slow");
        })
       

    }
});
app.map.infoView =  Backbone.View.extend({
    el:$('#info_box'),
    initialize:function(){
//        this.$el.hide();
//        this.$el.offset({top:120,left:$(window).width()-500});
//        this.$el.draggable();
    },
    showBox:function(model){
        //TODO : should inmplent for incident
        // TODO :should refresh every time open
        //show info from Model and render in Template ,Select Template Depend on Model Type;
        //stO


        this.model=model;
        this.template= _.template($('#info_table_template').html());
        this.$el.show();
        this.$el.html(this.template(this.model.toJSON()));

    },
    hideBox:function(){
        delete this.template;
        this.$el.hide();
    }
})
// Toolbar'
app.toolbar={}
app.toolbar.view=Backbone.View.extend({
   el:$('#toolbar'),
    initialize:function(){

        this.$el.offset({top:30,left:20});
        this.$el.draggable();
        this.$el.on('click','#insert_incident',this,this.toggleInsertIncident);
    },
    events:{

    },
    toggleInsertIncident:function(e){
        var self= e.data;
        console.log(e.target);
        if(!$(e.target).hasClass('active')){
            console.log('checked');
            app.map.mapObj.events.register('click', app.map.mapObj,app.incident.insertIncident);
        }else{

            app.map.mapObj.events.unregister('click',app.map.mapObj,app.incident.insertIncident);
        }

    }


})
// INCIDENTS
app.incident={};
app.incident.model=Backbone.Model.extend({
    initialize:function(){

    },
    methodToURL:function() {
       var urls={

        'read': '/index.php/service/incident.json/ID/'+this.get('ID'),
        'create': '/index.php/service/incident.json',
        'update': '/index.php/service/incident.json',
        'delete': '/index.php/service/incident.json'
        }
        return urls;
    },
    newSaved:function(model,method,options){
        app.incident.incidentList.add(model);
        app.incident.incidentForm.close();
    },
    sync: function(method, model, options) {
        options = options || {};
        options.url = model.methodToURL()[method.toLowerCase()];
        if(method=='create'){
            options.success=this.newSaved;
                options.wait=true;
        }
        Backbone.sync(method, model, options);
    }

});
app.incident.collection= Backbone.Collection.extend({
    url:"/index.php/service/incidents.json",
    model:app.incident.model
});
app.incident.incidentList=new app.incident.collection();

app.incident.itemView=Backbone.View.extend({
   tagName:'tr',
    template: _.template($('#incident_item_template').html()),
    initialize:function(){
            this.model.on('remove',this.remove,this);
            this.model.on('change',this.render, this);
        _.bindAll(this);
            this.$el.on('click',this.selected);

//            this.model.view=this;//They Call It a big No no so idont know what to do :D
//            this.$el.on("click",".addMarker",this,this.addMarker);
//           this.$el.on("click",".removeMarker",this,this.removeMarker);
           this.$el.on("click",".refreshModel",this,this.refreshModel);
//            this.$el.on("click",this,this.locateMarker);
//
//            this.listenTo(this.model,'sync' ,this.fetch_success);
    },
    render:function(){
        this.$el.html(this.template(this.model.toJSON()));
        if(!this.marker){
        var _marker=new app.incident.markerView(({model:this.model,itemView:this}));
        this.marker=_marker.render();
        }


        return this
    },
    remove:function(){
        console.log('removed')
    },
    refreshModel:function(e){
       var self= e.data;
        self.model.fetch();
    },
    selected:function(e){
      $('.highlighted_item').removeClass('highlighted_item');
      this.highlight();
      this.marker.locate();
    },
    highlight:function(){
        //We Will Know that marker Has been Selected
        console.log(this.$el);
        $('#incident_list').find('.highlighted_item').removeClass('highlighted_item');
        this.$el.addClass('highlighted_item');
    },
    unLight:function(){
        //We Will Know that marker Has been unSelected
        console.log('fromItem');
        this.$el.removeClass('highlighted_item');
    }
});
app.incident.markerView=Backbone.View.extend({
    initialize:function(options){
        //bind to model events
        this.listenTo(this.model,'change' ,this.dataChanged);
        this.itemView =options.itemView;
        this.LonLat = new OpenLayers.LonLat(this.model.get('position').lon,this.model.get('position').lat).transform(app.map.mapObj.displayProjection, app.map.mapObj.projection);
        var point =  new OpenLayers.Geometry.Point (this.LonLat.lon, this.LonLat.lat);
        var that=this

        this.marker = new OpenLayers.Feature.Vector(point,  null,{
            externalGraphic: "img/"+that.model.get('type')+".png",
            graphicWidth: 30,
            graphicHeight: 48,
            graphicYOffset: -40,
            graphicXOffset: -18,
            fillOpacity: 0.7,
            label:this.model.get('descript')
        });
        this.marker.view=this;

//        this.marker2 = new OpenLayers.Feature.Vector(point.clone(), null,{
//            pointRadius: 2
//        });
        this.f=[];
        this.f.push(this.marker);
    },
    render:function(){
        app.map.markersLayer.addFeatures(this.f);
        app.map.mapObj.setCenter(this.LonLat,15);
        return this;
    },
    remove:function(){
        app.map.markersLayer.removeFeatures([this.marker]);
    },
    locate:function(){
        app.map.mapObj.panTo(this.LonLat);
    },
    select:function(e){
        console.log(this);
        //open info box
        app.incident.infoBox.showBox(this.model);
        this.itemView.highlight();

    },
    unselect:function(e){
        console.log(this);
        //close Info Box
        app.incident.infoBox.hideBox();
        this.itemView.unLight();
        this.marker.style.fillOpacity= 0.7
    },
    dataChanged:function(){
        console.log('data Changed from marker');
        this.marker.move(new OpenLayers.LonLat(this.model.get('position').lng,this.model.get('position').lat).transform(app.map.mapObj.displayProjection, app.map.mapObj.projection)
        )
    }


})
app.incident.listView=Backbone.View.extend({
   el:$('#incident_list'),
    initialize:function(){
        this.collection=app.incident.incidentList;
        this.collection.fetch();
        this.collection.on('add',this.add,this);
        this.collection.on('remove',this.remove,this);
        this.render();
    },
    render:function(){
        this.$el.draggable({ handle: ".handler" });
        this.$el.offset({top:120,left:600});
    },
    add:function(model){
        this.$el.find('.list_wrapper').append((new app.incident.itemView({model:model})).render().el)
    },
    remove:function(model){
        model.trigger('remove');
    }

});
app.incident.formView=Backbone.View.extend({
   el:$('#incident_form'),
    initialize:function(){
        _.bindAll(this);
        this.$el.hide();
        this.$el.draggable();
        this.template= _.template($('#incident_form_template').html());

    },
    events:{
        "click button":"submit"
    },
    render:function(){
        this.$el.html(this.template(this.model.toJSON()));
        this.$el.show();


    },
    addIncident:function(model){
        this.model=model;
        this.render();
    },
    submit:function(e){
        // TODO : form data validation
        e.preventDefault();
        this.model.set({

            type:this.$el.find('#type').val(),
            descript:this.$el.find('#descript').val()
        });
        if(this.model.hasChanged()){
            this.model.save();
        }

    },
    close:function(e){
        this.model=null;
        this.$el.hide();
        $('#insert_incident').removeClass('active');
        app.map.mapObj.events.unregister('click',app.map.mapObj,app.incident.insertIncident);
    }
});
app.incident.infoView =  Backbone.View.extend({
    el:$('#incident_info_box'),
    events:{
        "change input":"search"
    },
    initialize:function(){
        this.sugCollection=app.incident.suggestion.collectionObj;
        this.$el.hide();
        this.$el.offset({top:120,left:$(window).width()-500});
        this.$el.draggable();
        this.listenTo(this.sugCollection,"sync",this.renderSug,this);
        this.listenTo(this.sugCollection,"error",this.noResult,this);
        this.listenTo(this.sugCollection,"remove",this.removingItems,this);
    },
    showBox:function(model){

        //show info from Model and render in Template ,Select Template Depend on Model Type;

        this.model=model;
        this.template= _.template($('#incidnet_info_table_template').html());
        this.$el.show();
        this.$el.find('#info').html(this.template(this.model.toJSON()));

        this.search()
    },
    search:function(){
        var filter=this.checkBox();
        this.sugCollection.remove(this.sugCollection.models);
        this.sugCollection.fetch({url:"/index.php/service/vehiclesSuggestion.json/incident/"+this.model.get('ID')+"/distance/1000/status/"+filter,reset:true});


    },
    checkBox:function(){
      var filter="";
        this.$el.find('input:checked').each(function(){
          filter += $(this).val();
          console.log(filter);

      })
        return filter;
    },
    renderSug:function(e){
        console.log(this.sugCollection);
        this.$el.find('.alert').remove();


        _.each(this.sugCollection.models,function(item){
            console.log(item)
            this.$el.find('#response').append(new app.incident.suggestion.itemView({model:item,incidentModel:this.model}).render().el);
        },this)

    },
    noResult:function(e){
        console.log('error');
        this.$el.find('.alert').remove();
        this.$el.find('#response').append("<div class='alert'>هیچ خودرویی برای پیشنهاد وجود ندارد</div> ");
        return false;
    },
    removingItems:function(model,collection){
        model.trigger("imRemoved");
    },
    hideBox:function(){
        delete this.template;
        this.$el.hide();
        this.$el.find('#response').html("");

    }

})



//Vehicle Suggestion List
app.incident.suggestion={}
app.incident.suggestion.collection=Backbone.Collection.extend({
    model:app.vehicle.model
});
app.incident.suggestion.collectionObj=new app.incident.suggestion.collection();
app.incident.suggestion.itemView=Backbone.View.extend({
    template: _.template($('#suggestion_item_template').html()),
    initialize:function(options){
        _.bindAll(this);
        this.incidentModel=options.incidentModel;
        this.$el.on("click",this.findMarker);
        this.model.on("imRemoved",this.suicide)
    },
    events:{
        "click a.btn" : "request"
    },
    request:function(){
        this.requestModel=new app.incident.response.orderRequest();
        this.requestModel.on('sync',this.showRequestResponse);
        this.requestModel.set('vehicle',this.model.get('ID'));
        this.requestModel.set('incident',this.incidentModel.get('ID'));
        this.requestModel.save();
    },
    showRequestResponse:function(){
        this.$el.html(this.requestModel.get('orderID'));
        this.requestModel.clear();
    },
    findMarker:function(){
        var vehicleItem=app.vehicle.vehicleList.findWhere({ID:this.model.get('ID')});
        vehicleItem.trigger('showMarker');
    },
    render:function(){
        this.$el.html(this.template(this.model.toJSON()));
        return this
    },
    suicide:function(){
        this.remove();
    }
})
//incident Ordering to Vehicle
app.incident.response={};
app.incident.response.orderRequest=Backbone.Model.extend({
    url:"/index.php/service/orderrequest.json"
})


// TODO app.incident.vehicleSugView=Backbone.View.extend({
//    collection:app.incident.vehicleSugObj,
//    render:function(){
//
//    }
//})

// Function To Handel Inserting Incident
 app.incident.insertIncident = function (e){
    console.log("from  Insert Incident");
    var lonlat = app.map.mapObj.getLonLatFromPixel(e.xy).transform(app.map.mapObj.projection,app.map.mapObj.displayProjection);
    var newIncident=new app.incident.model();
     var position={};
     position.lat=lonlat.lat;
     position.lon=lonlat.lon
    newIncident.set({position:position});
     app.incident.incidentForm.addIncident(newIncident);
}
app.render = function (){
    app.makePanels();
    var mapView = new app.map.view({mapType:"osm"});
    var vehcileListView= new app.vehicle.listView();

    app.map.infoBox=new app.map.infoView();
    app.toolbar.obj=new app.toolbar.view();
    app.vehicle.vehicleList.fetch();
    app.incident.incidentForm=new app.incident.formView();
    app.incident.listObj=new app.incident.listView();
    app.incident.infoBox=new app.incident.infoView();


}
app.makePanels=function(){
    $('#vehicle-panel').tabSlideOut({
        tabHandle: '.handle',                     //class of the element that will become your tab
        pathToTabImage: 'img/vehicle-panel.png', //path to the image for the tab //Optionally can be set using css
        imageHeight: '62px',                     //height of tab image           //Optionally can be set using css
        imageWidth: '62px',                       //width of tab image            //Optionally can be set using css
        tabLocation: 'right',                      //side of screen where tab lives, top, right, bottom, or left
        speed: 300,                               //speed of animation
        action: 'click',                          //options: 'click' or 'hover', action to trigger animation
        topPos: '100px',                          //position from the top/ use if tabLocation is left or right
        leftPos: '20px',                          //position from left/ use if tabLocation is bottom or top
        fixedPosition: false                      //options: true makes it stick(fixed position) on scroll
    });
//    $('#vehicle-panel').resizable({handles:"sw"}) ;

}
$(document).ready(function(){
    app.render();



})

;
