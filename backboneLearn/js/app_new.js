var app = app || {};
//agreg
app.vent = _.extend({}, Backbone.Events);
app.selectedIncident=null;
app.listItem = Backbone.Model.extend({

});
app.listItems = Backbone.Collection.extend({
    model: app.listItem,

    url: "/index.php/service/centers.json",
    initialize: function () {

    },
    filter:function(filter){
       var filterurl=this.url+"/"+filter
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
        app.map.imarkersLayer= new OpenLayers.Layer.Vector("Features");
        this.trackUrl="";
        app.map.trackLayer= new OpenLayers.Layer.Vector("Track",{
            projection: this.map.displayProjection,
            strategies: [new OpenLayers.Strategy.Fixed()],

            protocol: new OpenLayers.Protocol.HTTP({
                //set the url to your variable//
                url: "",
                //format this layer as KML//
                format: new OpenLayers.Format.KML({
                    //maxDepth is how deep it will follow network links//
                    maxDepth: 1,
                    //extract styles from the KML Layer//
//                    extractStyles: true,
                    //extract attributes from the KML Layer//
                    extractAttributes: true
                })
            })
        });

        app.map.featureSelect= new OpenLayers.Control.SelectFeature(app.map.markersLayer,
            {
                multiple: true,
                toggle: false,
                multipleKey: 'shiftKey'

            }
        );
        app.map.mapObj.addControl(app.map.featureSelect);
        app.map.featureSelect.activate();


        //Config The Map And Layers
        this.map.setCenter(new OpenLayers.LonLat(34, 54).transform(this.map.displayProjection, this.map.projection),15);
        this.renderMap();

        this.renderTrackLayer();
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
    renderTrackLayer:function(){
       this.map.addLayer(app.map.trackLayer);
       app.map.trackLayer.setVisibility(false);
    },
    refreshTrackLayer:function(SID){
        this.trackUrl='http://shadeland.net/index.php/kml?vehicleID='+SID;

        app.map.trackLayer.setVisibility(true);
        app.map.trackLayer.refresh({url:this.trackUrl});
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
        _.bindAll(this);
        //bind to model events
        this.listenTo(this.model,'change' ,this.dataChanged);
        this.itemView =options.itemView;
        this.LonLat = new OpenLayers.LonLat(this.model.get('LonLat').lng,this.model.get('LonLat').lat).transform(app.map.mapObj.displayProjection, app.map.mapObj.projection);
        var point =  new OpenLayers.Geometry.Point (this.LonLat.lon, this.LonLat.lat);
        var that=this

        this.marker = new OpenLayers.Feature.Vector(point,  null,{
            externalGraphic: "img/"+that.model.get('type')+"_"+app.statusParser(that.model)+".png",
            graphicWidth: 55,
            graphicHeight: 60,
            graphicYOffset: -60,
            graphicXOffset: -27,
            fillOpacity: 0.7

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

        //open info box
        app.map.infoBox.showBox(this.model);
        this.linedraw();
        app.map.mapView.refreshTrackLayer(this.model.get('ID'));
        this.itemView.highlight();
        if(!$('.handle').parent().hasClass('open')){
            $('.handle').click();
        }


    },
    unselect:function(e){
        console.log(this);
        //close Info Box
        app.map.infoBox.hideBox();
        this.itemView.unLight();
        this.marker.style.fillOpacity= 0.7
    },
    linedraw:function(){
        var style_green = {
            strokeColor: "#00FF00",
            strokeWidth: 3,
            strokeDashstyle: "dashdot",
            pointRadius: 6,
            pointerEvents: "visiblePainted",
            title: "this is a green line"
        };

        console.log('data Changed from marker');
        that=this;
        if(!_.isNull(this.model.get('order'))){

            inc=app.incident.incidentList.findWhere({ID:this.model.get('order').incident_ID});
            var pointList = [];

            incPoint = new OpenLayers.Geometry.Point(
                inc.get('position').lon,inc.get('position').lat ).transform(app.map.mapObj.displayProjection, app.map.mapObj.projection);
            vpoint= new OpenLayers.Geometry.Point(
                this.model.get('LonLat').lng,this.model.get('LonLat').lat).transform(app.map.mapObj.displayProjection, app.map.mapObj.projection);
            pointList.push(incPoint,vpoint);
            console.log(pointList);
            var lineFeature = new OpenLayers.Feature.Vector(
                new OpenLayers.Geometry.LineString(pointList),null,style_green);
            app.map.markersLayer.addFeatures(lineFeature);
        };
    },
    dataChanged:function(){

        console.log('data Changed from marker');
        that=this;

        this.marker.style.externalGraphic="img/"+that.model.get('type')+"_"+app.statusParser(that.model)+".png",
//        this.marker.imageDiv.firstChild.setAttribute(
//            'src', "img/"+that.model.get('type')+"_"+app.statusParser(that.model)+".png");
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
        distance:0,
    },
    initialize:function(){
        _.bindAll(this);
      this.bind('sync',function(){
          console.log('foo from model');
      })
    },
    orderRequest:function(incidentID){
       var that=this;
        $.ajax({
            url:"/index.php/service/orderrequest.json",
            type:"post",
            data:{vehicle:that.get('ID'),incident:incidentID}
        }).done(function(){
               that.trigger("orderRequested");
                that.fetch({url:"/index.php/service/vehicle.json/vehicleID/"+that.get("ID")+"/format/json"});
            })
    },
    orderCancel:function(){
        var that=this;
        $.ajax({
            url:"/index.php/service/ordercancel.json",
            type:"post",
            data:{vehicle:that.get('ID'),order:that.get('order')}
        }).done(function(){
                that.trigger("orderCanceled");
                that.set('order',null);
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
        this.listenTo(app.vent,'incidentSelect',this.calcDistance);
        this.listenTo(app.vent,'incidentUnselect',this.discalc);
        this.listenTo(this.model,'sync' ,this.fetch_success);
        this.model.on('change',this.refresh,this);
    },
    render:function(){
        this.$el.html(this.template(this.model.toJSON()));
        this.addMarker({data:this});
        return this;

    },
    refresh:function(e){

        this.$el.html(this.template(this.model.toJSON()));
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

    discalc:function(){
      this.model.set('distance',0);
    },
    calcDistance:function(){
      lat=this.model.get('LonLat').lat;
      lon=this.model.get('LonLat').lng;
      inlat=app.selectedIncident.model.get('position').lat;
      inlon=app.selectedIncident.model.get('position').lon;

        distance=app.distance(lat,lon,inlat,inlon);
        this.model.set('distance',distance);
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

    el:$('#vehicle_list').find('#list>ul'),
    events:{

    },
    initialize:function(){
        this.listenTo(app.vehicle.vehicleList,'sync',this.render);
        $('#vehicle_list').mCustomScrollbar();

//        this.$el.parents('#vehicle_list').draggable();
//        this.$el.parents('#vehicle_list').offset({top:120,left:20});
    },
    refresh:function(e){
        e.preventDefault();
      this.collection.fetch();
    },
    render:function(){
        that=this;


            _.each(app.vehicle.vehicleList.models,function(item){
                if(!item.view){
               var itemV=new app.vehicle.itemView({model:item});
                    this.$el.append(itemV.render().el);
                     $('#vehicle_list').mCustomScrollbar("update");
                }
            },that);


       

    }
});
app.map.infoView =  Backbone.View.extend({
    el:$('#info_box'),
    initialize:function(){
//        this.$el.hide();
//        this.$el.offset({top:120,left:$(window).width()-500});
//        this.$el.draggable();
        this.$el.mCustomScrollbar({advanced:{
            updateOnContentResize: true
        }});
        this.listenTo(app.vent,'incidentSelect',function(){
            if(this.model!=undefined){
            this.$el.find('.order_btn').removeAttr('disabled');
                var that=this
            setTimeout(function(){that.$el.find('.order_btn').append("به سانحه شماره "+app.selectedIncident.model.get('ID')+"("+that.model.get('distance')+")")},1000)
            }
        },this)
        this.listenTo(app.vent,'incidentUnselect',function(){
                if(this.model!=undefined){
            this.$el.find('.order_btn').attr('disabled','disabled');
                }
        },this);

        this.$el.on("click",".order_btn",this,this.sendOrder);
        _.bindAll(this);
    },
    sendOrder:function(e){
        self= e.data;
        self.model.orderRequest(app.selectedIncident.model.get('ID'));
        self.$el.find('.order_btn').attr('disabled','disabled');
        self.$el.find('.order_btn').html("درحال ارسال");

    },
    showBox:function(model){
        //TODO : should inmplent for incident
        // TODO :should refresh every time open
        //show info from Model and render in Template ,Select Template Depend on Model Type;
        //stO


        this.model=model;
        this.listenTo(this.model,'orderRequested',function(){
            if(this.model!=undefined){
                this.$el.find('.order_btn').attr('disabled','disabled');
                this.$el.find('.order_btn').html('در ماموریت');
            }
        },this);
        this.template= _.template($('#info_table_template').html());
        this.$el.show();
        this.$el.find('.content').html(this.template(this.model.toJSON()));
        if(app.selectedIncident==null){
            this.$el.find('.order_btn').attr('disabled','disabled');
        }else{
            this.$el.find('.order_btn').append("به سانحه شماره "+app.selectedIncident.model.get('ID')+"("+this.model.get('distance')+")");
        }
//        this.$el.mCustomScrollbar('update');

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

//        this.$el.offset({top:30,left:20});
//        this.$el.draggable();
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
        app.incident.incidentForm.close(null);
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
      $('.highlighted_item-incident').removeClass('highlighted_item-incident');
      this.highlight();
      this.marker.locate();
    },
    highlight:function(){
        //We Will Know that marker Has been Selected
        console.log(this.$el);
        $('.highlighted_item-incident').each(function(){
           $(this).removeClass('highlighted_item-incident');
        });
        this.$el.addClass('highlighted_item-incident');
    },
    unLight:function(){
        //We Will Know that marker Has been unSelected
        console.log('fromItem');
        this.$el.removeClass('highlighted_item-incident');
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
            fillOpacity: 0.7
//            label:this.model.get('descript')
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
        if(!$('.handle2').parent().hasClass('open')){
            $('.handle2').click();
        }
        app.selectedIncident=this;
        app.vent.trigger('incidentSelect');

    },
    unselect:function(e){
        console.log(this);
        //close Info Box
        app.incident.infoBox.hideBox();
        this.itemView.unLight();
        this.marker.style.fillOpacity= 0.7
        app.selectedIncident=null;
        app.vent.trigger('incidentUnselect');
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
        this.$el.mCustomScrollbar();
    },
    add:function(model){
        this.$el.find('.list_wrapper').append((new app.incident.itemView({model:model})).render().el);
        this.$el.mCustomScrollbar("update");
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
        "click button":"submit",
        "click .close":"close"
    },
    render:function(){
        this.$el.html(this.template(this.model.toJSON()));
        this.$el.show();
        this.$el.css("position","absolute");
        this.$el.css("top", Math.max(0, (($(window).height() - this.$el.outerHeight())) +
            $(window).scrollTop()) + "px");
        this.$el.css("left", Math.max(0, (($(window).width() - this.$el.outerWidth()) / 2) +
            $(window).scrollLeft()) + "px");


    },
    addIncident:function(model){
        console.log(model);
        this.model=model;
        var that=this
        address='http://nominatim.openstreetmap.org/reverse?format=json&lat='+model.get('position').lat+'&lon='+model.get('position').lon+'&zoom=27';
        $.getJSON( address, function( data ) {
            console.log(data.display_name);
            that.model.set('address',data.display_name);
            that.render();
            });



    },
    submit:function(e){
        // TODO : form data validation
        e.preventDefault();
        this.model.set({

            type:this.$el.find('#type').val(),
            descript:this.$el.find('#descript').val(),
            address:this.$el.find('#address').val()
        });
        if(this.model.hasChanged()){
            this.model.save();
        }
        this.close(e);

    },
    close:function(e){
        e.preventDefault();
        this.model=null;
        this.$el.hide();
        $('#insert_incident').removeClass('active');
        app.map.mapObj.events.unregister('click',app.map.mapObj,app.incident.insertIncident);
    }
});
// Actually This is View For All Suggestion And Dedicated And Information Views With One Global Object !
app.incident.infoView =  Backbone.View.extend({
    el:$('#incident_info_box'),
    events:{
        "change input":"search"
    },
    initialize:function(){
        this.sugCollection=app.incident.suggestion.collectionObj;
        this.$el.hide();

        this.listenTo(this.sugCollection,"Result",this.renderSug,this);
        this.listenTo(this.sugCollection,"noResult",this.noResult,this);
        this.listenTo(this.sugCollection,"remove",this.removingItems,this);


        this.$el.find('#incident-sug-list').mCustomScrollbar();
    },
    showBox:function(model){

        //show info from Model and render in Template ,Select Template Depend on Model Type;

        this.model=model;
        this.template= _.template($('#incidnet_info_table_template').html());
        this.$el.show();
        this.$el.find('#info').html(this.template(this.model.toJSON()));
        this.$el.find('#info').mCustomScrollbar();
        this.search()
    },
    search:function(){
        var filter=this.checkBox();
        //For Dedicated Vehicles we will start listen to them here ,the we should stop lintning

        this.listenTo(app.incident.dedicated.collectionObj,"Result",this.renderDedicated,this);
        this.listenTo(app.incident.dedicated.collectionObj,"noResult",this.noResultDedicated,this);
        this.listenTo(app.incident.dedicated.collectionObj,"remove",this.removingItems,this);
        this.sugCollection.remove(this.sugCollection.models);
        this.sugCollection.getData({url:"/index.php/service/vehiclesSuggestion.json/incident/"+this.model.get('ID')+"/distance/1000/status/"+filter,reset:true});
        // Dedicated Vehicle
        // Using Golobal Object For Collection !Each time a new incident selected will be refreshed by new data !
        app.incident.dedicated.collectionObj.getData({url:"/index.php/service/vehiclesDedicated.json/incident/"+this.model.get('ID'),reset:true});

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
        this.$el.find('#incident-sug-list').find('#content').html("");


        _.each(this.sugCollection.models,function(item){
            console.log(item)
            this.$el.find('#incident-sug-list').find('#content').append(new app.incident.suggestion.itemView({model:item,incidentModel:this.model}).render().el);
        },this);
        this.$el.find('#incident-sug-list').mCustomScrollbar("update");

    },
    noResult:function(e){
        console.log('error');
        this.$el.find('.alert').remove();
        this.$el.find('#incident-sug-list').find('#content').append("<div class='alert'>هیچ خودرویی برای پیشنهاد وجود ندارد</div> ");
        return false;
    },
    removingItems:function(model,collection){
        model.trigger("imRemoved");
    },
    renderDedicated:function(e){
        console.log('render dedicateds!')
    },
    noResultDedicated:function(){
        console.log('Dedicated Returns No Result!');
    },
    hideBox:function(){
        delete this.template;
        this.$el.hide();
//        this.$el.find('#response').html("");
        this.$el.find('#info').mCustomScrollbar("destroy");

    }

})



//Vehicle Suggestion List
app.incident.suggestion={}
app.incident.suggestion.collection=Backbone.Collection.extend({
    model:app.vehicle.model,
    getData:function(Option){
        Option=Option || {};
        Option.success=this.syncSuc;
        Option.error= this.syncError;
        this.fetch(Option);
    },
    syncError:function(collection,response,options){

        if(response.responseText===""){
            collection.trigger("noResult");
        }
    },
    syncSuc:function(collection,response,options){


            collection.trigger("Result");

    }


});
app.incident.suggestion.collectionObj=new app.incident.suggestion.collection();
app.incident.suggestion.itemView=Backbone.View.extend({
    template: _.template($('#suggestion_item_template').html()),
    tagName:'li',
    className:'vehicle-item',
    initialize:function(options){
        _.bindAll(this);
        this.incidentModel=options.incidentModel;
        this.$el.on("click",this.findMarker);
        this.model.on("imRemoved",this.suicide);
        this.model.on("change",this.render);
    },
    events:{
        "click a#send-order" : "request",
        "click a#cancel-order" : "cancel"
    },
    request:function(e){
        e.preventDefault();
        this.model.orderRequest(this.incidentModel.get('ID'));
    },
    cancel:function(e){
        e.preventDefault();
        this.model.orderCancel();
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
        this.$el.html("")
        this.$el.html(this.template(this.model.toJSON()));
        return this
    },
    suicide:function(){
        this.remove();
    }
})
//Vechile Dedicsted to Vehicle
app.incident.dedicated={};
app.incident.dedicated.collection=Backbone.Collection.extend({
    model:app.vehicle.model,
    getData:function(Option){
        Option=Option || {};
        Option.success=this.syncSuc;
        Option.error= this.syncError;
        this.fetch(Option);
    },
    syncError:function(collection,response,options){

        if(response.responseText===""){
            collection.trigger("noResult");
        }
    },
    syncSuc:function(collection,response,options){


        collection.trigger("Result");

    }
})
app.incident.dedicated.collectionObj=new app.incident.dedicated.collection();
app.incident.dedicated.itemView=Backbone.View.extend({
    template: _.template($('#suggestion_item_template').html()),
    tagName:'li',
    className:'vehicle-item',
    initialize:function(options){
        _.bindAll(this);
        this.incidentModel=options.incidentModel;
        this.$el.on("click",this.findMarker);
        this.model.on("imRemoved",this.suicide);
        this.model.on("change",this.render);
    },
    events:{
        "click a#send-order" : "request",
        "click a#cancel-order" : "cancel"
    },
    request:function(e){
        e.preventDefault();
        this.model.orderRequest(this.incidentModel.get('ID'));
    },
    cancel:function(e){
        e.preventDefault();
        this.model.orderCancel();
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
        this.$el.html("")
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
    app.map.mapView = new app.map.view({mapType:"osm"});
    var vehcileListView= new app.vehicle.listView();

    app.map.infoBox=new app.map.infoView();
    app.toolbar.obj=new app.toolbar.view();
    app.vehicle.vehicleList.fetch();
    app.incident.incidentForm=new app.incident.formView();
    setTimeout(function(){
        app.incident.listObj=new app.incident.listView();
    },1000)

    app.incident.infoBox=new app.incident.infoView();
//    app.mapView.refreshTrackLayer('1234');


}
app.makePanels = function () {
//    $('overlay_listl').tabSlideOut({
//        tabHandle: '.handle',                     //class of the element that will become your tab
//        pathToTabImage: 'img/vehicle-panel.png', //path to the image for the tab //Optionally can be set using css
//        imageHeight: '62px',                     //height of tab image           //Optionally can be set using css
//        imageWidth: '62px',                       //width of tab image            //Optionally can be set using css
//        tabLocation: 'right',                      //side of screen where tab lives, top, right, bottom, or left
//        speed: 300,                               //speed of animation
//        action: 'click',                          //options: 'click' or 'hover', action to trigger animation
//        topPos: '100px',                          //position from the top/ use if tabLocation is left or right
//        leftPos: '20px',                          //position from left/ use if tabLocation is bottom or top
//        fixedPosition: false                      //options: true makes it stick(fixed position) on scroll
//    });
//    $('#incident-panel').tabSlideOut({
//        tabHandle: '.handle2',                     //class of the element that will become your tab
//        pathToTabImage: 'img/incident-panel.png', //path to the image for the tab //Optionally can be set using css
//        imageHeight: '62px',                     //height of tab image           //Optionally can be set using css
//        imageWidth: '62px',                       //width of tab image            //Optionally can be set using css
//        tabLocation: 'left',                      //side of screen where tab lives, top, right, bottom, or left
//        speed: 300,                               //speed of animation
//        action: 'click',                          //options: 'click' or 'hover', action to trigger animation
//        topPos: '127px',                          //position from the top/ use if tabLocation is left or right
//        leftPos: '20px',                          //position from left/ use if tabLocation is bottom or top
//        fixedPosition: false                      //options: true makes it stick(fixed position) on scroll
//    });

//    $('#vehicle-panel').resizable({handles:"sw"}) ;

};
app.statusParser = function(model){
    status=model.get('status').status_ID;
    if(!_.isNull(model.get('order'))){
        return "red";
    }
    if(_.isNull(status) || _.isUndefined(status)){
        // No data
        return "normal";
    }
    if(status.indexOf('1')=== -1){
        // Switched off
        return "gray";
    }
    if(status.indexOf('2')=== -1){
        // In mission
        return "red";
    }
//    if(status.indexOf('5')!==-1){
//        //Waiting for Response to Sended Order
//        return "orange"
//    }
    return "normal";
}
$(document).ready(function(){
    app.render();
    setInterval(function(){
        app.vehicle.vehicleList.fetch();
    },300000)


})
app.distance=function(lat1, lon1, lat2, lon2, unit) {

    var R = 6371; // km (change this constant to get miles)
    var dLat = (lat2-lat1) * Math.PI / 180;
    var dLon = (lon2-lon1) * Math.PI / 180;
    var a = Math.sin(dLat/2) * Math.sin(dLat/2) +
        Math.cos(lat1 * Math.PI / 180 ) * Math.cos(lat2 * Math.PI / 180 ) *
            Math.sin(dLon/2) * Math.sin(dLon/2);
    var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
    var d = R * c;
    if (d>1) return Math.round(d)+"km";
    else if (d<=1) return Math.round(d*1000)+"m";
    return d;
}

;
