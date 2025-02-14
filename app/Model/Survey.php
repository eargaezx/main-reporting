<?php

App::uses('ImplementableModel', 'Implementable.Model');

class Survey extends ImplementableModel
{

    public $actions = [
        'view' => [
            'action' => 'view',
            'title' => 'Ver',
            'class' => 'btn btn-sm btn-warning waves-effect waves-light',
            'icon' => [
                'class' => 'fe-settings'
            ]
        ],
        'edit' => [
            'action' => 'edit',
            'title' => 'Edit',
            'class' => 'btn btn-sm btn-warning waves-effect waves-light',
            'icon' => [
                'class' => 'fe-edit-2'
            ]
        ],
        'delete' => [
            'confirm' => true,
            'action' => 'delete',
            'title' => 'Borrar',
            'class' => 'btn btn-sm btn-warning waves-effect waves-light',
            'data-message' => '¿Desea borrar el registro?',
            'data' => [],
            'icon' => [
                'class' => 'fe-trash-2'
            ]
        ]
    ];

    public $singularDisplayName = 'Survey';
    public $pluralDisplayName = 'Surveys';
    public $fields = [
        [
            'fieldKey' => 'id',
            'label' => FALSE,
            'type' => 'hidden',
            'showIn' => ['edit'],
            'show'
        ],
        [
            'fieldKey' => 'name',
            'label' => 'Name',
            'showIn' => TRUE,
            'div' => InputDiv::COL_SM_6,
            'filter' => [
                'type' => 'LIKE'
            ]
        ], 
        [
            'fieldKey' => 'sequence',
            'label' => 'Sequence',
            'div' => InputDiv::COL_SM_6,
            'type' => 'number',
            'showIn' => TRUE,
            'filter' => FALSE
        ], 
        [
            'fieldKey' => 'icon',
            'label' => 'Icon  (icon name of materialdesing icons)',
            'showIn' => ['add', 'edit', 'view'],
            'filter' => [
                'type' => 'LIKE'
            ]
        ],
        [
            'fieldKey' => 'created',
            'label' => 'Created',
            'type' => InputType::DATE,
            'showIn' => ['index', 'view'],
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
            'label' => 'Modified',
            'type' => 'text',
            'showIn' => ['index', 'view'],
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
            'label' => 'Comments',
            'type' => 'text',
            'rows' => 3,
            'weight' => UIFormHelper::UI_WEIGHT_MD_12,
            'showIn' => FALSE,
        ],
        [
            'fieldKey' => 'status',
            'label' => 'Status',
            'type' => InputType::SELECT,
            'weight' => UIFormHelper::UI_WEIGHT_MD_12,
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
        'name' => [
            'rule' => 'notBlank',
            'required' => true,
            'message' => 'El campo estatus campo es requerido'
        ],
        'status' => [
            'rule' => 'notBlank',
            'required' => true,
            'message' => 'El campo estatus campo es requerido'
        ]
    ];


    public $hasMany = [
        'Question' => [
            'className' => 'Question',
            'foreignKey' => 'survey_id',
            'dependent' => false,
            'order' => ['Question.sequence' => 'ASC']
        ]
    ];


}
