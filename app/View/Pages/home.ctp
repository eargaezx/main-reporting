<?PHP
App::uses('CakeTime', 'Utility');

if (AuthComponent::user() && AuthComponent::user('AccountType.name') != 'Subcontractor')
    return;
?>

<div class="container">
    <div class="row">
        <?php if (isset($licensing) && !empty($licensing['License']['id'])): ?>
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">

                        <h4 class="header-title mb-0">
                            MY <strong> <?= $licensing['License']['name'] ?> </strong> LICENSE
                        </h4>

                        <div class="widget-chart text-center" dir="ltr">

                            <div id="total-revenue2" class="mt-0" data-colors="#f1556c" style="min-height: 220.7px;">

                            </div>

                            <h5 class="text-muted mt-0">Total Users</h5>
                            <h2><?= $licensing['SubcontractorLicense']['current_users'] ?> /
                                <?= $licensing['SubcontractorLicense']['max_users'] ?></h2>

                            <p class="text-muted w-75 mx-auto sp-line-2">
                                This is the remaining users and day of you current active license, if you have any question,
                                contact to support team.
                            </p>

                            <div class="row mt-3">
                                <div class="col-4">
                                    <p class="text-muted font-15 mb-1 text-truncate">Target</p>
                                    <h4><i class="fe-arrow-down text-danger me-1"></i>$7.8k</h4>
                                </div>
                                <div class="col-4">
                                    <p class="text-muted font-15 mb-1 text-truncate">Last week</p>
                                    <h4><i class="fe-arrow-up text-success me-1"></i>$1.4k</h4>
                                </div>
                                <div class="col-4">
                                    <p class="text-muted font-15 mb-1 text-truncate">Last Month</p>
                                    <h4><i class="fe-arrow-down text-danger me-1"></i>$15k</h4>
                                </div>
                            </div>

                        </div>
                    </div>
                </div> <!-- end card -->
            </div>
        <?php endif; ?>
        <?php foreach (array_reverse($licenses) as $license): ?>
            <div class="col-md-3">
                <div class="card card-pricing card-pricing-recommended">
                    <div class="card-body text-center">
                        <h1 class="card-pricing-plan-name fw-bold text-uppercase text-white">
                            <?= $license['License']['name'] ?>
                        </h1>
                        <span class="card-pricing-icon text-white">
                            <i class="fe-award"></i>
                        </span>
                        <h2 class="card-pricing-price text-white">
                            <?= $license['License']['price'] == 0 ? 'FREE' : '$' . $license['License']['price'] ?> <span>/ <?= [
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
                        <button class="btn btn-light waves-effect mt-4 mb-2 width-sm">
                            BUY
                        </button>
                    </div>
                </div> <!-- end Pricing_card -->
            </div>
        <?php endforeach; ?>

    </div>
</div>

<script>
    <?php if (isset($licensing) && !empty($licensing['License']['id'])): ?>
        document.addEventListener('DOMContentLoaded', function () {
            var colors = ["#f1556c"],
                dataColors = $("#total-revenue2").data("colors");
            dataColors && (colors = dataColors.split(","));
            var options = {
                series: [ <?= ( max(0, ((strtotime($licensing['SubcontractorLicense']['end_date']) - strtotime(date('Y-m-d')))  / (60 * 60 * 24) )) / $licensing['License']['periodicity'] ) * 100 ?> ],
                chart: {
                    height: 242,
                    type: "radialBar"
                },
                plotOptions: {
                    radialBar: {
                        hollow: {
                            size: "<?= ( max(0, ((strtotime($licensing['SubcontractorLicense']['end_date']) - strtotime(date('Y-m-d')))  / (60 * 60 * 24) )) / $licensing['License']['periodicity'] ) * 100 ?>%"
                        }
                    }
                },
                colors: colors,
                labels: ["DAYS"]
            },
                chart = new ApexCharts(document.querySelector("#total-revenue2"), options);
            chart.render();
        });

    <?php endif; ?>

</script>