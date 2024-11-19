<?php

App::uses('ImplementableModel', 'Implementable.Model');

class Question extends ImplementableModel
{

    public $singularDisplayName = 'Question';
    public $pluralDisplayName = 'Questions';
    public $displayField = 'question';
    public $fields = [
        [
            'fieldKey' => 'id',
            'label' => FALSE,
            'type' => 'hidden',
            'showIn' => ['edit'],
            'show'
        ],
        'survey_id' => [
            'fieldKey' => 'survey_id',
            'label' => 'Survey',
            'type' => InputType::SELECT,
            'div' => InputDiv::COL_SM_12,
            'showIn' => TRUE,
            // 'bindValue' => 'Subcontractor.name',
            'options' => [
                '' => 'None'
            ],
            'filter' => TRUE
        ],
        [
            'fieldKey' => 'tag',
            'label' => 'Tag',
            'div' => InputDiv::COL_SM_12,
            'showIn' => TRUE,
            'maxLength' => 16,
            'filter' => [
                'type' => 'LIKE'
            ]
        ],
        [
            'fieldKey' => 'question',
            'label' => 'Question',
            'div' => InputDiv::COL_SM_12,
            'showIn' => TRUE,
            'filter' => [
                'type' => 'LIKE'
            ]
        ],
        [
            'fieldKey' => 'type',
            'label' => 'Type',
            'type' => InputType::SELECT,
            'showIn' => ['index', 'add', 'edit', 'view', 'import'],
            'div' => InputDiv::COL_SM_4,
            'options' => [
                '' => 'None',
                'text' => 'Text',
                'textarea' => 'Textarea',
                'number' => 'Number',
                'options' => 'Options',
                'date' => 'Date',
                'file' => 'Image'
            ],
            'filter' => TRUE
        ],

        [
            'fieldKey' => 'required',
            'label' => 'Required',
            'type' => InputType::SELECT,
            'div' => InputDiv::COL_SM_4,
            'options' => [
                '' => 'None',
                TRUE => 'YES',
                FALSE => 'NO',
            ],
            'showIn' => TRUE,
            'filter' => TRUE
        ],

        [
            'fieldKey' => 'readonly',
            'label' => 'ReadOnly',
            'type' => InputType::SELECT,
            'div' => InputDiv::COL_SM_4,
            'options' => [
                '' => 'None',
                TRUE => 'YES',
                FALSE => 'NO',
            ],
            'showIn' => TRUE,
            'filter' => TRUE
        ],

        [
            'fieldKey' => 'options',
            'label' => 'Options (separated by commas)',
            'div' => InputDiv::COL_SM_12,
            'rows' => 1,
            'showIn' => ['add', 'edit', 'view'],
            'filter' => [
                'type' => 'LIKE'
            ]
        ],


        [
            'fieldKey' => 'created',
            'label' => 'Created',
            'type' => InputType::DATE,
            'showIn' => ['view'],
            'sourceFormat' => 'Y-m-d',
            'displayFormat' => 'd/m/Y',
            'filter' => [
                'type' => InputType::DATERANGE,
                'operator' => 'BETWEEN',
                'split' => ' to '
            ],
        ],
        [
            'fieldKey' => 'modified',
            'label' => 'Modificado',
            'type' => 'text',
            'showIn' => ['view'],
            'sourceFormat' => 'Y-m-d',
            'displayFormat' => 'd/m/Y',
            'filter' => [
                'type' => InputType::DATERANGE,
                'operator' => 'BETWEEN',
                'split' => ' to '
            ],
        ],
        [
            'fieldKey' => 'comments',
            'label' => 'Comentarios',
            'type' => 'text',
            'rows' => 3,
            'weight' => UIFormHelper::UI_WEIGHT_MD_12,
            'showIn' => FALSE,
        ],
        [
            'fieldKey' => 'status',
            'label' => 'Status',
            'type' => InputType::SELECT,
            'div' => InputDiv::COL_SM_12,
            'options' => [
                '' => 'SELECT A OPTION',
                TRUE => 'ACTIVE',
                FALSE => 'INACTIVE',
            ],
            'showIn' => TRUE,
            'filter' => TRUE
        ],
    ];
    public $validate = [
        'survey_id' => [
            'rule' => 'notBlank',
            'required' => true,
            'message' => 'The field survey is required'
        ],
        'question' => [
            'rule' => 'notBlank',
            'required' => true,
            'message' => 'The field question is required'
        ],
        'type' => [
            'rule' => 'notBlank',
            'required' => true,
            'message' => 'The field type is required'
        ],
        'status' => [
            'rule' => 'notBlank',
            'required' => true,
            'message' => 'The field status is required'
        ]
    ];
    public $belongsTo = [
        'Survey' => [
            'className' => 'Survey',
            'dependent' => true
        ]
    ];



    public $hasMany = [
        'QuestionRule' => [
            'className' => 'QuestionRule',
            'foreignKey' => 'question_id',
            'dependent' => true
        ],
        'QuestionAnswer' => [
            'className' => 'QuestionAnswer',
            'foreignKey' => 'question_id',
            'dependent' => true
        ]
    ];
}
