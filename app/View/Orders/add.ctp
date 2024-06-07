<div class="card ">
    <div class="card-header card-header-transparent card-header-bordered font-weight-500 font-size-17">
        <?= 'Editar ' . $singularDisplayName ?>
    </div>
    <div class="card-block p-4">

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
            echo $this->Form->hidden('Order.id', [
                'default' =>  $_uuid
            ]);
            echo $this->Form->hidden('Order.survey_id', [
                'default' =>  $survey['Survey']['id']
            ]);
            foreach ($modelFields as $key => $options):
                echo $this->Form->input($key, $options);
            endforeach;
            /*
             *SURVEY QUESTIONS
             */
            foreach ($survey['Question'] as $index => $question):
                echo $this->Form->hidden('QuestionAnswer.' . $index . '.order_id', [
                    'default' => $_uuid
                ]);
                echo $this->Form->hidden('QuestionAnswer.' . $index . '.question_id', [
                    'default' => $question['id']
                ]);
                switch ($question['type']) {
                    case 'options':
                        echo $this->Form->input(
                            'QuestionAnswer.' . $index . '.value',
                            array_merge_recursive(
                                InputType::SELECT,
                                [
                                    'options' => Hash::combine(explode(',', $question['options']), '{n}', '{n}')
                                ]
                            )
                        );
                        break;
                    case 'number':
                        echo $this->Form->input('QuestionAnswer.' . $index . '.value', [
                            'label' => $question['question'],
                            'type' => 'number'
                        ]);
                        break;
                    case 'text':
                        echo $this->Form->input('QuestionAnswer.' . $index . '.value', [
                            'label' => $question['question'],
                            'type' => 'text'
                        ]);
                        break;
                }
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

    </div>
</div>