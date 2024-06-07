<?php

App::uses('ImplementableController', 'Implementable.Controller');

class QuestionsController extends ImplementableController
{

    public $settings = [
        'index' => [
            'order' => [
                'Question.sequence' => 'desc',
            ]
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

}
