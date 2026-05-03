<?php
require FEATURES_DIRECTORY . '/courses/CourseService.php';

const COURSES_VALIDATION_ERRORS = "COURSES_VALIDATION_ERRORS";
$errors = getSessionErrorMessage(COURSES_VALIDATION_ERRORS);

$courseService = new CourseService($connection);

if ($_SERVER['REQUEST_METHOD'] === HTTP_POST && isset($_POST["action"]) && $_POST['action'] == 'course-update') {
    $request = $_POST;

    $course = $courseService->getCourseById(isset($_POST['id']) ? $_POST['id'] : 0);
    if (!$course) {
        apiResponse(false, null, [REQUEST_RESOURCE_NOT_FOUND => "Course not found."]);
    }

    $course_code = trim($request["course_code"]);
    $course_title = trim($request["course_title"]);

    if ($course_code == "") {
        $errors["course_code"][] = "Course code is required.";
    }
    if ($course_title == "") {
        $errors["course_title"][] = "Course title is required.";
    }

    if (!empty($errors)) {
        apiResponse(false, null, $errors);
    }
    $course->code = $course_code;
    $course->name = $course_title;
    try {
        $result = $courseService->updateCourse($course);
        if ($result) {
            apiResponse(true, $course);
        } else {
            throw new Exception(INSERT_FAILED);
        }
    } catch (Exception $ex) {
        if (str_contains($ex, "Duplicate entry")) {
            $errors["course_code"][] = "Course code not available.";
            apiResponse(false, null, $errors);
        }
        apiResponse(false, null, [INSERT_FAILED => "Cannot process request, an unexpected error occurred."]);
    }
}

$selectedCourse = null;
if ($_SERVER['REQUEST_METHOD'] === HTTP_GET) {
    if (!isset($_GET["id"]) || $_GET['id'] <= 1) {
        $_SESSION[REQUEST_RESOURCE_NOT_FOUND] = "Course not found.";
        navigateTo($routes, "courses");
    }
    $request = $_GET;

    $result = $courseService->getCourseById($request["id"]);
    if (!$result) {
        $_SESSION[REQUEST_RESOURCE_NOT_FOUND] = "Course not found.";
        navigateTo($routes, "courses");
    }
    $selectedCourse = $result;
}

$pageTitle = "Courses";
ob_start();
?>
<div class="container-xl px-4 mt-4">
    <div class="card">
        <div class="card-header">Edit Course</div>
        <div class="card-body">
            <form method="post" id="form_course_edit">
                <input type="hidden" name="action" value="course-update" readonly required>
                <input type="hidden" name="id" value="<?= isset($_GET["id"]) ? $_GET["id"] : 0 ?>" readonly required>
                <div class="row gx-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="small mb-1" for="inputCourseCode">Course Code</label>
                            <input class="form-control"
                                id="inputCourseCode"
                                name="course_code"
                                type="text"
                                placeholder="Enter course code"
                                value="<?= (isset($selectedCourse)) ? $selectedCourse->code : getOldFormValue("course_code") ?>" />
                            <div class='invalid-feedback d-block' id="inputCourseCodeError"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="small mb-1" for="inputCourseTitle">Course Title</label>
                            <input class="form-control"
                                id="inputCourseTitle"
                                name="course_title"
                                type="text"
                                placeholder="Enter course title"
                                value="<?= (isset($selectedCourse)) ? $selectedCourse->name : getOldFormValue("course_title") ?>" />
                            <div class='invalid-feedback d-block' id="inputCourseTitleError"></div>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Update Course</button>
                <a class="btn btn-danger btn-block" href="<?= getRouteUrl($routes, "courses") ?>" id="btn_cancel">Cancel</a>
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
        $("#form_course_edit").submit(function(e) {
            e.preventDefault();
            removeFormValidationErrors("inputCourseCode", "is-invalid");
            removeFormValidationErrors("inputCourseTitle", "is-invalid");

            const formArray = $(this).serializeArray();
            const formData = {};
            formArray.forEach(field => {
                formData[field.name] = field.value;
            });

            $.ajax({
                type: "post",
                url: routes["courses-edit"].url,
                data: {
                    action: formData.action,
                    id: formData.id,
                    course_code: formData.course_code,
                    course_title: formData.course_title,
                },
                dataType: "json",
                success: function(response) {
                    if (!response.success) {
                        if (response.error["course_code"]) {
                            showFormValidationErrors("inputCourseCode", response.error["course_code"])
                        }
                        if (response.error["course_title"]) {
                            showFormValidationErrors("inputCourseTitle", response.error["course_title"])
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
                            cancelButtonText: 'Go to Courses Page'
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
