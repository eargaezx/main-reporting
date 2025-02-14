<?php

App::uses('ImplementableController', 'Implementable.Controller');

class QuestionsController extends ImplementableController
{

    public $settings = [
        'index' => [
            'limit' => 100,
            'order' => [
                'Question.sequence' => 'desc',
            ]
        ],
        'add' => [
            'saveMethod' => 'saveAll',
            'deep' => true,
        ],
        'edit' => [
            'saveMethod' => 'saveAll',
            'deep' => true
        ],
    ];

    public $controllerActions = [
        'add' => [
            'type' => 'post',
            'action' => 'add',
            'title' => 'Crear',
            'class' => 'btn btn-sm btn-dark waves-effect',
            'icon' => [
                'class' => 'fe-plus font-size-18'
            ]
        ]
    ];

    public function beforeFilter()
    {
        parent::beforeFilter();
        $surveyId = !empty($this->request->data['named']['filter']['survey_id'])
            ? $this->request->data['named']['filter']['survey_id']
            : (!empty($this->request->data['Question']['survey_id'])
                ? $this->request->data['Question']['survey_id']
                : null);

        if ($surveyId) {
            //$this->Question->fields['survey_id']['showIn'] = FALSE; 
            $this->Question->fields['survey_id']['default'] = $surveyId;

            $redirect = [
                'controller' => 'Surveys',
                'action' => 'view',
                $surveyId
            ];

            $this->settings['add']['redirect'] = $redirect;
            $this->settings['edit']['redirect'] = $redirect;
            $this->controllerActions['add']['data'] = $this->request->data;
            $this->Question->actions['delete']['data'] = $this->request->data;
        }

    }

}
