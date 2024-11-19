<?php
//echo pr($modelFields);
?>
<style>
    #map {
        height: 100%;
        width: 100%;
    }

    /* Estilos para el botón de autolocalización */
    .custom-map-control-button {
        background-color: #fff;
        border: 2px solid #fff;
        border-radius: 3px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
        cursor: pointer;
        margin-right: 10px;
        padding: 10px;
        text-align: center;
    }
</style>


<div class="row col-sm-12" style="height: 100%">
    <div class="col-sm-3">
        <div class="card ">
            <div class="card-header card-header-transparent card-header-bordered font-weight-500 font-size-17">
                Search
            </div>
            <div class="card-block p-2">
                <?PHP
                echo $this->Form->create('filter', [
                    'class' => 'form-horizontal match-height table-responsive ',
                    'inputDefaults' => [
                        'fieldset' => false,
                        'format' => ['label', 'before', 'between', 'input', 'after', 'error'],
                        'div' => [
                            'class' => 'mb-3 col-sm-12'
                        ],
                        'before' => '<div class="form-group"><div class="input-group">',
                        'after' => '</div></div>',
                        'class' => 'form-control',
                    ]
                ]);

                ?>

                <?PHP foreach ($modelFields as $key => $settings): ?>
                    <th>
                        <?PHP
                        if (!empty($settings['filter'])):
                            unset($modelFilters[$settings['filter']]['div']);
                            //unset($modelFilters[$settings['filter']]['label']);
                            unset($modelFilters[$key]);
                            echo $this->Form->input('named.filter.' . $settings['filter'], $modelFilters[$settings['filter']]);
                        endif;
                        ?>
                    </th>
                <?PHP endforeach ?>

                <?PHP
                echo $this->Form->submit('Search', ['class' => 'btn btn-warning']);
                echo $this->Form->end();
                ?>
            </div>
        </div>
    </div>

    <div class="col-sm-9">
        <div id="map"></div>
    </div>
</div>



<script>
    // Function to initialize the map
    function initMap() {
        // Create a map object and specify the DOM element for display
        var map = new google.maps.Map(document.getElementById('map'), {
            center: { lat: -34.397, lng: 150.644 }, // Set the center of the map
            zoom: 8 // Set the zoom level,


        });

        var infoWindow = new google.maps.InfoWindow();
        var geocoder = new google.maps.Geocoder();


        // Crear el botón de autolocalización
        const locationButton = $('<div style="margin-top:4px;">')
            .addClass('custom-map-control-button')
            .html(' <i class="fe-compass" style="color:black; font-size: 1rem;"></i>')
            .click(function () {
                // Hacer que el navegador intente obtener la ubicación del usuario
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(
                        function (position) {
                            const pos = {
                                lat: position.coords.latitude,
                                lng: position.coords.longitude,
                            };

                            infoWindow.setPosition(pos);
                            infoWindow.setContent('You are here');
                            infoWindow.open(map);
                            map.setCenter(pos);
                            map.setZoom(13);
                        },
                        function () {
                            handleLocationError(true, infoWindow, map.getCenter());
                        }
                    );
                } else {
                    // Si el navegador no soporta Geolocation
                    handleLocationError(false, infoWindow, map.getCenter());
                }
            });

        // Agregar el botón al mapa
        map.controls[google.maps.ControlPosition.TOP_RIGHT].push(locationButton[0]);



        /*loadData().then(function (data) {
            for (var i = 0; i < data.length; i++) {
                geocodeAddress(data[i]['Order'], map);
            }
        });*/

        var data = <?php echo json_encode($data); ?>;
        for (var i = 0; i < data.length; i++) {
            geocodeAddress(data[i]['Order'], map);
        }
    }

    function loadData() {
        var d = $.Deferred();
        $.ajax({
            type: 'POST',
            url: '<?php echo $this->Html->url(array('controller' => $controllerName, 'action' => 'index.json')); ?>',
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
                const pendingMarker = "<?= Router::url(['controller' => '/'], true) ?>/img/marker_pending.png";
                const completedMarker = "<?= Router::url(['controller' => '/'], true) ?>/img/marker_done.png";

                var marker = new google.maps.Marker({
                    map: map,
                    position: results[0].geometry.location,
                    icon: {
                        url: order.status == 1 ? pendingMarker : completedMarker,  // URL de la imagen
                        scaledSize: new google.maps.Size(50, 50),  // Tamaño de la imagen
                    }
                });

                // Add click event listener to marker
                marker.addListener('click', function () {
                    // Open info window when marker is clicked
                    var contentString = '<div>' +
                        '<strong>#' + order.name + '</strong><br>' +
                        '#' + order.address + '<br> <br>' +
                        '<a href="<?php echo $this->Html->url(array('controller' => $controllerName, 'action' => 'edit')); ?>/' + order.id + '">View</a>' +
                        '</div>';

                    // Open info window when marker is clicked
                    infoWindow.setContent(contentString);
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