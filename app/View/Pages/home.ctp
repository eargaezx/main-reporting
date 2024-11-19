<?PHP
App::uses('CakeTime', 'Utility');
?>

<?= $this->element('License/dashboard_rule_license') ?>

<?php if (AuthComponent::user() && AuthComponent::user('AccountType.name') == 'Contractor'): ?>
    <div class="container">
        <div class="row">
            <div class="col-sm-12 text-center pt-3">
                <img src=" <?= $contractor['Contractor']['logo'] ?>">
                <h1>
                    <?= $contractor['Contractor']['name'] ?>
                </h1>
            </div>
        </div>
    </div>
<?php endif; ?>


<?php if (AuthComponent::user() && in_array(AuthComponent::user('AccountType.name'), ['Subcontractor', 'Supervisor'])): ?>

    <div class="container">

    <h2>Orders</h2>
        <div class="row">
            <div class="col-md-6 col-xl-3">
                <div class="card" id="tooltip-container">
                    <div class="card-body">
                        <i class="fa fa-info-circle text-muted float-end" data-bs-container="#tooltip-container"
                            data-bs-toggle="tooltip" data-bs-placement="bottom" aria-label="More Info"
                            data-bs-original-title="More Info"></i>
                        <h4 class="mt-0 font-16">Total Uncompleted</h4>
                        <h2 class="text-primary my-3 text-center">
                            <span data-plugin="counterup">
                                <?= $metrics['Order']['pending'] ?>
                            </span>
                        </h2>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3">
                <div class="card" id="tooltip-container1">
                    <div class="card-body">
                        <i class="fa fa-info-circle text-muted float-end" data-bs-container="#tooltip-container1"
                            data-bs-toggle="tooltip" data-bs-placement="bottom" aria-label="More Info"
                            data-bs-original-title="More Info"></i>
                        <h4 class="mt-0 font-16">Recently Added</h4>
                        <h2 class="text-primary my-3 text-center">
                            <span data-plugin="counterup">
                                <?= $metrics['Order']['recently'] ?>
                            </span>
                        </h2>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3">
                <div class="card" id="tooltip-container2">
                    <div class="card-body">
                        <i class="fa fa-info-circle text-muted float-end" data-bs-container="#tooltip-container2"
                            data-bs-toggle="tooltip" data-bs-placement="bottom" aria-label="More Info"
                            data-bs-original-title="More Info"></i>
                        <h4 class="mt-0 font-16">Total Unasigned</h4>
                        <h2 class="text-primary my-3 text-center">
                            <span data-plugin="counterup">
                                <?= $metrics['Order']['unasigned'] ?>
                            </span>
                        </h2>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3">
                <div class="card" id="tooltip-container3">
                    <div class="card-body">
                        <i class="fa fa-info-circle text-muted float-end" data-bs-container="#tooltip-container3"
                            data-bs-toggle="tooltip" data-bs-placement="bottom" aria-label="More Info"
                            data-bs-original-title="More Info"></i>
                        <h4 class="mt-0 font-16">Total Completed</h4>
                        <h2 class="text-primary my-3 text-center">
                            <span data-plugin="counterup">
                                <?= $metrics['Order']['completed'] ?>
                            </span>
                        </h2>
                    </div>
                </div>
            </div>
        </div>

        <h2>Licenses</h2>
        <div class="row">
            <?php foreach ($licenses as $license): ?>
                <div class="col-md-3">

                    <?php if (isset($licensing) && !empty($licensing['License']['id']) && $licensing['License']['id'] == $license['License']['id']): ?>
                        <div class="card card-pricing card-pricing-recommended">
                            <div class="card-body text-center">
                                <h1 class="card-pricing-plan-name fw-bold text-uppercase text-white">
                                    <?= $license['License']['name'] ?>
                                </h1>
                                <span class="card-pricing-icon text-white">
                                    <i class="fe-award"></i>
                                </span>
                                <h2 class="card-pricing-price text-white">
                                    <?= $license['License']['price'] == 0 ? 'FREE' : '$' . $license['License']['price'] ?>
                                    <span>/ <?= [
                                        3 => '3 DAYS',
                                        7 => 'WEEKLY',
                                        15 => 'BIWEEKLY',
                                        30 => 'MONTHLY',
                                        90 => 'QUARTERLY',
                                        365 => 'ANNUALLY'
                                    ][$license['License']['periodicity']] ?></span>
                                </h2>
                                <ul class="card-pricing-features">
                                    <li> <strong><?= $license['License']['users'] ?> USERS </strong> </li>
                                    <li>UNLIMITED GB Storage</li>
                                    <li>UNLIMITED ORDERS</li>
                                    <li>24x7 Support</li> 
                                </ul>
                                <h4 class="mt-4 mb-2 width-sm fw-bold text-uppercase text-white">
                                    Expires on:
                                    <?= (new DateTime($licensing['SubcontractorLicense']['end_date']))->format('d/m/Y'); ?>
                                    <br />
                                    Users:
                                    <?= $licensing['SubcontractorLicense']['current_users'] . '/' . $licensing['SubcontractorLicense']['max_users'] ?>
                                </h4>
                            </div>
                        </div> <!-- end Pricing_card -->

                    <?php else: ?>
                        <div class="card card-pricing">
                            <div class="card-body text-center">
                                <h1 class="card-pricing-plan-name fw-bold text-uppercase">
                                    <?= $license['License']['name'] ?>
                                </h1>
                                <span class="card-pricing-icon">
                                    <i class="fe-award"></i>
                                </span>
                                <h2 class="card-pricing-price">
                                    <?= $license['License']['price'] == 0 ? 'FREE' : '$' . $license['License']['price'] ?>
                                    <span>/ <?= [
                                        3 => '3 DAYS',
                                        7 => 'WEEKLY',
                                        15 => 'BIWEEKLY',
                                        30 => 'MONTHLY',
                                        90 => 'QUARTERLY',
                                        365 => 'ANNUALLY'
                                    ][$license['License']['periodicity']] ?></span>
                                </h2>
                                <ul class="card-pricing-features">
                                    <li> <strong><?= $license['License']['users'] ?> USERS </strong> </li>
                                    <li>UNLIMITED GB Storage</li>
                                    <li>UNLIMITED ORDERS</li>
                                    <li>24x7 Support</li>
                                </ul>
                                <a href="<?= $license['License']['shoping'] ?>" class="btn btn-light waves-effect mt-4 mb-2 width-sm">
                                    BUY
                                </a>
                            </div>
                        </div> <!-- end Pricing_card -->

                    <?php endif; ?>



                </div>
            <?php endforeach; ?>
        </div>

    </div>
