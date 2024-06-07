<?php

App::uses('ImplementableModel', 'Implementable.Model');

class Operator extends ImplementableModel
{

    public $singularDisplayName = 'Technician';
    public $pluralDisplayName = 'Technicians';
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
            'showIn' => ['add', 'edit', 'view']
        ],
        [
            'fieldKey' => 'last_name',
            'label' => 'Apellido(s)',
            'showIn' => ['add', 'edit', 'view']
        ],
        [
            'fieldKey' => 'username',
            'modelClass' => Account::class,
            'weight' => UIFormHelper::UI_WEIGHT_MD_6,
        ],
        'password' => [
            'fieldKey' => 'password',
            'modelClass' => Account::class,
            'showIn' => ['add', 'view', 'edit']
        ],
        'repeated_password' => [
            'fieldKey' => 'repeated_password',
            'modelClass' => Account::class,
            'showIn' => ['add', 'view', 'edit']
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
            'message' => 'El campo contratista es requerido',
            'on' => 'update'
        ]
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
