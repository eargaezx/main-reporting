<?php

App::uses('ImplementableController', 'Implementable.Controller');

class OperatorsController extends ImplementableController
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

    public function index()
    {

        if (AuthComponent::user() && AuthComponent::user('AccountType.name') == 'Subcontractor') {
            $this->loadModel('Order');
            $this->loadModel('License');
            $this->loadModel('Contractor');
            $this->loadModel('Subcontractor');
            $this->loadModel('SubcontractorLicense');


            $licensing = $this->SubcontractorLicense->find(
                'first',
                [
                    'conditions' => [
                        'SubcontractorLicense.subcontractor_id' => AuthComponent::user('Operator.subcontractor_id'),
                        'SubcontractorLicense.status' => true,
                    ]
                ]
            );
            $this->set('licensing', $licensing);
        }

        parent::index();
    }
    public function list()
    {
        $this->Operator->virtualFields = ['name' => 'CONCAT( Operator.first_name ," ", Operator.last_name )'];
        parent::list();
    }

    public function summary()
    {

        $this->Operator->virtualFields = [];

        $data = [];

        if (AuthComponent::user() && (AuthComponent::user('AccountType.name') == 'Subcontractor' || AuthComponent::user('AccountType.name') == 'Technician')) {
            $subcontractor = $this->Operator->Subcontractor->find('first', [
                'recursive' => -1,
                'conditions' => [
                    'Subcontractor.id' => AuthComponent::user('Operator.subcontractor_id')
                ]
            ]);

            $data['Subcontractor'] = $subcontractor['Subcontractor'];
        }


        if (AuthComponent::user() && AuthComponent::user('AccountType.name') == 'Technician') {

            $data['pending'] = $this->Operator->Order->find('count', [
                'conditions' => [
                    'Order.operator_id' => AuthComponent::user('Operator.id'),
                    'Order.status' => 1
                ]
            ]);

            $data['completed'] = $this->Operator->Order->find('count', [
                'conditions' => [
                    'Order.operator_id' => AuthComponent::user('Operator.id'),
                    'Order.status' => 2
                ]
            ]);


            $data['assigned'] = $this->Operator->Order->find('count', [
                'conditions' => [
                    'Order.operator_id' => AuthComponent::user('Operator.id')
                ]
            ]);


        }


        if (AuthComponent::user() && AuthComponent::user('AccountType.name') == 'Subcontractor') {

            $data['pending'] = $this->Operator->Order->find('count', [
                'conditions' => [
                    'Order.subcontractor_id' => AuthComponent::user('Operator.subcontractor_id'),
                    'Order.status' => 1
                ]
            ]);

            $data['completed'] = $this->Operator->Order->find('count', [
                'conditions' => [
                    'Order.subcontractor_id' => AuthComponent::user('Operator.subcontractor_id'),
                    'Order.status' => 2
                ]
            ]);

            $data['assigned'] = $this->Operator->Order->find('count', [
                'conditions' => [
                    'Order.subcontractor_id' => AuthComponent::user('Operator.subcontractor_id')
                ]
            ]);
        }


        $this->request->data = $data;
        $this->set('data', $data);
    }

    public function beforeFilter()
    {
        $this->Operator->virtualFields = [
            'name' => 'CONCAT( Operator.first_name ," ", Operator.last_name )'
        ];

        parent::beforeFilter();

        //set redirect and condition to survey details page
        if (!empty($this->request->data['named']['filter']['subcontractor_id'])) {
            $this->Operator->fields['subcontractor_id']['showIn'] = FALSE;
            $this->Operator->fields['subcontractor_id']['default'] = $this->request->data['named']['filter']['subcontractor_id'];
            $this->settings['edit']['redirect'] = [
                'controller' => 'Subcontractors',
                'action' => 'view',
                $this->request->data['named']['filter']['subcontractor_id']
            ];
            $this->controllerActions['add']['data'] = $this->request->data;
        }

        if (!empty($this->request->data['Operator']['subcontractor_id'])) {
            $this->settings['edit']['redirect'] = [
                'controller' => 'Subcontractors',
                'action' => 'view',
                $this->request->data['Operator']['subcontractor_id']
            ];
        }

        if (!empty($this->request->data['Account'])) {
            //$this->request->data['Account']['account_type_id'] = '2c8be97d-04cb-4a97-965a-458f8f143ec4';
        }
    }

}
