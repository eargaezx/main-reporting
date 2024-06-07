<?php

App::uses('ImplementableController', 'Implementable.Controller');

class OperatorsController extends ImplementableController {

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

    public function beforeFilter() {
        parent::beforeFilter();
    }

    public function index($business_id = null) {
        $this->controllerActions['add']['params'][] = $business_id;
        //$this->Employee->conditions['Employee.business_id'] = $business_id;
       //$this->Employee->conditions['Account.account_type_id'] = 5;
        parent::index();
    }

    public function add() {
        if ($this->request->is('post') || $this->request->is('put')) {
            $this->request->data['Account']['account_type_id'] = 5;
            $this->request->data['Employee']['business_id'] = $this->Auth->user('Employee.business_id');
        }

        parent::add();
    }

}
