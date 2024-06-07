<?php

App::uses('Model', 'Model');
App::uses('CakeText', 'Utility');
App::uses('InputDiv', 'Config/Common');
App::uses('InputType', 'Config/Common');
App::uses('AuthComponent', 'Controller/Component');

class AppModel extends Model {

    function loadModel($modelName, $options = array()) {
        if (is_string($options))
            $options = array('alias' => $options);
        $options = array_merge(array(
            'datasource' => 'default',
            'alias' => false,
            'id' => false,
                ), $options);
        list($plugin, $className) = pluginSplit($modelName, true, null);
        if (empty($options['alias']))
            $options['alias'] = $className;
        if (!isset($this->{$options['alias']}) || $this->{$options['alias']}->name !== $className) {
            if (!class_exists($className)) {
                if ($plugin)
                    $plugin = "{$plugin}.";
                App::import('Model', "{$plugin}{$modelClass}");
            }
            $table = Inflector::tableize($className);
            if (PHP5) {
                $this->{$options['alias']} = new $className($options['id'], $table, $options['datasource']);
            } else {
                $this->{$options['alias']} =  new $className($options['id'], $table, $options['datasource']);
            }
            if (!$this->{$options['alias']}) {
                return $this->cakeError('missingModel', array(array(
                                'className' => $className, 'code' => 500
                )));
            }
            $this->{$options['alias']}->alias = $options['alias'];
        }
        $this->{$options['alias']}->create();
        return true;
    }

}