<?php endif; ?>



<?php if (AuthComponent::user() && AuthComponent::user('AccountType.name') == 'Systems'): ?>
    <div class="row mt-3">
        <div class="col-md-6 col-xl-3">
            <div class="widget-rounded-circle card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="avatar-lg rounded-circle bg-soft-primary border-primary border">
                                <i class="fe-file font-22 avatar-title text-primary"></i>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-end">
                                <h3 class="text-dark mt-1"><span data-plugin="counterup">58</span></h3>
                                <p class="text-muted mb-1 text-truncate">Active licenses</p>
                            </div>
                        </div>
                    </div> <!-- end row-->
                </div>
            </div> <!-- end widget-rounded-circle-->
        </div> <!-- end col-->

        <div class="col-md-6 col-xl-3">
            <div class="widget-rounded-circle card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="avatar-lg rounded-circle bg-soft-success border-success border">
                                <i class="fe-users font-22 avatar-title text-success"></i>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-end">
                                <h3 class="text-dark mt-1"><span data-plugin="counterup">127</span></h3>
                                <p class="text-muted mb-1 text-truncate">Active users</p>
                            </div>
                        </div>
                    </div> <!-- end row-->
                </div>
            </div> <!-- end widget-rounded-circle-->
        </div> <!-- end col-->



        <div class="col-md-6 col-xl-3">
            <div class="widget-rounded-circle card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="avatar-lg rounded-circle bg-soft-warning border-warning border">
                                <i class="fe-dollar-sign font-22 avatar-title text-warning"></i>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-end">
                                <h3 class="text-dark mt-1">$<span data-plugin="counterup">2</span>k</h3>
                                <p class="text-muted mb-1 text-truncate">Income this month</p>
                            </div>
                        </div>
                    </div> <!-- end row-->
                </div>
            </div> <!-- end widget-rounded-circle-->
        </div> <!-- end col-->

        <div class="col-md-6 col-xl-3">
            <div class="widget-rounded-circle card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="avatar-lg rounded-circle bg-soft-info border-info border">
                                <i class="fe-bar-chart-line- font-22 avatar-title text-info"></i>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-end">
                                <h3 class="text-dark mt-1"><span data-plugin="counterup">0.58</span>%</h3>
                                <p class="text-muted mb-1 text-truncate">Growth</p>
                            </div>
                        </div>
                    </div> <!-- end row-->
                </div>
            </div> <!-- end widget-rounded-circle-->
        </div> <!-- end col-->
    </div>

<?php endif; ?>