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

    public function beforeFilter()
    {
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
    }


}
