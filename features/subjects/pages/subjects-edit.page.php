<?php
require FEATURES_DIRECTORY . '/subjects/SubjectService.php';
require FEATURES_DIRECTORY . '/courses/CourseService.php';

const SUBJECT_VALIDATION_ERRORS = "SUBJECT_VALIDATION_ERRORS";
$errors = getSessionErrorMessage(SUBJECT_VALIDATION_ERRORS);

$subjectService = new SubjectService($connection);
$courseService = new CourseService($connection);

$courses = $courseService->getCourses();

if ($_SERVER['REQUEST_METHOD'] === HTTP_POST && isset($_POST["action"]) && $_POST['action'] == 'subject-edit') {
    $request = $_POST;

    $subject = $subjectService->getSubjectById(isset($_POST['id']) ? $_POST['id'] : 0);
    if (!$subject) {
        apiResponse(false, null, [REQUEST_RESOURCE_NOT_FOUND => "Subject not found."]);
    }

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

    if (!empty($errors)) {
        apiResponse(false, null, $errors);
    }

    $subject->code = $subject_code;
    $subject->name = $subject_title;
    $subject->course_id = $course_id;

    try {
        $result = $subjectService->updateSubject($subject);
        if ($result) {
            apiResponse(true, $subject);
        } else {
            throw new Exception(INSERT_FAILED);
        }
    } catch (Exception $ex) {
        if (str_contains($ex, "Duplicate entry")) {
            $errors["subject_code"][] = "Course code not available.";
            apiResponse(false, null, $errors);
        }
        apiResponse(false, null, [INSERT_FAILED => "Cannot process request, an unexpected error occurred."]);
    }
}

$selectedSubject = null;
if ($_SERVER['REQUEST_METHOD'] === HTTP_GET) {
    if (!isset($_GET["id"]) || $_GET['id'] <= 0) {
        $_SESSION[REQUEST_RESOURCE_NOT_FOUND] = "Subject not found.";
        navigateTo($routes, "subjects");
    }
    $request = $_GET;

    $result = $subjectService->getSubjectById($request["id"]);
    if (!$result) {
        $_SESSION[REQUEST_RESOURCE_NOT_FOUND] = "Subject not found.";
        navigateTo($routes, "subjects");
    }
    $selectedSubject = $result;
}

$pageTitle = "Subjects";
ob_start();
?>

<div class="container-xl px-4 mt-4">
    <div class="card">
        <div class="card-header">Edit Subject</div>
        <div class="card-body">
            <form method="post" id="form_subject_edit">
                <input type="hidden" name="action" value="subject-edit" readonly required>
                <input type="hidden" name="id" value="<?= isset($_GET["id"]) ? $_GET["id"] : 0 ?>" readonly required>
                <div class="row gx-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="small mb-1" for="inputSubjectCode">Subject Code</label>
                            <input class="form-control"
                                id="inputSubjectCode"
                                name="subject_code"
                                type="text"
                                placeholder="Enter subject code"
                                value="<?= isset($selectedSubject) ? $selectedSubject->code : getOldFormValue("subject_code") ?>" />
                            <div class='invalid-feedback d-block' id="inputSubjectCodeError"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="small mb-1" for="inputSubjectTitle">Subject Title</label>
                            <input class="form-control"
                                id="inputSubjectTitle"
                                name="subject_title"
                                type="text"
                                placeholder="Enter subject title"
                                value="<?= isset($selectedSubject) ? $selectedSubject->name : getOldFormValue("subject_title") ?>" />
                            <div class='invalid-feedback d-block' id="inputSubjectTitleError"></div>
                        </div>
                    </div>
                </div>
                <div class="row gx-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="small mb-1">Program/Course</label>
                            <select class="form-select"
                                aria-label="Default select example"
                                name="course"
                                id="inputCourse">
                                <option selected>Select program/course</option>
                                <?php
                                if (isset($courses) || !empty($courses)) {
                                    foreach ($courses as $course) {
                                        if ($course->id > 1) {
                                ?>
                                            <option
                                                value="<?= $course->id ?>"
                                                <?php
                                                if (isset($selectedSubject)) {
                                                    if ($selectedSubject->course_id === $course->id) {
                                                        echo 'selected';
                                                    }
                                                }
                                                ?>>
                                                <?= htmlspecialchars($course->code) . ' - ' . htmlspecialchars($course->name) ?>
                                            </option>
                                <?php
                                        }
                                    }
                                }
                                ?>
                            </select>
                            <div class='invalid-feedback d-block' id="inputCourseError"></div>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Update Subject</button>
                <a class="btn btn-danger btn-block" href="<?= getRouteUrl($routes, "subjects") ?>" id="btn_cancel">Cancel</a>
            </form>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
$scripts = ob_start();
?>
<script>
    $(document).ready(function() {
        $("#form_subject_edit").submit(function(e) {
            e.preventDefault();
            removeFormValidationErrors("inputSubjectCode", "is-invalid");
            removeFormValidationErrors("inputSubjectTitle", "is-invalid");
            removeFormValidationErrors("inputCourse", "is-invalid");

            const formArray = $(this).serializeArray();
            const formData = {};
            formArray.forEach(field => {
                formData[field.name] = field.value;
            });

            $.ajax({
                type: "post",
                url: routes["subjects-edit"].url,
                data: {
                    action: formData.action,
                    id: formData.id,
                    subject_code: formData.subject_code,
                    subject_title: formData.subject_title,
                    course: formData.course,
                },
                dataType: "json",
                success: function(response) {
                    if (!response.success) {
                        if (response.error["subject_code"]) {
                            showFormValidationErrors("inputSubjectCode", response.error["subject_code"])
                        }
                        if (response.error["subject_title"]) {
                            showFormValidationErrors("inputSubjectTitle", response.error["subject_title"])
                        }
                        if (response.error["course"]) {
                            showFormValidationErrors("inputCourse", response.error["course"])
                        }
                        if (response.error["INSERT_FAILED"]) {
                            Swal.fire({
                                title: "Update Failed",
                                text: response.error["INSERT_FAILED"],
                                icon: "error"
                            });
                        }
                    } else {
                        Swal.fire({
                            title: "Update Success",
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
                    }
                }
            });
        })
    });
</script>
<?php
$scripts = ob_get_clean();
include INCLUDES_DIRECTORY . '/layouts/protected_layout.php';
?>
