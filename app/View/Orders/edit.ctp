<div class="card ">
    <div class="card-header card-header-transparent card-header-bordered font-weight-500 font-size-17">
    <a href="javascript:window.history.back();">
            <i class="fe-arrow-left font-size-22" style="color:black"></i>
            </a>
        <?= 'Edit ' . $singularDisplayName . ' #' . $this->request->data['Order']['name'] ?>
    </div>
    <div class="card-block p-4">

        <?=
            $this->Form->create($modelName, [
                'enctype' => 'multipart/form-data',
                'class' => isset($formClass) ? $formClass : '',
                'inputDefaults' => [
                    'fieldset' => false,
                    'format' => ['label', 'before', 'between', 'input', 'after', 'error'],
                    'div' => [
                        'class' => 'mb-3 col-sm-6'
                    ],
                    'before' => '<div class="form-group"><div class="input-group">',
                    'after' => '</div></div>',
                    'class' => 'form-control',
                ]
            ])
            ?>

        <div class="row">
            <?PHP

            echo $this->Form->hidden('Order.id', [
                'default' => CakeText::uuid()
            ]);
            foreach ($modelFields as $key => $options):
                echo $this->Form->input($key, $options);
            endforeach;

            ?>
            <div id="survey-container" class="row col-sm-12">
                <?PHP
                $this->requestAction(
                    [
                        'controller' => 'Surveys',
                        'action' => 'survey',
                        !empty($this->request->data['Order']['survey_id']) ? $this->request->data['Order']['survey_id'] : 'EMPTY' . '.action'
                    ],
                    ['return']
                );
                ?>
            </div>
            <div class="col-sm-5 offset-md-5 d-flex align-items-center gap-1 mt-1" style="line-height:86px">
                <?php
                echo $this->Html->link('Cancel', 'javascript:void(0); window.history.back();', array('class' => 'btn btn-warning waves-effect waves-light btn-block'));
                echo $this->Form->submit('Save', ['class' => 'btn btn-warning waves-effect waves-light btn-block']);
                ?>
            </div>
            <?= $this->Form->end() ?>

        </div>


        <script type="text/javascript">
            document.addEventListener('DOMContentLoaded', function () {
                $('#OrderSurveyId').change(function () {
                    let selectedName = $(this).find('option:selected').text();
                    if (selectedName.trim() == 'None' || selectedName.trim().length == 0) {
                        selectedName = 'EMPTY'
                    }
                    let urlAction = "<?= Router::url([
                        'controller' => 'Surveys',
                        'action' => 'survey',
                    ]) ?>/" + selectedName +  "/<?=  $this->request->data['Order']['id'] ?> .action";

                    $.ajax({
                        url: urlAction,
                        type: 'POST',
                        data: {},
                        success: function (response) {
                            //$('#OrderSurveyId').parent().parent().parent().after('<div class="row col-sm-12">' + response + "</div>");
                            $("#survey-container").html(response);
                        }
                    });
                });
            })
        </script>