<?php
$pageTitle = "Register";
ob_start();
?>
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card shadow-lg border-0 rounded-lg mt-5">
            <div class="card-header justify-content-center">
                <h3 class="fw-light my-4">Create Account</h3>
            </div>
            <div class="card-body">
                <form>
                    <div class="row gx-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="small mb-1" for="inputFirstName">First Name</label>
                                <input class="form-control" id="inputFirstName" type="text" placeholder="Enter first name" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="small mb-1" for="inputLastName">Last Name</label>
                                <input class="form-control" id="inputLastName" type="text" placeholder="Enter last name" />
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="small mb-1" for="inputEmailAddress">Email</label>
                        <input class="form-control" id="inputEmailAddress" type="email" aria-describedby="emailHelp" placeholder="Enter email address" />
                    </div>
                    <div class="row gx-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="small mb-1" for="inputPassword">Password</label>
                                <input class="form-control" id="inputPassword" type="password" placeholder="Enter password" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="small mb-1" for="inputConfirmPassword">Confirm Password</label>
                                <input class="form-control" id="inputConfirmPassword" type="password" placeholder="Confirm password" />
                            </div>
                        </div>
                    </div>
                    <a class="btn btn-primary btn-block" href="auth-login-basic.html">Create Account</a>
                </form>
            </div>
            <div class="card-footer text-center">
                <div class="small"><a href="<?= getRouteUrl($routes, "login") ?>">Have an account? Go to login</a></div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include dirname(__DIR__) . '/../../includes/layouts/authentication_layout.php';
?>
