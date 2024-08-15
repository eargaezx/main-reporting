<?php

App::uses('ImplementableController', 'Implementable.Controller');

class OrdersController extends ImplementableController
{
    public $controllerActions = [
        [],
        [],
        [
            'action' => 'import',
            'title' => 'Upload',
            'class' => 'btn btn-sm btn-dark waves-effect',
            'icon' => [
                'class' => 'fe-upload font-size-18'
            ],
            'data' => []
        ],
    ];

    public $settings = [
        'add' => [
            'saveMethod' => 'saveAll',
            'deep' => true
        ],
        'edit' => [
            'saveMethod' => 'saveAll',
            'deep' => true
        ],
    ];

    public function maps()
    {

    }

}
