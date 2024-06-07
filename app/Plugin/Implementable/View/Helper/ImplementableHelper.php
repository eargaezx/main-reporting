<?PHP

App::uses('FormHelper', 'View/Helper');

class ImplementableHelper extends FormHelper {

    protected $view = null;

    public function __construct(View $view, $settings = array()) {
        $view->loadHelper('Implementable.UIForm');
        //$view->loadHelper('Implementable.UITable');
        $this->view = $view;
        parent::__construct($view, $settings);
    }



}
