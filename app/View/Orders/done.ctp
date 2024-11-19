<div class="card">
    <div class="card-body">
        <h3>Import</h3>
        <div class="container">
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
                        <li class="step active">
                            <span class="step-number">
                                <i class="mdi mdi-check"></i>
                            </span>
                            <span class="step-title">Done</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="row mt-5 text-center text-warning">
            <h2 style="color: #ffb355!important"> <?= $count ?> </h2>
            <h3 style="color: #ffb355!important"> Orders has been imported</h3>
            <div class="col-md-5 offset-md-5 d-flex align-items-center gap-1 mt-3">
                <?php
                echo $this->Html->link('DONE', 'index', array('class' => 'btn btn-warning waves-effect waves-light btn-block mt-2', 'style' => 'width: 210px'));
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
</style>