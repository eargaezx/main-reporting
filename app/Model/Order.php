<?php

App::uses('ImplementableModel', 'Implementable.Model');

class Order extends ImplementableModel
{

    public $singularDisplayName = 'Order';
    public $pluralDisplayName = 'Orders';
    public $fields = [
        [
            'fieldKey' => 'id',
            'label' => FALSE,
            'type' => 'hidden',
            'showIn' => ['edit'],
            'show'
        ],
        'subcontractor_id' => [
            'fieldKey' => 'subcontractor_id',
            'label' => 'Subcontractor',
            'type' => InputType::SELECT,
            'div' => InputDiv::COL_SM_12,
            'showIn' => TRUE,
            // 'bindValue' => 'Subcontractor.name',
            'options' => [
                '' => 'None'
            ],
            'filter' => TRUE
        ],
        'operator_id' => [
            'fieldKey' => 'operator_id',
            'label' => 'Operator',
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
            'fieldKey' => 'name',
            'label' => 'ORDER ID',
            'div' => InputDiv::COL_SM_12,
            'showIn' => TRUE,
            'filter' => [
                'type' => 'LIKE'
            ]
        ],
        [
            'fieldKey' => 'status',
            'label' => 'Status',
            'type' => InputType::SELECT,
            'div' => InputDiv::COL_SM_12,
            'showIn' => ['index', 'add', 'edit', 'view', 'import'],
            'options' => [
                '' => 'None',
                2 => 'Pending',
                1 => 'Completed'
            ],
            'filter' => TRUE
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
            'label' => 'Modificado',
            'type' => 'text',
            'showIn' => ['view'],
        ],
        [
            'fieldKey' => 'comments',
            'label' => 'Comentarios',
            'type' => 'text',
            'rows' => 3,
            'div' => UIFormHelper::UI_WEIGHT_MD_12,
            'showIn' => FALSE,
        ],
        'survey_id' => [
            'fieldKey' => 'survey_id',
            'label' => 'Work Type',
            'type' => InputType::SELECT,
            'div' => InputDiv::COL_SM_12,
            'showIn' => TRUE,
            // 'bindValue' => 'Subcontractor.name',
            'options' => [
                '' => 'None'
            ],
            'filter' => TRUE
        ],
    ];
    public $validate = [
        'subcontractor_id' => [
            'rule' => 'notBlank',
            'required' => true,
            'message' => 'El campo contratista es requerido',
            'on' => 'update'
        ]
    ];
    public $belongsTo = [
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

    public function afterSave($created, $options = array())
    {
        ///$this->oneTouchLink($created, $this->id);
        parent::afterSave($created, $options);
    }

    public function beforeDelete($cascade = true)
    {
        $this->Account->delete($this->read('account_id')['Employee']['account_id'], false);
        parent::beforeDelete($cascade);
    }

    /* Logic custom functions */
    public function beforeImplement()
    {
        if (AuthComponent::user() && AuthComponent::user('AccountType.name') != 'Systems') {
            unset($this->fields['subcontractor_id']);
            $this->conditions[$this->name . '.subcontractor_id'] = AuthComponent::user('Operator.subcontractor_id');
        }
    }
}
