<?php

App::uses('ImplementableController', 'Implementable.Controller');

class PartnershipsController extends ImplementableController
{

    public $controllerActions = [
        [],
        'add' =>
        [
            'icon' => [
                'class' => 'fe-send font-size-18'
            ],
        ]
    ];
    

    public $settings = [
        'setup' => [
            'controller' => 'Subcontractors',
            'saveMethod' => 'saveAll',
            'deep' => true
        ],
        'add' => [
            'controller' => 'Subcontractors',
            'saveMethod' => 'saveAll',
            'deep' => true
        ],

    ];


    public function beforeFilter()
    {
        $this->Auth->allow(['accept']);
        parent::beforeFilter();
    }


    public function setup($id = null)
    {
        $this->loadModel('Subcontractor');
        $this->Partnership = $this->Subcontractor;


        $this->set('id', $id);
        $this->set('actionType', empty($id) ? 'add' : 'edit');


        $dirtyData = $this->request->data;
        //echo pr($dirtyData); die();
        if ($this->request->is(['POST', 'PUT'])) {
            $cleanData = [
                'Subcontractor' => [
                    'logo' => $this->request->data['Subcontractor']['logo'],
                    'name' => $this->request->data['Subcontractor']['name'],
                    'Operator' => [
                        [
                            'first_name' => $this->request->data['Operator']['first_name'],
                            'last_name' => $this->request->data['Operator']['last_name'],
                            'Account' => $this->request->data['Account']
                        ]
                    ],
                    'SubcontractorLicense' => $this->request->data['SubcontractorLicense']
                ]
            ];

            $cleanData['Subcontractor']['Operator'][0]['owner'] = 1;
           // $cleanData['Subcontractor']['Operator'][0]['subcontractor_id'] = '';
            $cleanData['Subcontractor']['Operator'][0]['Account']['account_type_id'] = 'da59ff8b-26ab-41cd-ab78-704c41d9fe0a';

            if (!empty($id)){//IS UPDATE
                $cleanData['Subcontractor']['Operator'][0]['id'] = $dirtyData['Operator']['id'];
            }else{//IS CREATE
                $cleanData['Subcontractor']['status'] = true;
                $cleanData['Subcontractor']['Operator'][0]['Account']['status'] = 0;
            }
                

            $this->request->data = $cleanData;
        } else if (!empty($id)) {
            $this->Subcontractor->recursive = 2;
            $subcontractor = $this->Subcontractor->findById($id);
            //echo pr($subcontractor);
            $dirtyData = [
                'Subcontractor' => $subcontractor['Subcontractor'],
                'Operator' => $subcontractor['Admin'],
                'Account' => $subcontractor['Admin']['Account'],
                'SubcontractorLicense' => $subcontractor['SubcontractorLicense']
            ];
            $dirtyData['Account']['password'] = '';
        }
      

        if (!empty($id))
            $this->request->data['Subcontractor']['id'] = $id;

      
        $this->settings['setup']['redirect']['action'] = 'add';
        $this->settings['add']['redirect']['action'] = 'add';
        parent::add();
        $this->request->data = $dirtyData;
    }


    public function accept($token = null)
    {
        $this->Auth->logout();

        if (empty($token)) {
            $this->Session->setFlash('The invite was not accept successfully, maybe the token had expired, please request to the contractor a new invite, if the problem persist, please contact to support@mainreport.us', null, null, 'login');
            return $this->redirect(Router::url(['controller' => 'Accounts', 'action' => 'login'], true));
        }

        $partnership = $this->Partnership->findByToken($token);


        if (!isset($partnership['Partnership'])) {
            $this->Session->setFlash('The invite was not accept successfully, maybe the token had expired, please request to the contractor a new invite, if the problem persist, please contact to support@mainreport.us', null, null, 'login');
            return $this->redirect(Router::url(['controller' => 'Accounts','action' => 'login'], true));
        }

        //update
        $this->Partnership->id = $partnership['Partnership']['id'];

        if ( $this->Partnership->saveField('status', 1) && $this->Partnership->saveField('token', NULL) ) {

            $this->Session->setFlash('The invite was accept successfully, now you can collaborate with you Contractor', null, null, 'login');

            $this->redirect(Router::url(['controller' => 'Accounts', 'action' => 'login'], true));
        } else {
            $this->Session->setFlash('The invite was not accept successfully, maybe the token had expired, please request to the contractor a new invite, if the problem persist, please contact to support@mainreport.us', null, null, 'login');
        }
        $this->redirect(Router::url(['controller' => 'Accounts', 'action' => 'login'], true));
    }

}
