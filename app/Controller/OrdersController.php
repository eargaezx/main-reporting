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

    public function beforeFilter()
    {
        parent::beforeFilter();
    }

    public function add($survey = 'EMPTY')
    {
        $this->Order->Survey->recursive = 2;
        $survey = $this->Order->Survey->find('first', [
            'conditions' => ['Survey.name' => 'EMPTY'],
            'order' => [
                'Question.sequence' => 'asc'
            ]
        ]);
        $this->set('survey', $survey);
        $this->set('_uuid', CakeText::uuid());
        parent::add();
    }

    public function edit($id = null, $survey_id = '518748c6-9b85-446d-9d6a-d1c853caf2fc', $subcontractor_id = '' , $operator_id = '', $name = '', $status = '')
    {
        $this->Order->Survey->recursive = 2;
        $survey = $this->Order->Survey->find('first', [
            'conditions' => ['Survey.id' => $survey_id]
        ]);
        $this->set('survey', $survey);

        parent::edit($id);

        if(!empty($subcontractor_id)){
            $this->request->data['Order']['$subcontractor_id'] = $subcontractor_id;
        }
        if(!empty($operator_id)){
            $this->request->data['Order']['operator_id'] = $operator_id;
        }
        if(!empty($name)){
            $this->request->data['Order']['name'] = $name;
        }
        if(!empty($survey_id)){
            $this->request->data['Order']['survey_id'] = $survey_id;
        }
        if(!empty($status)){
            $this->request->data['Order']['status'] = $status;
        }
    }

}
