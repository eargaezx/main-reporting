<div class="account-pages mt-5 mb-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6 col-xl-4">
                <div class="card bg-pattern">

                    <div class="card-body p-4">

                        <div class="text-center w-75 m-auto">
                            <div class="auth-brand">
                                <a href="index.html" class="logo logo-dark text-center">
                                    <span class="logo-lg">
                                         <img src="<?= Router::url(['controller' => '/'], true) ?>/img/ic_launcher.png" width="200"/>
                                    </span>
                                </a>

                                <a href="index.html" class="logo logo-light text-center">
                                    <span class="logo-lg">
                                    <img src="<?= Router::url(['controller' => '/'], true) ?>/img/ic_launcher.png" width="200"/>
                                    </span>
                                </a>
                            </div>
                            <p class="text-muted mb-4 mt-3">Enter your email address and we'll send you an email with
                                instructions to reset your password.</p>

                                <?php echo $this->Flash->render(); ?>
                        </div>

                        <form method="POST"
                            action="<?= Router::url(['controller' => 'Accounts', 'action' => 'recover'], true) ?>">

                            <div class="mb-3">
                                <label for="emailaddress" class="form-label">Email address</label>
                                <input id="emailaddress" name="data[Account][username]" class="form-control" type="email" required=""
                                    placeholder="Enter your email">
                            </div>

                            <div class="text-center d-grid">
                                <button class="btn btn-warning" type="submit"> Reset Password </button>
                            </div>

                        </form>

                    </div> <!-- end card-body -->
                </div>
                <!-- end card -->

                <div class="row mt-3">
                    <div class="col-12 text-center">
                        <p class="text-white-50">Back to <a href="<?= Router::url(['controller' => 'Accounts', 'action' => 'login'], true) ?>" class="text-white ms-1"><b>Log
                                    in</b></a></p>
                    </div> <!-- end col -->
                </div>
                <!-- end row -->

            </div> <!-- end col -->
        </div>
        <!-- end row -->
    </div>
    <!-- end container -->
</div>