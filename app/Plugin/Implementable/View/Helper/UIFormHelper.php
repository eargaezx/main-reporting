<?PHP

App::uses('FormHelper', 'View/Helper');

class UIFormHelper extends FormHelper
{

    //UI INPUT TYPES
    const UI_TYPE_TEXT = 'text';
    const UI_TYPE_SMART_SELECT = 'smart-select';
    const UI_TYPE_SELECT = 'select';
    const UI_TYPE_DATE = 'date';
    const UI_TYPE_DATE_TIME = 'date-time';
    const UI_TYPE_IMAGE = 'file';
    const UI_TYPE_TIME = 'time';
    const UI_TYPE_TAGS_EDITOR = 'tags-editor';
    //UI INPUT PLUGINS
    const UI_PLUGIN_SELECT = 'dropify'; //data-plugins
    
    //UI INPUT CLSSSES
    const UI_CLASS_SELECT = 'form-control select2';
    const UI_CLASS_SMART_SELECT = 'form-control select2';
    const UI_CLASS_DATE = 'form-control flatpickr flatpickr-input';
    const UI_CLASS_DATE_TIME = 'form-control flatpickr flatpickr-input';
    const UI_CLASS_DATE_RANGE = 'form-control range-datepicker';
    const UI_CLASS_CLOCK_PICKER = 'form-control clockpicker';
    const UI_CLASS_TAGS_EDITOR = 'form-control tags-editor';
    //UI WEIGTHS
    const UI_WEIGHT_SM_1 = 'col-sm-1';
    const UI_WEIGHT_SM_2 = 'col-sm-2';
    const UI_WEIGHT_SM_3 = 'col-sm-3';
    const UI_WEIGHT_SM_4 = 'col-sm-4';
    const UI_WEIGHT_SM_5 = 'col-sm-5';
    const UI_WEIGHT_SM_6 = 'col-sm-6';
    const UI_WEIGHT_SM_7 = 'col-sm-7';
    const UI_WEIGHT_SM_8 = 'col-sm-8';
    const UI_WEIGHT_SM_9 = 'col-sm-9';
    const UI_WEIGHT_SM_10 = 'col-sm-10';
    const UI_WEIGHT_SM_11 = 'col-sm-11';
    const UI_WEIGHT_SM_12 = 'col-sm-12';
    const UI_WEIGHT_MD_1 = 'col-md-1';
    const UI_WEIGHT_MD_2 = 'col-md-2';
    const UI_WEIGHT_MD_3 = 'col-md-3';
    const UI_WEIGHT_MD_4 = 'col-md-4';
    const UI_WEIGHT_MD_5 = 'col-md-5';
    const UI_WEIGHT_MD_6 = 'col-md-6';
    const UI_WEIGHT_MD_7 = 'col-md-7';
    const UI_WEIGHT_MD_8 = 'col-md-8';
    const UI_WEIGHT_MD_9 = 'col-md-9';
    const UI_WEIGHT_MD_10 = 'col-md-10';
    const UI_WEIGHT_MD_11 = 'col-md-11';
    const UI_WEIGHT_MD_12 = 'col-md-12';

    protected $view = null;

    public function __construct(View $view, $settings = array())
    {
        $this->view = $view;
        parent::__construct($view, $settings);
    }

    public function create($model = null, $options = array())
    {
        if (!isset($options['enctype'])) {
            $options['enctype'] = 'multipart/form-data';
        }
        return parent::create($model, $options);
    }

