<?PHP
App::uses('ImplementableModel', 'Implementable.Model');
class Partnership extends ImplementableModel
{
    public $name = 'Partnership';
    public $singularDisplayName = 'Partnering';
    public $pluralDisplayName = 'Partnerings';
    // Array de configuración de campos
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
            'showIn' => true,
            'after' => '<div class="disclaimer">Not found you  subcontractor? <a href="setup" >Add here</a>.</div>',
        ],
        [
            'fieldKey' => 'created',
            'label' => 'Created',
            'type' => 'datetime',
            'showIn' => ['index', 'view']
        ],
        [
            'fieldKey' => 'modified',
            'label' => 'Modified',
            'type' => 'datetime',
            'showIn' => ['index', 'view']
        ],
        'status' => [
            'fieldKey' => 'status',
            'label' => 'Status',
            'type' => InputType::SELECT,
            'div' => InputDiv::COL_SM_12,
            'options' => [
                true => 'ACTIVE',
                false => 'INACTIVE'
            ],
            'showIn' => ['index', 'view']
        ],
        [
            'fieldKey' => 'comments',
            'label' => 'Comments',
            'type' => 'textarea',
            'div' => 'form-group col-sm-12',
            'rows' => 4,
            'showIn' => ['add', 'edit', 'view']
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
            $this->data['Partnership']['status'] = 1;
            echo pr($this->data);
        }
        return parent::beforeSave($options);
    }


    public function beforeImplement()
    {
        if (AuthComponent::user() && AuthComponent::user('AccountType.name') == 'Contractor') {
            $this->data['Partnership']['contractor_id'] = AuthComponent::user('Partner.id');
            $this->fields['contractor_id']['showIn'] = FALSE;
            $this->fields['contractor_id']['default'] = AuthComponent::user('Partner.id');


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
        //echo pr($data); die();
        $this->Subcontractor->recursive = 2;
        $subcontractor = $this->Subcontractor->read(null, $data['Partnership']['subcontractor_id']);
        $contractor = $this->Contractor->read(null, $data['Partnership']['contractor_id']);

        $urlActivate = Router::url([
            'controller' => 'Partnerships',
            'action' => 'activate',
            $id
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