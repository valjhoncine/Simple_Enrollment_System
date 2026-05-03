<?php
require_once FEATURES_DIRECTORY . '/users/UserService.php';

const LOGIN_VALIDATION_ERRORS = "LOGIN_VALIDATION_ERRORS";
$errors = getSessionErrorMessage(LOGIN_VALIDATION_ERRORS);

if ($_SERVER['REQUEST_METHOD'] === HTTP_POST && isset($_POST["action"])) {
    $request = $_POST;
    if ($request["action"] != "login") {
        navigateTo($routes, "login");
    }

    $email = trim($request["email"]);
    $password = $request["password"];

    if ($email == "") {
        $errors["email"][] = "Email is required.";
    }
    if ($password == "") {
        $errors["password"][] = "Password is required.";
    }

    if (!empty($errors)) {
        $_SESSION[LOGIN_VALIDATION_ERRORS] = $errors;
        $_SESSION[OLD_FORM_VAL] = [
            "email" => $email,
        ];
        navigateTo($routes, "login");
    }

    $userService = new UserService($connection);

    $result = $userService->authenticate($email, $password);

    if ($result) {
        session_regenerate_id(true);
        $_SESSION[SESSION_USER] = $result;
        navigateTo($routes, "dashboard");
    } else {
        $_SESSION["INVALID_CREDENTIALS"] = "Invalid Credentials.";
        navigateTo($routes, "login");
    }
}

$pageTitle = "Login";

ob_start();
?>
<div class="row justify-content-center">
    <div class="col-lg-5">
        <div class="card shadow-lg border-0 rounded-lg mt-5">
            <div class="card-header justify-content-center">
                <h3 class="fw-light my-4">Login</h3>
            </div>
            <div class="card-body">
                <form method="post">
                    <input type="hidden" name="action" value="login" readonly required>
                    <div class="mb-3">
                        <label class="small mb-1" for="inputEmailAddress">Email</label>
                        <input class="form-control <?= setFormFieldIsInvalid($errors, "email") ?>"
                            id="inputEmailAddress"
                            name="email"
                            type="email"
                            placeholder="Enter email address"
                            value="<?= getOldFormValue("email") ?>" />
                        <?php displayError($errors, "email"); ?>
                    </div>
                    <div class="mb-3">
                        <label class="small mb-1" for="inputPassword">Password</label>
                        <input class="form-control <?= setFormFieldIsInvalid($errors, "password") ?>"
                            id="inputPassword"
                            name="password"
                            type="password"
                            placeholder="Enter password" />
                        <?php displayError($errors, "password"); ?>
                    </div>
                    <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                        <!-- <a class="small" href="auth-password-basic.html">Forgot Password?</a> -->
                        <button type="submit" class="btn btn-primary">Login</button>
                    </div>
                </form>
            </div>
            <div class="card-footer text-center">
                <div class="small"><a href="<?= getRouteUrl($routes, "register") ?>">Need an account? Sign up!</a></div>
            </div>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();

$scripts = ob_start();
?>

<?php
if (isset($_SESSION["INVALID_CREDENTIALS"])) {
    $ifMes = $_SESSION["INVALID_CREDENTIALS"];
    unset($_SESSION["INVALID_CREDENTIALS"]);
?>
    <script>
        Swal.fire({
            title: "<?= $ifMes ?>",
            icon: "error"
        });
    </script>
<?php
}
?>
<?php
if (isset($_SESSION[INSERT_SUCCESS])) {
    $ifMes = $_SESSION[INSERT_SUCCESS];
    unset($_SESSION[INSERT_SUCCESS]);
?>
    <script>
        Swal.fire({
            title: "<?= $ifMes ?>",
            icon: "success"
        });
    </script>
<?php
}
?>

<?php
$scripts = ob_get_clean();
include INCLUDES_DIRECTORY . '/layouts/authentication_layout.php';
?>
