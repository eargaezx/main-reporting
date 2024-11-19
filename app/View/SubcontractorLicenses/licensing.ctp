<div class="card ">
    <div class="card-header card-header-transparent card-header-bordered font-weight-500 font-size-17">
        Apply Licensing
    </div>
    <div class="card-block p-4">

        <?PHP
        $modelFields['SubcontractorLicense.license_id']['label'] = 'New License';
 
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
            <label for="SubcontractorLicenseLicenseId">Last License</label>
            <h5 class="ml-5 mb-3 text-uppercase bg-light p-2" style="width: fit-content; margin-left: 12px;">
                <span class="btn btn-warning" style="pointer-events: none;">
                    <i class="fe-award"></i>
                    License <?= $licensing['License']['name'] ?>
                </span>

                <?php if ((new DateTime($licensing['SubcontractorLicense']['end_date'])) <= (new DateTime())): ?>
                    <span class="btn btn-warning" style="pointer-events: none;">
                        Expired sice <?= (new DateTime($licensing['SubcontractorLicense']['end_date']))->format('d/m/Y'); ?>
                    </span>


                <?php else: ?>
                    <span class="btn btn-warning" style="pointer-events: none;">
                        Expires <?= (new DateTime($licensing['SubcontractorLicense']['end_date']))->format('d/m/Y'); ?>
                    </span>

                    <span class="btn btn-warning" style="pointer-events: none;">
                        <?= $licensing['SubcontractorLicense']['current_users'] . ' of ' . $licensing['SubcontractorLicense']['max_users'] ?>
                        allow active users
                    </span>

                    <span class="btn btn-warning" style="pointer-events: none;">
                        Unlimited orders
                    </span>
                <?php endif; ?>


            </h5>

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
            <div class="col-md-5 offset-md-5 d-flex align-items-center gap-1 mt-4">
                <?php
                echo $this->Html->link('Cancel', 'javascript:void(0); window.history.back();', array('class' => 'btn btn-warning waves-effect waves-light btn-block'));
                echo $this->Form->submit('Save', ['class' => 'btn btn-warning waves-effect waves-light btn-block']);
                ?>
            </div>
        </div>

    </div>
</div>