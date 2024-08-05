<?php

App::uses('ImplementableModel', 'Implementable.Model');

class Contractor extends ImplementableModel
{

    public $singularDisplayName = "Contractor";
    public $pluralDisplayName = "Contractors";
    public $actions = [
        'edit' => [
            'action' => 'setup',
            'title' => 'Edit',
            'class' => 'btn btn-sm btn-outline-dark waves-effect waves-light',
            'icon' => [
                'class' => 'fe-edit-2'
            ]
        ],
    ];

    public $fields = [
        [
            'fieldKey' => 'id',
            'label' => FALSE,
            'type' => 'hidden',
            'showIn' => ['edit', 'setup']
        ],
        [
            'fieldKey' => 'logo',
            'label' => 'Logo',
            'type' => InputType::IMAGE,
            'div' => InputDiv::COL_SM_12,
            'showIn' => TRUE,
        ],
        [
            'fieldKey' => 'name',
            'label' => 'Name',
            'autocomplete' => 'off',
            'div' => InputDiv::COL_SM_12,
            'showIn' => TRUE,
            'filter' => [
                'operator' => 'LIKE',
                'or' => TRUE
            ]
        ],
        [
            'fieldKey' => 'created',
            'type' => 'text',
            'label' => 'Created',
            'showIn' => ['view'],
            'filter' => [
                'type' => InputType::DATERANGE,
                'operator' => 'BETWEEN',
                'split' => ' - '
            ],
        ],
        [
            'fieldKey' => 'modified',
            'type' => 'text',
            'label' => 'Modified',
            'showIn' => ['view']
        ],
        [
            'fieldKey' => 'status',
            'label' => 'Status',
            'type' => InputType::SELECT,
            'div' => InputDiv::COL_SM_12,
            'options' => [
                '' => 'SELECT A OPTION',
                true => 'ACTIVE',
                false => 'INACTIVE',
            ],
            'showIn' => TRUE,
            'filter' => [
                'operator' => 'LIKE'
            ]
        ],
        [
            'fieldKey' => 'comments',
            'label' => 'Comments',
            'div' => InputDiv::COL_SM_12,
            'rows' => 4,
            'showIn' => ['add', 'edit', 'view']
        ]
    ];
    public $validate = [
        'name' => [
            'rule' => 'notBlank',
            'message' => 'El nombre es requerido',
            'required' => true,
        ],
        'logo' => [
            'rule' => 'notBlank',
            'message' => 'El logo es requerido',
            'on' => 'create',
        ],
        'status' => [
            'noBlank' => [
                'rule' => 'notBlank',
                'message' => 'El campo estatus es requerido',
                 'on' => 'create'
            ]
        ],
    ];


    public $hasOne = [
        'Partner' => [
            'className' => 'Partner',
            'dependent' => true,
            'conditions' => 'Partner.owner = 1',
        ],
    ];


}
