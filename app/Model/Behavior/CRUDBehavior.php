<?php

App::uses('ModelBehavior', 'Model');

class CRUDBehavior extends ModelBehavior {

    protected $request = null;
    protected $model = null;

    public function setup(\Model $model, $config = array()) {
        parent::setup($model, $config);
        $this->model = $model;
        $this->settings[$model->alias] = [
            'success_message' => 'La operación se realizó correctamente.',
            'not_success_message' => 'La operación no pudo ser realizada.'
        ];

        $this->settings[$model->alias] = array_merge(
                $this->settings[$model->alias], $config
        );
        
        $this->settings[$model->alias]['result'] = [
            'success' => FALSE,
            
        ];
    }

    public function create($request) {
        $this->request = $request;
        if ($this->request->is('post') || $this->request->is('put')) {

            if ($this->model->save($this->request->data)) {
                $this->getController()->AXResponse->send(TRUE, "La operación se realizó correctamente", []);

                $this->getController()->redirect(urldecode(Router::url($this->getController()->Redirect, true)));
            } else {
                $this->getController()->Session->setFlash('La operación no pudo ser realizada', 'Flash/default', ['success' => false]);
            }
        }
    }

}
