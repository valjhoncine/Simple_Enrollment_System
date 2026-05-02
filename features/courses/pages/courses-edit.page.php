<?php
require FEATURES_DIRECTORY . '/courses/functions/courses-edit.function.php';
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
