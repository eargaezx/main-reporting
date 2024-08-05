<?php

App::uses('ImplementableController', 'Implementable.Controller');

class OrdersController extends ImplementableController
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

    public function maps(){

    }

}
