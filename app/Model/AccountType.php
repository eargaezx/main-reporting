<?php

App::uses('ImplementableModel', 'Implementable.Model');

class AccountType extends ImplementableModel {

    public $fields = [
        ['fieldKey' => 'name',
            'label' => 'Nombre',
            'showIn' => TRUE
        ],
        ['fieldKey' => 'comments',
            'label' => 'Comentarios',
            'showIn' => ['add', 'edit', 'view']
        ]
    ];
    public $hasMany = [
        'Account' => [
            'className' => 'Account',
            'foreignKey' => 'account_type_id',
            'dependent' => false
        ]
    ];

}
