<div class="card">
    <div class="card-body">
        <h4 class="header-title mb-3">
            <?php
            echo 'Upload ' . ' ' . $pluralDisplayName;
            ?>
        </h4>
        <p class="sub-header">
            Upload .CSV file with only the next fields name: <br />
            <strong>
                <?= implode(', ', array_keys(array_filter($modelFields, function ($value) {
                    return isset($value['importable']) && $value['importable'] === true;
                }))); ?>
            </strong>
        </p>
        <div class="row">

            <?= $this->Form->create('Upload', ['class' => 'row', '' => 'POST', 'type' => 'file']) ?>
            <?= $this->Form->input(
                'Upload.csv_file',
                array(
                    'type' => 'file',
                    'label' => 'File Upload',
                    'class' => 'form-control',
                    'data-plugins' => 'dropify',
                    'data-max-file-size' => '3M',
                    'data-allowed-file-extensions' => '["csv"]',
                    'div' => [
                        'class' => 'mb-3 col-lg-12'
                    ]
                )
            ); ?>

            <p class="sub-header">
                You can replace or fill columns with the next fields values: <br />
            </p>

            <?php
            foreach ($modelFields as $fieldName => $fieldAttrs) {

                if (!empty($fieldAttrs['separator']) && $fieldAttrs['separator'] !== false) {
                    unset($fieldAttrs['separator']);
                }

                if (!empty($fieldAttrs['type']) && $fieldAttrs['type'] === 'hidden') {
                    unset($fieldAttrs['class']);
                    unset($fieldAttrs['data-toggle']);
                    unset($fieldAttrs['data-plugin']);
                    unset($fieldAttrs['data-plugins']);
                }

                $fieldName = $modelName . '.' . $fieldName;
                //if (!empty($fieldAttrs['importable']) && $fieldAttrs['importable'] == true)
                    echo $this->Form->input($fieldName, $fieldAttrs);
            }
            ?>

            <div class="col-md-5 offset-md-5 d-flex align-items-center gap-1 mt-3">
                <?php
                echo $this->Html->link('Cancel', 'javascript:void(0); window.history.back();', array('class' => 'btn btn-warning waves-effect waves-light btn-block'));
                echo $this->Form->submit('Save', ['class' => 'btn btn-warning waves-effect waves-light btn-block']);
                ?>
            </div>
        </div>
    </div>
</div>