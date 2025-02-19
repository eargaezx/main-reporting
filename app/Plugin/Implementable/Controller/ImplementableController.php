<?php

App::uses('AppController', 'Controller');

abstract class ImplementableController extends AppController
{
    public $permissions = [ '' ];

    public $helpers = [
        'Implementable.Implementable'
    ];
    public $controllerActions = [];
    public $settings = [];


    public function beforeFilter()
    {

        $controllerName = $this->request->params['controller'];
        $actionName = $this->request->params['action'];
        if($this->{$this->modelClass}){
            $this->{$this->modelClass}->controllerName = $controllerName;
            $this->{$this->modelClass}->actionName = $actionName;
        }

        

        $this->controllerActions = array_replace_recursive([
            [
                'type' => 'search',
                'title' => 'Buscar',
                'class' => 'btn btn-sm btn-warning waves-effect',
                'icon' => [
                    'class' => 'fe-search font-size-18'
                ],
                'data' => []
            ],

            'add' =>
                [
                    'action' => 'add',
                    'title' => 'Crear',
                    'class' => 'btn btn-sm btn-warning waves-effect',
                    'icon' => [
                        'class' => 'fe-plus font-size-18'
                    ],
                    'data' => []
                ],
            [
                //'type' => 'post',
                'action' => 'export',
                'title' => 'Descargar',
                'class' => 'btn btn-sm btn-warning waves-effect',
                'icon' => [
                    'class' => 'fe-download font-size-18'
                ],
                'data' => []
            ],
        ], $this->controllerActions);



        $this->settings = array_replace_recursive([
            'index' => [
                'limit' => 20,
                'order' => ['created' => 'desc'],
                'redirect' => NULL,
            ],
            'add' => [
                'saveMethod' => 'save',
                'deep' => false,
                'redirect' => [
                    'action' => 'index'
                ],
            ],
            'edit' => [
                'saveMethod' => 'save',
                'deep' => false,
                'redirect' => [
                    'action' => 'index'
                ],
                'contain' => [],
            ],
            'view' => [
                'redirect' => NULL,
            ],
            'delete' => [
                'deleteMethod' => 'delete',
                'redirect' => [
                    'action' => 'index'
                ],
            ]
        ], $this->settings);


        if ($this->request->ext == 'action') {
            $this->layout = false;
            $this->autoRender = true;
        }
        parent::beforeFilter();
    }

    public function list(){
        $list = $this->{$this->modelClass}->find('list');
        $this->set('data', $list);
    }

    public function index()
    {
        $this->paginate = [
            'page' => empty($this->settings[$this->params['action']]['page']) ? 1 : $this->settings[$this->params['action']]['page'],
            'limit' => $this->settings[$this->params['action']]['limit'],
            'order' => $this->settings[$this->params['action']]['order']
        ];

        $this->set('limit', $this->settings[$this->params['action']]['limit']);
        $this->set('data', $this->Paginator->paginate($this->modelClass));
    }

