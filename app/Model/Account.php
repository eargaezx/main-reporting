<?php

App::uses('ImplementableModel', 'Implementable.Model');

class Account extends ImplementableModel
{
    const ADMINISTRATOR = 1;
    const MANAGER = 2;

    public $PACKAGE_ACCOUNT_TYPES = [
        "com.axcode.root" => 1,
        "com.axcode.mercaditonaranja" => 6,
        "com.axcode.mercaditonaranjarepartidor" => 5,
        "com.axcode.mercaditonaranjanegocio" => 3,
    ];


    public $singularDisplayName = 'System Account';
    public $pluralDisplayName = 'System Accounts';
    public $displayField = 'name';
    public $virtualFields = [
        //'name' => 'CONCAT(Account.first_name," ",Account.last_name)',
        //'phone' => 'CONCAT(Account.phone_country," ",Account.phone_number)'
    ];
    public $fields = [
        [
            'fieldKey' => 'id',
            'label' => FALSE,
            'type' => 'hidden',
            'showIn' => ['edit']
        ],
        [
            'fieldKey' => 'account_type_id',
            'label' => 'Perfil',
            'type' => InputType::SELECT,
            'options' => [
                '' => 'SELECT A OPTION'
            ],
            'showIn' => TRUE,
            'filter' => TRUE
        ],
        [
            'fieldKey' => 'username',
            'label' => 'Correo electrónico',
            'filter' => TRUE,
            'autocomplete' => 'nope',
            'showIn' => ['add', 'view', 'edit', 'index'],
        ],
        [
            'fieldKey' => 'password',
            'autocomplete' => 'nope',
            'label' => 'Clave de acceso',
            'showIn' => ['add', 'edit'],
        ],
        [
            'fieldKey' => 'repeated_password',
            'label' => 'Repetir Clave de acceso',
            'autocomplete' => 'nope',
            'type' => 'password',
            'showIn' => ['add', 'edit'],
        ],
        [
            'fieldKey' => 'status',
            'label' => 'Estatus',
            'type' => InputType::SELECT,
            'weight' => UIFormHelper::UI_WEIGHT_MD_12,
            'options' => [
                '' => 'SELECT A OPTION',
                1 => 'ACTIVO',
                2 => 'INACTIVO',
            ],
            'showIn' => TRUE,
            'filter' => TRUE
        ],
        [
            'fieldKey' => 'created',
            'label' => 'Creado',
            'type' => InputType::DATERANGE,
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
    ];
    public $validate = [
        'account_type_id' => [
            'rule' => 'notBlank',
            'required' => true,
            'message' => 'El campo Tipo de cuenta es requerido'
        ],

        'username' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'allowEmpty' => false,
                'message' => 'El campo Correo es requerido'
            ],
            'unique' => [
                'rule' => 'isUnique',
                'required' => true,
                'message' => 'Ya existe un usuario registrado con este correo',
                'on' => 'create'
            ]
        ],
        'password' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => 'El campo contraseña es requerido',
                'on' => 'create'
            ],
            'matchPasswords' => [
                'rule' => 'matchPasswords',
                'message' => 'No coinciden las contraseñas',
            ],
        ],
        'repeated_password' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => 'El campo repetir es requerido',
                'on' => 'create'
            ],
            ' matchPasswords' => [
                'rule' => 'matchPasswords',
                'message' => 'No coinciden las contraseñas'
            ],
        ],
        'status' => [
            'rule' => 'notBlank',
            //'required' => true,
            'message' => 'El campo estatus campo es requerido',
             'on' => 'create'
        ]
    ];
    public $hasOne = [
        'Operator' => [
            'className' => 'Operator',
            'foreignKey' => 'account_id',
            'dependent' => true
        ],
    ];
    public $belongsTo = [
        'AccountType' => [
            'className' => 'AccountType',
            'foreignKey' => 'account_type_id',
            'dependent' => false
        ]
    ];

    public function beforeImplement()
    {
        if (AuthComponent::user() && AuthComponent::user('account_type_id') != 1) {
            unset($this->fields[1]['options'][1]);
        }
    }

    public function matchPasswords()
    {
        if ($this->data[$this->name]['password'] == $this->data[$this->name]['repeated_password'])
            return true;
        return false;
    }

    /* LifeCycle Model Callbacks */



    public function beforeSave($options = array())
    {
        if (isset($this->data[$this->name]['password'])) {
            $this->data[$this->name]['password'] = AuthComponent::password($this->data[$this->name]['password']);
        }

        if ($this->isNewRecord()) {
            $this->data[$this->name]['token'] = CakeText::uuid();
        }

        return parent::beforeSave($options);
    }

    public function afterSave($created, $options = array())
    {
        $this->oneTouchLink($created, $this->id);
        parent::afterSave($created, $options);
    }

    public function oneTouchLink($created, $id)
    {

        if (!$created || !isset($id))
            return;

        $data = $this->read(null, $id);

        $urlActivate = Router::url([
            'controller' => 'Accounts',
            'action' => 'activate',
            $data['Account']['token']
        ], true);


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
                    'name' => isset($data['Operator']['first_name']) ? $data['Operator']['first_name'] : '',
                    'url' => $urlActivate
                ]
            ]
        ]);
    }

}
