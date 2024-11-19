<?php

App::uses('ImplementableModel', 'Implementable.Model');

class Subcontractor extends ImplementableModel
{
    public $singularDisplayName = "Subcontractor";
    public $pluralDisplayName = "Subcontractors";

    public $virtualFields = [
        'active_licensing' => '
             SELECT CASE 
                    WHEN MAX(SubcontractorLicense.end_date) >= NOW() 
                    THEN "CURRENT" 
                    ELSE "EXPIRED" 
                END
            FROM subcontractor_licenses AS SubcontractorLicense 
            INNER JOIN licenses AS License ON License.id = SubcontractorLicense.license_id
            WHERE SubcontractorLicense.subcontractor_id = Subcontractor.id
            AND SubcontractorLicense.end_date >= NOW()
        ',
        'active_license_users' => '
            SELECT CONCAT(SubcontractorLicense.current_users, "/", SubcontractorLicense.max_users)
            FROM subcontractor_licenses AS SubcontractorLicense 
            WHERE SubcontractorLicense.subcontractor_id = Subcontractor.id 
            AND SubcontractorLicense.end_date >= NOW()
            ORDER BY SubcontractorLicense.end_date DESC
            LIMIT 1 ',
    ];

    public $actions = [
        'edit' => [
            'action' => 'edit',
            'title' => 'Edit',
            'class' => 'btn btn-sm btn-warning waves-effect waves-light',
            'icon' => [
                'class' => 'fe-edit-2'
            ]
        ],
        'licensing' => [
            'controller' => 'SubcontractorLicenses',
            'action' => 'licensing',
            'title' => 'Licensing',
            'class' => 'btn btn-sm btn-warning waves-effect waves-light',
            'icon' => [
                'class' => 'fe-refresh-ccw'
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
            'fieldKey' => 'licensing',
            'label' => 'Licensing',
            'div' => InputDiv::COL_SM_12,
            'showIn' => ['view', 'index'],
            'filter' => FALSE
        ],
        [
            'fieldKey' => 'active_licensing',
            'label' => 'Licensing status',
            'type' => InputType::SELECT,
            'div' => InputDiv::COL_SM_12,
            'showIn' => ['view', 'index'],
            'filter' => TRUE,
            'options' => [
                '' => 'SELECT A OPTION',
                'CURRENT' => 'CURRENT',
                'EXPIRED' => 'EXPIRED',
            ],
        ],
        [
            'fieldKey' => 'active_license_users',
            'label' => 'Users',
            'div' => InputDiv::COL_SM_12,
            'showIn' => ['view', 'index'],
            'filter' => FALSE,
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
    public $hasMany = [
        'Operator' => [
            'className' => 'Operator',
            'dependent' => false,
            //'conditions' => 'Employee.account_type_id = 5',
        ],
        'Partnership' => [
            'className' => 'Partnership',
            'dependent' => true,
        ],
        'Order' => [
            'className' => 'Order',
            'dependent' => true,
        ],
    ];
    public $hasOne = [
        'Admin' => [
            'className' => 'Operator',
            'dependent' => false,
            'conditions' => 'Admin.owner = 1',
        ],
        'SubcontractorLicense' => [
            'className' => 'SubcontractorLicense',
            'dependent' => true,
            'conditions' => 'SubcontractorLicense.status = 1',
        ],
    ];

    /*public $belongsTo = [
        'Operator' => [
            'className' => 'Operator',
            'dependent' => true,
            //'conditions' => 'Employee.account_type_id = 3',
        ],
    ];*/


    public function beforeImplement()
    {
        if (AuthComponent::user() && AuthComponent::user('AccountType.name') == 'Systems') {
            $this->virtualFields = [
                'active_licensing' => '
                    SELECT CASE 
                        WHEN MAX(SubcontractorLicense.end_date) >= NOW() 
                        THEN "CURRENT" 
                        ELSE "EXPIRED" 
                    END
                    FROM subcontractor_licenses AS SubcontractorLicense 
                    WHERE SubcontractorLicense.subcontractor_id = Subcontractor.id 
                    AND SubcontractorLicense.end_date >= NOW()
                ',
                'active_license_users' => '
                SELECT CONCAT(SubcontractorLicense.current_users, "/", SubcontractorLicense.max_users)
                FROM subcontractor_licenses AS SubcontractorLicense 
                WHERE SubcontractorLicense.subcontractor_id = Subcontractor.id 
                AND SubcontractorLicense.end_date >= NOW()
                ORDER BY SubcontractorLicense.end_date DESC
                LIMIT 1
            '
            ];
        }
    }




    public function afterDelete($options = [])
    {

        $Account = ClassRegistry::init('Account');

        $operators = $this->Operator->find('all', [
            'conditions' => [
                'Operator.subcontractor_id' => $this->id
            ]
        ]);

        $operatorsIds = Hash::extract($operators, '{n}.Operator.id');
        $accountIds = Hash::extract($operators, '{n}.Operator.account_id');


        $Account->deleteAll([
            'Account.id' => $accountIds
        ], $cascade = false, $callbacks = false);

        $this->Operator->deleteAll([
            'Operator.id' => $operatorsIds
        ], $cascade = false, $callbacks = false);

        $this->SubcontractorLicense->deleteAll([
            'SubcontractorLicense.subcontractor_id' => $this->id
        ], $cascade = false, $callbacks = false);

        $this->Order->deleteAll([
            'Order.subcontractor_id' => $this->id
        ], $cascade = false, $callbacks = false);

        parent::afterDelete($options);
    }


    public function afterFind($results, $primary = false)
    {
        foreach ($results as $key => $val) {
            if (isset($val['SubcontractorLicense'])) {
                $licensing = $this->SubcontractorLicense->find('first', [
                    'conditions' => ['SubcontractorLicense.id' => $val['SubcontractorLicense']['id']]
                ]);
                $results[$key][$this->name]['licensing'] = $licensing['License']['name'];
            }
        }
        return $results;
    }


}
