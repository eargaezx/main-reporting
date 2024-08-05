<div class="card ">
    <div class="card-header card-header-transparent card-header-bordered font-weight-500 font-size-17">
    Edit <?= $singularDisplayName ?>
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
        
        $modelFields['Product.photo']['data-default-file'] = $this->request->data[$modelName]['photo'];

        $logo = $modelFields['BusinessProduct.photo'];
        unset($modelFields['BusinessProduct.photo']);
        ?>
        <div class="row">
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