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


