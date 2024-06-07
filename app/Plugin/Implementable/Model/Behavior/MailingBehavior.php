<?php
App::uses('CakeEmail', 'Network/Email');
class MailingBehavior extends ModelBehavior {

    public function setup(\Model $model, $config = array()) {
        parent::setup($model, $config);


    }

    public function sendEmail(\Model $model, $settings){
        $defaults= [
            'config' => 'default',
            'to' => [],
            'subject' => 'Sin asunto',
            'content' => '',
            'emailFormat' => 'text',
            'template' => 'default',
            'viewVars' => []
        ];
        
        $settings = array_replace_recursive($defaults, $settings);
        
        $Email = new CakeEmail($settings['config']);
        $Email->to($settings['to']);
        $Email->subject($settings['subject']);
        $Email->emailFormat($settings['emailFormat']);
        if($settings['emailFormat'] == 'html'){
            $Email->template($settings['template']);
            $Email->viewVars($settings['viewVars']);
        }
        $Email->send($settings['content']);
        
    }

}
