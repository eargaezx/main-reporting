<?php

App::uses('ImplementableController', 'Implementable.Controller');

class AccountsController extends ImplementableController
{

    public $PACKAGE_ACCOUNT_TYPES = [
        "com.axcode.mercaditonaranja" => 6,
        "com.axcode.mercaditonaranjarepartidor" => 5,
        "com.axcode.mercaditonaranjanegocio" => 3,
    ];
    public $settings = [
        'signin' => [
            'saveMethod' => 'save',
            'deep' => false,
            'redirect' => [
                'action' => 'index'
            ],
        ],
    ];



    public function beforeFilter()
    {
        if (!in_array($this->request->params['action'], ['index', 'add', 'view', 'edit'])) {
            $this->layout = 'start';
        }

        $this->Auth->allow(['login', 'logout', 'signin', 'activate', 'recover', 'restore', 'unauthorized']);
        parent::beforeFilter();
    }

    public function signin()
    {
        parent::add();
    }

    public function login()
    {

       $this->Account->Partner->virtualFields = [];

        if ($this->request->is('post') ) {
            if ($this->Auth->login()) {
                $data = $this->Auth->user();

                $this->set('data', $data);
                $this->redirect($this->Auth->redirectUrl());
            } else {
                $this->Session->setFlash('Invalid username or password.', 'Flash/modal_unsuccess', null, 'login');
            }
        }
    }

    public function logout()
    {

        return $this->redirect($this->Auth->logout());
    }

    public function play()
    {

        if ($this->request->is('post')) {
            if (
                !isset($this->request->data['Account']['package_id']) || empty($this->request->data['Account']['package_id']) || !isset($this->request->data['Account']['phone_country']) || empty($this->request->data['Account']['phone_country']) || !isset($this->request->data['Account']['phone_number']) || empty($this->request->data['Account']['phone_number'])
                || !array_key_exists($this->data['Account']['package_id'], $this->PACKAGE_ACCOUNT_TYPES)
            ) {
                $this->Session->setFlash('No existe ninguna cuenta, registrada con este correo.', null, null, 'recover');
                return;
            } else {
                $this->request->data = [
                    'Account' => [
                        'username' => $this->data['Account']['package_id'] . '_' . $this->data['Account']['phone_country'] . $this->data['Account']['phone_number']
                    ]
                ];
            }

            $Account = $this->Account->findByUsername($this->request->data['Account']['username']);

            if (!isset($Account['Account'])) {
                $this->Session->setFlash('No existe ninguna cuenta, registrada con este correo.', null, null, 'recover');
                return;
            }

            $token = CakeText::uuid();

            $device = isset($this->request->data['Account']['device']) && !empty($this->request->data['Account']['device']) ?
                $this->request->data['Account']['device'] : '';

            //update
            $this->Account->id = $Account['Account']['id'];

            if ($this->Account->saveField('token', $token)) {
                $this->set('data', true);
                $this->Session->setFlash('Te hemos enviado un mensaje a ' . $Account['Account']['phone_country'] . $Account['Account']['phone_number'] . ', con las instrucciones para ingresar a tu cuenta.', null, null, 'recover');

                $urlActivate = Router::url(
                    [
                        'controller' => 'Accounts',
                        'action' => 'activate',
                        $token,
                    ],
                    true
                );


                $result = $this->SMSGateway->send([
                    'message' => 'Hola,' . $Account['Account']['first_name'] . ' activa tu cuenta para ingresar a tu dispositivo ' . $Account['Account']['device'] . ' en la siguiente liga ' . $urlActivate,
                    'phone_number' => $Account['Account']['phone_country'] . $Account['Account']['phone_number']
                ]);


                $this->request->data = [
                    'account_type_id' => $Account['Account']['account_type_id']
                ];


                $this->set('data', $this->request->data);
            }
        }
    }

    public function activate($token = null)
    {
        $this->Account->virtualFields = [];
        $this->Account->Operator->virtualFields = [];
        $this->Account->Partner->virtualFields = [];

        $this->Auth->logout();

        if (empty($token)) {
            $this->Session->setFlash('Tu cuenta no ha podido ser activada, probablemente ya ha sido activada con anterioridad o el token es inválido, intenta iniciar sesión, si el problema persiste envianos un correo a support@mainreport.us', null, null, 'login');
            return $this->redirect(Router::url(['action' => 'login'], true));
        }

        $Account = $this->Account->findByToken($token);


        if (!isset($Account['Account'])) {
            $this->Session->setFlash('Your account could not be activated. It has probably already been activated or the token is invalid. Try logging in, and if the problem persists, send us an email to support@mainreport.us', 'Flash/modal_unsuccess', null, 'login');
            return $this->redirect(Router::url(['action' => 'login'], true));
        }

        //update
        $this->Account->id = $Account['Account']['id'];

        if ($this->Account->saveField('status', 1) && $this->Account->saveField('token', NULL)) {

            $this->Session->setFlash('Your account has been successfully verified. You can now log in using your email and password.', 'Flash/modal_success', null, 'login');

            $this->layout = false;
        } else {
            $this->Session->setFlash('Your account could not be activated. It has probably already been activated or the token is invalid. Try logging in, and if the problem persists, send us an email to support@mainreport.us', 'Flash/modal_unsuccess', null, 'login');
        }
        $this->redirect(Router::url(['Controller' => 'Accounts', 'action' => 'login'], true));
    }

