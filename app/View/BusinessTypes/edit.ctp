<div class="card ">
    <div class="card-header card-header-transparent card-header-bordered font-weight-500 font-size-17">
    <a href="javascript:window.history.back();">
            <i class="fe-arrow-left font-size-22" style="color:black"></i>
            </a>
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
        
        $modelFields['BusinessType.banner']['data-default-file'] = $this->request->data[$modelName]['banner'];

        $logo = $modelFields['BusinessType.banner'];
        unset($modelFields['BusinessType.banner']);
        ?>
        <div class="row">
            <div class="col-sm-4">
                <?= $this->Form->input('BusinessType.banner', $logo); ?> 
            </div>
            <div class="col-sm-8">
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