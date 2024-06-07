<?php

class ImplementableBehavior extends ModelBehavior
{

    public function setup(\Model $model, $config = array())
    {
        parent::setup($model, $config);

        if (method_exists($model, 'beforeImplement')) {
            $model->beforeImplement();
        }

        $this->implementFields($model);
        $this->implementFilters($model);


        if (method_exists($model, 'afterImplement')) {
            $model->afterImplement();
        }
    }

    protected function implementFields($model)
    {
        $this->_fields = $model->fields;

        $belongstToFields = $this->getBelongsToFields($model);

        $fields = [];
        foreach ($model->fields as $k => $settings) {
            if (!isset($settings['fieldKey'])) {
                echo 'An Field  has "fieldKey" undefined into model ' . $model->name . '.';
                die();
            }

            if (!isset($settings['modelClass'])) {
                $settings['modelClass'] = $model->name;
            }

            if (!isset($settings['bindValue'])) {
                $settings['bindValue'] = $settings['modelClass'] . '.' . $settings['fieldKey'];
            }

            //set options by related model
            if (array_key_exists($settings['fieldKey'], $belongstToFields)) {
                //echo pr($belongstToFields[$settings['fieldKey']]); die();
                if (!isset($settings['options']) || !is_array($settings['options'])) {
                    $settings['options'] = [];
                }
                $settings['options'] = array_replace_recursive($settings['options'], $model->{$belongstToFields[$settings['fieldKey']]['modelClass']}->find('list'));
                //echo pr($options); die();
            }

            if ($settings['modelClass'] != $model->name) {
                $settings = array_replace_recursive(
                    $model->{$settings['modelClass']}->getField($settings['fieldKey']),
                    $settings
                );
            }

            $fields[$k] = $settings;
        }
        $model->fields = $fields;
    }

    protected function implementFilters($model)
    {
        $filters = [];
        foreach ($model->fields as $k => $settings) {
            if (!isset($settings['filter']) || empty($settings['filter'])) {
                $model->fields[$k]['filter'] = FALSE;
                continue;
            }


            if (!is_array($settings['filter'])) {
                $settings['filter'] = [];
            }

            if (isset($settings['type']) && !isset($settings['filter']['type'])) {
                $settings['filter']['type'] = $settings['type'];
            }

            if (!isset($settings['filter']['operator'])) {
                $settings['filter']['operator'] = 'EQUAL';
            }

            if (!isset($settings['filter']['alias'])) {
                $settings['filter']['alias'] = $settings['fieldKey'];
            }

            if (isset($settings['displayFormat'])) {
                $settings['filter']['displayFormat'] = $settings['displayFormat'];
            }

            if (isset($settings['sourceFormat'])) {
                $settings['filter']['sourceFormat'] = $settings['sourceFormat'];
            }

            $settings['filter']['modelClass'] = $settings['modelClass'];
            $settings['filter']['fieldKey'] = $settings['fieldKey'];

            $filters[$settings['filter']['alias']] = $settings['filter'];
            $model->fields[$k]['filter'] = $settings['filter']['alias'];
        }

        $model->filters = $filters;
    }

    public function getFields($model, $view = null, $prefixed_model_class = true)
    {
        $fields = [];

        foreach ($model->fields as $settings) {
            if (!isset($settings['showIn']) || $settings['showIn'] === FALSE)
                continue;

            //patch field type
            if (isset($settings['type']) && is_array($settings['type'])) {
                $settings = array_replace_recursive($settings, $settings['type']);
            }

            if ($view === null || $settings['showIn'] === TRUE || (is_array($settings['showIn']) && in_array($view, $settings['showIn']))) {
                $fieldName = $settings['modelClass'] . '.' . $settings['fieldKey'];

                if ($prefixed_model_class === false) {
                    $fieldName = $settings['fieldKey'];
                }

                $fields[$fieldName] = $settings;

                unset($fields[$fieldName]['showIn']);
                unset($fields[$fieldName]['fieldKey']);
                unset($fields[$fieldName]['modelClass']);
            }
        }



        return $fields;
    }

    public function getFilters($model)
    {
        $filters = [];

        foreach ($model->fields as $settings) {
            if (empty($settings['filter']) || !isset($model->filters[$settings['filter']]))
                continue;

            $_filterSettings = $model->filters[$settings['filter']];
            $settings = array_replace_recursive($settings, $_filterSettings);

            //patch field type
            if (isset($settings['type']) && is_array($settings['type'])) {
                $settings = array_replace_recursive($settings, $settings['type']);
            }

            //sanitize field settings
            unset($settings['filter']);
            unset($settings['showIn']);
            unset($settings['fieldKey']);
            unset($settings['modelClass']);
            unset($settings['operator']);
            unset($settings['fieldKey']);
            unset($settings['modelClass']);
            unset($settings['showIn']);
            unset($settings['bindValue']);
            unset($settings['readonly']);
            unset($settings['required']);

            $filters[$settings['alias']] = $settings;
        }

        return $filters;
    }

    public function getField($model, $fieldName)
    {
        foreach ($model->fields as $settings) {
            if ($fieldName == $settings['fieldKey']) {
                return $settings;
            }
        }
        return null;
        echo 'An related Field undefined "' . $fieldName . '" into model ' . $model->name;
        die();
    }