    public function add()
    {
        $saveMethod = $this->settings[$this->params['action']]['saveMethod'];
        $deep = $this->settings[$this->params['action']]['deep'];

        if ($this->request->is('post') || $this->request->is('put')) {
            $data = $this->{$this->modelClass}->{$saveMethod}($this->request->data, ['deep' => $deep]);
            if ($data) {
                $this->Session->setFlash('La operación se realizó correctamente.', 'Flash/success');
                $this->set('data', $data);
                if (isset($this->settings[$this->params['action']]['redirect'])) {
                    $this->redirect(Router::url($this->settings[$this->params['action']]['redirect'], true));
                }
            } else {
                //echo pr($this->{$this->modelClass}->validationErrors);
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

    public function edit($id = null)
    {
        $saveMethod = $this->settings[$this->params['action']]['saveMethod'];
        $deep = $this->settings[$this->params['action']]['deep'];
        $contain = $this->settings[$this->params['action']]['contain'];

        if (empty($id)) {
            return $this->redirect($this->request->referer());
        }

        $this->{$this->modelClass}->id = $id;

        if (!$this->{$this->modelClass}->exists()) {
            return $this->redirect($this->request->referer());
        }

        if (($this->request->is('post') || $this->request->is('put')) && (!isset($this->request->data['_isPostLink']) || !$this->request->data['_isPostLink'])) {
            if ($this->{$this->modelClass}->{$saveMethod}($this->request->data, ['deep' => $deep])) {
                $this->set('data', $this->{$this->modelClass}->read());
                $this->Session->setFlash('La operación se realizó correctamente.', 'Flash/success');
                if (isset($this->settings[$this->params['action']]['redirect'])) {
                    $this->redirect(Router::url($this->settings[$this->params['action']]['redirect'], true));
                }
            } else {
                //echo pr($this->{$this->modelClass}->invalidFields()); die();
                $errors = 'La operación se realizó correctamente.';
                if ($this->request->ext == 'json') {
                    $errors = $this->{$this->modelClass}->validationErrors;
                    $errors = $this->{$this->modelClass}->invalidFields();


                    $errors = Hash::extract($errors, '{s}.{n}')
                        + Hash::extract($errors, '{s}.{s}.{n}')
                        + Hash::extract($errors, '{s}.{s}.{s}.{n}')
                        + Hash::extract($errors, '{n}.{s}.{n}.{s}')
                        + Hash::extract($errors, '{s}.{n}.{s}.{s}');




                    $errors = '-' . implode('<br> -', array_unique($errors));
                }


                $this->Session->setFlash($errors, 'Flash/not_success');
            }
        } else {
            //$this->{$this->modelClass}->contain($contain);
            $this->request->data = $this->{$this->modelClass}->read();
            $this->set('data', $this->request->data);
        }
    }

    public function view($id = null)
    {
        if (empty($id)) {
            return $this->redirect($this->request->referer());
        }

        $this->{$this->modelClass}->id = $id;

        if (!$this->{$this->modelClass}->exists()) {
            return $this->redirect($this->request->referer());
        }

        $this->request->data = $this->{$this->modelClass}->read();
        $this->set('data', $this->request->data);
    }

    public function delete($id = null)
    {
        $deleteMethod = $this->settings[$this->params['action']]['deleteMethod'];

        if (empty($id)) {
            return $this->redirect($this->request->referer());
        }

        $this->{$this->modelClass}->id = $id;


        if (!$this->{$this->modelClass}->exists()) {
            return $this->redirect($this->request->referer());
        }

        $_backup = $this->{$this->modelClass}->read();

        if ($this->{$this->modelClass}->{$deleteMethod}($id)) {
            $this->set('data', $_backup);
            $this->Session->setFlash('La operación se realizó correctamente.', 'Flash/success');
        } else {
            $this->Session->setFlash('La operación no pudo ser realizada.', 'Flash/not_success');
        }

        $redirect = Router::url($this->settings[$this->params['action']]['redirect'], true);
        if (!empty($redirect)) {
            $this->redirect($redirect);
        }
    }

    public function fields($action = null)
    {
        if (empty($action))
            $action = $this->params['action'];
        return $this->{$this->modelClass}->getFields($action);
    }

    public function beforeRender()
    {
        if (property_exists($this, 'uses') && ($this->uses == FALSE || empty($this->uses))) {
            return parent::beforeRender();
        }


        $this->set('modelFields', $this->{$this->modelClass}->getFields($this->params['action']));
        $this->set('modelFilters', $this->{$this->modelClass}->getFilters());
        $this->set('actionName', $this->params['action']);
        $this->set('modelName', $this->modelClass);
        $this->set('controllerName', $this->name);
        $this->set('modelActions', $this->{$this->modelClass}->getModelActions());
        $this->set('controllerActions', $this->controllerActions);

        $this->set('singularDisplayName', $this->{$this->modelClass}->singularDisplayName);
        $this->set('pluralDisplayName', $this->{$this->modelClass}->pluralDisplayName);



        if ($this->request->is('ajax')) {
            $this->request->ext = 'ajax';
        }



        $this->set('_ext', $this->request->ext);
        if (!empty($this->request->ext)) {
            if ($this->request->is('json')) {
                $this->request->ext = 'json';
                $this->view = '../Layouts/json/default';
                $this->layout = $this->request->ext;
            }
        }

        return parent::beforeRender();
    }

    public function redirect($url, $status = null, $exit = true)
    {
        if ($this->request->is('json')) {
            return;
        } else {
            parent::redirect($url, $status, $exit);
        }
    }

    public function import()
    {

        if ($this->request->is('post')) {
            $fields = $this->fields('import');
            $importables = array_keys(array_filter($fields, function ($value) {
                return isset($value['importable']) && $value['importable'] === true;
            }));

            $patchables = array_filter($this->request->data[$this->modelClass], function ($value) {
                return !empty($value);
            });

            // pr($patchables);

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

                $file = fopen($filePath, 'r');
                while (($row = fgetcsv($file)) !== FALSE) {
                    // Crear un array asociativo para cada fila
                    $rowData = array();
                    foreach ($headers as $index => $header) {
                        if (in_array($header, $importables)) {
                            $rowData[$header] = isset($row[$index]) ? $row[$index] : null;
                        }

                        $rowData = array_merge($rowData, $patchables);
                    }
                    // Agregar el array asociativo al array principal
                    $csvData[] = $rowData;
                }

            }
            fclose($file);
            unset($csvData[0]);
            // pr($this->request->data);
            //pr($csvData);

            if ($this->{$this->modelClass}->saveAll($csvData)) {
                $this->Session->setFlash(__('The records has been imported'), 'default', ['class' => 'alert alert-success bg-success']);
            } else {
                $this->Session->setFlash(__('Error import records'), 'default', ['class' => 'alert alert-danger bg-danger']);
            }

        }

        $this->render('../Elements/Components/import');
    }


    public function export()
    {
        $modelClass = $this->modelClass;
        $fields = $this->{$this->modelClass}->fields;

        //echo pr( $this->request->data ); die();

        $fields = array_filter($fields, function ($value) {
            return isset($value['exportable']) && $value['exportable'] === true;
        });

        $headers = array_map(function ($value) {
            return $value['fieldKey'];
        }, $fields);

        // Obtener los datos del modelo
        $data = $this->$modelClass->find('all');


        // Preparar el nombre del archivo CSV
        $filename = strtolower($modelClass) . '_index_' . date('YmdHis') . '.csv';

        // Generar el archivo CSV
        $csvFile = fopen('php://output', 'w');
        ob_start();

        // Insertar encabezados en el CSV
        fputcsv($csvFile, $headers);

        //echo pr($fields); die();

        // Insertar datos en el CSV
        foreach ($data as $record) {
            $row = [];
            foreach ($fields as $field) {

                if (isset($field['options'])) {
                    $row[] = (!empty($field['options'][Set::extract($field['bindValue'], $record)]) ? $field['options'][Set::extract($field['bindValue'], $record)] : 'None');
                } else {
                    $value = Set::extract($field['bindValue'], $record);
                    if (empty($value)) {
                        $row[] = 'Empty';
                    } else {
                        $row[] = $record[$modelClass][$field['fieldKey']];
                    }
                }

            }
            fputcsv($csvFile, $row);
        }

        fclose($csvFile);
        $csvContent = ob_get_clean();

        // Configurar las cabeceras HTTP para forzar la descarga del archivo
        $this->response->type('csv');
        $this->response->download($filename);
        $this->response->body($csvContent);

        return $this->response;
    }
}
