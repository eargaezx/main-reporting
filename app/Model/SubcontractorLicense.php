<?php
App::uses('ImplementableModel', 'Implementable.Model');

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package       app.Model
 */
class SubcontractorLicense extends ImplementableModel
{
    public $singularDisplayName = 'Licensing';
    public $pluralDisplayName = 'Licensing';

    public $virtualFields = [
        'name' => 'SELECT CONCAT(Subcontractor.name, " - ", License.name) 
                   FROM subcontractors AS Subcontractor
                   JOIN licenses AS License ON License.id = SubcontractorLicense.license_id
                   WHERE Subcontractor.id = SubcontractorLicense.subcontractor_id',
        'remaining_days' => 'DATEDIFF(SubcontractorLicense.end_date, NOW())'
    ];

    /**
     * Fields settings
     * This section describes the settings for defining general settings fields.
     */
    public $fields = [
        [
            'fieldKey' => 'id',
            'label' => FALSE,
            'type' => 'hidden',
            'showIn' => ['edit', 'setup']
        ],
        [
            'fieldKey' => 'voucher',
            'label' => 'Voucher',
            'type' => 'file',
            'class' => 'form-control',
            'showIn' => ['add', 'edit', 'view'],
            'data-plugins' => 'dropify',
            'data-max-file-size' => '3M',
            'searchable' => false,
            'div' => [
                'class' => 'mb-3 col-lg-12'
            ]
        ],
        [
            'fieldKey' => 'subcontractor_id',
            'label' => 'Subcontractor',
            'type' => InputType::SELECT,
            'showIn' => ['index', 'view'],
            // 'bindValue' => 'Subcontractor.name',
            'options' => [
                '' => 'SELECT A OPTION'
            ],
            'filter' => TRUE
        ],
        [
            'fieldKey' => 'license_id',
            'label' => 'License',
            'type' => InputType::SELECT,
            'div' => InputDiv::COL_SM_12,
            'showIn' => TRUE,
            // 'bindValue' => 'Subcontractor.name',
            'options' => [
                '' => 'SELECT A OPTION'
            ],
            'filter' => TRUE
        ],
        [
            'fieldKey' => 'max_users',
            'label' => 'Allowed users',
            'type' => 'number',
            'div' => InputDiv::COL_SM_12,
            'class' => 'form-control',
            'showIn' => ['index', 'view'],
            'default' => 10
        ],
        [
            'fieldKey' => 'current_users',
            'label' => 'Current users',
            'type' => 'number',
            'class' => 'form-control',
            'showIn' => ['index', 'view'],
            'default' => 0
        ],
        [
            'fieldKey' => 'start_date',
            'label' => 'Activation',
            'type' => 'text',
            'class' => 'form-control',
            'data-plugin' => 'datepicker',
            'showIn' => ['index', 'view'],
        ],
        [
            'fieldKey' => 'end_date',
            'label' => 'Expiration',
            'type' => 'text',
            'class' => 'form-control',
            'data-plugin' => 'datepicker',
            'showIn' => ['index', 'view'],
        ],

        [
            'fieldKey' => 'created',
            'label' => 'Created',
            'type' => 'text',
            'showIn' => ['view'],
            'searchable' => false
        ],
        [
            'fieldKey' => 'status',
            'label' => 'Status',
            'type' => 'select',
            'div' => InputDiv::COL_SM_12,
            'data-toggle' => 'select2',
            'class' => 'form-control select2',
            'showIn' => ['index', 'view'],
            'options' => [
                '' => 'Empty',
                true => 'Active',
                false => 'Inactive'
            ]
        ]
    ];



    /**
     * Validation settings
     * This section describes the settings for defining validation rules fields.
     */
    public $validate = [
        'id' => [
            'unique' => [
                'rule' => 'isUnique',
                'message' => 'This record has already been taken'
            ]
        ],
        'voucher' => [
            'required' => [
                'rule' => 'notBlank',
                'required' => 'create', // Required only on add
                'message' => 'Please complete this field',
                'on' => 'create' // Specify 'on' condition to apply this rule only when adding
            ]
        ],
        'max_active_accounts' => [
            'required' => [
                'rule' => 'numeric',
                'message' => 'Please complete this field'
            ]
        ],
        'max_current_accounts' => [
            'required' => [
                'rule' => 'numeric',
                'message' => 'Please complete this field'
            ]
        ],
        'expiration_date' => [
            'rule' => ['date', 'ymd'], // Validate date format as YYYY-MM-DD
            'message' => 'Please enter a valid date in YYYY-MM-DD format.'
        ],
        'status' => [
            'required' => [
                'rule' => 'notBlank',
                'message' => 'Please complete this field'
            ],
            'valid' => [
                'rule' => 'numeric',
                'message' => 'Please enter a valid value'
            ]
        ]
    ];

    /**
     * Relations settings
     * This section describes the settings for defining the relationshibs between models.
     */

    public $belongsTo = [
        'Subcontractor' => [
            'className' => 'Subcontractor',
            'foreignKey' => 'subcontractor_id',
            'dependent' => false
        ],
        'License' => [
            'className' => 'License',
            'foreignKey' => 'license_id',
            'dependent' => false
        ]
    ];

    /**
     * Logical Business
     * This section the differents behaviors model.
     */
    public function beforeValidate($options = [])
    {

        if (!$this->isNewRecord()) {
            $licensing = $this->findById($this->data[$this->alias]['id']);
            if ($licensing['SubcontractorLicense']['license_id'] != $this->data[$this->alias]['license_id']) {
                unset($this->data[$this->alias]['id']);
            }
        }

        // Check if this is a new record
        if ($this->isNewRecord()) {
            // Set the status of the current record to active
            $this->data[$this->alias]['status'] = 1;

            // Calculate the start date (current date)
            $startDate = date('Y-m-d');

            // Retrieve the periodicity from the associated License
            $license = $this->License->findById($this->data[$this->alias]['license_id']);
            $periodicityDays = $license['License']['periodicity'];
            $maxUsers = $license['License']['users'];

            //Calculate the current users for the subcontractor
            $currentUsers = 0;

            //If current users is distinct than maxUsers, disable all operator users for the current Subcontractor

            // Calculate the end date based on the start date and periodicity
            $endDate = date('Y-m-d', strtotime($startDate . " + $periodicityDays days"));

            //set max users
            $this->data[$this->alias]['max_users'] = $maxUsers;
            $this->data[$this->alias]['current_users'] = $currentUsers;

            // Set start_date and end_date
            $this->data[$this->alias]['start_date'] = $startDate;
            $this->data[$this->alias]['end_date'] = $endDate;
        }
        return true;
    }

    public function beforeSave($options = [])
    {
        // Check if this is a new record
        if ($this->isNewRecord()) {
            // Deactivate other licenses for the same subcontractor
            $this->updateAll(
                ['SubcontractorLicense.status' => 0],
                ['SubcontractorLicense.subcontractor_id' => $this->data[$this->alias]['subcontractor_id']]
            );
        }
        return true;
    }

}
