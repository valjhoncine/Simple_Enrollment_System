<?php
require FEATURES_DIRECTORY . '/users/UserService.php';
require FEATURES_DIRECTORY . '/courses/CourseService.php';

const REGISTER_VALIDATION_ERRORS = "REGISTER_VALIDATION_ERRORS";
$errors = getSessionErrorMessage(REGISTER_VALIDATION_ERRORS);

$courseService = new CourseService($connection);
$courses = $courseService->getCourses();

if ($_SERVER['REQUEST_METHOD'] === HTTP_POST && isset($_POST["action"]) && $_POST['action'] === 'register') {
    $request = $_POST;

    $first_name = trim($request["first_name"]);
    $last_name = trim($request["last_name"]);
    $email = trim($request["email"]);
    $role = trim($request["role"]);
    $courseId = trim($request["course"]);

    if ($first_name == "") {
        $errors["first_name"][] = "First name is required.";
    }
    if ($last_name == "") {
        $errors["last_name"][] = "Last name is required.";
    }
    if ($email == "") {
        $errors["email"][] = "Email is required.";
    }
    if (!array_key_exists($role, PAGE_ACCESS_ROLES)) {
        $errors['role'][] = "Role is required.";
    }
    if (!array_key_exists($courseId, $courses)) {
        $errors['course'][] = "Program/Course is required.";
    }

    if (!empty($errors)) {
        $_SESSION[REGISTER_VALIDATION_ERRORS] = $errors;
        $_SESSION[OLD_FORM_VAL] = [
            "first_name" => $first_name,
            "last_name" => $last_name,
            "email" => $email,
        ];
        navigateTo($routes, "users-create");
    }

    $userService = new UserService($connection);

    $result = $userService->getUserByEmail($email);
    if ($result) {
        $_SESSION[INSERT_FAILED] = "Failed to create account, email may not be available.";
        navigateTo($routes, "users-create");
    }

    $password = ucfirst($last_name) . '@' . date("Y");
    $result = $userService->saveEmployee(
        $first_name,
        $last_name,
        $email,
        $password,
        $role,
        $courseId
    );

    if ($result) {
        $_SESSION[INSERT_SUCCESS] = "New user account created successfully.";
        navigateTo($routes, "users-create");
    } else {
        $_SESSION[INSERT_FAILED] = "An error occurred failed to create account, please try again.";
        navigateTo($routes, "users-create");
    }
}

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
                            </select>
                            <?php displayError($errors, "role"); ?>
                        </div>
                    </div>
                </div>
                <div class="row gx-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="small mb-1">Program/Course</label>
                            <select class="form-select <?= setFormFieldIsInvalid($errors, "course") ?>"
                                aria-label="Default select example"
                                name="course">
                                <option selected>Select program/course</option>
                                <?php
                                if (isset($courses) || !empty($courses)) {
                                    foreach ($courses as $course) {
                                ?>
                                        <option value="<?= $course->id ?>"><?= htmlspecialchars($course->code) . ' - ' . htmlspecialchars($course->name) ?></option>
                                <?php
                                    }
                                }
                                ?>
                            </select>
                            <?php displayError($errors, "course"); ?>
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
        }).then(function(result) {
            if (result.dismiss === Swal.DismissReason.cancel) {
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
