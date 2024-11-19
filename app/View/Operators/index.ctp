<div class="card small-padding col-sm-12" style="padding-left: 0px; padding-right: 0px; padding-bottom: 0px;">
    <div class="card-header   d-flex justify-content-between align-items-center   card-header-bordered font-weight-500 font-size-17"
        style="background-color: #ffffff; color: #333; font-weight: bold;">
        <h class="card-title mb-0">
            <?= $pluralDisplayName . ' List' ?>
        </h>


    </div>
    <div class="card-block" style="padding: 0px!important;">
        <?= $this->element('License/operator_rule_license') ?>
        <?= $this->element('Table/table') ?>
    </div>
</div>