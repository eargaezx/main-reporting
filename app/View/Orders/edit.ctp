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
            foreach ($modelFields as $key => $options):
                echo $this->Form->input($key, $options);
            endforeach;
            /*
             *SURVEY QUESTIONS
             */
            $answers = $this->request->data['QuestionAnswer'];
            unset($this->request->data['QuestionAnswer']);
            foreach ($survey['Question'] as $index => $question):
                echo $this->Form->hidden('QuestionAnswer.' . $index . '.id', [
                    'default' => empty(Hash::extract($answers, '{n}[question_id=' . $question['id'] . ']')[0]['id']) ?
                        CakeText::uuid() : Hash::extract($answers, '{n}[question_id=' . $question['id'] . ']')[0]['id']
                ]);
                echo $this->Form->hidden('QuestionAnswer.' . $index . '.order_id', [
                    'default' => $this->request->data['Order']['id']
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
                                    'label' => $question['question'],
                                    'options' => Hash::combine(explode(',', $question['options']), '{n}', '{n}'),
                                    'default' => empty(Hash::extract($answers, '{n}[question_id=' . $question['id'] . ']')[0]['value']) ? '' : Hash::extract($answers, '{n}[question_id=' . $question['id'] . ']')[0]['value']
                                ]
                            )
                        );
                        break;
                    case 'number':
                        echo $this->Form->input('QuestionAnswer.' . $index . '.value', [
                            'label' => $question['question'],
                            'type' => 'number',
                            'default' => empty(Hash::extract($answers, '{n}[question_id=' . $question['id'] . ']')[0]['value']) ? '' : Hash::extract($answers, '{n}[question_id=' . $question['id'] . ']')[0]['value']
                        ]);
                        break;
                    case 'text':
                        echo $this->Form->input('QuestionAnswer.' . $index . '.value', [
                            'label' => $question['question'],
                            'type' => 'text',
                            'default' => empty(Hash::extract($answers, '{n}[question_id=' . $question['id'] . ']')[0]['value']) ? '' : Hash::extract($answers, '{n}[question_id=' . $question['id'] . ']')[0]['value']
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

<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function () {
        jQuery(document).ready(function () {
            function onQuestionType(selectedValue) {

                window.location.href = "<?= Router::url([
                    'controller' => 'Orders',
                    'action' => 'edit',
                    $this->request->data['Order']['id'],

                ]) ?>/" + selectedValue 
                + "/" + $('#OrderSubcontractorId').val() 
                + "/" +  $('#OrderOperatorId').val()
                + "/" +  $('#OrderName').val()
                + "/" +  $('#OrderStatus').val();

            }

            $('#OrderSurveyId').change(function () {
                onQuestionType($(this).val());
            });

            //onQuestionType($('#OrderSurveyId').val());
        })
    });
</script>