<?php

$modelFields['Account.password']['required'] = false;
$modelFields['Account.repeated_password']['required'] = false;
$this->request->data['Account']['password'] = '';

?>



<?PHP if ($this->request->ext != 'ajax'): ?>
    <div class="card ">
        <div class="card-header card-header-transparent card-header-bordered font-weight-500 font-size-17">
            <?= 'Edit '.$singularDisplayName ?>
        </div>
        <div class="card-block p-4">
        <?PHP endif; ?>
            
        <?= $this->element('Form/form', ['modelFields' => $modelFields]) ?>
            
        <?PHP if ($this->request->ext != 'ajax'): ?>   
        </div>
    </div>
<?PHP endif; ?>