<?PHP if ($this->request->ext != 'ajax'): ?>
    <div class="card small-padding col-sm-12" style="padding-left: 0px; padding-right: 0px; padding-bottom: 0px;">
        <div class="card-header  card-header-bordered font-weight-500 font-size-17" style="background-color: #ffffff; color: #333; font-weight: bold;">
             <?= $this->fetch('title') ?> 
        </div>
        <div class="card-block" style="padding: 0px!important;">
        <?PHP endif; ?>

        <?= $this->element('Table/table') ?>

        <?PHP if ($this->request->ext != 'ajax'): ?>   
        </div>
    </div>
<?PHP endif; ?>