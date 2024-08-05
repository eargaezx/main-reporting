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


<h2>Operators</h2>
<div id="questions-container">
    <?php
    $questions = $this->requestAction(
        [
            'controller' => 'Operators',
            'action' => 'index' . '.action'
        ],
        [
            'return',
            'data' => [
                'named' => [
                    'filter' => [
                        'subcontractor_id' => $data['Subcontractor']['id'] 
                    ]
                ],
                'Operator' =>  [
                    'subcontractor_id' => $data['Subcontractor']['id'] 
                ],
            ]
        ]
    );
    echo $questions;
    ?>
</div>