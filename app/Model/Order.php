<?php

App::uses('ImplementableModel', 'Implementable.Model');

class Order extends ImplementableModel
{

    public $singularDisplayName = 'Order';
    public $pluralDisplayName = 'Orders';


    /*public $actions = [
        'edit' => [
            'action' => 'edit',
            'title' => 'Edit',
            'class' => 'btn btn-sm btn-warning waves-effect waves-light',
            'icon' => [
                'class' => 'fe-edit-2'
            ]
        ],
        'licensing' => [
            'controller' => 'Orders',
            'action' => 'reassign',
            'title' => 'Reassign',
            'class' => 'btn btn-sm btn-warning waves-effect waves-light',
            'icon' => [
                'class' => 'fe-repeat'
            ]
        ],
    ];*/

    public $fields = [
        [
            'fieldKey' => 'id',
            'label' => FALSE,
            'type' => 'hidden',
            'showIn' => ['edit'],
            'show'
        ],
        'contractor_id' => [
            'fieldKey' => 'contractor_id',
            'label' => 'Contractor',
            'type' => InputType::SELECT,
            'div' => InputDiv::COL_SM_12,
            'importable' => true,
            'importAs' => 'contractor',
            'exportable' => true,
            'showIn' => ['index', 'add', 'edit', 'view', 'store', 'maps'],
            'options' => [
                '' => 'SELECT A OPTION'
            ],
            'filter' => TRUE
        ],
        'subcontractor_id' => [
            'fieldKey' => 'subcontractor_id',
            'label' => 'Subcontractor',
            'type' => InputType::SELECT,
            'div' => InputDiv::COL_SM_12,
            'importable' => true,
            'exportable' => true,
            'showIn' => ['index', 'add', 'edit', 'view', 'store', 'maps', 'reassign'],
            // 'bindValue' => 'Subcontractor.name',
            'options' => [
                '' => 'None'
            ],
            'filter' => TRUE
        ],
        'operator_id' => [
            'fieldKey' => 'operator_id',
            'label' => 'Technician',
            'type' => InputType::SELECT,
            'div' => InputDiv::COL_SM_12,
            'exportable' => true,
            'showIn' => ['index', 'add', 'edit', 'view', 'store', 'maps'],
            'importable' => true,
            // 'bindValue' => 'Subcontractor.name',
            'options' => [
                '' => 'None'
            ],
            'filter' => TRUE
        ],
        [
            'fieldKey' => 'name',
            'label' => 'ORDER ID',
            'exportable' => true,
            'importable' => true,
            'div' => InputDiv::COL_SM_12,
            'showIn' => ['index', 'view', 'maps'],
            'filter' => [
                'type' => 'LIKE'
            ]
        ],
        [
            'fieldKey' => 'customer',
            'class' => 'form-control',
            'label' => 'Customer',
            'exportable' => true,
            'div' => InputDiv::COL_SM_6,
            'showIn' => ['index', 'add', 'edit', 'view', 'maps'],
            'importable' => true,
            'filter' => [
                'type' => 'LIKE'
            ]
        ],
        [
            'fieldKey' => 'phone',
            'class' => 'form-control',
            'label' => 'Phone',
            'div' => InputDiv::COL_SM_6,
            'showIn' => ['index', 'add', 'edit', 'view', 'maps'],
            'exportable' => true,
            'importable' => true,
            'filter' => [
                'type' => 'LIKE'
            ]
        ],
        [
            'fieldKey' => 'address',
            'label' => 'Address',
            'type' => InputType::SELECT,
            'div' => InputDiv::COL_SM_4,
            'showIn' => ['index', 'add', 'edit', 'view', 'maps'],
            'importable' => true,
            'exportable' => true,
            'filter' => [
                'type' => 'LIKE'
            ]
        ],
        [
            'fieldKey' => 'action',
            'class' => 'form-control',
            'label' => 'Action',
            'div' => InputDiv::COL_SM_4,
            'showIn' => ['index', 'add', 'edit', 'view'],
            'importable' => true,
            'exportable' => true,
            'filter' => [
                'type' => 'LIKE'
            ]
        ],
        [
            'fieldKey' => 'drop_install_type',
            'class' => 'form-control',
            'label' => 'Install Type',
            'div' => InputDiv::COL_SM_4,
            'showIn' => ['add', 'edit', 'view'],
            'importable' => false,
            'exportable' => true,
            'filter' => [
                'type' => 'LIKE'
            ]
        ],
        [
            'fieldKey' => 'ped',
            'class' => 'form-control',
            'label' => 'PED',
            'div' => InputDiv::COL_SM_12,
            'showIn' => ['add', 'edit', 'view'],
            'importable' => true,
            'exportable' => true,
            'filter' => [
                'type' => 'LIKE'
            ]
        ],

        [
            'fieldKey' => 'created',
            'label' => 'Created',
            'type' => InputType::DATE,
            'showIn' => ['view', 'maps'],
            'sourceFormat' => 'Y-m-d',
            'displayFormat' => 'd/m/Y',
            'exportable' => true,
            'filter' => [
                'type' => InputType::DATERANGE,
                'operator' => 'BETWEEN',
                'split' => ' to '
            ],
        ],

        [
            'fieldKey' => 'status',
            'label' => 'Status',
            'type' => InputType::SELECT,
            'div' => InputDiv::COL_SM_12,
            'exportable' => true,
            'showIn' => ['index', 'add', 'edit', 'view', 'maps'],
            'options' => [
                0 => 'None',
                1 => 'PENDING',
                2 => 'COMPLETED'
            ],
            'filter' => TRUE
        ],


        [
            'fieldKey' => 'notes',
            'label' => 'Notes',
            'type' => 'text',
            'rows' => 3,
            'div' => InputDiv::COL_SM_12,
            'importable' => true,
            'exportable' => true,
            'showIn' => ['add', 'edit', 'view'],
        ],


        [
            'fieldKey' => 'modified',
            'label' => 'Modificado',
            'type' => 'text',
            'exportable' => false,
            'showIn' => ['view'],
        ],
        [
            'fieldKey' => 'comments',
            'label' => 'Comentarios',
            'type' => 'text',
            'rows' => 3,
            'exportable' => true,
            'div' => UIFormHelper::UI_WEIGHT_MD_12,
            'showIn' => FALSE,
        ],
        'survey_id' => [
            'fieldKey' => 'survey_id',
            'label' => 'Work Type',
            'type' => InputType::SELECT,
            'div' => InputDiv::COL_SM_12,
            'showIn' => ['index', 'add', 'edit', 'view'],
            'exportable' => false,
            // 'bindValue' => 'Subcontractor.name',
            'options' => [
                '' => 'None'
            ],
            'filter' => TRUE
        ],

        [
            'fieldKey' => 'image_evidence_1',
            'label' => 'Evidence 1',
            'type' => InputType::IMAGE,
            'div' => InputDiv::COL_SM_3,
            'showIn' => ['add', 'edit'],
        ],
        [
            'fieldKey' => 'image_evidence_2',
            'label' => 'Evidence 2',
            'type' => InputType::IMAGE,
            'div' => InputDiv::COL_SM_3,
            'showIn' => ['add', 'edit'],
        ],
        [
            'fieldKey' => 'image_evidence_3',
            'label' => 'Evidence 3',
            'type' => InputType::IMAGE,
            'div' => InputDiv::COL_SM_3,
            'showIn' => ['add', 'edit'],
        ],
        [
            'fieldKey' => 'image_evidence_4',
            'label' => 'Evidence 4',
            'type' => InputType::IMAGE,
            'div' => InputDiv::COL_SM_3,
            'showIn' => ['add', 'edit'],
        ],
        [
            'fieldKey' => 'image_evidence_5',
            'label' => 'Evidence 5',
            'type' => InputType::IMAGE,
            'div' => InputDiv::COL_SM_3,
            'showIn' => ['add', 'edit'],
        ],
        [
            'fieldKey' => 'image_evidence_6',
            'label' => 'Evidence 6',
            'type' => InputType::IMAGE,
            'div' => InputDiv::COL_SM_3,
            'showIn' => ['add', 'edit'],
        ],
    ];
    public $validate = [
        'subcontractor_id' => [
            'rule' => 'notBlank',
            //'required' => true,
            'message' => 'El campo contratista es requerido',
            'on' => 'update'
        ]
    ];
    public $belongsTo = [
        'Contractor' => [
            'className' => 'Contractor',
            'foreignKey' => 'contractor_id',
        ],
        'Subcontractor' => [
            'className' => 'Subcontractor',
            'dependent' => true
        ],
        'Operator' => [
            'className' => 'Operator',
            'dependent' => true
        ],
        'Survey' => [
            'className' => 'Survey',
            'dependent' => true
        ]
    ];

