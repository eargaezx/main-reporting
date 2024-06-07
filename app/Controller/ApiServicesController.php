<?php

App::uses('ImplementableController', 'Implementable.Controller');

class ApiServicesController extends ImplementableController {

    public $uses = ['Account'];

    public function beforeFilter() {
        $this->Auth->allow(['places', 'terms', 'privacy']);
        parent::beforeFilter();
    }

    public function terms() {
        $this->layout = 'landing';
    }

    public function privacy() {
        $this->layout = 'landing';
    }

    public function places($like) {
        if (empty($like)) {
            throw new NotFoundException('Could not find that post');
        }

        $this->set('data', $this->getPlaces($like));
    }

    private function getPlaces($like) {
        $url = "https://maps.googleapis.com/maps/api/place/autocomplete/json?input=" . rawurlencode($like) . "&region=mx&key=AIzaSyA1sWtgRBcgQVXJwtl9bHxewFzviOlUjlk";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($ch);
        curl_close($ch);
        $response_a = json_decode($response, true);


        return $response_a;
    }

}
