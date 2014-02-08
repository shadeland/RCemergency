var app = app || {};
//agreg
app.vent = _.extend({}, Backbone.Events);
app.selectedIncident=null;

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
                    extractStyles: true,
                    //extract styles from the KML Layer//
//                    extractStyles: true,
                    //extract attributes from the KML Layer//
                    extractAttributes: true
                })
            })
        });
        this.map.addLayer(app.map.trackLayer);
        app.map.featureSelect= new OpenLayers.Control.SelectFeature(app.map.trackLayer);
        app.map.mapObj.addControl(app.map.featureSelect);
        app.map.featureSelect.activate();

        console.log(app.lon+"   "+app.lat);
        //Config The Map And Layers
        this.map.setCenter(new OpenLayers.LonLat(app.lon,app.lat).transform(this.map.displayProjection, this.map.projection),15);
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

    },
    renderTrackLayer:function(){

        app.map.trackLayer.events.register('featureselected', this, this.selected_feature);
        app.map.trackLayer.events.register('featureunselected', this,this.unselected_feature);
        app.map.trackLayer.setVisibility(false);
    },
    refreshTrackLayer:function(SID){
//        this.trackUrl='http://shadeland.net/index.php/kml_service/order/'+SID;
        this.trackUrl='http://shadeland.net/index.php/kml_service/dataproxy/105/2014-01-31%2016:00:29/2014-02-1%2016:00:29';
        app.map.trackLayer.setVisibility(true);
        app.map.trackLayer.refresh({url:this.trackUrl});
    },

    selected_feature:function(e){

        var feature = e.feature;
        console.log(feature);
        // Since KML is user-generated, do naive protection against
        // Javascript.
        var content = feature.attributes.description;

        popup = new OpenLayers.Popup.FramedCloud("chicken",
            feature.geometry.getBounds().getCenterLonLat(),
            new OpenLayers.Size(100,100),
            content,
            null, true, this.onPopupClose);

        feature.popup = popup;
        app.map.mapObj.addPopup(popup);
//        e.feature.style.fillOpacity= 1;
//        e.feature.layer.redraw();
//        e.feature.view.select(e);
    },
    unselected_feature:function(e){
        console.log("unselect");
//        e.feature.style.fillOpacity= 0.7;
//        e.feature.layer.redraw();
//        e.feature.view.unselect(e);
        var feature = e.feature;
        if(feature.popup) {
            app.map.mapObj.removePopup(feature.popup);
            feature.popup.destroy();
            delete feature.popup;
        }
    },
    onPopupClose:function(){
        console.log("close");
        app.map.featureSelect.unselectAll();
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




//Vehicle Suggestion List



// TODO app.incident.vehicleSugView=Backbone.View.extend({
//    collection:app.incident.vehicleSugObj,
//    render:function(){
//
//    }
//})

// Function To Handel Inserting Incident

app.render = function (){

    app.map.mapView = new app.map.view({mapType:"osm"});
   setTimeout(function(){
       app.map.mapView.refreshTrackLayer(app.orderID)
   },500)







}


$(document).ready(function(){
    app.render();





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
