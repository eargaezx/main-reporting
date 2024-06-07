<?php

App::uses('InputType', 'Config/Common');

class FilterableComponent extends Component
{

    public $components = ['Paginator', 'Session'];
    protected $controller = null;

    const EQUAL_FILTER = 'EQUAL';
    const LIKE_FILTER = 'LIKE';
    const EQUAL_HIGHER = 'HIGHER';
    const EQUAL_MINOR = 'MINOR';

    public function initialize(\Controller $controller)
    {
        $this->controller = $controller;

        if (property_exists($this->controller, 'uses') && ($this->controller->uses == FALSE || empty($this->controller->uses))) {
            return parent::initialize($controller);
        }

        $this->controller->Paginator = $this->Paginator;
        $this->controller->Session = $this->Session;

        $this->buildData();
        $this->buildFilters();

        parent::initialize($controller);
    }

    public function implement()
    {
        $this->buildData();
        $this->buildFilters();
    }

    public function getFilters()
    {
        $values = isset($this->controller->request->param('named')['filter']) ?
            $this->controller->request->param('named')['filter'] : (
                isset($this->controller->request->data['named']['filter']) ?
                $this->controller->request->data['named']['filter'] : []);

        return $values;
    }

    protected function buildData()
    {
        if (empty($this->controller->{$this->controller->modelClass}->fields)) {
            return;
        }

        $values = isset($this->controller->request->param('named')['data']) ?
            $this->controller->request->param('named')['data'] : (
                isset($this->controller->request->data['named']['data']) ?
                $this->controller->request->data['named']['data'] : []);


        foreach ($this->controller->{$this->controller->modelClass}->fields as $k => $fieldSettings) {
            if (!isset($values[$fieldSettings['fieldKey']])) {
                continue;
            }

            if ($fieldSettings['modelClass'] != $this->controller->modelClass) {
                continue;
            }

            $this->controller->{$this->controller->modelClass}->fields[$k]['default'] = $values[$fieldSettings['fieldKey']];
        }
    }



    protected function buildFilters()
    {
        if (empty($this->controller->{$this->controller->modelClass}->filters)) {
            return;
        }

        $filters = $this->controller->{$this->controller->modelClass}->filters;

        $values = isset($this->controller->request->param('named')['filter']) ?
            $this->controller->request->param('named')['filter'] : [];

        if (empty($values)) {
            $values = (isset($this->controller->request->data['named']['filter']) ?
                $this->controller->request->data['named']['filter'] : []);
        }

        if (!empty($values['page'])) {
            $this->controller->settings['index']['page'] = $values['page'];
        }


        $this->controller->set('filter', $values);

        $conditions = [];

        foreach ($values as $aliasKey => $value) {
            if (!isset($filters[$aliasKey]) || empty($value)) {
                unset($values[$aliasKey]);
                continue;
            }

            $conditionKey = $filters[$aliasKey]['modelClass'] . '.' . $filters[$aliasKey]['fieldKey'];


            switch ($filters[$aliasKey]['operator']) {
                case 'EQUAL':
                    break;

                case 'LIKE':
                    $conditionKey = $conditionKey . ' LIKE ';
                    $value = '%' . $value . '%';
                    break;

                case 'HIGHER':
                    $conditionKey = $conditionKey . ' >=';
                    break;

                case 'MINOR':
                    $conditionKey = $conditionKey . ' <=';
                    break;
                case 'BETWEEN':

                    $value = explode($filters[$aliasKey]['split'], $value);

                    $start = $value[0];
                    $end = $value[1];

                    if ($filters[$aliasKey]['type'] == InputType::DATE || $filters[$aliasKey]['type'] == InputType::DATERANGE) {
                        $displayFormat = empty($filters[$aliasKey]['displayFormat']) ? 'Y-m-d' : $filters[$aliasKey]['displayFormat'];
                        $sourceFormat = empty($filters[$aliasKey]['sourceFormat']) ? 'Y-m-d' : $filters[$aliasKey]['sourceFormat'];

                        $start = DateTime::createFromFormat($displayFormat, $value[0])->format($sourceFormat);
                        $end = DateTime::createFromFormat($displayFormat, $value[1])->format($sourceFormat);
                    }

                    $conditionKey = $conditionKey . ' BETWEEN ? and ?';
                    $value = array($start, $end);

                    break;
                case 'WITHIN':
                    $value = 'DATE("' . $value . '") BETWEEN MealSchedule.start_date AND MealSchedule.end_date';
                    $conditionKey = '';
                    break;
                default:
                    break;
            }
            $conditions[$conditionKey] = $value;
        }


        $this->controller->{$this->controller->modelClass}->conditions = array_replace($conditions, isset($this->controller->{$this->controller->modelClass}->conditions) ? $this->controller->{$this->controller->modelClass}->conditions : []);

        //$this->controller->{$this->controller->modelClass}->conditions = $conditions;
        $this->controller->request->data['named']['filter'] = $values;
    }

}
