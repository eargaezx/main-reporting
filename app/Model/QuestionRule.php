<?php

App::uses('ImplementableModel', 'Implementable.Model');

class QuestionRule extends ImplementableModel
{

    public $singularDisplayName = 'Question Rule';
    public $pluralDisplayName = 'Question Rules';
    public $displayField = 'id';
    public $fields = [
        [
            'fieldKey' => 'id',
            'label' => FALSE,
            'type' => 'hidden',
            'showIn' => ['edit'],
        ],
        'question_id' => [
            'fieldKey' => 'question_id',
            'label' => 'Question',
            'type' => InputType::SELECT,
            'div' => InputDiv::COL_SM_12,
            'showIn' => TRUE,
            // 'bindValue' => 'Subcontractor.name',
            'options' => [
                '' => 'None'
            ],
            'filter' => TRUE
        ],

        'related_question_id' => [
            'fieldKey' => 'related_question_id',
            'label' => 'Related Question',
            'type' => InputType::SELECT,
            'div' => InputDiv::COL_SM_12,
            'showIn' => TRUE,
            // 'bindValue' => 'Subcontractor.name',
            'options' => [
                '' => 'None'
            ],
            'filter' => TRUE
        ],
        'related_question_value' => [
            'fieldKey' => 'related_question_value',
            'label' => 'Related Question Value',
            'rows' => 1,
            'div' => InputDiv::COL_SM_12,
            'showIn' => TRUE,
        ],
    ];
    public $validate = [
        'related_question_id' => [
            'rule' => 'notBlank',
            'required' => true,
            'message' => 'The field related question is required'
        ],
        'question_id' => [
            'rule' => 'notBlank',
            'required' => true,
            'message' => 'The field question is required'
        ],
    ];
    public $belongsTo = [
        'Question' => [
            'className' => 'Question',
            'foreignKey' => 'question_id',
            'dependent' => false
        ],
        'RelatedQuestion' => [
            'className' => 'Question',
            'foreignKey' => 'related_question_id',
            'dependent' => false
        ]
    ];

}