    public $hasMany = [
        'QuestionAnswer' => [
            'className' => 'QuestionAnswer',
            'foreignKey' => 'order_id',
            'dependent' => true,
        ]
    ];

    public function beforeSave($options = array())
    {
        if (empty($this->data[$this->name]['id']) || !$this->exists($this->data[$this->name]['id'])) {
            $this->data[$this->name]['status'] = 1;
        } else if (!empty($this->data[$this->name]['id']) && $this->exists($this->data[$this->name]['id']) && !empty($this->data[$this->name]['survey_id'])) {
            $this->data[$this->name]['status'] = 2;
            $this->data[$this->name]['deployEndEmail'] = 1;
        }


        if (!empty($this->data[$this->name]['id']) || (!empty($this->data[$this->name]['id']) && $this->exists($this->data[$this->name]['id']))) {
            unset($this->data[$this->name]['created']);
        }

        return parent::beforeSave($options);
    }

    public function afterSave($created, $options = array())
    {
        ///$this->oneTouchLink($created, $this->id);

        if (!empty($this->data[$this->name]['deployEndEmail']) && $this->data[$this->name]['deployEndEmail'] == 1) {
            $this->sendEmailCompleted($this->id);
        }

        parent::afterSave($created, $options);
    }

    public function beforeDelete($cascade = true)
    {
        //$this->Account->delete($this->read('account_id')['Employee']['account_id'], false);
        parent::beforeDelete($cascade);
    }


