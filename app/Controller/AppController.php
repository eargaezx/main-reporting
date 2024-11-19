<?php

App::uses('Controller', 'Controller');
App::uses('Security', 'Utility');
App::uses('CakeText', 'Utility');
App::uses('AuthComponent', 'Controller/Component');
App::uses('AuthComponent', 'RequestHandler/Component');

class AppController extends Controller
{


    // Define los dominios permitidos
    protected $allowedOrigins = [
        'http://localhost:5173',
        'http://192.168.0.102:5173',
        'https://app-dev.mainreport.us',
        'app-dev.mainreport.us',
        'https://app.mainreport.us'
    ];


    public $components = [
        'Session',
        'Acl',
        'Implementable.Filterable',
        'Export',
        'FirebaseMessaging',
        'SMSGateway',
        'Auth' => [
            'storage' => 'Memory', 
            'authorize' => [
                'Controller',
                'Actions' => ['actionPath' => 'controllers']
            ],
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
                'controller' => 'Pages',
                'action' => 'display',
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

    public function beforeFilter()
    {
        

        $origin = $this->request->header('Origin');



        // Verifica si el origen está en la lista de permitidos
        if (in_array($origin, $this->allowedOrigins)) {
            $this->response->header('Access-Control-Allow-Origin', $origin);
        }


        $this->response->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
        $this->response->header('Access-Control-Allow-Headers', 'Content-Type, Authorization, Access-Control-Allow-Origin, X-Requested-With, Accept'); // Asegúrate de incluir todos los encabezados necesarios

        if ($this->request->method() === 'OPTIONS') {
            $this->response->header('Access-Control-Allow-Credentials', 'true');
            $this->response->send();
            exit;
        }


        $this->Auth->allow(['isAuthorized']);
        if ($this->request->ext == 'json') {
            $this->loadModel('Account');
            $this->Account->Operator->virtualFields = [];
            $this->Account->Partner->virtualFields = [];
            
            $this->Auth->loginRedirect = false;
            $this->Auth->autoRedirect = false;
            AuthComponent::$sessionKey = false;
            $this->Auth->unauthorizedRedirect = false;
        }

        if (isset($_SERVER['REDIRECT_REDIRECT_REDIRECT_REDIRECT_HTTP_AUTHORIZATION']) && !empty($_SERVER['REDIRECT_REDIRECT_REDIRECT_REDIRECT_HTTP_AUTHORIZATION'])) {
            list($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']) = explode(':', base64_decode(substr($_SERVER['REDIRECT_REDIRECT_REDIRECT_REDIRECT_HTTP_AUTHORIZATION'], 6)));
        }


       

        if ($this->request->ext == 'json') {
            if(!$this->checkPermissions()){
                //return $this->redirect(Router::url(['action' => 'isAuthorized'], false));
            }
        }else{
            $this->isAuthorized($this->checkPermissions());
        }

        parent::beforeFilter();

    }

    public function isAuthorized($account = null)
    { 
   
        if (!empty($account)) {
           return true;
        } else {
            //$this->Session->setFlash('Ooops parece que no tienes permisos para realizar esta accion ', 'Flash/danger_swal');
            $this->render('../Errors/unauthorized');
        }
    }

    //USER VERIFY PERMISSIONS
    protected function checkPermissions()
    {
        //echo pr($this->Auth->user()); 
        $aro = array('model' => 'AccountType', 'foreign_key' => $this->Auth->user('account_type_id')); 
        return ((in_array($this->params['action'], $this->Auth->allowedActions)) || ($this->Auth->user() && $this->Acl->check($aro, $this->params['controller'] . '/' . $this->params['action'])));
    }
}