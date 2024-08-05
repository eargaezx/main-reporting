<?php

?>
<style>
    #map {
        height: 100%;
        width: 100%;
    }
</style>

<div id="map"></div>

<script>
    // Function to initialize the map
    function initMap() {
        // Create a map object and specify the DOM element for display
        var map = new google.maps.Map(document.getElementById('map'), {
            center: { lat: -34.397, lng: 150.644 }, // Set the center of the map
            zoom: 8 // Set the zoom level
        });


        loadData().then(function (data) {
            for (var i = 0; i < data.length; i++) {
                geocodeAddress(data[i]['Order'], map);
            }
        });
    }

    function loadData() {
        var d = $.Deferred();
        $.ajax({
            type: 'POST',
            url: '<?php echo $this->Html->url(array('controller' => $controllerName, 'action' => 'index')); ?>',
            // dataType: 'json',
            data: {
                conditions: {}
            }
        }).done(function (response) {
            d.resolve(response.data);
        });

        return d.promise();
    }

    // Function to geocode an address and add a marker to the map
    function geocodeAddress(order, map) {
        var infoWindow = new google.maps.InfoWindow();
        var geocoder = new google.maps.Geocoder();

        geocoder.geocode({ 'address': order.address }, function (results, status) {
            if (status === 'OK') {
                var marker = new google.maps.Marker({
                    map: map,
                    position: results[0].geometry.location
                });

                // Add click event listener to marker
                marker.addListener('click', function () {
                    // Open info window when marker is clicked
                    infoWindow.setContent('#' + order.name);
                    infoWindow.open(map, marker);
                });

                map.setCenter(marker.getPosition());
            } else {
                console.error('Geocode was not successful for the following reason: ' + status);
            }
        });
    }
</script>

<script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA-UluRFUjiyByEOTzMnMJ8yifRRshgrNU&callback=initMap"></script>