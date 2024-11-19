<?php

App::uses('ImplementableController', 'Implementable.Controller');

class SubcontractorLicensesController extends ImplementableController
{


    public function licensing($subcontractorId = null)
    {

        if (empty($subcontractorId)) {
            return $this->redirect($this->request->referer());
        }

        $licensing = $this->SubcontractorLicense->find(
            'first',
            [
                'conditions' => [
                    'SubcontractorLicense.subcontractor_id' => $subcontractorId
                ],
                'order' => ['SubcontractorLicense.created' => 'DESC']
            ]
        );

        if (empty($licensing)) {
            return $this->redirect($this->request->referer());
        }



        $this->set('licensing', $licensing);

        if ($this->request->is('post') || $this->request->is('put')) {
            $this->request->data['SubcontractorLicense']['subcontractor_id'] = $subcontractorId;
            //echo pr( $this->request->data); die();
            if ($this->SubcontractorLicense->save($this->request->data)) {
                $this->Session->setFlash('La operación se realizó correctamente.', 'Flash/success');
                $this->redirect(Router::url(['controller' => 'Subcontractors', 'action', 'index'], true));

            } else {
                echo pr($this->{$this->modelClass}->validationErrors);
                $errors = 'La operación se realizó correctamente.';
                if ($this->request->ext == 'json') {
                    $errors = $this->{$this->modelClass}->validationErrors;
                    $errors = $this->{$this->modelClass}->invalidFields();
                    $errors = Hash::extract($errors, '{s}.{n}') + Hash::extract($errors, '{s}.{s}.{n}') + Hash::extract($errors, '{s}.{s}.{s}.{n}');

                    $errors = '-' . implode('<br> -', array_unique($errors));
                }

                $this->Session->write('ValidationErrors', $this->{$this->modelClass}->validationErrors);
                $this->Session->write('FormData', $this->request->data);
                $this->Session->setFlash($errors, 'Flash/not_success');
            }

        }
    }
}
