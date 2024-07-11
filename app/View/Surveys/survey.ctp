<?php
$_uuid = CakeText::uuid();
$survey = $this->request->data;
/*
 *SURVEY QUESTIONS
 */
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
]);


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
                        'label' => $question['question'],
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