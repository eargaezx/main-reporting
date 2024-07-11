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
class License extends ImplementableModel
{
    public $singularDisplayName = 'License';
    public $pluralDisplayName = 'Liceses';

    public $virtualFields = array(
        'remaining_days' => 'DATEDIFF(License.expiration_date, NOW())'
    );

    /**
     * Fields settings
     * This section describes the settings for defining general settings fields.
     */
    public $fields = [
        [
            'fieldKey' => 'voucher',
            'label' => 'Voucher',
            'type' => 'file',
            'class' => 'form-control',
            'showIn' => ['index', 'add', 'edit', 'view'],
            'data-plugins' => 'dropify',
            'data-max-file-size' => '3M',
            'searchable' => false,
            'div' => [
                'class' => 'mb-3 col-lg-12'
            ]
        ],
        [
            'fieldKey' => 'name',
            'label' => 'Serial',
            'type' => 'text',
            'class' => 'form-control',
            'autogenerate' => true,
            'showIn' => ['index', 'add', 'edit', 'view']
        ],
        [
            'fieldKey' => 'expiration_date',
            'label' => 'Expiration',
            'type' => 'text',
            'class' => 'form-control',
            'data-plugin' => 'datepicker',
            'showIn' => ['index', 'add', 'edit', 'view']
        ],
        [
            'fieldKey' => 'max_active_accounts',
            'label' => 'Max active',
            'type' => 'number',
            'class' => 'form-control',
            'showIn' => ['index', 'add', 'edit', 'view'],
            'default' => 10
        ],
        [
            'fieldKey' => 'current_active_accounts',
            'label' => 'Current active',
            'type' => 'number',
            'class' => 'form-control',
            'showIn' => ['index', 'add', 'edit', 'view'],
            'default' => 0
        ],
        [
            'fieldKey' => 'created',
            'label' => 'Created',
            'type' => 'text',
            'showIn' => ['index', 'view'],
            'searchable' => false
        ],
        [
            'fieldKey' => 'status',
            'label' => 'Status',
            'type' => 'select',
            'data-toggle' => 'select2',
            'class' => 'form-control select2',
            'showIn' => ['index', 'add', 'edit', 'view'],
            'options' => [
                '' => 'Empty',
                '1' => 'Active',
                '2' => 'Inactive'
            ]
        ]
    ];



    /**
     * Validation settings
     * This section describes the settings for defining validation rules fields.
     */
    public $validate = array(
        'id' => array(
            'unique' => array(
                'rule' => 'isUnique',
                'message' => 'This record has already been taken'
            )
        ),
        'voucher' => array(
            'required' => array(
                'rule' => 'notBlank',
                'required' => 'create', // Required only on add
                'message' => 'Please complete this field',
                'on' => 'create'// Specify 'on' condition to apply this rule only when adding
            )
        ),
        'name' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'Please complete this field'
            )
        ),
        'max_active_accounts' => array(
            'required' => array(
                'rule' => 'numeric',
                'message' => 'Please complete this field'
            )
        ),
        'max_current_accounts' => array(
            'required' => array(
                'rule' => 'numeric',
                'message' => 'Please complete this field'
            )
        ),
        'expiration_date' => array(
            'rule' => array('date', 'ymd'), // Validate date format as YYYY-MM-DD
            'message' => 'Please enter a valid date in YYYY-MM-DD format.'
        ),
        'status' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'Please complete this field'
            ),
            'valid' => array(
                'rule' => 'numeric',
                'message' => 'Please enter a valid value'
            )
        )
    );

    /**
     * Relations settings
     * This section describes the settings for defining the relationshibs between models.
     */

    public $hasOne = array(
        'Suncontractor' => array(
            'className' => 'Subcontractor',
            'foreignKey' => 'license_id',
            'dependent' => false
        )
    );

}
