<div id="map" class="map"></div>
{include file="mapDefault.tpl"}
<script>
    var mapIsChange = "{$mapIsChange}";
    var map = setMap("map");
    var startlon = "{$data.pos_deb_long_dd}";
    var startlat = "{$data.pos_deb_lat_dd}";
    var endlon = "{$data.pos_fin_long_dd}";
    var endlat = "{$data.pos_fin_lat_dd}";
    var startend = [];
    function setPosition(pointnum, lat, lon, pos) {
        if (startend[pointnum] == undefined) {
            startend[pointnum] = L.marker([lat, lon]).bindTooltip(pos, { permanent: true, className: "transparent-tooltip" });
            startend[pointnum].addTo(map);
        } else {
            startend[pointnum].setLatLng([lat, lon]);
        }
        if (pointnum == 0) {
            map.setView([lat, lon]);
        }

    }
    function setDefaultPosition(lat, lon) {
        if (lat.length > 0 && lon.length > 0) {
            mapData.mapDefaultLong = lon;
            mapData.mapDefaultLat = lat;
            map.setView([lat, lon]);
        }
    }
    
    try {
        var trace = JSON.parse('{$tracegps}');
    } catch (error) {
        var trace = [];
    }
    var points = [];
    trace.coordinates.forEach(function (element, i) {
        points[i] = [element[1], element[0]];
    });

    mapDisplay(map);
    if (startlon.length > 0 && startlat.length > 0) {
        setPosition(0, startlat, startlon, "{t}Début{/t}");
    }
    if (endlon.length > 0 && endlat.length > 0) {
        setPosition(1, endlat, endlon, "{t}Fin{/t}");
    }
    if (points.length > 0) {
        var polyline = L.polyline(points);
        polyline.addTo(map);
    }

    if (mapIsChange == true) {
        map.on('click', function (e) {
            setPosition(e.latlng.lat, e.latlng.lng);
            $("#location_long").val(e.latlng.lng);
            $("#location_lat").val(e.latlng.lat);
        });
    }


</script>