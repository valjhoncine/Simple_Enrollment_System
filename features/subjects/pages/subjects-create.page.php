<?php
require FEATURES_DIRECTORY . '/subjects/SubjectService.php';
require FEATURES_DIRECTORY . '/courses/CourseService.php';

const SUBJECT_VALIDATION_ERRORS = "SUBJECT_VALIDATION_ERRORS";
$errors = getSessionErrorMessage(SUBJECT_VALIDATION_ERRORS);

$subjectService = new SubjectService($connection);
$courseService = new CourseService($connection);

$courses = $courseService->getCourses();

if ($_SERVER['REQUEST_METHOD'] === HTTP_POST && isset($_POST["action"]) && $_POST['action'] === 'subject') {
    $request = $_POST;

    $subject_code = trim($request["subject_code"]);
    $subject_title = trim($request["subject_title"]);
    $course_id = trim($request["course"]);

    if ($subject_code == "") {
        $errors["subject_code"][] = "Subject code is required.";
    }
    if ($subject_title == "") {
        $errors["subject_title"][] = "Subject title is required.";
    }
    if (!array_key_exists($course_id, $courses) || $course_id <= 1) {
        $errors["course"][] = "Program/Course is required.";
    }

    $result = $subjectService->getSubjectByCode($subject_code);
    if ($result) {
        $errors["subject_code"][] = "Subject code not available, please enter a different subject code.";
    }

    if (!empty($errors)) {
        $_SESSION[SUBJECT_VALIDATION_ERRORS] = $errors;
        $_SESSION[OLD_FORM_VAL] = [
            "subject_code" => $subject_code,
            "subject_title" => $subject_title,
        ];
        navigateTo($routes, "subjects-create");
    }

    $result = $subjectService->saveSubject($subject_code, $subject_title, $course_id);
    if ($result) {
        $_SESSION[INSERT_SUCCESS] = "New subject created successfully.";
        navigateTo($routes, "subjects-create");
    } else {
        $_SESSION[INSERT_FAILED] = "An error occurred failed to create subject, please try again.";
        navigateTo($routes, "subjects-create");
    }
}

$pageTitle = "Subjects";
ob_start();
?>
<div class="container-xl px-4 mt-4">
    <div class="card">
        <div class="card-header">Add New Subject</div>
        <div class="card-body">
            <form method="post">
                <input type="hidden" name="action" value="subject" readonly required>
                <div class="row gx-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="small mb-1" for="inputSubjectCode">Subject Code</label>
                            <input class="form-control <?= setFormFieldIsInvalid($errors, "subject_code") ?>"
                                id="inputSubjectCode"
                                name="subject_code"
                                type="text"
                                placeholder="Enter subject code"
                                value="<?= getOldFormValue("subject_code") ?>" />
                            <?php displayError($errors, "subject_code"); ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="small mb-1" for="inputSubjectTitle">Subject Title</label>
                            <input class="form-control <?= setFormFieldIsInvalid($errors, "subject_title") ?>"
                                id="inputSubjectTitle"
                                name="subject_title"
                                type="text"
                                placeholder="Enter subject title"
                                value="<?= getOldFormValue("subject_title") ?>" />
                            <?php displayError($errors, "subject_title"); ?>
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
                                        if ($course->id > 1) {
                                ?>
                                            <option value="<?= $course->id ?>"><?= htmlspecialchars($course->code) . ' - ' . htmlspecialchars($course->name) ?></option>
                                <?php
                                        }
                                    }
                                }
                                ?>
                            </select>
                            <?php displayError($errors, "course"); ?>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Create Subject</button>
                <a class="btn btn-danger btn-block" href="<?= getRouteUrl($routes, "subjects") ?>" id="btn_cancel">Cancel</a>
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
            cancelButtonText: 'Go to Subjects Page'
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
