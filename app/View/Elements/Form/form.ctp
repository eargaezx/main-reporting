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
?>
<div class="row">
    <?PHP
    foreach ($modelFields as $key => $options) {

        if (!empty($options['type']) && $options['type'] == 'file' && !empty (Set::extract($key, $this->request->data)) && !is_array(Set::extract($key, $this->request->data) ) ) {
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