    public function recover()
    {
        $this->Account->virtualFields = [];
        $this->Account->Operator->virtualFields = [];
        $this->Account->Partner->virtualFields = [];
        if ($this->request->is('post')) {
            if (!isset($this->request->data['Account']['username']) || empty($this->request->data['Account']['username'])) {
                $this->Session->setFlash('There is no account registered with this email.', 'Flash/modal_unsuccess', null, 'recover');
                return;
            }

            $Account = $this->Account->findByUsername($this->request->data['Account']['username']);

            if (!isset($Account['Account'])) {
                $this->Session->setFlash('There is no account registered with this email.', 'Flash/modal_unsuccess', null, 'recover');
                return;
            }


            $recoveryCode = CakeText::uuid();
            $this->Account->id = $Account['Account']['id'];

            if ($this->Account->saveField('token', $recoveryCode)) {
                //echo pr("successfully"); 

                $this->Session->setFlash('We have sent an email to ' . $Account['Account']['username'] . ' with instructions to recover your account.', 'Flash/modal_success', null, 'recover');

                $name = '';
                if (!empty($Account['Operator']['first_name'])) {
                    $name = $Account['Operator']['first_name'];
                }

                if (!empty($Account['Partner']['first_name'])) {
                    $name = $Account['Partner']['first_name'];
                }

                $urlActivate = Router::url([
                    'controller' => 'Accounts',
                    'action' => 'restore',
                    $recoveryCode,
                ], true);

                $this->Account->sendEmail([
                    'to' => [
                        $Account['Account']['username']
                    ],
                    'subject' => 'Restore password',
                    'content' => '',
                    'emailFormat' => 'html',
                    'template' => 'recover',
                    'viewVars' => [
                        'name' => $name,
                        'url' => $urlActivate
                    ]
                ]);

                $this->request->data = [];
            }
        }
    }

    public function restore($token = null)
    {
        $this->Account->virtualFields = [];
        $this->Account->Operator->virtualFields = [];
        $this->Account->Partner->virtualFields = [];

        if (empty($token)) {
            $this->Session->setFlash('Your account cannot be restored; it has likely already been restored previously or the token is invalid. Please try to recover it again. If the problem persists, send us an email at support@mainreport.us.', 'Flash/modal_unsuccess', null, 'recover');
            $this->redirect(Router::url(['action' => 'recover'], true));
        }

        $Account = $this->Account->findByToken($token);

        if (!isset($Account['Account'])) {
            $this->Session->setFlash('Your account cannot be restored; it has likely already been restored previously or the token is invalid. Please try to recover it again. If the problem persists, send us an email at support@mainreport.us.', 'Flash/modal_unsuccess', null, 'recover');
            $this->redirect(Router::url(['action' => 'recover'], true));
        }

        $this->set('_email', $Account['Account']['username']);
        $this->set('_recovery_code', $token);


        if ($this->request->is('post')) {

            if (!isset($this->request->data['Account']['password']) || empty($this->request->data['Account']['password']) || !isset($this->request->data['Account']['repeated_password']) || empty($this->request->data['Account']['repeated_password'])) {
                $this->Session->setFlash('Verify that the fields are correct.', 'Flash/modal_unsuccess', null, 'restore');
                return;
            }

            if (
                $this->Account->save([
                    'Account' => [
                        'id' => $Account['Account']['id'],
                        'account_type_id' => $Account['Account']['account_type_id'],
                        'username' => $Account['Account']['username'],
                        'password' => $this->request->data['Account']['password'],
                        'repeated_password' => $this->request->data['Account']['repeated_password'],
                        'status' => $Account['Account']['status'],
                        'token' => NULL
                    ]
                ])
            ) {


                $name = '';
                if (!empty($Account['Operator']['first_name'])) {
                    $name = $Account['Operator']['first_name'];
                }

                if (!empty($Account['Partner']['first_name'])) {
                    $name = $Account['Partner']['first_name'];
                }


                $this->Account->sendEmail([
                    'to' => [
                        $Account['Account']['username']
                    ],
                    'subject' => 'Password restored',
                    'emailFormat' => 'html',
                    'template' => 'restored',
                    'viewVars' => [
                        'name' => $name,
                        'url' => Router::url(['controller' => '/'], true)
                    ]
                ]);

                $this->Session->setFlash('Your account has been successfully restored. You can now log in using your email and your new password.', 'Flash/modal_success', null, 'login');
            } else {
                $this->Session->setFlash('Your account cannot be restored; it has likely already been restored previously or the token is invalid. Please try to recover it again. If the problem persists, send us an email at support@mainreport.us.', 'Flash/modal_unsuccess', null, 'recover');
                return;
            }
            $this->redirect(Router::url(['action' => 'login'], true));
        }
    }

    public function unauthorized()
    {
        echo 'unauthorized';
        die();
    }

}
