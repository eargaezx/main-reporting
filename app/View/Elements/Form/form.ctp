<?=
    $this->Form->create($modelName, [
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
    ])
    ?>
<div class="row">
    <?PHP
    foreach ($modelFields as $key => $options):
        echo $this->Form->input($key, $options);
    endforeach;
    ?>
    <div class="col-md-5 offset-md-5 d-flex align-items-center gap-1 mt-4">
        <?php
        echo $this->Html->link('Cancel', 'javascript:void(0); window.history.back();', array('class' => 'btn btn-warning waves-effect waves-light btn-block'));
        echo $this->Form->submit('Save', ['class' => 'btn btn-warning waves-effect waves-light btn-block']);
        ?>
    </div>
</div>
<?= $this->Form->end() ?>