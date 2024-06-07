<?php

App::uses('ImplementableModel', 'Implementable.Model');

class QuestionAnswer extends ImplementableModel
{

    public $singularDisplayName = 'Question Answer';
    public $pluralDisplayName = 'Question Answers';
    public $displayField = 'id';
    public $fields = [
        [
            'fieldKey' => 'id',
            'label' => FALSE,
            'type' => 'hidden',
            'showIn' => ['edit'],
        ],
        'order_id' => [
            'fieldKey' => 'order_id',
            'label' => 'Order',
            'type' => InputType::SELECT,
            'div' => InputDiv::COL_SM_12,
            'showIn' => TRUE,
            // 'bindValue' => 'Subcontractor.name',
            'options' => [
                '' => 'None'
            ],
            'filter' => TRUE
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

        'value' => [
            'fieldKey' => 'related_question_value',
            'label' => 'Related Question Value',
            'rows' => 1,
            'div' => InputDiv::COL_SM_12,
            'showIn' => TRUE,
        ],
    ];
    public $validate = [
        'order_id' => [
            'rule' => 'notBlank',
            'required' => true,
            'message' => 'The field related question is required'
        ],
        'question_id' => [
            'rule' => 'notBlank',
            'required' => true,
            'message' => 'The field question is required'
        ]
    ];
    public $belongsTo = [
        'Question' => [
            'className' => 'Question',
            'foreignKey' => 'question_id',
            'dependent' => false
        ],
        'Order' => [
            'className' => 'Order',
            'foreignKey' => 'order_id',
            'dependent' => true
        ]
    ];

}
