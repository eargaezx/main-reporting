<?PHP
App::uses('ImplementableModel', 'Implementable.Model');
class Partnership extends ImplementableModel
{
    public $name = 'Partnership';
    public $singularDisplayName = 'B2B Partnership';
    public $pluralDisplayName = 'B2B Partnership';
    // Array de configuración de campos
    public $actions = [
        'delete' => [
            'confirm' => true,
            'action' => 'delete',
            'title' => 'Borrar',
            'class' => 'btn btn-sm btn-warning waves-effect waves-light',
            'data-message' => '¿Desea borrar el registro?',
            'data' => [],
            'icon' => [
                'class' => 'fe-x'
            ]
        ]
    ];

    public $virtualFields = [
        'pending' => '
                    SELECT COUNT(*)
                    FROM orders 
                    WHERE 
                        orders.subcontractor_id = Partnership.subcontractor_id 
                        AND orders.status = 1
                    ',
    ];


    public $fields = [
        [
            'fieldKey' => 'id',
            'label' => false,
            'type' => 'hidden',
            'showIn' => ['edit', 'setup']
        ],
        'contractor_id' => [
            'fieldKey' => 'contractor_id',
            'label' => 'Contractor',
            'type' => InputType::SELECT,
            'div' => InputDiv::COL_SM_12,
            'options' => [
                '' => 'SELECT A OPTION'
            ],
            'showIn' => true
        ],
        [
            'fieldKey' => 'subcontractor_id',
            'label' => 'Subcontractor',
            'type' => InputType::SELECT,
            'div' => InputDiv::COL_SM_12,
            'options' => [
                '' => 'SELECT A OPTION'
            ],
            'showIn' => true
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
        [
            'fieldKey' => 'drop_bury_role',
            'label' => 'Drop Bury',
            'type' => InputType::SELECT,
            'div' => InputDiv::COL_SM_12,
            'showIn' => ['index'],
            'filter' => TRUE,
            'options' => [
                '' => 'SELECT A OPTION',
                FALSE => 'NO',
                TRUE => 'YES'
            ],
        ],
        [
            'fieldKey' => 'directional_bore_role',
            'label' => 'Directional Bore',
            'type' => InputType::SELECT,
            'div' => InputDiv::COL_SM_12,
            'showIn' => ['index'],
            'filter' => TRUE,
            'options' => [
                '' => 'SELECT A OPTION',
                FALSE => 'NO',
                TRUE => 'YES'
            ],
        ],
        [
            'fieldKey' => 'created',
            'label' => 'Created',
            'type' => 'datetime',
            'showIn' => ['view']
        ],
        [
            'fieldKey' => 'modified',
            'label' => 'Modified',
            'type' => 'datetime',
            'showIn' => ['view']
        ],
        'status' => [
            'fieldKey' => 'status',
            'label' => 'Status',
            'type' => InputType::SELECT,
            'div' => InputDiv::COL_SM_12,
            'options' => [
                0 => 'INACTIVE',
                1 => 'ACTIVE',
                2 => 'PENDING'
            ],
            'showIn' => ['index', 'view']
        ],
        [
            'fieldKey' => 'comments',
            'label' => 'Comments',
            'type' => 'textarea',
            'div' => 'form-group col-sm-12',
            'rows' => 4,
            'showIn' => ['view']
        ],

    ];

    // Relación con Contractor y Subcontractor
    public $belongsTo = [
        'Contractor' => [
            'className' => 'Contractor',
            'foreignKey' => 'contractor_id',
        ],
        'Subcontractor' => [
            'className' => 'Subcontractor',
            'foreignKey' => 'subcontractor_id',
        ]
    ];

    // Validaciones
    public $validate = [
        'contractor_id' => [
            'rule' => 'notBlank',
            'message' => 'Contractor ID is required'
        ],
        'subcontractor_id' => [
            'rule' => 'notBlank',
            'message' => 'Subcontractor ID is required'
        ],
        'status' => [
            'rule' => ['inList', [0, 1, true, false]],
            'message' => 'Status must be 0 or 1',
            'allowEmpty' => false
        ],
        'uniqueCombination' => [
            'rule' => 'checkUniqueCombination',
            'message' => 'This contractor and subcontractor combination parthner exists.'
        ]
    ];

    // Método para verificar la combinación única
    public function checkUniqueCombination($check)
    {
        $contractorId = $this->data[$this->alias]['contractor_id'];
        $subcontractorId = $this->data[$this->alias]['subcontractor_id'];

        $existing = $this->find('count', [
            'conditions' => [
                'Partnership.contractor_id' => $contractorId,
                'Partnership.subcontractor_id' => $subcontractorId,
                'Partnership.id !=' => $this->id // Ignorar el registro actual al editar
            ],
            'recursive' => -1
        ]);

        return $existing == 0;
    }

    public function beforeSave($options = array())
    {

        if (AuthComponent::user() && AuthComponent::user('AccountType.name') == 'Contractor') {
            $this->data['Partnership']['contractor_id'] = AuthComponent::user('Partner.contractor_id');
            $this->data['Partnership']['token'] = CakeText::uuid();
            $this->data['Partnership']['status'] = 2;
            //echo pr($this->data);

            $existing = $this->find('count', array(
                'conditions' => array(
                    'Partnership.contractor_id' => $this->data['Partnership']['contractor_id'],
                    'Partnership.subcontractor_id' => $this->data['Partnership']['subcontractor_id'],
                ),
            ));

            if ($existing > 0) {
                //$this->invalidate('contractor_id', 'La combinación de email y nombre de usuario ya está en uso.');
                $this->invalidate('subcontractor_id', 'The partnership already exists');
                return false;
            }
        }
        return parent::beforeSave($options);
    }


    public function beforeImplement()
    {
        if (AuthComponent::user() && AuthComponent::user('AccountType.name') == 'Contractor') {
            $this->data['Partnership']['contractor_id'] = AuthComponent::user('Partner.id');
            $this->conditions['Partnership.contractor_id'] = AuthComponent::user('Partner.contractor_id');
            $this->fields['contractor_id']['showIn'] = FALSE;
            $this->fields['contractor_id']['default'] = AuthComponent::user('Partner.id');


            if ($this->controllerName == 'Partnerships' && in_array($this->actionName, ['add', 'edit'])) {
                $this->Subcontractor->virtualFields = [
                    'name' =>
                        'CONCAT(
                        Subcontractor.name, 
                        " | ", 
                        (
                            SELECT CONCAT(first_name, " ", last_name) FROM operators WHERE operators.subcontractor_id = Subcontractor.id AND operators.owner = 1 LIMIT 1
                        ), 
                        " => ",
                        (
                            SELECT UPPER(username) FROM operators LEFT JOIN accounts ON operators.account_id = accounts.id  WHERE operators.subcontractor_id = Subcontractor.id AND operators.owner = 1 LIMIT 1
                        )
                    )',
                ];
            }
        }
    }

