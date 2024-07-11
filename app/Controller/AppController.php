<?php

App::uses('Controller', 'Controller');
App::uses('Security', 'Utility');
App::uses('CakeText', 'Utility');
App::uses('AuthComponent', 'Controller/Component');
App::uses('AuthComponent', 'RequestHandler/Component');

class AppController extends Controller {

    public $components = [
        'Implementable.Filterable',
        'Export',
        'FirebaseMessaging',
        'SMSGateway',
        'Auth' => [
            /* 'authorize' => [
              'Controller',
              'Actions' => ['actionPath' => 'controllers']
              ], */
            'authenticate' => [
                'all' => ['userModel' => 'Account'],
                'Basic' => [
                    'scope' => ['Account.status' => 1],
                ],
                'Form' => [
                    'scope' => ['Account.status' => 1],
                ]
            ],
            'loginRedirect' => [
                'controller' => 'Business',
                'action' => 'dashboard',
                //'prefix' => false
            ],
            'logoutRedirect' => [
                'controller' => 'Accounts',
                'action' => 'login',
                //'prefix' => false
            ],
            'unauthorizedRedirect' => FALSE,
            'loginAction' => [
                'controller' => 'Accounts',
                'action' => 'login',
            ],
            'authError' => 'Did you really think you are allowed to see that?'
        ]
    ];

    public function beforeFilter() {
        //$this->Auth->allow(['add', 'index', 'view', 'edit']);
        if ($this->request->ext == 'json') {
            $this->Auth->loginRedirect = false;
            $this->Auth->autoRedirect = false;
            AuthComponent::$sessionKey = false;
            $this->Auth->unauthorizedRedirect = false;

            
            
            
        

        }


        if (isset($_SERVER['REDIRECT_REDIRECT_REDIRECT_REDIRECT_HTTP_AUTHORIZATION']) && !empty($_SERVER['REDIRECT_REDIRECT_REDIRECT_REDIRECT_HTTP_AUTHORIZATION'])) {
            list($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']) = explode(':', base64_decode(substr($_SERVER['REDIRECT_REDIRECT_REDIRECT_REDIRECT_HTTP_AUTHORIZATION'], 6)));
        }




        parent::beforeFilter();
     
    }

    public function isAuthorized($account = null) {

        if (!empty($account)) {
            return true;
        } else {
            $this->Session->setFlash('Ooops parece que no tienes permisos para realizar esta accion ', 'Flash/danger_swal');
            $this->redirect('error');
            return false;
        }
    }

    //USER VERIFY PERMISSIONS
    protected function _checkPermissions() {
        return ((in_array($this->params['action'], $this->Auth->allowedActions)) || ($this->Auth->user() && $this->Acl->check(array('User' => $this->Auth->user()), $this->params['controller'] . '/' . $this->params['action'])) );
    }

}
