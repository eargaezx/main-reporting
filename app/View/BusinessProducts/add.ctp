<div class="card ">
    <div class="card-header card-header-transparent card-header-bordered font-weight-500 font-size-17">
    <a href="javascript:window.history.back();">
            <i class="fe-arrow-left font-size-22" style="color:black"></i>
            </a>
        Add <?= $singularDisplayName ?>
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
                    'class' => 'col-sm-6'
                ],
                'before' => '<div class="form-group"><div class="input-group">',
                'after' => '</div></div>',
                'class' => 'form-control',
            ]
        ]);

        $logo = $modelFields['BusinessProduct.photo'];
        unset($modelFields['BusinessProduct.photo']);
        ?>
        <div class="row">

            <div class=" col-sm-12  required" style="margin-bottom: 20px;">
                <label for="BusinessProductBusinessId">Catalago</label>
                <div class="form-group">
                    <div class="input-group">
                        <select name="data[BusinessProduct][business_id]" class="form-control select2 select2-hidden-accessible" filter="business_id" bindvalue="BusinessProduct.business_id" id="BusinessProductBusinessId" required="required" tabindex="-1" aria-hidden="true">
                            <option value="">SELECCIONAR </option>
                            <option value="5ecb0235-48ec-477e-b532-3f484ad0ef44">La Tradicional</option>
                        </select>
                    </div>
                </div>
            </div>


            <div class="col-sm-3">
                <?= $this->Form->input('BusinessProduct.photo', $logo); ?>
            </div>
            <div class="col-sm-9">
                <div class="row">
                    <?PHP
                    foreach ($modelFields as $key => $options):
                        echo $this->Form->input($key, $options);
                    endforeach;
                    ?>
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