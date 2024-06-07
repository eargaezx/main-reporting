<?php

App::uses('ImplementableController', 'Implementable.Controller');

class QuestionRulesController extends ImplementableController {

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

}
