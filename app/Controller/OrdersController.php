<?php

App::uses('ImplementableController', 'Implementable.Controller');
App::uses('HttpSocket', 'Network/Http');


class OrdersController extends ImplementableController
{
    public $controllerActions = [
        [],
        [],
        [
            'action' => 'import',
            'title' => 'Upload',
            'class' => 'btn btn-sm btn-warning waves-effect',
            'icon' => [
                'class' => 'fe-upload font-size-18'
            ],
            'data' => []
        ],
    ];

    public $settings = [
        'index' => [
            'limit' => 10,
            'order' => ['created' => 'desc']
        ],
        'add' => [
            'saveMethod' => 'saveAll',
            'deep' => true
        ],
        'edit' => [
            'saveMethod' => 'saveAll',
            'deep' => true
        ],
        'maps' => [
            'limit' => 1000,
            'order' => ['created' => 'desc'],
            'redirect' => NULL,
        ]
    ];


    public function index()
    {

        if (AuthComponent::user() && AuthComponent::user('AccountType.name') == 'Subcontractor') {
            $this->loadModel('Order');
            $this->loadModel('License');
            $this->loadModel('Contractor');
            $this->loadModel('Subcontractor');
            $this->loadModel('SubcontractorLicense');


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
        }


        if ($this->request->ext == 'json') {
            $this->settings['index']['limit'] = 1000;
            $this->settings['index']['order'] = ['created' => 'desc'];
        }
        parent::index();
    }

    public function maps()
    {
        parent::index();
    }

    public function edit($id = null)
    {
        if ($this->request->ext == 'json')
            return parent::edit($id);

        $order = $this->Order->findById($id);
        if (!empty($order['Order']['status']) && $order['Order']['status'] == 2) {
            return $this->redirect(Router::url(['controller' => 'Orders', 'action' => 'view', $id], true));
        }
        parent::edit($id);
    }

    public function view($id = null)
    {

        if ($this->request->ext == 'json')
            return parent::view($id);

        $this->set('questions', []);
        $order = $this->Order->findById($id);
        if (!empty($order['Order']['survey_id'])) {
            $survey = $this->Order->Survey->findById($order['Order']['survey_id']);
            $this->set('questions', $survey['Question']);
        }

        parent::view($id);
    }


    public function places()
    {
        $this->layout = 'ajax';
        $httpSocket = new HttpSocket();
        $apiKey = 'AIzaSyA-UluRFUjiyByEOTzMnMJ8yifRRshgrNU';
        $query = $this->request->query['query'];
        $url = 'https://maps.googleapis.com/maps/api/place/textsearch/json';

        // Parámetros de la solicitud
        $response = $httpSocket->get($url, [
            'query' => $query,
            'key' => $apiKey
        ]);

        $places = [];
        if ($response->isOk()) {
            $places = json_decode($response->body, true);
        }

        // Haz algo con la respuesta, como enviar los datos a la vista
        echo json_encode($places);
        die();
    }


