<div class="card">
    <div class="card-body">

        <h4 class="header-title mb-3">Subcontractor</h4>

        <div class="container" autocomplete="off">
            <div id="custom-wizard">
                <ul class="nav nav-pills bg-light nav-justified form-wizard-header mb-4" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a href="#basictab1" data-toggle="tab" class="nav-link rounded-0 pt-2 pb-2 active"
                            aria-selected="true" role="tab">
                            <i class="mdi mdi-store me-1"></i>
                            <span class="d-none d-sm-inline">Business</span>
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a href="#basictab2" data-toggle="tab" class="nav-link rounded-0 pt-2 pb-2"
                            aria-selected="false" tabindex="-1" role="tab">
                            <i class="mdi mdi-face-profile me-1"></i>
                            <span class="d-none d-sm-inline">Account</span>
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a href="#basictab3" data-toggle="tab" class="nav-link rounded-0 pt-2 pb-2"
                            aria-selected="false" tabindex="-1" role="tab">
                            <i class="mdi mdi-checkbox-marked-circle-outline me-1"></i>
                            <span class="d-none d-sm-inline">License</span>
                        </a>
                    </li>
                </ul>

                <div class="tab-content b-0 mb-0 pt-0">
                    <div class="tab-pane active show" id="basictab1" role="tabpanel">
                        <?PHP
                        echo $this->requestAction(
                            [
                                'controller' => 'Subcontractors',
                                'action' => 'add.action'
                            ],
                            ['return']
                        );
                        ?>
                    </div>

                    <div class="tab-pane" id="basictab2" role="tabpanel">
                        <?PHP
                        echo $this->requestAction(
                            [
                                'controller' => 'Operators',
                                'action' => 'add.action'
                            ],
                            ['return']
                        );
                        ?>
                    </div>

                    <div class="tab-pane" id="basictab3" role="tabpanel">
                        <?PHP
                        echo $this->requestAction(
                            [
                                'controller' => 'Licenses',
                                'action' => 'add.action'
                            ],
                            ['return']
                        );
                        ?>
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
            </di>

        </div> <!-- end card-body -->
    </div>


    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function () {

            $(document).ready(function () {
                $('#custom-wizard').bootstrapWizard({
                    'tabClass': 'nav nav-pills',
                    'nextSelector': '.next',
                    'previousSelector': '.previous'
                });

                $('button, a, input').filter(function () {
                    return $(this).text() === 'Cancel' || $(this).text() === 'Save' || $(this).val() === 'Save';
                }).remove();

            });
        });
    </script>