    /* Logic custom functions */
    public function beforeImplement()
    {
        $this->Operator->virtualFields = [
            'name' => 'CONCAT( Operator.first_name ," ", Operator.last_name )',
            'pending' => '
            SELECT COUNT(*)
            FROM orders 
            WHERE 
                orders.operator_id = Operator.id 
                AND orders.status = 1
            ',
        ];

        if (AuthComponent::user() && in_array(AuthComponent::user('AccountType.name'), ['Subcontractor', 'Supervisor'])) {
            unset($this->fields['subcontractor_id']);
            $this->conditions[$this->name . '.subcontractor_id'] = AuthComponent::user('Operator.subcontractor_id');
            $this->data[$this->name]['subcontractor_id'] = AuthComponent::user('Operator.subcontractor_id');
        }

        if (AuthComponent::user() && AuthComponent::user('AccountType.name') == 'Contractor') {
            unset($this->fields['operator_id']);
            unset($this->fields['contractor_id']);
            $this->conditions[$this->name . '.contractor_id'] = AuthComponent::user('Partner.contractor_id');
            $this->data[$this->name]['contractor_id'] = AuthComponent::user('Partner.contractor_id');
        }

        if (AuthComponent::user() && AuthComponent::user('AccountType.name') == 'Technician') {
            unset($this->fields['subcontractor_id']);
            $this->conditions['Order.subcontractor_id'] = AuthComponent::user('Operator.subcontractor_id');
            $this->conditions['Order.operator_id'] = AuthComponent::user('Operator.id');
            $this->data['Order']['subcontractor_id'] = AuthComponent::user('Operator.subcontractor_id');
        }
    }


    public function sendEmailCompleted($id)
    {
        $Account = ClassRegistry::init('Account');
        $Account->recursive = -1;
        $Account->virtualFields = [];
        $Account->Operator->virtualFields = [];
        $Account->Partner->virtualFields = [];

        if (!isset($id))
            return;

        $order = $this->read(null, $id);

        $url = Router::url([
            'controller' => 'Accounts',
            'action' => 'login'
        ], true);

        $to = [];

        $operator = $Account->find('first', array(
            'fields' => ['Account.username'],//bind by operator id
            'joins' => [
                [
                    'table' => 'operators',
                    'alias' => 'Operator',
                    'type' => 'INNER',  // Solo usuarios con operador
                    'conditions' => [
                        'Account.id = Operator.account_id',
                        'Operator.owner !=' => 1, //technicians and supervisors
                        'Operator.id' =>  $order['Order']['operator_id']
                    ]
                ]
            ],
        ));

  
        $subcontractor = $Account->find('first', array(
            'fields' => ['Account.username'], //bind by subcontractor id
            'joins' => [
                [
                    'table' => 'operators',
                    'alias' => 'Operator',
                    'type' => 'INNER',  // Solo usuarios con operador
                    'conditions' => [
                        'Account.id = Operator.account_id',
                        'Operator.owner' => 1, //sucontractistas
                        'Operator.subcontractor_id' =>  $order['Order']['subcontractor_id']
                    ]
                ]
            ]
        ));

        $contractor =  $Account->find('first', array(
            'fields' => ['Account.username'], //bind by contractor id
            'joins' => [
                [
                    'table' => 'partners',
                    'alias' => 'Partner',
                    'type' => 'INNER',  // Solo usuarios con operador
                    'conditions' => [
                        'Account.id = Partner.account_id',
                        'Partner.contractor_id' =>  $order['Order']['contractor_id']
                    ]
                ]
            ]
        ));

        $survey = $this->Survey->findById($order['Order']['survey_id']);




        if (!empty($operator['Account']['username'])) {
            $to[] = $operator['Account']['username'];
        }

        if (!empty($subcontractor['Account']['username'])) {
            $to[] = $subcontractor['Account']['username'];
        }

        if (!empty($contractor['Account']['username'])) {
            $to[] = $contractor['Account']['username'];
        }


        $this->sendEmail([
            'to' => $to,
            'subject' => 'Finished Order',
            'content' => '',
            'emailFormat' => 'html',
            'template' => 'completed',
            'viewVars' => [
                'order' => $order['Order']['name'],
                'survey' => $survey['Survey']['name'],
                'url' => $url
            ]
        ]);
    }
}
