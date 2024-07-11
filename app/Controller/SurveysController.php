<?php

App::uses('ImplementableController', 'Implementable.Controller');

class SurveysController extends ImplementableController
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

    public function survey($name)
    {
        $this->request->data = $this->Survey->find('first', [
            'conditions' => ['Survey.name' => $name]
        ]);
        
        $this->set('data', $this->request->data);
    }
}