    public function input($fieldName, $options = array())
    {

        if (isset($options['type']) && ($options['type'] == self::UI_TYPE_DATE || $options['type'] == self::UI_TYPE_DATE_TIME)) {
            $options['type'] = self::UI_TYPE_TEXT;
            $options['class'] = self::UI_CLASS_DATE;
        }

        if (isset($options['type']) && $options['type'] == self::UI_TYPE_TIME) {
            $options['type'] = self::UI_TYPE_TEXT;
            $options['class'] = self::UI_CLASS_CLOCK_PICKER;
        }


        if (isset($options['options']) || (isset($options['type']) && $options['type'] == self::UI_TYPE_SELECT)) {
            unset($options['type']);
            $options['data-toggle'] = 'select2';
            $options['class'] = self::UI_CLASS_SELECT;
        }


        if (isset($options['type']) && $options['type'] == self::UI_TYPE_SMART_SELECT) {
            unset($options['type']);
            $options['class'] = self::UI_CLASS_SMART_SELECT;
        }


        if (isset($options['type']) && $options['type'] == self::UI_TYPE_TAGS_EDITOR) {
            unset($options['type']);
            $options['class'] = self::UI_CLASS_TAGS_EDITOR;
        }



        if (isset($options['type']) && $options['type'] == self::UI_TYPE_IMAGE) {
            $defaultImage = (Set::extract($options['bindValue'], $this->view->request->data) != null ? Set::extract($options['bindValue'], $this->view->request->data) : (isset($options['default']) ? $options['default'] : ''));

            $options['default'] = empty($defaultImage) ? '' : $defaultImage;



            $minHeight = '270px';
            if (isset($options['thumbnail']['preview-height'])) {
                $minHeight = $options['thumbnail']['preview-height'];
            }

            $options['before'] = '<div class="fileinput ' . (empty($defaultImage) ? 'fileinput-new' : 'fileinput-exists') . '" data-provides="fileinput" style="width: 100%;">
                    <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="display: flex; width: 100%; min-height: ' . $minHeight . '; background-color: #323232;">
                        <img src="' . $defaultImage . '" width="' . (empty($defaultImage) ? '' : '100%') . '">
                    </div>
                    <div class="col-sm-12 fileinput-actions">
                        <div class="row">
                        <span class="btn btn-sm btn-primary btn-file  col-sm-6">
                            <span class="fileinput-new">Seleccionar</span>';
            $options['after'] = '</span>
                        <a href="#" class="btn btn-sm btn-secondary fileinput-exists col-sm-6" data-dismiss="fileinput">Remover</a>
                       </div>
                    </div>
                </div>';

            $options['style'] = 'width:0px; height:0px;';
            $options['class'] = '';
            $options['type'] = 'file';
            $options['weight'] = false;
        }

        //for input wehights
        if (isset($options['weight']) && $options['weight'] == false) {
            $options['div'] = false;
        } else if (isset($options['weight'])) {
            $options['div']['class'] = $options['weight'];
            unset($options['weight']);
        } else {
            $options['div']['class'] = 'col-sm-6';
        }



        //for input icon 
        if (isset($options['icon'])) {
            $style = '';
            if (isset($options['class']) && $options['class'] == self::UI_CLASS_SMART_SELECT) {
                $style = 'position:absolute; z-index:1; height:38px;';
            }

            /*$options['between'] = '<div class="input-group-prepend" style="' . $style . '">
                        <span class="input-group-text">
                            <i class="material-icons">'.$options['icon'].'</i>
                        </span>
                     </div>';*/

            $options['between'] =
                '<span class="input-group-addon" style="margin-right:-2px;">'
                . '<i style="color: red" class="material-icons">' . $options['icon'] . '</i> '
                . '</span>';

            unset($options['icon']);
        }

        //unset visible views
        if (isset($options['views'])) {
            unset($options['views']);
        }

        //unset searchable settings
        if (isset($options['filter'])) {
            unset($options['filter']);
        }

        //unset searchable settings
        if (isset($options['bindValue'])) {
            unset($options['bindValue']);
        }


        return parent::input($fieldName, $options);
    }

    public function filter($fieldName, $options = array())
    {
        if ($options['filter'] === FALSE)
            return;

        $options['div'] = false;
        $options['label'] = false;
        unset($options['rows']);
        unset($options['weight']);
        unset($options['readonly']);
        unset($options['disabled']);

        $options['class'] = 'form-control' . (isset($options['class']) ? $options['class'] : '');
        $options['name'] = 'named[filter][' . $options['filter'] . ']';


        if (isset($this->view->request->data['named']['filter'][$options['filter']])) {
            $options['default'] = $this->view->request->data['named']['filter'][$options['filter']];
        }

        if (isset($options['options']) || (isset($options['type']) && $options['type'] == self::UI_TYPE_SELECT)) {
            unset($options['type']);
            $options['data-toggle'] = 'select2';
            $options['class'] = self::UI_CLASS_SELECT;
        }

        if (isset($options['type']) && $options['type'] == self::UI_TYPE_SMART_SELECT) {
            unset($options['type']);
            $options['data-toggle'] = 'select2';
            $options['class'] = self::UI_CLASS_SMART_SELECT;
        }

        if (isset($options['type']) && $options['type'] == self::UI_TYPE_TIME) {
            $options['type'] = self::UI_TYPE_TEXT;
            $options['class'] = self::UI_CLASS_CLOCK_PICKER;
        }



        //fix for interval date
        if (isset($options['type']) && ($options['type'] == self::UI_TYPE_DATE || $options['type'] == self::UI_TYPE_DATE_TIME)) {
            $options['type'] = 'text';
            $options['class'] = self::UI_CLASS_DATE_RANGE;
        }

    
        unset($options['filter']);
        return parent::input($fieldName, $options);
    }

}