    public function import()
    {

        if ($this->request->is('post')) {
            $fields = $this->{$this->modelClass}->fields;
            $importables = array_map(function ($value) {
                return $value['fieldKey']; // Cambia 'fieldKey' por el nombre del campo que deseas obtener
            }, array_filter($fields, function ($value) {
                return isset($value['importable']) && $value['importable'] === true;
            }));

            $this->loadModel('Account');

            $operators = $this->Account->find('list', array(
                'fields' => ['Account.username', 'Operator.id'],//bind by operator id
                'joins' => [
                    [
                        'table' => 'operators',
                        'alias' => 'Operator',
                        'type' => 'INNER',  // Solo usuarios con operador
                        'conditions' => [
                            'Account.id = Operator.account_id',
                            'Operator.owner !=' => 1 //technicians and supervisors
                        ]
                    ]
                ]
            ));


            $subcontractors = $this->Account->find('list', array(
                'fields' => ['Account.username', 'Operator.subcontractor_id'], //bind by subcontractor id
                'joins' => [
                    [
                        'table' => 'operators',
                        'alias' => 'Operator',
                        'type' => 'INNER',  // Solo usuarios con operador
                        'conditions' => [
                            'Account.id = Operator.account_id',
                            'Operator.owner' => 1 //sucontractistas
                        ]
                    ]
                ]
            ));



            $contractors = $this->Account->find('list', array(
                'fields' => ['Account.username', 'Partner.contractor_id'], //bind by contractor id
                'joins' => [
                    [
                        'table' => 'partners',
                        'alias' => 'Partner',
                        'type' => 'INNER',  // Solo usuarios con operador
                        'conditions' => [
                            'Account.id = Partner.account_id'
                        ]
                    ]
                ]
            ));

            // echo pr($subcontractors);

            $patchables = [];

            $file = $this->request->data['Upload']['csv_file'];
            if ($file['error'] === UPLOAD_ERR_OK) {

                // Guarda el archivo en una ubicación temporal
                move_uploaded_file($file['tmp_name'], TMP . $file['name']);
                // Procesa el archivo CSV
                $filePath = TMP . $file['name'];

                // Array para almacenar los datos CSV
                $csvData = array();

                $file = fopen($filePath, 'r');

                // Leer la primera fila como encabezados
                $headers = fgetcsv($file);


                while (($row = fgetcsv($file)) !== FALSE) {
                    // Crear un array asociativo para cada fila
                    $rowData = array();
                    foreach ($headers as $index => $header) {
                        $fixedHeader = str_replace(' ', '_', trim($header));

                        if (in_array($fixedHeader, ['contractor', 'subcontractor', 'operator', 'technician'])) {
                            $fixedHeader = $fixedHeader . '_id';
                            if ($fixedHeader == 'technician_id') {
                                $fixedHeader = 'operator_id';
                            }
                        }

                        if ($fixedHeader == 'order' || $fixedHeader == '# order' || $fixedHeader == '#_order') {
                            $fixedHeader = 'name';
                        }

                        if (in_array($fixedHeader, $importables)) {
                            $rowData[$fixedHeader] = isset($row[$index]) ? $row[$index] : null;
                        }

                        if ($fixedHeader == 'contractor_id' && !empty($rowData[$fixedHeader]) && !empty($contractors[$rowData[$fixedHeader]])) {
                            $rowData[$fixedHeader] = $contractors[$rowData[$fixedHeader]];
                        }

                        if ($fixedHeader == 'subcontractor_id' && !empty($rowData[$fixedHeader]) && !empty($subcontractors[$rowData[$fixedHeader]])) {
                            $rowData[$fixedHeader] = $subcontractors[$rowData[$fixedHeader]];
                        }

                        if ($fixedHeader == 'operator_id' && !empty($rowData[$fixedHeader]) && !empty($operators[$rowData[$fixedHeader]])) {
                            $rowData[$fixedHeader] = $operators[$rowData[$fixedHeader]];
                        }

                        $rowData = array_merge($rowData, $patchables);
                    }
                    // Agregar el array asociativo al array principal
                    $csvData[] = $rowData;
                }

            }
            fclose($file);
            //unset($csvData[0]);

            //echo  pr($csvData); die();

            $this->Session->write('csvData', $csvData);

            $this->redirect(array('action' => 'store'));
        }
    }



    public function store()
    {
        if ($this->request->is('post')) {

            $csvData = $this->request->data['Orders'];
            
            $csvData = array_filter($csvData, function ($value) {
                return isset($value['import']);
            });


            if ($this->{$this->modelClass}->saveAll($csvData)) {
                $this->Session->write('csvDataCount', count($csvData));
                $this->sendEmail(count($csvData));
                // $this->Session->setFlash(__('The records has been imported'), 'default', ['class' => 'alert alert-success bg-success']);
                $this->redirect(array('action' => 'done'));
            } else {
                $this->Session->setFlash(__('Error import records'), 'default', ['class' => 'alert alert-danger bg-danger']);
            }

        } else {

            $csvData = $this->Session->read('csvData');
            if (empty($csvData))
                $this->redirect(array('action' => 'index'));

            $addresses = [];

            foreach ($csvData as $index => $value) {
                $addresses[$index] = ['id' => "FD$index", 'address' => $this->sanitizeAddress($value['address'])];
            }

            $validAdsresses = $this->validteAddresses($addresses);

            foreach ($csvData as $index => $value) {
                $csvData[ $index ]['isValidAddress'] =  in_array( $this->sanitizeAddress($value['address']),$validAdsresses)  ;
                $csvData[ $index ]['isUnassigned'] =  empty($value['contractor_id']) || empty($value['operator_id']) ;
            }

            //echo pr($csvData ); die();

            $fields = $this->{$this->modelClass}->fields;
            $importableFields = array_map(function ($value) {
                return $value; // Cambia 'fieldKey' por el nombre del campo que deseas obtener
            }, array_filter($fields, function ($value) {
                return isset($value['importable']) && $value['importable'] === true;
            }));


            $this->set('importableFields', $importableFields);
        }

        $this->set('data', $csvData);
    }

    public function done()
    {
        $csvDataCount = $this->Session->read('csvDataCount');
        if (empty($csvDataCount)) {
            $this->redirect(array('action' => 'index'));
        }
        $this->Session->delete('csvData');
        $this->Session->delete('csvDataCount');
        $this->set('count', $csvDataCount);
    }


    private function saveToCSV($filePath, $headers, $data)
    {
        $file = fopen($filePath, 'w');
        fputcsv($file, $headers);

        foreach ($data as $row) {
            fputcsv($file, $row);
        }

        fclose($file);
    }

    private function downloadFile($filePath)
    {
        $this->response->file($filePath, ['download' => true]);
        return $this->response;
    }


