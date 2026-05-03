<?php
require FEATURES_DIRECTORY . '/courses/CourseService.php';

const COURSES_VALIDATION_ERRORS = "COURSES_VALIDATION_ERRORS";
$errors = getSessionErrorMessage(COURSES_VALIDATION_ERRORS);

$courseService = new CourseService($connection);

if ($_SERVER['REQUEST_METHOD'] === HTTP_POST && isset($_POST["action"]) && $_POST['action'] === 'course') {
    $request = $_POST;

    $course_code = trim($request["course_code"]);
    $course_title = trim($request["course_title"]);

    if ($course_code == "") {
        $errors["course_code"][] = "Course code is required.";
    }
    if ($course_title == "") {
        $errors["course_title"][] = "Course title is required.";
    }

    $result = $courseService->getCourseByCode($course_code);
    if ($result) {
        $errors["course_code"][] = "Course code not available, please enter a different course code.";
    }

    if (!empty($errors)) {
        $_SESSION[COURSES_VALIDATION_ERRORS] = $errors;
        $_SESSION[OLD_FORM_VAL] = [
            "course_code" => $course_code,
            "course_title" => $course_title,
        ];
        navigateTo($routes, "courses-create");
    }

    $result = $courseService->saveCourse($course_code, $course_title);
    if ($result) {
        $_SESSION[INSERT_SUCCESS] = "New course created successfully.";
        navigateTo($routes, "courses-create");
    } else {
        $_SESSION[INSERT_FAILED] = "An error occurred failed to create course, please try again.";
        navigateTo($routes, "courses-create");
    }
}

$pageTitle = "Courses";
ob_start();
?>
<div class="container-xl px-4 mt-4">
    <div class="card">
        <div class="card-header">Add New Course</div>
        <div class="card-body">
            <form method="post">
                <input type="hidden" name="action" value="course" readonly required>
                <div class="row gx-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="small mb-1" for="inputCourseCode">Course Code</label>
                            <input class="form-control <?= setFormFieldIsInvalid($errors, "course_code") ?>"
                                id="inputCourseCode"
                                name="course_code"
                                type="text"
                                placeholder="Enter course code"
                                value="<?= getOldFormValue("course_code") ?>" />
                            <?php displayError($errors, "course_code"); ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="small mb-1" for="inputCourseTitle">Course Title</label>
                            <input class="form-control <?= setFormFieldIsInvalid($errors, "course_title") ?>"
                                id="inputCourseTitle"
                                name="course_title"
                                type="text"
                                placeholder="Enter course title"
                                value="<?= getOldFormValue("course_title") ?>" />
                            <?php displayError($errors, "course_title"); ?>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Create Course</button>
                <a class="btn btn-danger btn-block" href="<?= getRouteUrl($routes, "courses") ?>" id="btn_cancel">Cancel</a>
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
            title: "Create Success",
            text: "<?= $ifMes ?>",
            icon: "success",
            showCancelButton: true,
            confirmButtonText: "Ok",
            cancelButtonText: 'Go to Courses Page'
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
