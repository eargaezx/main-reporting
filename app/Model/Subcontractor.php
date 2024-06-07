<?php

App::uses('ImplementableModel', 'Implementable.Model');

class Subcontractor extends ImplementableModel
{

    public $singularDisplayName = "Subcontractor";
    public $pluralDisplayName = "Subcontractors";
    public $fields = [
        [
            'fieldKey' => 'id',
            'type' => 'hidden',
            'showIn' => ['edit']
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
            'label' => 'Nombre',
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
            'label' => 'Creado',
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
            'label' => 'Modificado',
            'showIn' => ['view']
        ],
        [
            'fieldKey' => 'status',
            'label' => 'Estatus',
            'type' => InputType::SELECT,
            'div' => InputDiv::COL_SM_12,
            'options' => [
                '' => 'SELECCIONAR',
                '1' => 'ACTIVO',
                '0' => 'INACTIVO',
            ],
            'showIn' => TRUE,
            'filter' => [
                'operator' => 'LIKE'
            ]
        ],
        [
            'fieldKey' => 'comments',
            'label' => 'Comentarios',
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
            'required' => true,
        ],
        'status' => [
            'noBlank' => [
                'rule' => 'notBlank',
                'message' => 'El campo estatus es requerido'
            ]
        ],
    ];
    public $hasMany = [
        'Operator' => [
            'className' => 'Operator',
            'dependent' => true,
            //'conditions' => 'Employee.account_type_id = 5',
        ],
    ];
    public $belongsTo = [
        'Operator' => [
            'className' => 'Operator',
            'dependent' => true,
            //'conditions' => 'Employee.account_type_id = 3',
        ],
    ];


    public function beforeImplement()
    {
        if (AuthComponent::user() && AuthComponent::user('account_type_id') == 6) {
            $this->data[$this->name]['customer_id'] = AuthComponent::user('Customer.id');
        }
    }


}
