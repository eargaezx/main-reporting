<div class="container-fluid">
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h3 class="page-title">Pricing</h3>
            </div>
        </div>
    </div>
    <!-- end page title -->


    <div class="row justify-content-center">
        <div class="col-xl-10">

            <!-- Pricing Title-->
            <div class="text-center">
                <h3 class="mb-2">Our <b>Plans</b></h3>
                <p class="text-muted w-50 m-auto">
                    We have plans and prices that fit your business perfectly. Make your client drops a success with our
                    products.
                </p>
            </div>

            <!-- Plans -->
            <div class="row my-3">
                <?php foreach ($licenses as $license): ?>
                    <div class="col">

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

                                    <?php
                                    // Supongamos que la fecha está almacenada en la variable $endDate
                                    $endDate = $licensing['SubcontractorLicense']['end_date']; // "12-12-2024"
                            
                                    // Convertir la fecha a formato DateTime
                                    $endDateTime = DateTime::createFromFormat('Y-m-d', $endDate);
                                    $currentDateTime = new DateTime(); // Fecha y hora actual
                                    $expired = $endDateTime < $currentDateTime;
                                    ?>
                                    <?php if (!$expired): ?>
                                        <h4 class="mt-4 mb-2 width-sm fw-bold text-uppercase text-white">
                                            Expires on:
                                            <?= (new DateTime($licensing['SubcontractorLicense']['end_date']))->format('d/m/Y'); ?>
                                            <br />
                                            Users:
                                            <?= $licensing['SubcontractorLicense']['current_users'] . '/' . $licensing['SubcontractorLicense']['max_users'] ?>
                                        </h4>
                                    <?php else: ?>
                                        <a href="<?= $license['License']['shoping'] ?>"
                                            class="btn btn-light waves-effect mt-4 mb-2 width-sm">
                                            RENEW
                                        </a>
                                    <?php endif; ?>
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
                                    <a href="<?= $license['License']['shoping'] ?>"
                                        class="btn btn-light waves-effect mt-4 mb-2 width-sm">
                                        BUY
                                    </a>
                                </div>
                            </div> <!-- end Pricing_card -->

                        <?php endif; ?>



                    </div>
                <?php endforeach; ?>
            </div>
            <!-- end row -->

        </div> <!-- end col-->
    </div>
    <!-- end row -->
</div>