<?php

App::uses('ImplementableController', 'Implementable.Controller');

class SubcontractorsController extends ImplementableController
{

    public $settings = [
        'add' => [
            'controller' => 'Subcontractors',
            'saveMethod' => 'saveAll',
            'deep' => true
        ],
        'edit' => [
            'saveMethod' => 'saveAll',
            'deep' => true
        ],
        'setup' => [
            'controller' => 'Subcontractors',
            'saveMethod' => 'saveAll',
            'deep' => true
        ],
    ];


    public function setup()
    {

        $dirtyData = $this->request->data;
        if ($this->request->is(['POST', 'PUT'])) {

            $cleanData = [
                'Subcontractor' => [
                    'logo' => $this->request->data['Subcontractor']['logo'],
                    'name' => $this->request->data['Subcontractor']['name'],
                    'status' => $this->request->data['Subcontractor']['status'],
                    //'License' => $this->request->data['License'],
                    'Operator' => [
                        [
                            'first_name' => $this->request->data['Operator']['first_name'],
                            'last_name' => $this->request->data['Operator']['last_name'],
                            'Account' => $this->request->data['Account']
                        ]
                    ]
                ]
            ];
            $this->request->data = $cleanData;
        }

        $this->settings['setup']['redirect']['action'] = 'index';
        parent::add();
        $this->request->data = $dirtyData;
    }


}
