<?php
require FEATURES_DIRECTORY . '/account/AccountService.php';
require FEATURES_DIRECTORY . '/users/UserService.php';

const ACCOUNT_VAIDATION_ERRORS = "ACCOUNT_VAIDATION_ERRORS";
$errors = getSessionErrorMessage(ACCOUNT_VAIDATION_ERRORS);

if ($_SERVER['REQUEST_METHOD'] === HTTP_POST && isset($_POST["action"]) && $_POST['action'] === 'change_password') {
    $request = $_POST;

    $currentPassword = $request["currentPassword"];
    $newPassword = $request["newPassword"];
    $confirmPassword = $request["confirmPassword"];

    $userService = new UserService($connection);
    $accountService = new AccountService($connection);

    $user = $userService->getUserByEmail(Auth::email());
    if (!$user) {
        $errors["currentPassword"][] = "User not found";
    }
    if (trim($currentPassword) === "") {
        $errors["currentPassword"][] = "Current password is requried.";
    } else if ($user && !password_verify($currentPassword, $user->passwordhash)) {
        $errors["currentPassword"][] = "Incorrect password.";
    }
    if (trim($newPassword) === "") {
        $errors["newPassword"][] = "New password is requried.";
    }
    if (trim($confirmPassword) === "") {
        $errors["confirmPassword"][] = "Confirm password is requried.";
    }

    if ($newPassword !== $confirmPassword) {
        $errors["newPassword"][] = "Password do not match";
        $errors["confirmPassword"][] = "";
    }

    if (!empty($errors)) {
        $_SESSION[ACCOUNT_VAIDATION_ERRORS] = $errors;
        navigateTo($routes, "account");
    }
    $user->changePassword($newPassword);
    $result = $accountService->changePassword($user);
    if ($result) {
        $_SESSION[INSERT_SUCCESS] = "Password updated successfully.";
        navigateTo($routes, "account");
    } else {
        $_SESSION[INSERT_FAILED] = "An error occurred failed to create, please try again.";
        navigateTo($routes, "account");
    }
}

$pageTitle = "Account";
ob_start();
?>
<div class="container-xl px-4 mt-4">
    <div class="card">
        <div class="card-body">
            <form method="post">
                <input type="hidden" name="action" value="change_password" readonly required>
                <div class="mb-3">
                    <label class="small mb-1" for="inputEmail">Email</label>
                    <input class="form-control" id="inputEmail" type="text" placeholder="Enter your email" value="<?= Auth::email() ?>" readonly disabled />
                </div>
                <div class="row gx-3 mb-3">
                    <div class="col-md-6">
                        <label class="small mb-1" for="inputFirstName">First name</label>
                        <input class="form-control" id="inputFirstName" type="text" placeholder="Enter your first name" value="<?= Auth::user()->first_name ?>" readonly disabled />
                    </div>
                    <div class="col-md-6">
                        <label class="small mb-1" for="inputLastName">Last name</label>
                        <input class="form-control" id="inputLastName" type="text" placeholder="Enter your last name" value="<?= Auth::user()->last_name ?>" readonly disabled />
                    </div>
                </div>
                <div class="mb-3">
                    <label class="small mb-1" for="currentPassword">Current Password</label>
                    <input class="form-control <?= setFormFieldIsInvalid($errors, "currentPassword") ?>" id="currentPassword" name="currentPassword" type="password" placeholder="Enter current password" />
                    <?php displayError($errors, "currentPassword"); ?>
                </div>
                <div class="mb-3">
                    <label class="small mb-1" for="newPassword">New Password</label>
                    <input class="form-control <?= setFormFieldIsInvalid($errors, "newPassword") ?>" id="newPassword" name="newPassword" type="password" placeholder="Enter new password" />
                    <?php displayError($errors, "newPassword"); ?>
                </div>
                <div class="mb-3">
                    <label class="small mb-1" for="confirmPassword">Confirm Password</label>
                    <input class="form-control <?= setFormFieldIsInvalid($errors, "confirmPassword") ?>" id="confirmPassword" name="confirmPassword" type="password" placeholder="Confirm new password" />
                    <?php displayError($errors, "confirmPassword"); ?>
                </div>
                <button class="btn btn-primary" type="submit">Save changes</button>
            </form>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();
$scripts = ob_start();
?>

<?php
if (isset($_SESSION[INSERT_SUCCESS])) {
    $ifMes = $_SESSION[INSERT_SUCCESS];
    unset($_SESSION[INSERT_SUCCESS]);
?>
    <script>
        Swal.fire({
            title: "<?= $ifMes ?>",
            icon: "success",
        });
    </script>
<?php
}
?>

<?php
if (isset($_SESSION[INSERT_FAILED])) {
    $ifMes = $_SESSION[INSERT_FAILED];
    unset($_SESSION[INSERT_FAILED]);
?>
    <script>
        Swal.fire({
            title: "Update Failed",
            text: "<?= $ifMes ?>",
            icon: "error"
        });
    </script>
<?php
}
?>

<?php
$scripts = ob_get_clean();
include INCLUDES_DIRECTORY . '/layouts/protected_layout.php';
?>
