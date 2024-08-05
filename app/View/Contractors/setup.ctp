<div class="card">
    <div class="card-body">
        <?PHP
        $this->Form->inputDefaults([
            'fieldset' => false,
            'format' => ['label', 'before', 'between', 'input', 'after', 'error'],
            'div' => [
                'class' => 'mb-3 col-sm-6'
            ],
            'before' => '<div class="form-group"><div class="input-group">',
            'after' => '</div></div>',
            'class' => 'form-control',

        ]);
        ?>
        <h4 class="header-title mb-3">Contractor</h4>

        <form novalidate id="setup-form"
            action="<?= Router::url(['controller' => 'Contractors', 'action' => 'setup', $id], true) ?>"
            method="POST" class="container" autocomplete="off" enctype="multipart/form-data">
            <input id="setup-form-submit" type="submit" hidden>
            <div id="custom-wizard">
                <ul class="nav nav-pills bg-light nav-justified form-wizard-header mb-4" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a href="#basictab1" data-toggle="tab" class="nav-link rounded-0 pt-2 pb-2 active"
                            aria-selected="true" role="tab">
                            <i class="mdi mdi-store me-1"></i>
                            <span class="d-none d-sm-inline">Contractor Company</span>
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a href="#basictab2" data-toggle="tab" class="nav-link rounded-0 pt-2 pb-2"
                            aria-selected="false" tabindex="-1" role="tab">
                            <i class="mdi mdi-face-profile me-1"></i>
                            <span class="d-none d-sm-inline">Contractor Account</span>
                        </a>
                    </li>
                </ul>

                <div class="tab-content b-0 mb-0 pt-0">
                    <div class="tab-pane active show" id="basictab1" role="tabpanel">
                        <div class="row">
                            <?PHP
                            $contractorFields = $this->requestAction(
                                [
                                    'controller' => 'Contractors',
                                    'action' => 'fields',
                                    $actionType
                                ],
                                [
                                    'return' => true,
                                    'autoRender' => false,
                                ]
                            );
                            unset($contractorFields['Contractor.status']);
                            echo $this->Form->inputs($contractorFields);
                            ?>
                        </div>
                    </div>

                    <div class="tab-pane" id="basictab2" role="tabpanel">
                        <div class="row">
                            <?PHP
                            $operatorFields = $this->requestAction(
                                [
                                    'controller' => 'Partners',
                                    'action' => 'fields',
                                    $actionType,
                                ],
                                [
                                    'return' => true,
                                    'autoRender' => false
                                ]
                            );
                            unset($operatorFields['Account.status']);
                            unset($operatorFields['Partner.contractor_id']);
                            unset($operatorFields['Account.account_type_id']);
                            $operatorFields['Account.password']['required'] = $actionType == 'add';
                            $operatorFields['Account.repeated_password']['required'] = $actionType == 'add';
                            echo $this->Form->inputs($operatorFields);
                            ?>
                        </div>
                    </div>


                    <ul class="pager list-inline wizard mb-0">
                        <li class="list-inline-item">
                            <a class="previous btn btn-warning">Previous</a>
                        </li>
                        <li class="list-inline-item float-end">
                            <a class="next btn btn-warning">Next</a>
                        </li>
                    </ul>

                </div> <!-- tab-content -->
            </div> <!-- end #basicwizard-->

        </form> <!-- end card-body -->
    </div>
</div>


<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function () {
        $(document).ready(function () {

            $('#custom-wizard').composableWizard();

        });
    });
</script>