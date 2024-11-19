<?php if ($this->request->ext != 'ajax' && AuthComponent::user() && AuthComponent::user('AccountType.name') == 'Subcontractor'): ?>
    <h5 class="mb-3 text-uppercase bg-light p-2" style="width: fit-content;">
        <span class="btn btn-warning" style="pointer-events: none;">
            <i class="fe-award"></i>
            License <?= $licensing['License']['name'] ?>
        </span>

        <?php if ((new DateTime($licensing['SubcontractorLicense']['end_date'])) <= (new DateTime())): ?>
            <span class="btn btn-warning" style="pointer-events: none;">
                Expired sice <?= (new DateTime($licensing['SubcontractorLicense']['end_date']))->format('d/m/Y'); ?>
            </span>

            <span style="font-size: 12px; color:#333; text-transform: capitalize;">
                Your license has expired, do you want to renew it?
                <a type="button" class="btn btn-sm btn-warning rounded-pill waves-effect waves-light" target="_blank"
                    href="<?= $licensing['License']['shoping'] ?>">
                    BUY<span class="btn-label-right"><i class="mdi mdi-cart"></i></span>
                </a>
            </span>
        <?php else: ?>
            <span class="btn btn-warning" style="pointer-events: none;">
                Expires <?= (new DateTime($licensing['SubcontractorLicense']['end_date']))->format('d/m/Y'); ?>
            </span>

            <span class="btn btn-warning" style="pointer-events: none;">
                <?= $licensing['SubcontractorLicense']['current_users'] . ' of ' . $licensing['SubcontractorLicense']['max_users'] ?>
                allow active users
            </span>
        <?php endif; ?>


    </h5>
<?php endif; ?>