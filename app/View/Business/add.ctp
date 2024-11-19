<div class="card ">
    <div class="card-header card-header-transparent card-header-bordered font-weight-500 font-size-17">
    <a href="javascript:window.history.back();">
            <i class="fe-arrow-left font-size-22" style="color:black"></i>
            </a>
        Add <?= $singularDisplayName ?>
    </div>
    <div class="card-block p-4">

        <?PHP
        $employeeFields['Account.account_type_id']['type'] = 'hidden';
        $employeeFields['Account.account_type_id']['class'] = '';
        $employeeFields['Account.account_type_id']['default'] = 3; //admin business

        $employeeFields['Account.status']['type'] = 'hidden';
        $employeeFields['Account.status']['class'] = '';
        $employeeFields['Account.status']['default'] = 1; //admin business

        unset($employeeFields['Account.password']);
        unset($employeeFields['Account.repeated_password']);



        echo $this->Form->create($modelName, [
            'enctype' => 'multipart/form-data',
            'class' => isset($formClass) ? $formClass : '',
            'inputDefaults' => [
                'fieldset' => false,
                'format' => ['label', 'before', 'between', 'input', 'after', 'error'],
                'div' => [
                    'class' => 'col-sm-6'
                ],
                'before' => '<div class="form-group"><div class="input-group">',
                'after' => '</div></div>',
                'class' => 'form-control',
            ]
        ]);

        $logo = $modelFields['Business.logo'];
        $_status = $modelFields['Business.status'];
        $_comments = $modelFields['Business.comments'];
        unset($modelFields['Business.logo']);
        unset($modelFields['Business.status']);
        unset($modelFields['Business.comments']);
        ?>
        <div class="row">
            <div class="col-sm-3" style="padding-top: 20px">
                <?= $this->Form->input('Business.logo', $logo); ?>
            </div>
            <div class="col-sm-9">
                <h4>Negocio Información</h4>
                <div class="row" style="border-top: 1px solid #124968; padding-top: 10px;">
                    <?PHP
                    foreach ($modelFields as $key => $options):

                        if ($key == 'Business.schedule_weekday')
                            echo '<div class="col-sm-12" style="border-bottom: 1px solid #124968;  padding-top: 10px; margin-bottom: 10px;"><h4>Horario</h4></div>';




                        echo $this->Form->input($key, $options);

                        if ($key == 'Business.fiscal_address')
                            echo '<div class="col-sm-12" style="border-bottom: 1px solid #124968;  padding-top: 10px; margin-bottom: 10px;"><h4>Configuraciones</h4></div>';

                        if ($key == 'Business.location_address')
                            echo '<div class="col-sm-12"><img id="map-preview" width="800" height="320" src="https://hipertextual.com/files/2020/04/hipertextual-mas-facil-durante-cuarentena-google-maps-muestra-que-restaurantes-envian-domicilio-2020815281.jpg"></div>';


                    endforeach;
                    ?>
                </div>



                <h4>Contacto & Cuenta</h4>
                <div class="row" style="border-top: 1px solid #124968; padding-top: 10px;">
                    <?PHP
                    unset($employeeFields['Employee.photo']);
                    unset($employeeFields['Employee.business_id']);
                    foreach ($employeeFields as $key => $options):
                        echo $this->Form->input($key, $options);
                    endforeach;
                    ?>
                </div>

                <h4>Informacion Adicional</h4>
                <div class="row" style="border-top: 1px solid #124968; padding-top: 10px;">
                    <?= $this->Form->input('Business.status', $_status); ?>
                    <?= $this->Form->input('Business.comments', $_comments); ?>
                </div>
            </div>

            <div class="col-sm-12 row">
                <div class="col-sm-6 text-right">
                    <button type="button" onclick="window.history.back();" class="btn btn-secondary col-sm-4">
                        Regresar
                    </button>
                </div>
                <div class="col-sm-6 text-left">
                    <?=
                    $this->Form->submit('Guardar', [
                        'class' => 'btn btn-dark col-sm-4'
                    ])
                    ?>
                </div>
            </div>
        </div>
        <?= $this->Form->end() ?>

    </div>
</div>



<script>
// This sample uses the Autocomplete widget to help the user select a
// place, then it retrieves the address components associated with that
// place, and then it populates the form fields with those details.
// This sample requires the Places library. Include the libraries=places
// parameter when you first load the API. For example:
// <script
// src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAYzR1KKoa1Rwkp-aoCgfQiv7wlcCvyNwk&libraries=places">
    let placeSearch;
    let autocomplete;
    const componentForm = {
        street_number: "short_name",
        route: "long_name",
        locality: "long_name",
        administrative_area_level_1: "short_name",
        country: "long_name",
        postal_code: "short_name",
    };
    function initAutocomplete() {
        // Create the autocomplete object, restricting the search predictions to
        // geographical location types.
        autocomplete = new google.maps.places.Autocomplete(
                document.getElementById("BusinessLocationAddress"),
                {types: ["geocode"]}
        );
        // Avoid paying for data that you don't need by restricting the set of
        // place fields that are returned to just the address components.
        autocomplete.setFields(["address_component", "geometry"]);
        // When the user selects an address from the drop-down, populate the
        // address fields in the form.
        autocomplete.addListener("place_changed", fillInAddress);
    }

    function fillInAddress() {
        // Get the place details from the autocomplete object.
        const place = autocomplete.getPlace();

        const address = document.getElementById("BusinessLocationAddress").value;
        const latitude = place.geometry.location.lat();
        const longitude = place.geometry.location.lng();


        document.getElementById("BusinessLocationLatitude").value = latitude;
        document.getElementById("BusinessLocationLongitude").value = longitude;

        var static_url = "https://maps.googleapis.com/maps/api/staticmap?center=" + address + "&zoom=18&size=800x320&maptype=roadmap&markers=icon:https://mercadito-naranja.axcode.com.mx/img/ic_launcher.png%7C" + latitude + "," + longitude + "&key=AIzaSyAYzR1KKoa1Rwkp-aoCgfQiv7wlcCvyNwk";
        document.getElementById("map-preview").src = static_url;



        /*for (const component in componentForm) {
         document.getElementById(component).value = "";
         document.getElementById(component).disabled = false;
         }*/

        // Get each component of the address from the place details,
        // and then fill-in the corresponding field on the form.
        /*for (const component of place.address_components) {
         const addressType = component.types[0];
         if (componentForm[addressType]) {
         const val = component[componentForm[addressType]];
         document.getElementById(addressType).value = val
         
         
         }
         }*/
    }

// Bias the autocomplete object to the user's geographical location,
// as supplied by the browser's 'navigator.geolocation' object.
    function geolocate() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition((position) => {
                const geolocation = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude,
                };
                const circle = new google.maps.Circle({
                    center: geolocation,
                    radius: position.coords.accuracy,
                });
                autocomplete.setBounds(circle.getBounds());
            });
        }
    }

</script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAYzR1KKoa1Rwkp-aoCgfQiv7wlcCvyNwk&callback=initAutocomplete&libraries=places&v=weekly" defer></script>

