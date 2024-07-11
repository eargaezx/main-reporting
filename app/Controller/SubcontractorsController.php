<?php

App::uses('ImplementableController', 'Implementable.Controller');

class SubcontractorsController extends ImplementableController
{

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

    public function beforeFilter()
    {
        parent::beforeFilter();

    }

    public function setup()
    {

    }


}
