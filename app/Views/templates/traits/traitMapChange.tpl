<script>

var earth_radius = 6389125.541;
var zoom = 5;
{if $mapDefaultZoom > 0}zoom = {$mapDefaultZoom};{/if}
var mapIsChange = 0;
{if $mapIsChange == 1}mapIsChange = 1;{/if}
var mapCenter = [2.3,46];
{if strlen($mapDefaultX) > 0 && strlen($mapDefaultY) > 0}mapCenter = [{$mapDefaultX}, {$mapDefaultY}];{/if}
{if strlen({$data.pos_deb_long_dd})>0 && strlen({$data.pos_deb_lat_dd})>0} 
	mapCenter = [{$data.pos_deb_long_dd}, {$data.pos_deb_lat_dd}];
{/if}
var view = new ol.View({
  	center: ol.proj.fromLonLat(mapCenter),
    zoom: zoom
  });
function getStyle(libelle) {
	libelle = libelle.toString();
	//console.log("libelle : "+libelle);
	var styleRed = new ol.style.Style( { 
		image: new ol.style.Circle({
		    radius: 6,
		    fill: new ol.style.Fill({
		          color: [255, 0, 0, 0.5]
		 	}),
			stroke: new ol.style.Stroke( { 
				color: [255 , 0 , 0 , 1],
				width: 1
			})
		}),
		text: new ol.style.Text( {
			textAlign: 'Left',
			text: libelle,
			textBaseline: 'middle',
			offsetX: 7,
			offsetY: 0,
			font: 'bold 12px Arial',
			/*fill: new ol.style.Fill({ color: 'rgba(255, 0, 0, 0.1)' }),
			stroke : new ol.style.Stroke({ color : 'rgba(255, 0, 0, 1)' })*/
		})
	});
return styleRed;
}
var styleMarine = new ol.style.Style( { 
	stroke: new ol.style.Stroke( { 
		color: [0 , 58 , 128 , 1],
		width: 2
	})
});


var attribution = new ol.control.Attribution({
  collapsible: false
});
var mousePosition = new ol.control.MousePosition( { 
    coordinateFormat: ol.coordinate.createStringXY(4),
    projection: 'EPSG:4326',
    target: undefined,
    undefinedHTML: '&nbsp;'
});
var map = new ol.Map({
  controls: ol.control.defaults({ attribution: false }).extend([attribution]),
  target: 'map',
  view: view
});

var layer = new ol.layer.Tile({
  source: new ol.source.OSM()
});

function transform_geometry(element) {
  var current_projection = new ol.proj.Projection({ code: "EPSG:4326" });
  var new_projection = layer.getSource().getProjection();

  element.getGeometry().transform(current_projection, new_projection);
}

function setPosition(pointNumber, lon, lat) {
	var lonlat3857 = ol.proj.transform([parseFloat(lon),parseFloat(lat)], 'EPSG:4326', 'EPSG:3857');
	if (pointNumber == 1) {
		point1.setCoordinates (lonlat3857);
		view.setCenter(lonlat3857);
	} else {
		point2.setCoordinates (lonlat3857);
	}
}

map.addLayer(layer);
var coordinates;
var point1, point2;
var point_feature1, point_feature2;
var features = new Array();
/*
 * Traitement de chaque localisation
 */
/*console.log("Début de traitement de l'affichage du point");
console.log("x : " + {$data.wgs84_x});
console.log("y  : "+ {$data.wgs84_y});
*/

point1 = new ol.geom.Point([{$data.pos_deb_long_dd}, {$data.pos_deb_lat_dd}]);
point2 = new ol.geom.Point([{$data.pos_fin_long_dd}, {$data.pos_fin_lat_dd}]);
// console.log("Coordonnées : "+coordinates);
// console.log("point :" + point);
point_feature1 = new ol.Feature ( {
	geometry: point1
});
point_feature2 = new ol.Feature ( {
	geometry: point2
});
point_feature1.setStyle(getStyle("deb"));
point_feature2.setStyle(getStyle("fin"));
features.push ( point_feature1) ;
features.push (point_feature2);
/*
 * Integration de la trace gps
 */
 {if !empty($tracegps["ligne_geom"])}
 var trace = new ol.geom.LineString({$tracegps["ligne_geom"]}, 'XY');
 var trace_feature = new ol.Feature ( {
		geometry: trace
	});
	trace_feature.setStyle(styleMarine);
	features.push ( trace_feature);

 {/if}

/*  
 * Fin d'integration des points
 * Affichage de la couche
 */
var layerPoint = new ol.layer.Vector({
  source: new ol.source.Vector( {
    features: features
  })
});
features.forEach(transform_geometry);
map.addLayer(layerPoint);
map.addControl(mousePosition);


if ( mapIsChange == 1) {

 
	$(".position").change(function () {
		var lon = $("#wgs84_x").val();
		var lat = $("#wgs84_y").val();
		if (lon.length > 0 && lat.length > 0) {
			console.log("longitude saisie : "+ lon);
			console.log ("latitude saisie : " + lat);
			var lonlat3857 = ol.proj.transform([parseFloat(lon),parseFloat(lat)], 'EPSG:4326', 'EPSG:3857');
	        point.setCoordinates (lonlat3857);
		}
	});
 
map.on('click', function(evt) {
	  var lonlat3857 = evt.coordinate;
	  var lonlat = ol.proj.transform(lonlat3857, 'EPSG:3857', 'EPSG:4326');
	  var lon = lonlat[0];
	  var lat = lonlat[1];
	  console.log("longitude sélectionnée : "+ lon);
	  console.log ("latitude sélectionnée : " + lat);
	  point.setCoordinates (lonlat3857);
	  $("#wgs84_x").val(lon);
	  $("#wgs84_y").val(lat);
});
}
map.on("moveend", function() {
	var zoom = map.getView().getZoom();
	 document.getElementById('zoomlevel').innerHTML = zoom;
});
</script>