<div class="card">
    <div class="card-header card-header-transparent card-header-bordered font-weight-500 font-size-17">
    <a href="javascript:window.history.back();">
            <i class="fe-arrow-left font-size-22" style="color:black"></i>
            </a>
        <?= $singularDisplayName  . 'Detail ' ?>
    </div>
    <div class="card-block px-1">

        <?= $this->element('Form/read', [
            'formClass' => 'readonly'
        ]) ?>

    </div>
</div>

