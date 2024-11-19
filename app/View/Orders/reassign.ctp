<div class="card ">
    <div class="card-header card-header-transparent card-header-bordered font-weight-500 font-size-17">
        <a href="javascript:window.history.back();">
            <i class="fe-arrow-left font-size-22" style="color:black"></i>
        </a>
        Reassign Order
    </div>
    <div class="card-block p-4">

        <?PHP
        echo $this->Form->create($modelName, [
            'enctype' => 'multipart/form-data',
            'class' => isset($formClass) ? $formClass : '',
            'inputDefaults' => [
                'fieldset' => false,
                'format' => ['label', 'before', 'between', 'input', 'after', 'error'],
                'div' => [
                    'class' => 'mb-3 col-sm-6'
                ],
                'before' => '<div class="form-group"><div class="input-group">',
                'after' => '</div></div>',
                'class' => 'form-control',
            ]
        ]);
        ?>
        <div class="row">

            <?PHP
            foreach ($modelFields as $key => $options) {

                if (!empty($options['type']) && $options['type'] == 'file' && !empty(Set::extract($key, $this->request->data)) && !is_array(Set::extract($key, $this->request->data))) {
                    $options['data-default-file'] = Router::url('/', true) . Set::extract($key, $this->request->data);
                }


                echo $this->Form->input($key, array_merge([
                    'fieldset' => false,
                    'format' => ['label', 'before', 'between', 'input', 'after', 'error'],
                    'div' => [
                        'class' => 'mb-3 col-sm-6'
                    ],
                    'before' => '<div class="form-group"><div class="input-group">',
                    'after' => '</div></div>',
                    'class' => 'form-control',
                ], $options));
            }
            ?>
            <label for="PartnershipSubcontractorId" class="mt-4">Role</label>
            <div class="col-sm-12 row ">
                <div class="d-flex">
                    <?php
                    echo $this->Form->input('Partnership.drop_bury_role', array_merge([
                        'type' => 'checkbox',  // Asegúrate de que el tipo sea 'checkbox'
                        'fieldset' => false,   // Desactiva el campo fieldset
                        'format' => ['before', 'input', 'label', 'after', 'error'],  // Ajusta el orden para que el input aparezca antes del label
                        'div' => false,        // El div por defecto se desactiva para personalizarlo completamente
                        'before' => '<div class="form-check" style="margin-right: 36px">',  // Abre el contenedor form-check
                        'after' => '</div>',    // Cierra el contenedor form-check
                        'class' => 'form-check-input',  // Clase para el input (checkbox)
                        'label' => [
                            'class' => 'form-check-label',  // Clase para la etiqueta
                            'text' => 'Drop bury',  // El texto del label
                        ],
                    ]));
                    ?>

                    <?php
                    echo $this->Form->input('Partnership.directional_bore_role', array_merge([
                        'type' => 'checkbox',  // Asegúrate de que el tipo sea 'checkbox'
                        'fieldset' => false,   // Desactiva el campo fieldset
                        'format' => ['before', 'input', 'label', 'after', 'error'],  // Ajusta el orden para que el input aparezca antes del label
                        'div' => false,        // El div por defecto se desactiva para personalizarlo completamente
                        'before' => '<div class="form-check">',  // Abre el contenedor form-check
                        'after' => '</div>',    // Cierra el contenedor form-check
                        'class' => 'form-check-input',  // Clase para el input (checkbox)
                        'label' => [
                            'class' => 'form-check-label',  // Clase para la etiqueta
                            'text' => 'Directional Bore',  // El texto del label
                        ],
                    ]));
                    ?>
                </div>
            </div>
            <div class="col-md-5 offset-md-5 d-flex align-items-center gap-1 mt-4">
                <?php
                echo $this->Html->link('Cancel', 'javascript:void(0); window.history.back();', array('class' => 'btn btn-warning waves-effect waves-light btn-block'));
                echo $this->Form->submit('Send', ['class' => 'btn btn-warning waves-effect waves-light btn-block']);
                ?>
            </div>
        </div>

    </div>
</div>

<style type="text/css">
    .circle-step {
        width: 50px;
        height: 50px;
        background-color: #f6bb72;
        /* color similar al púrpura que se ve en la imagen */
        color: white;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 20px;
        margin: 0 auto;
        font-weight: bold;
    }

    h4 {
        font-size: 18px;
        font-weight: 600;
    }

    p {
        font-size: 14px;
        color: #333;
    }

    .text-center {
        text-align: center;
    }
</style>