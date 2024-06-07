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
        [
            'fieldKey' => 'name',
            'label' => 'Nombre',
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
            'label' => 'Creado',
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

    public function oneTouchLink($created, $id)
    {
        if (!$created || !isset($id))
            return;

        $data = $this->read(null, $id);


        $urlActivate = Router::url(
            [
                'controller' => 'Accounts',
                'action' => 'activate',
                $data['Account']['token'],
            ],
            true
        );

        $this->sendEmail([
            'to' => [
                $data['Account']['username']
            ],
            'subject' => 'Activar cuenta',
            'content' => '',
            'emailFormat' => 'html',
            'template' => 'activate',
            'viewVars' => [
                'data' => [
                    'name' => isset($data['Employee']['first_name']) ? $data['Employee']['first_name'] : '',
                    'url' => $urlActivate,
                    'device' => $data['Account']['device']
                ]
            ]
        ]);
    }

    /* public function beforeImplement() {
      if (AuthComponent::user() && AuthComponent::user('account_type_id') != 1) {
      unset($this->fields['school_id']);

      $this->conditions[$this->name . '.id !='] = AuthComponent::user('Partner.id');

      $this->data[$this->name]['school_id'] = AuthComponent::user('Partner.school_id');
      $this->conditions[$this->name . '.school_id'] = AuthComponent::user('Partner.school_id');
      }
      } */
}
