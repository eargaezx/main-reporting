<?PHP if ($this->request->ext != 'ajax'): ?>
    <div class="card">
        <div class="card-header card-header-transparent card-header-bordered font-weight-500 font-size-17">
            
            <a href="javascript:window.history.back();">
            <i class="fe-arrow-left font-size-22" style="color:black"></i>
            </a>
            <?= $this->fetch('title') ?>
        </div>
        <div class="card-block px-1">
        <?PHP endif; ?>

        <?= $this->element('Form/read', [
            'formClass' => 'readonly'
        ]) ?>

        <?PHP if ($this->request->ext != 'ajax'): ?>
        </div>
    </div>
<?PHP endif; ?>