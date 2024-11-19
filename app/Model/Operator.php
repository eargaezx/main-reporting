<?php

App::uses('ImplementableModel', 'Implementable.Model');

class Operator extends ImplementableModel
{

    public $singularDisplayName = 'Operator';

    public $pluralDisplayName = 'Operators';
    public $displayField = 'name';


    public $virtualFields = [
        'name' => 'CONCAT(""," ","")'
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
            'div' => InputDiv::COL_SM_12,
            'showIn' => TRUE,
            // 'bindValue' => 'Subcontractor.name',
            'filter' => TRUE,
            'options' => [
                '' => 'SELECT A OPTION',
            ]
        ],
        'account_type_id' => [
            'label' => 'Operator Type',
            'div' => InputDiv::COL_SM_12,
            'fieldKey' => 'account_type_id',
            'modelClass' => Account::class
        ],
        [
            'fieldKey' => 'photo',
            'label' => 'Photo',
            'type' => InputType::IMAGE,
            'div' => InputDiv::COL_SM_12,
            'showIn' => TRUE,
        ],
        [
            'fieldKey' => 'name',
            'label' => 'Name',
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
            'fieldKey' => 'phone',
            'label' => 'Phone number',
            'div' => InputDiv::COL_SM_6,
            'showIn' => TRUE,
            'filter' => [
                'type' => 'LIKE'
            ]
        ],
        [
            'fieldKey' => 'username',
            'modelClass' => Account::class,
            'div' => InputDiv::COL_SM_6,
            'showIn' => ['add', 'view', 'edit', 'setup', 'index']
        ],
        [
            'fieldKey' => 'pending',
            'label' => 'Pending Orders',
            'div' => InputDiv::COL_SM_12,
            'showIn' => ['index'],
            'filter' => [
                'type' => 'LIKE'
            ]
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
            'modelClass' => Account::class,
            'div' => InputDiv::COL_SM_12,

        ],
        [
            'fieldKey' => 'created',
            'modelClass' => Account::class,
            'showIn' => ['view'],
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
            'div' => InputDiv::COL_SM_12,
            'showIn' => FALSE,
        ],
    ];
    public $validate = [
        /*'subcontractor_id' => [
            'rule' => 'notBlank',
            'required' => true,
            'message' => 'Subcontractor field is required',
            'on' => 'update'
        ],*/
        'first_name' => [
            'rule' => 'notBlank',
            'required' => true,
            'message' => 'First name field is required'
        ],
        'last_name' => [
            'rule' => 'notBlank',
            'required' => true,
            'message' => 'Last name field is required'
        ]
    ];
    public $belongsTo = [
        'Subcontractor' => [
            'className' => 'Subcontractor',
            'foreignKey' => 'subcontractor_id',
            'dependent' => true
        ],
        'Account' => [
            'className' => 'Account',
            'foreignKey' => 'account_id',
            'dependent' => true
        ]
    ];

    public $hasMany = [
        'Order' => [
            'className' => 'Order',
            'dependent' => true,
        ]
    ];

    public function beforeFind($query)
    {
        $this->beforeImplement();
        return parent::beforeFind($query);
    }



    public function afterFind($results, $primary = false)
    {
        foreach ($results as $key => $val) {
            if (isset($val[$this->name]['first_name'])) {
                $results[$key][$this->name]['pending'] = $this->Order->find('count', [
                    'conditions' => ['Order.status' => 1, 'Order.operator_id' => $val[$this->name]['id']]
                ]);
            }
        }
        return $results;
    }



    /* Logic custom functions */
    public function beforeImplement()
    {

        if (AuthComponent::user() && in_array(AuthComponent::user('AccountType.name'), ['Subcontractor', 'Supervisor']) ) {
            unset($this->fields['subcontractor_id']['options']['']);
            $this->fields['subcontractor_id']['disabled'] = 'true';
            $this->Subcontractor->conditions['Subcontractor.id'] = AuthComponent::user('Operator.subcontractor_id');
            $this->conditions['Operator.subcontractor_id'] = AuthComponent::user('Operator.subcontractor_id');
            $this->conditions['Operator.id !='] = AuthComponent::user('Operator.id');
            unset($this->fields['subcontractor_id']);
           // unset($this->fields['account_type_id']);

            if (is_array($this->data)) {
                foreach ($this->data as &$dataItem) {
                    // Verifica si subcontractor_id está presente y configúralo a 1
                    if (AuthComponent::user('AccountType.name') == 'Subcontractor')
                        $dataItem['subcontractor_id'] = AuthComponent::user('Operator.subcontractor_id');
                }
            }

        }


        if (AuthComponent::user() && AuthComponent::user('AccountType.name') == 'Technician') {
            $this->conditions['Operator.subcontractor_id'] = AuthComponent::user('Operator.subcontractor_id');

        }

    }


    public function afterSave($created, $options = [])
    {
        // Llama al método para actualizar el counter cache
        $subcontractorLicense = $this->Subcontractor->SubcontractorLicense;
        $subcontractorLicense->updateCurrentUsersCount($this->data['Operator']['subcontractor_id']);
    }


    public function afterDelete($options = [])
    {
        // Llama al método para actualizar el counter cache
        $subcontractorLicense = $this->Subcontractor->SubcontractorLicense;
        if (!empty($this->data['Operator']))
            $subcontractorLicense->updateCurrentUsersCount($this->data['Operator']['subcontractor_id']);
    }


}
