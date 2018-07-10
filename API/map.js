$( document ).ready(function() {
        // Create a map
        var map = L.map('map')
            .setView([52.2263, 0.1275], 15);    // Easy way to work out lat/lon/zoom is http://www.openstreetmap.org/
        // Add tile background
        L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://osm.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);
	// Add the GeoJSON data in the file to the map
//        var geojsonLayer = new L.GeoJSON.AJAX('API.php');
//        geojsonLayer.addTo(map);
        var _layer;
        function loadData () {
            // Add the GeoJSON data in the file to the map
            var url = 'API.php';
            var params = {
                bbox: map.getBounds().toBBoxString()
            };
            // Obtain over AJAX
            $.ajax({
                type: "GET",
                url: url,
                data: params,
                dataType: 'json',
                success: function (data) {
                    if (_layer) {
                    	map.removeLayer (_layer);
                    }
                    _layer = L.geoJson(data, {
                        // Set popup
                        onEachFeature: function (feature, layer) {
                            //console.log(feature.properties);
                            var popupContent = '<p><strong>Crime Number: ' + feature.properties.id + '</strong></p>';
                            popupContent += '<p>Date: ' + feature.properties.date + '</p>';
                            popupContent += '<p>Location: ' + feature.properties.location + '</p>';
                            layer.bindPopup(popupContent);
                        }
                    }).addTo(map);
                }
            });
        }
        // Initial load
        loadData();
        // Set a handler to reload when moving the map
        map.on ('moveend', function (e) {
            loadData();
        });
});

