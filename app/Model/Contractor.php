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
            'class' => 'btn btn-sm btn-warning waves-effect waves-light',
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
            'exportable' => TRUE,
            'filter' => [
                'operator' => 'LIKE',
                'or' => TRUE
            ]
        ],
        [
            'fieldKey' => 'created',
            'type' => 'text',
            'exportable' => TRUE,
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
            'exportable' => TRUE,
            'label' => 'Modified',
            'showIn' => ['view']
        ],
        [
            'fieldKey' => 'status',
            'label' => 'Status',
            'exportable' => TRUE,
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
            'exportable' => TRUE,
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
            'dependent' => false,
            'conditions' => 'Partner.owner = 1',
        ],
    ];

    public $hasMany = [
        'Partnership' => [
            'className' => 'Partnership',
            'dependent' => true,
        ],
        'Order' => [
            'className' => 'Order',
            'dependent' => false,
        ]
    ];



    public function afterDelete($options = [])
    {
        $Account = ClassRegistry::init('Account');

        $partners = $this->Partner->find('all', [
            'conditions' => [
                'Partner.contractor_id' => $this->id
            ]
        ]);

        
        $accountIds = Hash::extract($partners, '{n}.Partner.account_id');

        $Account->deleteAll([
            'Account.id' => $accountIds
        ], $cascade = false, $callbacks = false);


        $this->Order->updateAll([
            'Order.contractor_id' => NULL,
        ], [
            'Order.contractor_id' => $this->id,
        ]);

        $this->Partner->deleteAll([
            'Partner.contractor_id' =>  $this->id,
        ], $cascade = false, $callbacks = false);

        parent::afterDelete($options);
    }


}