    public function afterImplement()
    {
        unset($this->actions['edit']);
    }

    public function afterSave($created, $options = array())
    {

        $this->oneTouchLink($created, $this->id);
        parent::afterSave($created, $options);
    }

    public function oneTouchLink($created, $id)
    {

        $data = $this->data;
        if (!$created || !isset($id))
            return;


        $this->Subcontractor->virtualFields = [];

        $this->Subcontractor->recursive = 2;
        $subcontractor = $this->Subcontractor->read(null, $data['Partnership']['subcontractor_id']);
        $contractor = $this->Contractor->read(null, $data['Partnership']['contractor_id']);

        $urlActivate = Router::url([
            'controller' => 'Partnerships',
            'action' => 'accept',
            $data['Partnership']['token']
        ], true);
        //echo pr($contractor);
        $email = $subcontractor['Admin']['Account']['username'];
        $contractorName = $contractor['Contractor']['name'];
        $subcontractorName = $subcontractor['Admin']['name'];


        $this->sendEmail([
            'to' => [
                $email
            ],
            'subject' => 'Invitation Request',
            'content' => '',
            'emailFormat' => 'html',
            'template' => 'partnership',
            'viewVars' => [
                'data' => [
                    'contractorName' => $contractorName,
                    'subcontractorName' => $subcontractorName,
                    'url' => $urlActivate
                ]
            ]
        ]);
    }

}
?>