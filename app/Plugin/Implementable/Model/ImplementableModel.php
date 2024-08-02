<?php

App::uses('UIFormHelper', 'Implementable.View/Helper');
App::uses('AppModel', 'Model');
App::uses('Folder', 'Utility');

abstract class ImplementableModel extends AppModel
{

    public $actsAs = array('Implementable.Implementable', 'Implementable.Mailing', 'Implementable.SMS');
    public $displaySingularName = '';
    public $displayPluralName = '';
    public $conditions = [];
    public $fields = [];
    public $actions = [
        'view' => [
            'action' => 'view',
            'title' => 'Ver',
            'class' => 'btn btn-sm btn-outline-dark waves-effect waves-light',
            'icon' => [
                'class' => 'fe-eye'
            ]
        ],
        'edit' => [
            'action' => 'edit',
            'title' => 'Editar',
            'class' => 'btn btn-sm btn-outline-dark waves-effect waves-light',
            'icon' => [
                'class' => 'fe-edit-2'
            ]
        ],
        'delete' => [
            'action' => 'delete',
            'title' => 'Borrar',
            'class' => 'btn btn-sm btn-outline-dark waves-effect waves-light swal-confirm',
            'data-message' => '¿Desea borrar el registro?',
            'data' => [],
            'icon' => [
                'class' => 'fe-trash-2'
            ]
        ]
    ];
    public $belongsTo = [];


    protected function isNewRecord() {
        return empty($this->data[$this->alias]['id']);
    }
}
