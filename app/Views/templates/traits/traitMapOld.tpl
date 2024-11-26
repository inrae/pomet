{if strlen($data.pos_deb_lat_dd) > 0 && strlen($data.pos_deb_long_dd) > 0}
<div id="map" class="mapDisplay"></div>
Longueur du trait calculée : ~ <span id="longueur"></span> m
<script>
var earth_radius = 6389125.541;
var long_deb = {$data.pos_deb_long_dd};
var lat_deb = {$data.pos_deb_lat_dd};
var long_fin = {$data.pos_fin_long_dd};
var lat_fin = {$data.pos_fin_lat_dd};
var zoom = 13;
var styleRed = new ol.style.Style( { 
	stroke: new ol.style.Stroke( { 
		color: [255 , 0 , 0 , 1],
		width: 2
	})
});
var styleMarine = new ol.style.Style( { 
	stroke: new ol.style.Stroke( { 
		color: [0 , 58 , 128 , 1],
		width: 2
	})
});


var attribution = new ol.control.Attribution({
  collapsible: false
});

var map = new ol.Map({
  controls: ol.control.defaults({ attribution: false }).extend([attribution]),
  target: 'map',
  view: new ol.View({
  	center: ol.proj.fromLonLat([long_deb, lat_deb]),
    zoom: zoom
  })
});

var layer = new ol.layer.Tile({
  source: new ol.source.OSM()
});
function transform_geometry(element) {
  var current_projection = new ol.proj.Projection({ code: "EPSG:4326" });
  var new_projection = layer.getSource().getProjection();

  element.getGeometry().transform(current_projection, new_projection);
}

map.addLayer(layer);
var coordinates = [[long_deb, lat_deb], [long_fin, lat_fin]];
var linestring = new ol.geom.LineString(coordinates, 'XY');
var lineString_feature = new ol.Feature ( {
	geometry: linestring
});
lineString_feature.setStyle(styleRed);
/*
 * Integration de la trace gps
 */
 {if !empty($tracegps["ligne_geom"])}
 var trace = new ol.geom.LineString({$tracegps["ligne_geom"]}, 'XY');
 var trace_feature = new ol.Feature ( {
		geometry: trace
	});
	trace_feature.setStyle(styleMarine);
	var features = [ trace_feature, lineString_feature ];
 {else}
 var features = [ lineString_feature ];
 {/if}

var layerLine = new ol.layer.Vector({
  source: new ol.source.Vector( {
    features: features
  })
});
features.forEach(transform_geometry);
map.addLayer(layerLine);

//create sphere to measure on
var wgs84sphere = new ol.Sphere(earth_radius); 

// get distance on sphere
var longueur = wgs84sphere.haversineDistance([long_deb, lat_deb], [long_fin, lat_fin]);
//console.log("longueur", longueur);
longueur = parseInt(longueur);
// var longueur =linestring.getLength();
$("#longueur").append(longueur);

</script>
{/if}
