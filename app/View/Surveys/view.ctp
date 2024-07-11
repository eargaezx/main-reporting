<div class="card">
    <div class="card-header card-header-transparent card-header-bordered font-weight-500 font-size-17">
        <?= 'Detalles de ' . $singularDisplayName ?>
    </div>
    <div class="card-block px-1">

        <?= $this->element('Form/read', [
            'formClass' => 'readonly'
        ]) ?>

    </div>
</div>


<h2>Questions</h2>
<div id="questions-container">
    <?php
    $questions = $this->requestAction(
        [
            'controller' => 'Questions',
            'action' => 'index' . '.action'
        ],
        [
            'return',
            'data' => [
                'named' => [
                    'filter' => [
                        'survey_id' => $data['Survey']['id'] 
                    ]
                ],
                'Question' =>  [
                    'survey_id' => $data['Survey']['id'] 
                ],
            ]
        ]
    );
    echo $questions;
    ?>
</div>