<?php

App::uses('ImplementableModel', 'Implementable.Model');

class Operator extends ImplementableModel
{

    public $singularDisplayName = 'Technician';
    public $virtualFields = [
        'name' => 'CONCAT(Operator.first_name," ",Operator.last_name)',
    ];
    public $fields = [
        [
            'fieldKey' => 'id',
            'label' => FALSE,
            'type' => 'hidden',
            'showIn' => ['edit'],
            'show'
        ],
        [
            'fieldKey' => 'id',
            'modelClass' => Account::class
        ],
        'subcontractor_id' => [
            'fieldKey' => 'subcontractor_id',
            'label' => 'Subcontractor',
            'type' => InputType::SELECT,
            'weight' => InputDiv::COL_SM_12,
            'showIn' => TRUE,
            // 'bindValue' => 'Subcontractor.name',
            'filter' => [
                'type' => 'EQUAL'
            ]
        ],
        [
            'fieldKey' => 'account_type_id',
            'modelClass' => Account::class
        ],
        [
            'fieldKey' => 'name',
            'label' => 'Nombre',
            'div' => InputDiv::COL_SM_12,
            'showIn' => ['index'],
            'filter' => [
                'type' => 'LIKE'
            ]
        ],
        [
            'fieldKey' => 'first_name',
            'label' => 'Nombre(s)',
            'showIn' => ['add', 'edit', 'view', 'setup']
        ],
        [
            'fieldKey' => 'last_name',
            'label' => 'Apellido(s)',
            'showIn' => ['add', 'edit', 'view', 'setup']
        ],
        [
            'fieldKey' => 'username',
            'modelClass' => Account::class,
            'weight' => UIFormHelper::UI_WEIGHT_MD_6,
            'showIn' => ['add', 'view', 'edit', 'setup']
        ],
        'password' => [
            'fieldKey' => 'password',
            'modelClass' => Account::class,
            'showIn' => ['add', 'view', 'edit', 'setup']
        ],
        'repeated_password' => [
            'fieldKey' => 'repeated_password',
            'modelClass' => Account::class,
            'showIn' => ['add', 'view', 'edit', 'setup']
        ],
        [
            'fieldKey' => 'status',
            'modelClass' => Account::class
        ],
        [
            'fieldKey' => 'created',
            'modelClass' => Account::class,
            'showIn' => ['index', 'view']
        ],
        [
            'fieldKey' => 'modified',
            'label' => 'Modificado',
            'type' => 'text',
            'showIn' => ['view'],
            'sourceFormat' => 'Y-m-d hh:mm:ss',
            'displayFormat' => 'd/m/Y hh:mm'
        ],
        [
            'fieldKey' => 'comments',
            'label' => 'Comentarios',
            'type' => 'text',
            'rows' => 3,
            'weight' => UIFormHelper::UI_WEIGHT_MD_12,
            'showIn' => FALSE,
        ],
    ];
    public $validate = [
        'subcontractor_id' => [
            'rule' => 'notBlank',
            'required' => true,
            'message' => 'Subcontractor field is required',
            'on' => 'update'
        ],
        'first_name' => [
            'rule' => 'notBlank',
            'required' => true,
            'message' => 'First name field is required'
        ],
        'last_name' => [
            'rule' => 'notBlank',
            'required' => true,
            'message' => 'Last name field is required'
        ],
    ];
    public $belongsTo = [
        'Subcontractor' => [
            'className' => 'Subcontractor',
            'dependent' => true
        ],
        'Account' => [
            'className' => 'Account',
            'foreignKey' => 'account_id',
            'dependent' => true
        ]
    ];

    public function afterSave($created, $options = array())
    {
        ///$this->oneTouchLink($created, $this->id);
        parent::afterSave($created, $options);
    }



    /* Logic custom functions */


}
