<?php
require FEATURES_DIRECTORY . '/users/functions/register.function.php';
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
                <form method="post">
                    <input type="hidden" name="action" value="register" readonly required>
                    <div class="row gx-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="small mb-1" for="inputFirstName">First Name</label>
                                <input class="form-control <?= setFormFieldIsInvalid($errors, "first_name") ?>"
                                    id="inputFirstName"
                                    name="first_name"
                                    type="text"
                                    placeholder="Enter first name"
                                    value="<?= getOldFormValue("first_name") ?>" />
                                <?php displayError($errors, "first_name"); ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="small mb-1" for="inputLastName">Last Name</label>
                                <input class="form-control <?= setFormFieldIsInvalid($errors, "last_name") ?>"
                                    id="inputLastName"
                                    name="last_name"
                                    type="text"
                                    placeholder="Enter last name"
                                    value="<?= getOldFormValue("last_name") ?>" />
                                <?php displayError($errors, "last_name"); ?>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="small mb-1" for="inputEmailAddress">Email</label>
                        <input class="form-control <?= setFormFieldIsInvalid($errors, "email") ?>"
                            id="inputEmailAddress"
                            type="email"
                            name="email"
                            aria-describedby="emailHelp"
                            placeholder="Enter email address"
                            value="<?= getOldFormValue("email") ?>" />
                        <?php displayError($errors, "email"); ?>
                    </div>
                    <div class="row gx-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="small mb-1" for="inputPassword">Password</label>
                                <input class="form-control <?= setFormFieldIsInvalid($errors, "password") ?>"
                                    id="inputPassword"
                                    name="password"
                                    type="password"
                                    placeholder="Enter password" />
                                <?php displayError($errors, "password"); ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="small mb-1" for="inputConfirmPassword">Confirm Password</label>
                                <input class="form-control <?= setFormFieldIsInvalid($errors, "password_confirmation") ?>"
                                    id="inputConfirmPassword"
                                    name="password_confirmation"
                                    type="password"
                                    placeholder="Confirm password" />
                                <?php displayError($errors, "password_confirmation"); ?>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Create Account</button>
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

$scripts = ob_start();
if (isset($_SESSION[INSERT_FAILED])) {
    $ifMes = $_SESSION[INSERT_FAILED];
    unset($_SESSION[INSERT_FAILED]);
?>
    <script>
        Swal.fire({
            title: "Registration Failed",
            text: "<?= $ifMes ?>",
            icon: "error"
        });
    </script>
<?php
}
$scripts = ob_get_clean();
include dirname(__DIR__) . '/../includes/layouts/authentication_layout.php';
?>
