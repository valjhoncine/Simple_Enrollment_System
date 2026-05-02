<?php
include __DIR__ . '/users-create.function.php';
$pageTitle = "Users";
ob_start();
?>
<div class="container-xl px-4 mt-4">
    <div class="card">
        <div class="card-header">Add New User</div>
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
                <div class="row gx-3">
                    <div class="col-md-6">
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
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="small mb-1">Role</label>
                            <select class="form-select <?= setFormFieldIsInvalid($errors, "role") ?>"
                                aria-label="Default select example"
                                name="role">
                                <option selected>Select role</option>
                                <option value="1">Clerk</option>
                                <option value="2">Faculty</option>
                                <option value="3">Student</option>
                            </select>
                            <?php displayError($errors, "role"); ?>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Create Account</button>
                <a class="btn btn-danger btn-block" href="<?= getRouteUrl($routes, "users") ?>" id="btn_cancel">Cancel</a>
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
            title: "Create Account Success",
            text: "<?= $ifMes ?>",
            icon: "success",
            showCancelButton: true,
            confirmButtonText: "Ok",
            cancelButtonText: 'Go to Users Page'
        }).then(function(result){
            if(result.dismiss === Swal.DismissReason.cancel){
                console.log(result)
                $("#btn_cancel")[0].click();
            }
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
            title: "Create Account Failed",
            text: "<?= $ifMes ?>",
            icon: "error"
        });
    </script>
<?php
}
?>

<?php
$scripts = ob_get_clean();
include dirname(__DIR__) . '/../../includes/layouts/protected_layout.php';
?>
