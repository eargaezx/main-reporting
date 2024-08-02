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

    public $displayField = '_name';  // Especifica el campo virtual como displayField

    public $virtualFields = [
        '_name' => 'CONCAT(
            License.name, 
            " | ", 
            License.users, 
            " USERS | ", 
            CASE
                WHEN License.periodicity = 3 THEN "3 DAYS" 
                WHEN License.periodicity = 7 THEN "WEEKLY"
                WHEN License.periodicity = 30 THEN "MONTHLY"
                WHEN License.periodicity = 365 THEN "YEARLY"
                ELSE CONCAT(License.periodicity, " DAYS")
            END
        )',
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
            'showIn' => ['edit'],
            'show'
        ],
        [
            'fieldKey' => 'name',
            'label' => 'Name',
            'type' => 'text',
            'class' => 'form-control',
            'autogenerate' => true,
            'showIn' => ['index', 'add', 'edit', 'view']
        ],
        [
            'fieldKey' => 'periodicity',
            'label' => 'Perioodicity',
            'type' => InputType::SELECT,
            'options' => [
                '' => 'SELECT A OPTION',
                3 => '3 DAYS',
                7 => 'WEEKLY',
                15 => 'BIWEEKLY',
                30 => 'MONTHLY',
                90 => 'QUARTERLY',
                365 => 'ANNUALLY'
            ],
            'showIn' => TRUE,
            'filter' => TRUE
        ],
        [
            'fieldKey' => 'price',
            'label' => 'Price',
            'type' => 'number',
            'class' => 'form-control',
            'showIn' => ['index', 'add', 'edit', 'view']
        ],
        [
            'fieldKey' => 'users',
            'label' => 'Allowed users',
            'type' => 'number',
            'class' => 'form-control',
            'showIn' => ['index', 'add', 'edit', 'view'],
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
            'label' => 'Modified',
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
            'fieldKey' => 'status',
            'label' => 'Status',
            'type' => 'select',
            'data-toggle' => 'select2',
            'class' => 'form-control select2',
            'div' => InputDiv::COL_SM_12,
            'showIn' => ['index', 'add', 'edit', 'view'],
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
    public $validate = array(
        'id' => array(
            'unique' => array(
                'rule' => 'isUnique',
                'message' => 'This record has already been taken'
            )
        ),
        'name' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'Please complete this field'
            )
        ),
        'users' => array(
            'required' => array(
                'rule' => 'numeric',
                'message' => 'Please complete this field'
            )
        ),
        'periodicity' => array(
            'required' => array(
                'rule' => 'numeric',
                'message' => 'Please complete this field'
            )
        ),
        'price' => array(
            'required' => array(
                'rule' => 'numeric',
                'message' => 'Please complete this field'
            )
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

    /*public $hasMany = array(
        'Suncontractor' => array(
            'className' => 'Subcontractor',
            'foreignKey' => 'license_id',
            'dependent' => false
        )
    );*/

}