    public function getModelActions($model)
    {
        foreach ($model->actions as $key => $modelAction) {
            if (!isset($model->actions[$key]['bindValue'])) {
                $model->actions[$key]['bindValue'] = $model->name . '.' . 'id';
            }
        }
        return $model->actions;
    }

    public function getBelongsToFields($model)
    {
        $belongsToColumns = [];
        foreach ($model->belongsTo as $key => $current):
            $belongsToColumns[$current['foreignKey']] = array(
                'modelClass' => $current['className'],
                'modelKey' => $key
            );
        endforeach;
        return $belongsToColumns;
    }

    public function beforeValidate(\Model $model, $options = array())
    {
        $this->prepareFileFields($model);
        parent::beforeValidate($model, $options);
    }

    public function beforeSave(\Model $model, $options = array())
    {

        if (method_exists($model, 'beforeImplement')) {
            $model->beforeImplement();
        }


        if (method_exists($model, 'afterImplement')) {
            $model->afterImplement();
        }

        if (isset($model->data['named'])) {
            unset($model->data['named']);
        }

        foreach ($model->data[$model->name] as $key => $value) {
            //echo $model->name.'.'.$key;
            $field = $model->getField($key);
            if ($field == null || !isset($field['sourceFormat']) || !isset($field['displayFormat'])) {
                continue;
            }

            if (isset($field['type']) && $field['type'] == InputType::DATE) {
                $model->data[$model->name][$key] = date($field['sourceFormat'], strtotime($value));
            }
        }

        parent::beforeSave($model, $options);
    }

    public function beforeFind(\Model $model, $query)
    {
        $query['conditions'] = array_merge($model->conditions, (isset($query['conditions']) ? $query['conditions'] : []));
        //$query['offset'] = (($this->limit * $this->page) - $this->limit);
        //$query['limit'] = $this->limit;

        return $query;
    }

    public function afterFind(\Model $model, $results, $primary = false)
    {
        // Iterate over each result
        foreach ($results as $key => $val) {
            // Check if the result is an array
            if (is_array($val)) {
                // Iterate over each field in the result

                foreach ($model->fields as $settings) {
                    $fieldkey = $settings['fieldKey'];
                    if (isset($val[$model->alias][$fieldkey]) && isset($settings['type']) && $settings['type'] == InputType::DATE) {
                        $displayFormat = empty($settings['displayFormat']) ? 'Y-m-d' : $settings['displayFormat'];

                        $results[$key][$model->alias][$fieldkey] = date($displayFormat, strtotime($results[$key][$model->alias][$fieldkey]));
                    }
                }




            }
        }
        return $results;
    }



    protected function prepareFileFields($model)
    {

        foreach ($model->fields as $options) {

            $key = $options['fieldKey'];
            if (!isset($options['type']) || ($options['type'] != 'file' && $options['type'] != InputType::IMAGE))
                continue;


            if (isset($model->data[$model->name][$key]) && !empty($model->data[$model->name][$key]) && is_array($model->data[$model->name][$key]) && isset($model->data[$model->name][$key]['name']) && !empty($model->data[$model->name][$key]['name'])) {//with value
                $fileprefix = uniqid();
                $simpledir = Router::url(['controller' => '/'], true) . '/files/' . $fileprefix . '/';
                $folder_dir = WWW_ROOT . '/files/' . $fileprefix . '/';

                $ext = explode('.', $model->data[$model->name][$key]['name']);
                $new_name = uniqid() . '.' . end($ext);

                new Folder($folder_dir, true);

                $filename = $folder_dir . $fileprefix . $new_name;
                move_uploaded_file($model->data[$model->name][$key]['tmp_name'], $filename);

                $model->data[$model->name][$key] = $simpledir . $fileprefix . $new_name;
            } else if (isset($model->data[$model->name][$key]) && !empty($model->data[$model->name][$key]) && !(is_array($model->data[$model->name][$key]) || $model->data[$model->name][$key] instanceof Traversable) && strpos($model->data[$model->name][$key], 'data:') !== false) {

                $fileprefix = uniqid();
                $simpledir = Router::url(['controller' => '/'], true) . '/files/' . $fileprefix . '/';
                $folder_dir = WWW_ROOT . '/files/' . $fileprefix . '/';

                $type = explode(';', $model->data[$model->name][$key])[0];
                $ext = explode('/', $type)[1];
                //$ext = explode('.', $model->data[$model->name][$key]['name']);
                $new_name = uniqid() . '.' . $ext;


                new Folder($folder_dir, true);


                $filename = $folder_dir . $fileprefix . $new_name;

                $fileBin = file_get_contents($model->data[$model->name][$key]);

                file_put_contents($filename, $fileBin);

                /*$file = fopen($filename, "wb");
                fwrite($file, preg_replace('#^data:image/\w+;base64,#i', '',  $model->data[$model->name][$key] ));
                fclose($file);*/

                $model->data[$model->name][$key] = $simpledir . $fileprefix . $new_name;

            } else if (isset($model->data[$model->name][$key]) && !empty($model->data[$model->name][$key]) && filter_var($model->data[$model->name][$key], FILTER_VALIDATE_URL)) {
                $model->data[$model->name][$key] = $model->data[$model->name][$key];
            } else {//without value
                unset($model->data[$model->name][$key]);
            }
        }
    }

}