    protected function sendEmail($count)
    {
        $to = [];
        $name = '';
        $allocatorType = '';
        $allocator = 'Main Report';

        $this->loadModel('Account');
        if (!empty($this->request->data['Order']['Order']['subcontractor_id'])) {
            $subcontractor = $this->Order->Subcontractor->findById($this->request->data['Order']['Order']['subcontractor_id']);
            $name = $subcontractor['Admin']['name'];

            $this->Account->virtualFields = [];
            $this->Account->Operator->virtualFields = [];
            $account = $this->Account->findById($subcontractor['Admin']['account_id']);


            $to = array_merge($to, [$account['Account']['username']]);
        }

        if (!empty($this->request->data['Order']['Order']['contractor_id'])) {
            $contractor = $this->Order->Contractor->findById($this->request->data['Order']['Order']['contractor_id']);
            $name = $contractor['Partner']['name'];

            $this->Account->virtualFields = [];
            $this->Account->Operator->virtualFields = [];
            $account = $this->Account->findById($contractor['Partner']['account_id']);

            $to = array_merge($to, [$account['Account']['username']]);
        }

        if (!empty($this->request->data['Order']['Order']['operator_id'])) {
            $operator = $this->Order->Operator->findById($this->request->data['Order']['Order']['operator_id']);
            $name = $operator['Operator']['first_name'];

            $this->Account->virtualFields = [];
            $this->Account->Operator->virtualFields = [];
            $account = $this->Account->findById($operator['Operator']['account_id']);

            $to = array_merge($to, [$account['Account']['username']]);
        }

        if (AuthComponent::user() && AuthComponent::user('AccountType.name') == 'Contractor') {
            $contractor = $this->Order->Contractor->findById(AuthComponent::user('Partner.contractor_id'));
            $allocator = $contractor['Contractor']['name'];
        }

        if (AuthComponent::user() && AuthComponent::user('AccountType.name') == 'Subcontractor') {
            $subcontractor = $this->Order->Subcontractor->findById(AuthComponent::user('Operator.subcontractor_id'));
            $allocator = $subcontractor['Subcontractor']['name'];
        }



        $allocatorType = AuthComponent::user('AccountType.name');

        if (count($to) > 1) {
            $name = '';
        }

        if (empty($to))
            return;

        $this->Order->sendEmail([
            'to' => $to,
            'subject' => 'Orders Assigned',
            'emailFormat' => 'html',
            'template' => 'imported',
            'viewVars' => [
                'count' => $count,
                'name' => $name,
                'allocator' => $allocator,
                'allocatorType' => $allocatorType,
                'url' => Router::url(['controller' => '/'], true)
            ]
        ]);
    }


    protected function validteAddresses($addresses)
    {
        // Inicializar el cliente HTTP
        $http = new HttpSocket([
            'ssl_verify_peer' => false,  // Desactiva la verificación SSL
            'ssl_verify_peer_name' => false  // Desactiva la verificación del nombre
        ]);
        $validAddresses = [];
        $apiKey = '4ca786087dc7904d988c888699db9c94c6c8089';
        $geocodioUrl = "https://api.geocod.io/v1.7/geocode?api_key=$apiKey";

        $addresses = array_map(function ($item) {
            return $item['address'];
        }, $addresses);

        $addresses = array_filter($addresses, function ($item) {
            return !empty($item);
        });

        $addresses = array_values($addresses);

        // Hacer la solicitud en lote (array de direcciones)
        $response = $http->post($geocodioUrl, json_encode($addresses), [
            'header' => ['Content-Type' => 'application/json']
        ]);

        // Decodificar la respuesta JSON
        $result = json_decode($response->body, true);
        //echo pr($result); die();

         // Verificar si hay resultados en la respuesta
         if (!empty($result['results'])) {
            foreach ($result['results'] as $index => $res) {
                // Extraer la respuesta asociada a la dirección
                $response = $res['response'];
                
                // Verificar si la dirección tiene al menos un resultado y un tipo de precisión correcto
                if (!empty($response['results'])) {
                    foreach ($response['results'] as $resultData) {
                        $accuracyType = isset($resultData['accuracy_type'])? $resultData['accuracy_type'] :  '';

                        // Solo consideramos las direcciones con "accuracy_type" de "rooftop"
                        if ($accuracyType === 'rooftop') {
                            // Agregamos la dirección original que fue enviada, tal como fue ingresada
                            $validAddresses[] = $res['query'];
                            break;  // Salir del loop si encontramos una dirección válida
                        }
                    }
                }
            }
        }

        return $validAddresses;
    }

    // Función para sanitizar la dirección
    protected function sanitizeAddress($address)
    {
        // Eliminar caracteres no imprimibles
        $address = preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $address);

        // Definir un patrón para permitir solo caracteres alfanuméricos, espacios y algunos caracteres especiales
        $pattern = '/^[\w\s,.#-]+$/';

        // Verificar si la dirección es válida según el patrón
        if (preg_match($pattern, $address)) {
            // Limpiar la dirección, eliminar espacios extra y normalizarla
            $sanitizedAddress = trim(preg_replace('/\s+/', ' ', $address));
            return $sanitizedAddress;
        }

        // Si no es válida, retornar null
        return null;
    }


    public function reassign($id){

    }
}
