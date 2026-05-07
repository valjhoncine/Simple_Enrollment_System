<?php
require FEATURES_DIRECTORY . '/courses/CourseService.php';

const ENROLLMENT_VALIDATION_ERRORS = "ENROLLMENT_VALIDATION_ERRORS";
$errors = getSessionErrorMessage(ENROLLMENT_VALIDATION_ERRORS);
if (!$enrollment) {

    $courseService = new CourseService($connection);
    $courses = $courseService->getCourses();

    if ($_SERVER['REQUEST_METHOD'] === HTTP_POST && isset($_POST['action']) && $_POST['action'] === 'enrollment') {
        $request = $_POST;

        $address = trim($request["address"]);
        $dob = trim($request["dob"]);
        $course_id = trim($request["course_id"]);

        if ($address == "") {
            $errors["address"][] = "Address is required.";
        }
        if ($dob == "") {
            $errors["dob"][] = "Date of birth is required.";
        }
        if (!array_key_exists($course_id, $courses) || $course_id <= 1) {
            $errors['course_id'][] = "Course is required";
        }

        if (!empty($errors)) {
            $_SESSION[ENROLLMENT_VALIDATION_ERRORS] = $errors;
            $_SESSION[OLD_FORM_VAL] = [
                "address" => $address,
                "dob" => $dob,
                "course_id" => $course_id
            ];
            navigateTo($routes, "student-enrollment-profile");
        }

        $result = $enrollmentService->saveEnrollment($address, $dob, $course_id, Auth::id());
        if ($result) {
            $_SESSION[INSERT_SUCCESS] = "Enrollment application submitted successfully.";
            navigateTo($routes, "student-enrollment-profile");
        } else {
            $_SESSION[INSERT_FAILED] = "An error occurred failed to submit application, please try again.";
            navigateTo($routes, "student-enrollment-profile");
        }
    }

    ob_start();
?>
    <div class="container-xl px-4 mt-4">
        <div class="card">
            <div class="card-header">Submit Enrollment Application</div>
            <div class="card-body">
                <form method="post">
                    <input type="hidden" name="action" value="enrollment" readonly required>
                    <div class="row gx-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="small mb-1" for="inputAddress">Address</label>
                                <input class="form-control <?= setFormFieldIsInvalid($errors, "address") ?>"
                                    id="inputAddress"
                                    name="address"
                                    type="text"
                                    placeholder="Enter address"
                                    value="<?= getOldFormValue("address") ?>" />
                                <?php displayError($errors, "address"); ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="small mb-1" for="inputDob">Date of Birth</label>
                                <input class="form-control <?= setFormFieldIsInvalid($errors, "dob") ?>"
                                    id="inputDob"
                                    name="dob"
                                    type="date"
                                    placeholder="Enter date of birth"
                                    value="<?= getOldFormValue("dob") ?>" />
                                <?php displayError($errors, "dob"); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row gx-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="small mb-1">Program/Course</label>
                                <select class="form-select <?= setFormFieldIsInvalid($errors, "course_id") ?>"
                                    aria-label="Default select example"
                                    name="course_id">
                                    <option selected>Select program/course</option>
                                    <?php
                                    if (isset($courses) || !empty($courses)) {
                                        foreach ($courses as $course) {
                                            if ($course->id > 1) {
                                    ?>
                                                <option value="<?= $course->id ?>"><?= htmlspecialchars($course->code) . ' - ' . htmlspecialchars($course->name) ?></option>
                                    <?php
                                            }
                                        }
                                    }
                                    ?>
                                </select>
                                <?php displayError($errors, "course_id"); ?>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Submit Application</button>
                </form>
            </div>
        </div>
    </div>
    <?php
    $content = ob_get_clean();
    $scripts = ob_start();
    ?>
    <?php
    if (isset($_SESSION[INSERT_FAILED])) {
        $ifMes = $_SESSION[INSERT_FAILED];
        unset($_SESSION[INSERT_FAILED]);
    ?>
        <script>
            Swal.fire({
                title: "Create Failed",
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
<?php
} else if ($enrollment->status === 0) {
    ob_start();
?>
    <div class="container-xl px-4 mt-4">
        <div class="card">
            <div class="card-header">Enrollment Application Pending</div>
            <div class="card-body">
                Your enrollment application is pending for approval.
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
            })
        </script>
    <?php
    }
    ?>
<?php
    $scripts = ob_get_clean();
    include INCLUDES_DIRECTORY . '/layouts/protected_layout.php';
}
?>
