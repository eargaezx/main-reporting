<?php

App::uses('ImplementableController', 'Implementable.Controller');

class ContractorsController extends ImplementableController
{

    public $controllerActions = [
        'add' => [
            'action' => 'setup',
        ]
    ];


    public $settings = [
        'setup' => [
            'controller' => 'Contractors',
            'saveMethod' => 'saveAll',
            'deep' => true
        ]
    ];


    public function setup($id = null)
    {
        $this->set('id', $id);
        $this->set('actionType', empty($id) ? 'add' : 'edit');


        $dirtyData = $this->request->data;
        //echo pr($dirtyData); die();
        if ($this->request->is(['POST', 'PUT'])) {
            $cleanData = [
                'Contractor' => [
                    'logo' => $this->request->data['Contractor']['logo'],
                    'name' => $this->request->data['Contractor']['name'],
                    'Partner' => [
                        'first_name' => $this->request->data['Partner']['first_name'],
                        'last_name' => $this->request->data['Partner']['last_name'],
                        'Account' => $this->request->data['Account']
                    ]
                ]
            ];

            $cleanData['Contractor']['Partner']['owner'] = 1;
            $cleanData['Contractor']['Partner']['Account']['account_type_id'] = 'c5a963d6-52b1-11ef-a5b8-74d83e73084c';

            if (!empty($id)) {//IS UPDATE
                $cleanData['Contractor']['Partner']['id'] = $dirtyData['Partner']['id'];
            } else {//IS CREATE
                $cleanData['Contractor']['status'] = true;
                $cleanData['Contractor']['Partner']['Account']['status'] = 0;
            }


            $this->request->data = $cleanData;
        } else if (!empty($id)) {
            $this->Contractor->recursive = 2;
            $contractor = $this->Contractor->findById($id);
            //echo pr($subcontractor);
            $dirtyData = [
                'Contractor' => $contractor['Contractor'],
                'Partner' => $contractor['Partner'],
                'Account' => $contractor['Partner']['Account']
            ];
            $dirtyData['Account']['password'] = '';
        }

        if (!empty($id))
            $this->request->data['Contractor']['id'] = $id;


        $this->settings['setup']['redirect']['action'] = 'index';
        $this->settings['add']['redirect']['action'] = 'index';
        parent::add();
        $this->request->data = $dirtyData;
    }


}
