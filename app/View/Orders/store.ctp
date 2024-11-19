<div class="card">
    <div class="card-body">
        <h3>Import</h3>
        <div class="container" style="margin-top: -45px;;">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <!-- Stepper Navigation (Visual Only) -->
                    <ul class="stepper-horizontal">
                        <li class="step active">
                            <span class="step-number">
                                <i class="mdi mdi-upload"></i>
                            </span>
                            <span class="step-title">Import</span>
                        </li>
                        <li class="step active">
                            <span class="step-number">
                                <i class="mdi mdi-file-find"></i>
                            </span>
                            <span class="step-title">Review</span>
                        </li>
                        <li class="step">
                            <span class="step-number">
                                <i class="mdi mdi-check"></i>
                            </span>
                            <span class="step-title">Done</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 mt-3" style="display: flex;">
                <div class="avatar-sm bg-warning rounded-circle"
                    style="background-color: #f67272AA!important; width: 1.8rem;  height: 1.8rem;">
                </div>
                <span class="p-1"> Invalid address</span>

                <div class="avatar-sm bg-warning rounded-circle"
                    style="background-color: #CCCCCCAA!important; width: 1.8rem;  height: 1.8rem;">
                </div>
                <span class="p-1"> Unassigned</span>
            </div>


            <?= $this->Form->create('Upload', ['class' => 'row', '' => 'POST', 'type' => 'file']) ?>

            <div class="container mt-4">
                <div style="height: 480px; overflow-y: auto;" class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th
                                    style="position: sticky; top: 0; background-color: #f6bb72!important; z-index: 1; color:#fff">
                                    #
                                </th>
                                <th
                                    style="position: sticky; top: 0; background-color: #f6bb72!important; z-index: 1; color:#fff">
                                    Import
                                </th>
                                <?PHP foreach ($importableFields as $key => $settings): ?>
                                    <th
                                        style="position: sticky; top: 0; background-color: #f6bb72!important; z-index: 1; color:#fff">
                                        <?= $settings['label'] ?>
                                    </th>
                                <?PHP endforeach; ?>
                            </tr>
                        </thead>
                        <tbody>


                            <?PHP foreach ($data as $index => $row): ?>

                                <?PHP
                                $style = '';
                                if (!empty($row['isUnassigned'])) {
                                    $style = 'background-color: #CCCCCCAA!important';
                                }

                                if (empty($row['isValidAddress'])) {
                                    $style = 'background-color: #f67272AA!important; color:#fff';
                                }
                                ?>

                                <tr>
                                    <td style="<?= $style ?>">
                                        <?= $index + 1 ?>
                                    </td>
                                    <td style="<?= $style ?>; padding-left: 10px!important">
                                        <input 
                                        style="width: 1.4rem; height: 1.4rem;"
                                        class="form-check-input rounded-circle"
                                        type="checkbox"
                                        value="" 
                                        checked=""
                                        name="data[Orders][<?= $index ?>][import]">
                                    </td>
                                    <?PHP foreach ($importableFields as $fieldKey => $settings): ?>
                                        <td style="<?= $style ?>">
                                            <?php
                                            if (!empty($row[$settings['fieldKey']])) {
                                                if (!in_array($settings['fieldKey'], ['contractor_id', 'operator_id', 'subcontractor_id'])) {
                                                    echo $this->Form->hidden("Orders.$index." . $settings['fieldKey'], array('label' => false, 'value' => $row[$settings['fieldKey']]));
                                                    echo $row[$settings['fieldKey']];
                                                } else {
                                                    $modelFields['Order.' . $settings['fieldKey']]['label'] = false;
                                                    $modelFields['Order.' . $settings['fieldKey']]['div'] = false;
                                                    $modelFields['Order.' . $settings['fieldKey']]['default'] = $row[$settings['fieldKey']];
                                                    echo $this->Form->input("Orders.$index." . $settings['fieldKey'], $modelFields['Order.' . $settings['fieldKey']]);
                                                }
                                            } else if (in_array($settings['fieldKey'], ['contractor_id', 'operator_id', 'subcontractor_id'])) {
                                                $modelFields['Order.' . $settings['fieldKey']]['label'] = false;
                                                $modelFields['Order.' . $settings['fieldKey']]['div'] = false;
                                                $modelFields['Order.' . $settings['fieldKey']]['default'] = $row[$settings['fieldKey']];
                                                echo $this->Form->input("Orders.$index." . $settings['fieldKey'], $modelFields['Order.' . $settings['fieldKey']]);
                                            } else {

                                            }
                                            ?>
                                        </td>
                                    <?PHP endforeach; ?>
                                </tr>
                            <?PHP endforeach; ?>



                            <!-- Añade más filas aquí -->
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col-md-5 offset-md-5 d-flex align-items-center gap-1 mt-3">
                <?php
                echo $this->Html->link('Back', 'javascript:void(0); window.history.back();', array('class' => 'btn btn-warning waves-effect waves-light btn-block'));
                echo $this->Form->submit('Import', ['class' => 'btn btn-warning waves-effect waves-light btn-block']);
                ?>
            </div>
        </div>
    </div>
</div>


<style type="text/css">
    .stepper-horizontal {
        list-style: none;
        display: flex;
        justify-content: space-between;
        padding: 0;
        margin: 0;
    }

    .step {
        text-align: center;
        position: relative;
        flex: 1;
        bottom: -16px;
    }

    .step::before {
        content: '';
        position: absolute;
        width: 100%;
        height: 2px;
        background-color: #ddd;
        top: 50%;
        left: -50%;
        z-index: 0;
    }

    .step:first-child::before {
        display: none;
    }

    .step-number {
        display: inline-block;
        width: 40px;
        height: 40px;
        line-height: 40px;
        border-radius: 50%;
        background-color: #ddd;
        color: #fff;
        font-weight: bold;
        position: relative;
        z-index: 1;
        bottom: -16px;
        color: #333;
    }

    .step-title {
        display: block;
        margin-top: 18px;
        font-size: 14px;
        color: #333;
    }

    .step-subtitle {
        display: block;
        margin-top: 18px;
        font-size: 12px;
        color: gray;
    }

    .step.active .step-number {
        background-color: #f6bb72;
        /* Color del paso activo */
        color: #fff;
    }

    .step.completed .step-number {
        background-color: #28a745;
        /* Color del paso completado */
    }

    .step.completed .step-title {
        color: #f6bb72;
    }

    .step.active .step-title {
        font-weight: bold;
        color: #f6bb72;
    }

    .step.active::before {
        content: '';
        position: absolute;
        width: 100%;
        height: 2px;
        background-color: #f6bb72;
        top: 50%;
        left: -50%;
        z-index: 0;
    }

    .table>:not(caption)>*>* {
        padding: .3rem .3rem !important;
        vertical-align: middle;
        text-align: center;
    }

    .select2-container {
        margin-right: 48px !important;
    }

    .bg-caution {
        background-color: #f67272AA !important;
    }
</style>