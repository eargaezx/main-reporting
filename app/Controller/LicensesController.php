<?php

App::uses('ImplementableController', 'Implementable.Controller');

class LicensesController extends ImplementableController
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
        $this->Auth->allow(['buy']);
        parent::beforeFilter();
    }


    public function buy()
    {
        $licenses = $this->License->find('all', [
            'conditions' => ['License.status' => 'active', 'License.commercial' => true],
            'order' => ['License.sort' => 'ASC']
        ]);
        $this->set('licenses', $licenses);



        if (in_array(AuthComponent::user('AccountType.name'), ['Subcontractor', 'Supervisor'])) {
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
    }


}
