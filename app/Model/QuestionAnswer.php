<?php

App::uses('ImplementableModel', 'Implementable.Model');

class QuestionAnswer extends ImplementableModel
{

    public $singularDisplayName = 'Question Answer';
    public $pluralDisplayName = 'Question Answers';
    public $displayField = 'id';
    public $fields = [
        [
            'fieldKey' => 'id',
            'label' => FALSE,
            'type' => 'hidden',
            'showIn' => ['edit'],
        ],
        'order_id' => [
            'fieldKey' => 'order_id',
            'label' => 'Order',
            'type' => InputType::SELECT,
            'div' => InputDiv::COL_SM_12,
            'showIn' => TRUE,
            // 'bindValue' => 'Subcontractor.name',
            'options' => [
                '' => 'None'
            ],
            'filter' => TRUE
        ],
        'question_id' => [
            'fieldKey' => 'question_id',
            'label' => 'Question',
            'type' => InputType::SELECT,
            'div' => InputDiv::COL_SM_12,
            'showIn' => TRUE,
            // 'bindValue' => 'Subcontractor.name',
            'options' => [
                '' => 'None'
            ],
            'filter' => TRUE
        ],
    ];
    public $validate = [
        'order_id' => [
            'rule' => 'notBlank',
            'required' => true,
            'message' => 'The field related question is required'
        ],
        'question_id' => [
            'rule' => 'notBlank',
            'required' => true,
            'message' => 'The field question is required'
        ]
    ];
    public $belongsTo = [
        'Question' => [
            'className' => 'Question',
            'foreignKey' => 'question_id',
            'dependent' => false
        ],
        'Order' => [
            'className' => 'Order',
            'foreignKey' => 'order_id',
            'dependent' => true
        ]
    ];


    public function afterValidate()
    {
        parent::afterValidate();
        $this->prepareFileAnswers($this->data);
    }


    protected function prepareFileAnswers($data)
    {
        $this->Question->recursive = -1;
        $question = $this->Question->findById($data['QuestionAnswer']['question_id']);

        if (
            $question['Question']['type'] == 'file'
            && empty($data['QuestionAnswer']['value'])
            && is_array($data['QuestionAnswer']['value']
                && !empty($data['QuestionAnswer']['value']['name']))
        ) {
            $fileprefix = uniqid();
            $simpledir = Router::url(['controller' => '/'], true) . '/files/' . $fileprefix . '/';
            $folder_dir = WWW_ROOT . '/files/' . $fileprefix . '/';

            $ext = explode('.', $data['QuestionAnswer']['value']['name']);
            $new_name = uniqid() . '.' . end($ext);

            new Folder($folder_dir, true);

            $filename = $folder_dir . $fileprefix . $new_name;
            move_uploaded_file($data['QuestionAnswer']['value']['tmp_name'], $filename);

            $data['QuestionAnswer']['value'] = $simpledir . $fileprefix . $new_name;
        }
    }

}
