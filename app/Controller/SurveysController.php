<?php

App::uses('ImplementableController', 'Implementable.Controller');

class SurveysController extends ImplementableController
{

    public $settings = [
        'index' => [
            'order' => ['sequence' => 'asc'],
        ],
        'add' => [
            'saveMethod' => 'saveAll',
            'deep' => true
        ],
        'edit' => [
            'saveMethod' => 'saveAll',
            'deep' => true
        ],
    ];

    public function survey($name, $orderId = null)
    {
        $this->loadModel('QuestionAnswer');

        $this->request->data = $this->Survey->find('first', [
            'conditions' => ['Survey.name' => $name],
            'contain' => [
                'Question'
            ]
        ]);

        if (!empty($orderId)) {
            $this->QuestionAnswer->recursive = -1;
            $this->request->data['QuestionAnswer'] = $this->QuestionAnswer->find('all', [
                'conditions' => ['QuestionAnswer.order_id' => $orderId]
            ]);

            $flatArray = array_map(function($item) {
                return $item['QuestionAnswer'];
            },  $this->request->data['QuestionAnswer']);

            $this->request->data['QuestionAnswer']  = $flatArray;
        }

       // echo pr( $this->request->data);

        $this->set('data', $this->request->data);
    }
}
