<div class="card ">
    <div class="card-header card-header-transparent card-header-bordered font-weight-500 font-size-17">
    <a href="javascript:window.history.back();">
            <i class="fe-arrow-left font-size-22" style="color:black"></i>
            </a>
        <?= 'Add ' . $singularDisplayName ?>
    </div>
    <div class="card-block p-4">

        <?PHP
        if (empty($this->request->data['data']['unwrapped'])) {
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
        }

        $logo = $modelFields['Operator.photo'];
        unset($modelFields['Operator.photo']);
        ?>
        <div class="row">
            <div class="col-sm-3">
                <?= $this->Form->input('Operator.photo', $logo); ?>
            </div>

            <div class="col-sm-9 row">
                <?PHP
                foreach ($modelFields as $key => $options) {
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
            </div>
            <div class="col-md-5 offset-md-5 d-flex align-items-center gap-1 mt-4">
                <?php
                echo $this->Html->link('Cancel', 'javascript:void(0); window.history.back();', array('class' => 'btn btn-warning waves-effect waves-light btn-block'));
                echo $this->Form->submit('Save', ['class' => 'btn btn-warning waves-effect waves-light btn-block']);
                ?>
            </div>
        </div>

        <?PHP
        if (empty($this->request->data['data']['unwrapped'])) {
            $this->Form->end();
        }
        ?>

    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        setTimeout(function () {
            $('#AccountPassword').val('');
            $('#AccountRepeatedPassword').val('');
        }, 700);
    });
</script>