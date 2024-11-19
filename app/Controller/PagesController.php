<?php
App::uses('ImplementableController', 'Implementable.Controller');

class PagesController extends AppController
{

    public $uses = ['Order', 'Business'];

    public function display()
    {
        //$this->layout = 'start';

        $this->loadModel('Order');
        $this->loadModel('License');
        $this->loadModel('Operator');
        $this->loadModel('Contractor');
        $this->loadModel('Subcontractor');
        $this->loadModel('SubcontractorLicense');

        $licenses = $this->License->find('all', [
            'conditions' => ['License.status' => 'active', 'License.commercial' => true],
            'order' => ['License.sort' => 'ASC']
        ]);
        $this->set('licenses', $licenses);

        if ( in_array(AuthComponent::user('AccountType.name'), ['Subcontractor', 'Supervisor'])) {
            $licensing = $this->SubcontractorLicense->find(
                'first',
                [
                    'conditions' => [
                        'SubcontractorLicense.subcontractor_id' => AuthComponent::user('Operator.subcontractor_id'),
                        'SubcontractorLicense.status' => true,
                    ]
                ]
            );
            $this->set('licensing', $licensing);

            $operators = $this->Operator->find(
                'all',
                [
                    'conditions' => [
                        'Operator.subcontractor_id' => AuthComponent::user('Operator.subcontractor_id'),
                    ],
                    'order' => ['Operator.name' => 'ASC']
                ]
            );
            $this->set('operators', $operators);

            $this->Order->virtualFields = [
                'unasigned' => 'SUM(CASE WHEN Order.operator_id IS NULL THEN 1 ELSE 0 END)',
                'recently' => '
                 SUM(CASE 
                    WHEN DAYOFWEEK(NOW()) IN (1, 7) AND Order.status = 0 AND Order.created >= DATE_SUB(NOW(), INTERVAL 3 DAY) THEN 1 
                    WHEN DAYOFWEEK(NOW()) BETWEEN 2 AND 6 AND Order.status = 0 AND Order.created >= DATE_SUB(NOW(), INTERVAL 1 DAY) THEN 1 
                    ELSE 0 
                 END)',
                'pending' => 'SUM(CASE WHEN Order.status = 1 THEN 1 ELSE 0 END)',
                'completed' => 'SUM(CASE WHEN Order.status = 2 THEN 1 ELSE 0 END)'
            ];

            $metrics = $this->Order->find('first', [
                'fields' => [
                    'Order.unasigned',
                    'Order.recently',
                    'Order.pending',
                    'Order.completed'
                ]
            ]);

            $this->set('metrics', $metrics);

        }


        if (AuthComponent::user() && AuthComponent::user('AccountType.name') == 'Contractor') {
            $contractor = $this->Contractor->find(
                'first',
                [
                    'conditions' => [
                        'Contractor.id' => AuthComponent::user('Partner.contractor_id')
                    ]
                ]
            );
            $this->set('contractor', $contractor);
        }

        $path = func_get_args();
        $count = count($path);
        if (!$count) {
            return $this->redirect('/');
        }
        $page = $subpage = $title_for_layout = null;

        if (!empty($path[0])) {
            $page = $path[0];
        }
        if (!empty($path[1])) {
            $subpage = $path[1];
        }
        if (!empty($path[$count - 1])) {
            $title_for_layout = Inflector::humanize($path[$count - 1]);
        }
        $this->set(compact('page', 'subpage', 'title_for_layout'));

        try {
            $this->render(implode('/', $path));
        } catch (MissingViewException $e) {
            if (Configure::read('debug')) {
                throw $e;
            }
            throw new NotFoundException();
        }
    }

}
