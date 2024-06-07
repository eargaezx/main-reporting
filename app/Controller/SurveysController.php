<?php

App::uses('ImplementableController', 'Implementable.Controller');

class SurveysController extends ImplementableController {

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
