<!-- ========== Topbar Start ========== -->
<div class="navbar-custom">
    <div class="topbar ">
        <div class="topbar-menu d-flex align-items-center gap-1">

            <!-- Topbar Brand Logo -->
            <div class="logo-box">
                <!-- Brand Logo Light -->
                <a href="index.html" class="logo-light">
                    <img src="<?= Router::url('/', true); ?>/img/ic_launcher.png" alt="logo" class="logo-lg">
                    <img src="<?= Router::url('/', true); ?>/img/ic_launcher.png" alt="small logo" class="logo-sm">
                </a>
            </div>

            <div class=" d-none d-xl-block">
                <a class="nav-link  waves-effect waves-light"
                    href="<?php echo $this->Html->url(array('controller' => 'Pages', 'action' => 'display')); ?>"
                    role="button" aria-haspopup="false">
                    <i class="mdi mdi-monitor-dashboard"></i>&nbsp;
                    Dashboard
                </a>
            </div>

            <!-- Dropdown Menu -->
            <?php if (AuthComponent::user('AccountType.name') == 'Systems'): ?>

                <!-- Dropdown Menu -->
                <div class=" d-none d-xl-block">
                    <a class="nav-link  waves-effect waves-light"
                        href="<?php echo $this->Html->url(array('controller' => 'Licenses', 'action' => 'index')); ?>"
                        role="button" aria-haspopup="false">
                        <i class="mdi mdi-key-chain"></i>
                        Licenses
                    </a>
                </div>


                <div class=" d-none d-xl-block">
                    <a class="nav-link  waves-effect waves-light"
                        href="<?php echo $this->Html->url(array('controller' => 'Contractors', 'action' => 'index')); ?>"
                        role="button" aria-haspopup="false">
                        <i class="mdi mdi-home"></i>
                        Contractors
                    </a>
                </div>

                <div class=" d-none d-xl-block">
                    <a class="nav-link  waves-effect waves-light"
                        href="<?php echo $this->Html->url(array('controller' => 'Subcontractors', 'action' => 'index')); ?>"
                        role="button" aria-haspopup="false">
                        <i class="mdi mdi-office-building"></i>
                        Subcontractors
                    </a>
                </div>

            <?php endif; ?>

            <?php if (in_array(AuthComponent::user('AccountType.name'), ['Contractor'])): ?>
                <div class=" d-none d-xl-block">
                    <a class="nav-link  waves-effect waves-light"
                        href="<?php echo $this->Html->url(array('controller' => 'Partnerships', 'action' => 'index')); ?>"
                        role="button" aria-haspopup="false">
                        <i class="mdi mdi-handshake"></i>
                        Partnerships
                    </a>
                </div>
            <?php endif; ?>

            <?php if (in_array(AuthComponent::user('AccountType.name'), ['Systems', 'Subcontractor'])): ?>
                <!-- Dropdown Menu -->
                <div class=" d-none d-xl-block">
                    <a class="nav-link  waves-effect waves-light"
                        href="<?php echo $this->Html->url(array('controller' => 'Operators', 'action' => 'index')); ?>"
                        role="button" aria-haspopup="false">
                        <i class="mdi mdi-account-multiple-outline"></i>&nbsp;
                        Technicians
                    </a>
                </div>
            <?php endif; ?>

            <!-- Dropdown Menu -->
            <div class=" d-none d-xl-block">
                <a class="nav-link  waves-effect waves-light"
                    href="<?php echo $this->Html->url(array('controller' => 'Orders', 'action' => 'index')); ?>">
                    <i class="mdi  mdi-clipboard-text"></i>&nbsp;
                    Orders
                </a>
            </div>

            <!-- Dropdown Menu -->
            <div class=" d-none d-xl-block">
                <a class="nav-link  waves-effect waves-light"
                    href="<?php echo $this->Html->url(array('controller' => 'Orders', 'action' => 'maps')); ?>">
                    <i class="mdi mdi-map"></i>&nbsp;
                    Map
                </a>
            </div>


            <!-- Mega Menu Dropdown -->

        </div>

        <ul class="topbar-menu d-flex  align-items-center">

            <!-- User Dropdown -->
            <li class="dropdown">
                <a class="nav-link dropdown-toggle nav-user me-0 waves-effect waves-light" data-bs-toggle="dropdown"
                    href="#" role="button" aria-haspopup="false" aria-expanded="false">

                    <i class="mdi mdi-chevron-down"></i>
                    <img src="<?= Router::url('/', true); ?>/assets/images/users/user-5.jpg" alt="user-image"
                        class="rounded-circle">
                    <span class="ms-1 d-none d-md-inline-block">
                        <strong>
                            <?= AuthComponent::user('AccountType.name') ?>
                        </strong> &nbsp
                        <br />
                        <?php
                        $name = 'SysAdmin';
                        switch (AuthComponent::user('AccountType.name')) {
                            case 'Contractor':
                                $name = AuthComponent::user('Partner.first_name') . ' '.AuthComponent::user('Partner.last_name'); ;
                                break;
                            case 'Subcontractor':
                                $name = AuthComponent::user('Operator.first_name') . ' '.AuthComponent::user('Operator.last_name'); 
                                break;
                            case 'Technician':
                                $name = AuthComponent::user('Operator.name');
                                break;
                        }
                        echo $name;
                        ?>
                        &nbsp
                        <br />
                        <?= AuthComponent::user('username') ?> &nbsp
                    </span>


                </a>
                <div class="dropdown-menu dropdown-menu-end profile-dropdown ">
                    <!-- item-->
                    <div class="dropdown-header noti-title">
                        <h6 class="text-overflow m-0">Welcome !</h6>
                    </div>


                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <i class="fe-settings"></i>
                        <span>Settings</span>
                    </a>

                    <div class="dropdown-divider"></div>

                    <!-- item-->
                    <a href="<?php echo $this->Html->url(array('controller' => 'Accounts', 'action' => 'logout')); ?>"
                        class="dropdown-item notify-item">
                        <i class="fe-log-out"></i>
                        <span>Logout</span>
                    </a>

                </div>
            </li>

        </ul>
    </div>
</div>
<!-- ========== Topbar End ========== -->