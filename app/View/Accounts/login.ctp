<div class="account-pages " >
    <div class="container pt-5 pb-2">
        <div class="row justify-content-center no-gutters">
            <div class="col-md-3 col-lg-3 col-xl-3 pb-3">
                <div class="card bg-pattern" style="height: 100%!important;">
                    <div class="card-body text-center p-4 justify-content-center align-self-center">
                        <img src="<?= Router::url(['controller' => '/'], true) ?>/img/ic_launcher.png" width="200"
                            style="margin-top: 75px;">
                        <br><h3 class="mt-3">Main Report</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-8 col-lg-6 col-xl-5">
                <div class="card bg-pattern">

                    <div class="card-body p-4">

                        <div class="text-center w-75 m-auto">
                            <div class="auth-logo">
                                <a href="#" class="logo logo-dark text-center">
                                    <i class="mdi mdi-account-circle logo-lg"
                                        style="font-size: 80px; color: #eb9534!important;"></i>
                                </a>
                            </div>
                        </div>

                        <?=
                            $this->Form->create('Account', [
                                'class' => '',
                                'method' => 'POST',
                                'url' => Router::url([
                                    'controller' => 'Accounts',
                                    'action' => 'login'
                                ], true),
                                'inputDefaults' => [
                                    'label' => false,
                                    'fieldset' => false,
                                    'format' => ['label', 'before', 'between', 'input', 'after', 'error'],
                                    'div' => [
                                        'class' => 'col-sm-12 no-padding'
                                    ],
                                    'before' => '<div class="form-group"><div class="input-group">',
                                    'after' => '</div></div>',
                                    'class' => 'form-control form-control-lg',
                                ]
                            ])
                            ?>
                        <div class="">
                            <div class="panel-heading">
                                <h5>
                                    <strong class="">Ingresar</strong>
                                </h5>
                                <!--<span class="text-size-18">
                                    Content Manager System
                                </span>-->

                                <br>
                                <?= $this->Session->flash('login') ?>
                            </div>


                            <div class="">
                                <?=
                                    $this->Form->input('username', [
                                        'placeholder' => 'Correo electrónico',
                                        'type' => 'email'
                                    ])
                                    ?>
                                <?=
                                    $this->Form->input('password', [
                                        'placeholder' => 'Contraseña',
                                        'type' => 'password'
                                    ])
                                    ?>


                                <div class="form-group text-center mt-4">
                                    <div class="col-12">
                                        <button class="btn btn-primary waves-effect waves-light col-sm-6" type="submit"
                                            style="background-color: #eb9534!important">
                                            Ingresar
                                        </button>
                                    </div>
                                </div>

                                <div class="form-group m-t-30 m-b-0">
                                    <div class="col-12">
                                        <!--<a href="page-recoverpw.html" class="text-dark">
                                            <i class="fa fa-lock m-r-5"></i>
                                            
                                        </a>-->
                                    </div>
                                </div>

                            </div>
                        </div>

                        <!--<div class="row">
                            <div class="col-sm-12 text-center">
                                <p>Don't have an account? <a href="page-register.html" class="text-primary m-l-5"><b>Sign Up</b></a>
                                </p>
                    
                            </div>
                        </div>-->
                        <?= $this->Form->end() ?>


                    </div> <!-- end card-body -->
                </div>
                <!-- end card -->


            </div> <!-- end col -->
        </div>
        <!-- end row -->
    </div>
    <!-- end container -->
</div>